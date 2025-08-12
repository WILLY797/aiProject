<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\PriceBreak;
use App\Services\EquinoxClient;
use App\Http\Requests\StoreOrderRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Public catalogue (throttled). Secure account/order/pricing via Sanctum.
| Uses Equinox field names and shapes (sku/mpn/last_updated, orderLines[], etc.).
*/

/** -------------------- Products (public) -------------------- */
Route::middleware('throttle:120,1')->group(function () {
    // GET /api/products?q=&branch_id=
    Route::get('/products', function (Request $request) {
        $q = trim((string) $request->query('q', ''));
        $branchId = $request->integer('branch_id');

        $query = Product::query()
            ->when($q, fn ($w) =>
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('mpn', 'like', "%{$q}%")
            )
            ->select('id', 'equinox_id', 'sku', 'mpn', 'name', 'base_price', 'last_updated')
            ->orderBy('name');

        if ($branchId) {
            $query->whereExists(function ($s) use ($branchId) {
                $s->select(DB::raw(1))
                    ->from('branch_stocks as bs')
                    ->whereColumn('bs.product_id', 'equinox_products.id')
                    ->where('bs.branch_id', $branchId);
            });
        }

        return $query->paginate(24);
    })->name('api.products.index');

    // GET /api/products/{id}
    Route::get('/products/{id}', function ($id) {
        return Product::query()
            ->select('id', 'equinox_id', 'sku', 'mpn', 'name', 'description', 'base_price', 'metadata', 'last_updated')
            ->findOrFail($id);
    })->name('api.products.show');

    // GET /api/products/{id}/stock  (branch stock + unprocessed qty)
    Route::get('/products/{id}/stock', function ($id) {
        return DB::table('branch_stocks')
            ->join('branches', 'branches.id', '=', 'branch_stocks.branch_id')
            ->where('branch_stocks.product_id', $id)
            ->select(
                'branches.id as branch_id',
                'branches.name',
                'branch_stocks.stock',
                'branch_stocks.unprocessed_order_qty'
            )
            ->orderBy('branches.name')
            ->get();
    })->name('api.products.stock');
});

/** -------------------- Accounts & Pricing (secure pass-through/local) -------------------- */
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Mirror Equinox for SPA
    Route::get('/accounts', fn (EquinoxClient $api) => $api->get('accounts'))->name('api.accounts.index');
    Route::get('/accounts/{id}', fn ($id, EquinoxClient $api) => $api->get("accounts/{$id}"))->name('api.accounts.show');
    Route::get('/accounts/{id}/orders', fn ($id, EquinoxClient $api) => $api->get("accounts/{$id}/orders"))->name('api.accounts.orders');
    Route::get('/accounts/{id}/quotes', fn ($id, EquinoxClient $api) => $api->get("accounts/{$id}/quotes"))->name('api.accounts.quotes');
    Route::get('/accounts/{id}/invoices', fn ($id, EquinoxClient $api) => $api->get("accounts/{$id}/invoices"))->name('api.accounts.invoices');

    // Local price breaks (populated by sync): /api/accounts/{accountId}/products/{productId}/price-breaks
    Route::get('/accounts/{accountId}/products/{productId}/price-breaks', function ($accountId, $productId) {
        $product = Product::query()
            ->where(fn ($q) => $q->where('id', $productId)->orWhere('equinox_id', $productId))
            ->firstOrFail();

        return PriceBreak::query()
            ->where('product_id', $product->id)
            ->where('account_id', (int) $accountId)
            ->orderBy('quantity')
            ->get(['quantity', 'price']);
    })->name('api.accounts.product.pricebreaks');

    // Helper: best price resolver (?product_id=|equinox_id&account_id=&qty=)
    Route::get('/pricing/best', function (Request $r) {
        $r->validate([
            'product_id' => ['required'],
            'account_id' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);
        $pid = $r->input('product_id');
        $qty = (int) $r->integer('qty');
        $acc = (int) $r->integer('account_id');

        $product = Product::query()
            ->where(fn ($q) => $q->where('id', $pid)->orWhere('equinox_id', $pid))
            ->firstOrFail();

        $break = PriceBreak::query()
            ->where('product_id', $product->id)
            ->where('account_id', $acc)
            ->where('quantity', '<=', $qty)
            ->orderByDesc('quantity')
            ->first();

        $price = $break?->price ?? (float) $product->base_price;

        return ['price' => (float) $price, 'source' => $break ? 'price_break' : 'base_price'];
    })->name('api.pricing.best');
});

/** -------------------- Orders (secure, idempotent, Equinox shape) -------------------- */
// POST /api/orders  (payload must match Equinox doc: orderLines[] with sku/quantity/unitPrice)
Route::middleware(['auth:sanctum', 'throttle:30,1'])->post('/orders', function (StoreOrderRequest $request, EquinoxClient $api) {
    $payload = $request->validated();

    try {
        $idem = $request->header('Idempotency-Key'); // optional idempotency support
        $eq = $api->post('orders', $payload, $idem);

        if (isset($eq['status']) && $eq['status'] === 'OrderInvalid') {
            return response()->json(['ok' => false, 'errors' => $eq['errors'] ?? []], 422);
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'status' => $eq['status'] ?? 'submitted',
            'external_ref' => $eq['orderId'] ?? $eq['id'] ?? null,
            'payload' => $payload,
            'response' => $eq,
        ]);

        return response()->json(['ok' => true, 'order_id' => $order->id, 'external' => $order->external_ref], 201);

    } catch (\Throwable $e) {
        report($e);
        return response()->json(['ok' => false, 'error' => 'Failed to submit order to Equinox'], 502);
    }
})->name('api.orders.store');
