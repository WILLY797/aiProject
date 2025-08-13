<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\EquinoxClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(private EquinoxClient $equinox)
    {
    }

    public function index(Request $request)
    {
        // Get orders from both local database and Equinox API
        $localOrders = Order::with('user')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        // Get additional order statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return Inertia::render('Orders/Index', [
            'orders' => $localOrders,
            'stats' => $stats,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);

        // Get order details from Equinox if we have an external reference
        $equinoxOrder = null;
        if ($order->external_ref) {
            try {
                $equinoxOrder = $this->equinox->get("orders/{$order->external_ref}");
            } catch (\Exception $e) {
                // Handle Equinox API errors gracefully
                logger()->warning("Failed to fetch order from Equinox: ".$e->getMessage());
            }
        }

        // Get products mentioned in the order for AI analysis
        $products = [];
        if (isset($order->payload['orderLines'])) {
            $skus = collect($order->payload['orderLines'])->pluck('sku')->unique();
            $products = Product::whereIn('sku', $skus)->get();
        }

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'equinoxOrder' => $equinoxOrder,
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Orders/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'orderLines' => 'required|array|min:1',
            'orderLines.*.sku' => 'required|string',
            'orderLines.*.quantity' => 'required|integer|min:1',
            'orderLines.*.unitPrice' => 'required|numeric|min:0',
        ]);

        try {
            // Submit to Equinox first
            $equinoxResponse = $this->equinox->post('orders', $validated);

            // Create local order record
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => $equinoxResponse['status'] ?? 'submitted',
                'external_ref' => $equinoxResponse['orderId'] ?? $equinoxResponse['id'] ?? null,
                'payload' => $validated,
                'response' => $equinoxResponse,
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create order: '.$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        // Note: Status updates would need to be handled via Equinox webhook or separate sync job
        // as the API only supports GET and POST operations

        return back()->with('success', 'Order updated successfully');
    }
}
