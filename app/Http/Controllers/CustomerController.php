<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\EquinoxClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function __construct(private EquinoxClient $equinox)
    {
    }

    public function index(Request $request)
    {
        // Get customers from local Equinox sync
        $customers = Customer::query()
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->latest('created_at')
            ->paginate(20);

        // Calculate customer statistics
        $stats = [
            'total' => Customer::count(),
            'with_users' => Customer::whereNotNull('user_id')->count(),
            'without_users' => Customer::whereNull('user_id')->count(),
            'this_month' => Customer::whereMonth('created_at', now()->month)->count(),
        ];

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        // Get customer details from Equinox
        $equinoxCustomer = null;
        $customerOrders = [];
        $customerInvoices = [];
        $customerQuotes = [];

        try {
            if ($customer->equinox_id) {
                $equinoxCustomer = $this->equinox->get("customers/{$customer->equinox_id}");

                // Get customer's orders, invoices, and quotes
                try {
                    $customerOrders = $this->equinox->get("customers/{$customer->equinox_id}/orders");
                } catch (\Exception $e) {
                    $customerOrders = ['data' => []];
                }

                try {
                    $customerInvoices = $this->equinox->get("customers/{$customer->equinox_id}/invoices");
                } catch (\Exception $e) {
                    $customerInvoices = ['data' => []];
                }

                try {
                    $customerQuotes = $this->equinox->get("customers/{$customer->equinox_id}/quotes");
                } catch (\Exception $e) {
                    $customerQuotes = ['data' => []];
                }
            }
        } catch (\Exception $e) {
            logger()->warning("Failed to fetch customer data from Equinox: ".$e->getMessage());
        }

        // Calculate customer metrics
        $orders = $customerOrders['data'] ?? [];
        $invoices = $customerInvoices['data'] ?? [];
        $quotes = $customerQuotes['data'] ?? [];

        $totalOrderValue = collect($orders)->sum('total_amount');
        $totalInvoiceValue = collect($invoices)->sum('total_gross');
        $totalQuoteValue = collect($quotes)->sum('value');

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'equinoxCustomer' => $equinoxCustomer,
            'orders' => $orders,
            'invoices' => $invoices,
            'quotes' => $quotes,
            'metrics' => [
                'total_order_value' => $totalOrderValue,
                'total_invoice_value' => $totalInvoiceValue,
                'total_quote_value' => $totalQuoteValue,
                'order_count' => count($orders),
                'invoice_count' => count($invoices),
                'quote_count' => count($quotes),
                'last_order_date' => collect($orders)->max('created_at'),
                'avg_order_value' => count($orders) > 0 ? $totalOrderValue / count($orders) : 0,
            ],
        ]);
    }

    public function analyze($id)
    {
        $customer = Customer::findOrFail($id);

        // Get customer data for AI analysis
        try {
            $equinoxCustomer = $this->equinox->get("customers/{$customer->equinox_id}");
            $customerOrders = $this->equinox->get("customers/{$customer->equinox_id}/orders");
            $customerInvoices = $this->equinox->get("customers/{$customer->equinox_id}/invoices");

            // Calculate risk metrics
            $orders = $customerOrders['data'] ?? [];
            $invoices = $customerInvoices['data'] ?? [];

            $totalSpent = collect($invoices)->sum('total_gross');
            $orderFrequency = count($orders);
            $avgOrderValue = count($orders) > 0 ? $totalSpent / count($orders) : 0;

            // Simple risk scoring algorithm
            $riskScore = 50; // Base score

            if ($totalSpent > 10000)
                $riskScore -= 20; // High value customer
            if ($orderFrequency > 10)
                $riskScore -= 15; // Frequent customer
            if ($avgOrderValue > 1000)
                $riskScore -= 10; // High value orders

            $riskScore = max(0, min(100, $riskScore)); // Keep between 0-100

            $analysis = [
                'risk_score' => $riskScore,
                'risk_level' => $riskScore < 30 ? 'Low' : ($riskScore < 70 ? 'Medium' : 'High'),
                'total_spent' => $totalSpent,
                'order_frequency' => $orderFrequency,
                'avg_order_value' => round($avgOrderValue, 2),
                'credit_recommendation' => $riskScore < 50 ? 'Approve credit increase' : 'Monitor closely',
                'payment_behavior' => $riskScore < 40 ? 'Excellent' : ($riskScore < 70 ? 'Good' : 'Requires attention'),
                'customer_segment' => $totalSpent > 5000 ? 'Premium' : ($totalSpent > 1000 ? 'Standard' : 'Basic'),
            ];

        } catch (\Exception $e) {
            // Fallback analysis
            $analysis = [
                'risk_score' => 60,
                'risk_level' => 'Medium',
                'credit_recommendation' => 'Insufficient data for analysis',
                'payment_behavior' => 'Unknown',
                'customer_segment' => 'Unclassified',
            ];
        }

        return response()->json($analysis);
    }
}
