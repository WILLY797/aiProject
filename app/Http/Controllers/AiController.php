<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\AiService;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;

class AiController extends Controller
{
    public function __construct(
        protected AiService $aiService
    ) {
    }

    /**
     * Get AI service status
     */
    public function status(): JsonResponse
    {
        return response()->json($this->aiService->getStatus());
    }

    /**
     * Legacy handle method for backward compatibility
     */
    public function handle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
        ]);

        $response = $this->aiService->process($validated['input']);

        return response()->json(['output' => $response]);
    }

    /**
     * Process general AI request
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'input' => 'required|string|max:1000',
            'options' => 'array'
        ]);

        $response = $this->aiService->process(
            $request->input,
            $request->options ?? []
        );

        return response()->json([
            'input' => $request->input,
            'response' => $response
        ]);
    }

    /**
     * Generate product description
     */
    public function generateProductDescription(Request $request, $product_id): JsonResponse
    {
        $product = Product::findOrFail($product_id);

        $description = $this->aiService->generateProductDescription([
            'name' => $product->name,
            'sku' => $product->sku,
            'mpn' => $product->mpn,
            'description' => $product->description,
        ]);

        return response()->json([
            'product_id' => $product->id,
            'original_description' => $product->description,
            'ai_generated_description' => $description
        ]);
    }

    /**
     * Analyze customer data
     */
    public function analyzeCustomer(Request $request, $customer_id): JsonResponse
    {
        $customer = Customer::with(['accounts.orders'])->findOrFail($customer_id);

        $orders = $customer->accounts->flatMap->orders;
        $totalValue = $orders->sum('order_total_gross');

        $analysis = $this->aiService->analyzeCustomerData([
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'date' => $order->created_at,
                    'total' => $order->order_total_gross,
                    'status' => $order->status
                ];
            })->toArray(),
            'total_value' => $totalValue
        ]);

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'analysis' => $analysis
        ]);
    }

    /**
     * Get product recommendations for a customer
     */
    public function recommendProducts(Request $request, $customer_id): JsonResponse
    {
        $request->validate([
            'limit' => 'integer|min:1|max:10'
        ]);

        $customer = Customer::with(['accounts.orders'])->findOrFail($customer_id);
        $limit = $request->limit ?? 5;

        $orders = $customer->accounts->flatMap->orders;
        $availableProducts = Product::where('is_active', true)
            ->limit(50)
            ->get()
            ->toArray();

        $recommendations = $this->aiService->recommendProducts(
            $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'date' => $order->created_at,
                    'total' => $order->order_total_gross ?? 0,
                    'status' => $order->status
                ];
            })->toArray(),
            $availableProducts
        );

        return response()->json([
            'customer_id' => $customer->id,
            'recommendations' => array_slice($recommendations, 0, $limit)
        ]);
    }

    /**
     * Analyze order patterns
     */
    public function analyzeOrders(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'exists:equinox_customers,id',
            'account_id' => 'exists:equinox_accounts,id',
            'days' => 'integer|min:1|max:365'
        ]);

        $days = $request->days ?? 90;
        $query = Order::where('created_at', '>=', now()->subDays($days));

        if ($request->customer_id) {
            $customer = Customer::findOrFail($request->customer_id);
            $accountIds = $customer->accounts->pluck('id');
            $query->whereIn('account_id', $accountIds);
        } elseif ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        $orders = $query->get();

        $analysis = $this->aiService->analyzeOrderPatterns(
            $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'date' => $order->created_at,
                    'total' => $order->order_total_gross,
                    'status' => $order->status,
                    'shipping_method' => $order->shipping_method
                ];
            })->toArray()
        );

        return response()->json([
            'period_days' => $days,
            'orders_analyzed' => $orders->count(),
            'analysis' => $analysis
        ]);
    }

    /**
     * Generate search suggestions
     */
    public function searchSuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|max:100',
            'context' => 'array'
        ]);

        $suggestions = $this->aiService->generateSearchSuggestions(
            $request->input('query'),
            $request->input('context', [])
        );

        return response()->json([
            'original_query' => $request->input('query'),
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Classify a product
     */
    public function classifyProduct(Request $request, $product_id): JsonResponse
    {
        $product = Product::findOrFail($product_id);

        $classification = $this->aiService->classifyProduct([
            'name' => $product->name,
            'sku' => $product->sku,
            'mpn' => $product->mpn,
            'description' => $product->description,
        ]);

        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'classification' => $classification
        ]);
    }
}
