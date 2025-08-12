<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Services\EquinoxClient;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These power the frontend for products, stock, and order submission.
| Public product endpoints are lightly throttled.
| Order creation is protected by auth:sanctum.
*/

Route::middleware('throttle:120,1')->group(function () {
    // List products: /api/products?q=&branch_id=
    Route::get('/products', function (Request $request) {
        $q = trim((string) $request->query('q', ''));
        $branchId = $request->integer('branch_id');

        $query = Product::query()
            ->when($q, fn ($w) =>
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
            )
            ->select('id', 'equinox_id', 'sku', 'name', 'base_price')
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

    // Product detail: /api/products/{id}
    Route::get('/products/{id}', function ($id) {
        return Product::query()
            ->select('id', 'equinox_id', 'sku', 'name', 'description', 'base_price', 'metadata')
            ->findOrFail($id);
    })->name('api.products.show');

    // Per-branch stock: /api/products/{id}/stock
    Route::get('/products/{id}/stock', function ($id) {
        return DB::table('branch_stocks')
            ->join('branches', 'branches.id', '=', 'branch_stocks.branch_id')
            ->where('branch_stocks.product_id', $id)
            ->select('branches.id as branch_id', 'branches.name', 'branch_stocks.stock', 'branch_stocks.unprocessed_order_qty')
            ->orderBy('branches.name')
            ->get();
    })->name('api.products.stock');
});

// Order creation (requires auth)
Route::middleware(['auth:sanctum', 'throttle:30,1'])->post('/orders', function (Request $request, EquinoxClient $api) {
    $payload = $request->validate([
        'customer_ref' => ['nullable', 'string', 'max:100'],
        'delivery' => ['required', 'array'],
        'delivery.method' => ['required', 'string', 'max:50'],
        'delivery.address' => ['required', 'array'],
        'delivery.address.line1' => ['required', 'string', 'max:255'],
        'delivery.address.city' => ['required', 'string', 'max:120'],
        'delivery.address.postcode' => ['required', 'string', 'max:20'],
        'lines' => ['required', 'array', 'min:1'],
        'lines.*.product_id' => ['required', 'integer', 'exists:equinox_products,id'],
        'lines.*.qty' => ['required', 'integer', 'min:1', 'max:9999'],
        'lines.*.price' => ['nullable', 'numeric', 'min:0'],
        'notes' => ['nullable', 'string', 'max:1000'],
    ]);

    // Build Equinox payload
    $lines = collect($payload['lines'])->map(function ($l) {
        $p = Product::find($l['product_id']);
        return [
            'product_id' => $p->equinox_id,
            'sku' => $p->sku,
            'qty' => (int) $l['qty'],
            'price' => isset($l['price']) ? (float) $l['price'] : (float) $p->base_price,
        ];
    })->values()->all();

    $eqPayload = [
        'customer_reference' => $payload['customer_ref'] ?? null,
        'delivery' => $payload['delivery'],
        'lines' => $lines,
        'notes' => $payload['notes'] ?? null,
        'meta' => ['user_id' => $request->user()->id],
    ];

    // TODO: Replace this with actual POST to Equinox /orders
    // $response = $api->post('orders', $eqPayload);

    // Persist local mirror
    $order = Order::create([
        'user_id' => $request->user()->id,
        'status' => 'pending',
        'payload' => $eqPayload,
        'external_ref' => null, // fill from $response if available
    ]);

    return response()->json([
        'ok' => true,
        'order_id' => $order->id,
        'external' => $order->external_ref,
    ], 201);
})->name('api.orders.store');
