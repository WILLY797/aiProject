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

// Features page
Route::get('/features', function () {
    return Inertia::render('Features', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('features');

// About page
Route::get('/about', function () {
    return Inertia::render('About', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('about');

// Contact page
Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('contact');

// Demo page
Route::get('/demo', function () {
    return Inertia::render('Demo', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('demo');

// Contact form submission
Route::post('/contact', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'company' => 'nullable|string|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:2000',
        'interest' => 'required|string|max:255',
    ]);

    // Here you would typically send an email or save to database
    // For now, we'll just return a success response

    return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
})->name('contact.submit');

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

// Business Management Routes (protected)
Route::middleware(['auth'])->group(function () {
    // Orders Management
    Route::get('/orders', function (Request $request) {
        $query = \App\Models\Order::with(['account.customer'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $accounts = \App\Models\Account::select('id', 'name')->orderBy('name')->get();

        $stats = [
            'total' => \App\Models\Order::count(),
            'total_value' => \App\Models\Order::sum('order_total_gross'),
            'pending' => \App\Models\Order::where('status', 'pending')->count(),
            'this_month' => \App\Models\Order::whereMonth('created_at', now()->month)->count()
        ];

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'accounts' => $accounts,
            'filters' => $request->only(['status', 'account_id', 'date_from', 'date_to']),
            'stats' => $stats
        ]);
    })->name('orders.index');

    Route::get('/orders/{order}', function (\App\Models\Order $order) {
        $order->load(['account.customer', 'order_lines.product']);
        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    })->name('orders.show');

    Route::patch('/orders/{order}', function (Request $request, \App\Models\Order $order) {
        $order->update($request->only(['status', 'notes']));
        return redirect()->back();
    })->name('orders.update');

    // Invoices Management
    Route::get('/invoices', function (Request $request) {
        $query = \App\Models\Invoice::with(['account.customer', 'order'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $accounts = \App\Models\Account::select('id', 'name')->orderBy('name')->get();

        $stats = [
            'total' => \App\Models\Invoice::count(),
            'total_value' => \App\Models\Invoice::sum('invoice_total_gross'),
            'overdue' => \App\Models\Invoice::where('due_date', '<', now())->where('status', '!=', 'paid')->count(),
            'paid' => \App\Models\Invoice::where('status', 'paid')->count()
        ];

        $aging = [
            'current' => \App\Models\Invoice::where('due_date', '>', now())->sum('invoice_total_gross'),
            'days_30' => \App\Models\Invoice::whereBetween('due_date', [now()->subDays(30), now()])->sum('invoice_total_gross'),
            'days_60' => \App\Models\Invoice::whereBetween('due_date', [now()->subDays(60), now()->subDays(30)])->sum('invoice_total_gross'),
            'days_90' => \App\Models\Invoice::whereBetween('due_date', [now()->subDays(90), now()->subDays(60)])->sum('invoice_total_gross'),
            'over_90' => \App\Models\Invoice::where('due_date', '<', now()->subDays(90))->sum('invoice_total_gross')
        ];

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'accounts' => $accounts,
            'filters' => $request->only(['status', 'account_id', 'date_from', 'date_to']),
            'stats' => $stats,
            'aging' => $aging
        ]);
    })->name('invoices.index');

    Route::get('/invoices/{invoice}', function (\App\Models\Invoice $invoice) {
        $invoice->load(['account.customer', 'order']);
        return Inertia::render('Invoices/Show', [
            'invoice' => $invoice
        ]);
    })->name('invoices.show');

    // Quotes Management
    Route::get('/quotes', function (Request $request) {
        $query = \App\Models\Quote::with(['account.customer'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->account_id, fn ($q) => $q->where('account_id', $request->account_id))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $quotes = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $accounts = \App\Models\Account::select('id', 'name')->orderBy('name')->get();

        $stats = [
            'total' => \App\Models\Quote::count(),
            'total_value' => \App\Models\Quote::sum('quote_total_gross'),
            'accepted' => \App\Models\Quote::where('status', 'accepted')->count(),
            'conversion_rate' => round((\App\Models\Quote::where('status', 'accepted')->count() / max(\App\Models\Quote::count(), 1)) * 100, 1)
        ];

        $analytics = [
            'avg_quote_value' => \App\Models\Quote::avg('quote_total_gross'),
            'avg_response_time' => 3, // Placeholder
            'win_rate' => $stats['conversion_rate'],
            'top_products' => [] // Placeholder
        ];

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes,
            'accounts' => $accounts,
            'filters' => $request->only(['status', 'account_id', 'date_from', 'date_to']),
            'stats' => $stats,
            'analytics' => $analytics
        ]);
    })->name('quotes.index');

    Route::get('/quotes/{quote}', function (\App\Models\Quote $quote) {
        $quote->load(['account.customer', 'products']);
        return Inertia::render('Quotes/Show', [
            'quote' => $quote
        ]);
    })->name('quotes.show');

    // Customer Accounts Management
    Route::get('/customers', function (Request $request) {
        $query = \App\Models\Account::with(['customer'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn ($q) => $q->where('account_status', $request->status))
            ->when($request->sort, function ($q) use ($request) {
                switch ($request->sort) {
                    case 'balance':
                        return $q->orderBy('balance', 'desc');
                    case 'credit_limit':
                        return $q->orderBy('credit_limit', 'desc');
                    default:
                        return $q->orderBy('name');
                }
            });

        $accounts = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => \App\Models\Account::count(),
            'total_credit' => \App\Models\Account::sum('credit_limit'),
            'outstanding' => \App\Models\Account::sum('balance'),
            'active_this_month' => \App\Models\Account::whereHas('orders', fn ($q) => $q->whereMonth('created_at', now()->month))->count()
        ];

        $riskAnalysis = [
            'low_risk' => \App\Models\Account::whereRaw('balance / NULLIF(credit_limit, 0) < 0.3')->count(),
            'medium_risk' => \App\Models\Account::whereRaw('balance / NULLIF(credit_limit, 0) BETWEEN 0.3 AND 0.7')->count(),
            'high_risk' => \App\Models\Account::whereRaw('balance / NULLIF(credit_limit, 0) > 0.7')->count()
        ];

        return Inertia::render('Customers/Index', [
            'accounts' => $accounts,
            'filters' => $request->only(['search', 'status', 'credit_level', 'sort']),
            'stats' => $stats,
            'riskAnalysis' => $riskAnalysis
        ]);
    })->name('accounts.index');

    Route::get('/customers/{account}', function (\App\Models\Account $account) {
        $account->load(['customer', 'orders', 'quotes', 'invoices']);
        return Inertia::render('Customers/Show', [
            'account' => $account
        ]);
    })->name('accounts.show');
});

// Authenticated profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
