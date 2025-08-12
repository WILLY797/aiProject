<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Product;

// Home page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

// Dashboard (auth only)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Product list
Route::get('/products', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    $products = Product::query()
        ->when($q, fn ($w) =>
            $w->where('name', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%")
        )
        ->select('id', 'equinox_id', 'sku', 'name', 'base_price')
        ->orderBy('name')
        ->paginate(24)
        ->withQueryString();

    return Inertia::render('Products/Index', [
        'products' => $products,
        'q' => $q,
    ]);
})->name('products.index');

// Product detail + stock by branch
Route::get('/products/{product}', function (Product $product) {
    $stock = DB::table('branch_stocks')
        ->join('branches', 'branches.id', '=', 'branch_stocks.branch_id')
        ->where('branch_stocks.product_id', $product->id)
        ->select('branches.id as branch_id', 'branches.name', 'branch_stocks.stock', 'branch_stocks.unprocessed_order_qty')
        ->orderBy('branches.name')
        ->get();

    return Inertia::render('Products/Show', [
        'product' => $product,
        'stock' => $stock,
    ]);
})->name('products.show');

// Simple session cart
Route::get('/cart', function (Request $request) {
    $cart = $request->session()->get('cart', []);
    return Inertia::render('Cart/Index', [
        'cart' => $cart,
    ]);
})->name('cart.index');

Route::post('/cart/add', function (Request $request) {
    $data = $request->validate([
        'product_id' => ['required', 'integer', 'exists:equinox_products,id'],
        'qty' => ['required', 'integer', 'min:1', 'max:9999'],
    ]);
    $product = Product::findOrFail($data['product_id']);
    $cart = $request->session()->get('cart', []);
    $line = $cart[$product->id] ?? [
        'id' => $product->id,
        'sku' => $product->sku,
        'name' => $product->name,
        'price' => (float) $product->base_price,
        'qty' => 0,
    ];
    $line['qty'] += $data['qty'];
    $cart[$product->id] = $line;
    $request->session()->put('cart', $cart);

    return redirect()->route('cart.index');
})->name('cart.add');

Route::post('/cart/remove', function (Request $request) {
    $request->validate(['product_id' => ['required', 'integer']]);
    $cart = $request->session()->get('cart', []);
    unset($cart[(int) $request->input('product_id')]);
    $request->session()->put('cart', $cart);

    return redirect()->route('cart.index');
})->name('cart.remove');

// Checkout (UI only; order POST happens via API route)
Route::get('/checkout', function (Request $request) {
    $cart = $request->session()->get('cart', []);
    return Inertia::render('Checkout/Index', [
        'cart' => $cart,
    ]);
})->name('checkout.index');

// Authenticated profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
