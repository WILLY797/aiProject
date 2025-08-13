<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AiService;
use App\Models\Product;
use App\Models\Customer;

class TestAiService extends Command
{
    protected $signature = 'ai:test {--feature= : Specific feature to test (status|process|product|customer|search)}';

    protected $description = 'Test the AI service functionality';

    public function __construct(
        protected AiService $aiService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $feature = $this->option('feature');

        $this->info('🤖 Testing AI Service...');
        $this->newLine();

        if (! $feature || $feature === 'status') {
            $this->testStatus();
        }

        if (! $feature || $feature === 'process') {
            $this->testBasicProcessing();
        }

        if (! $feature || $feature === 'product') {
            $this->testProductFeatures();
        }

        if (! $feature || $feature === 'customer') {
            $this->testCustomerFeatures();
        }

        if (! $feature || $feature === 'search') {
            $this->testSearchSuggestions();
        }

        $this->newLine();
        $this->info('✅ AI Service testing complete!');

        return 0;
    }

    private function testStatus(): void
    {
        $this->info('📊 Testing AI Service Status...');
        $status = $this->aiService->getStatus();

        $this->table(
            ['Property', 'Value'],
            [
                ['Available', $status['available'] ? '✅ Yes' : '❌ No'],
                ['Provider', $status['provider']],
                ['Model', $status['model']],
                ['Features', implode(', ', $status['features'])],
            ]
        );
        $this->newLine();
    }

    private function testBasicProcessing(): void
    {
        $this->info('💬 Testing Basic Processing...');

        $testInputs = [
            'Hello, how are you?',
            'What is the best product for industrial use?',
            'Can you help me find components for electrical work?'
        ];

        foreach ($testInputs as $input) {
            $this->line("Input: {$input}");
            $response = $this->aiService->process($input);
            $this->line("Output: ".substr($response, 0, 100).'...');
            $this->newLine();
        }
    }

    private function testProductFeatures(): void
    {
        $this->info('🛍️ Testing Product Features...');

        // Test product description generation
        $product = Product::first();
        if ($product) {
            $this->line("Generating description for: {$product->name}");
            $description = $this->aiService->generateProductDescription([
                'name' => $product->name,
                'sku' => $product->sku,
                'mpn' => $product->mpn,
                'description' => $product->description,
            ]);
            $this->line("Generated: ".substr($description, 0, 150).'...');
            $this->newLine();

            // Test product classification
            $this->line("Classifying product: {$product->name}");
            $classification = $this->aiService->classifyProduct([
                'name' => $product->name,
                'sku' => $product->sku,
                'description' => $product->description,
            ]);
            $this->table(
                ['Property', 'Value'],
                [
                    ['Category', $classification['category']],
                    ['Tags', implode(', ', $classification['tags'])],
                    ['Confidence', $classification['confidence']],
                ]
            );
        } else {
            $this->warn('No products found in database. Skipping product tests.');
        }
        $this->newLine();
    }

    private function testCustomerFeatures(): void
    {
        $this->info('👥 Testing Customer Features...');

        $customer = Customer::with(['accounts.orders'])->first();
        if ($customer) {
            $orders = $customer->accounts->flatMap->orders;
            $totalValue = $orders->sum('order_total_gross');

            $this->line("Analyzing customer: {$customer->name}");
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

            $this->table(
                ['Property', 'Value'],
                [
                    ['Segment', $analysis['segment']],
                    ['Insights', implode('; ', $analysis['insights'])],
                    ['Recommendations', implode('; ', $analysis['recommendations'])],
                ]
            );

            // Test product recommendations
            if ($orders->count() > 0) {
                $this->line("Generating product recommendations...");
                $availableProducts = Product::limit(10)->get()->toArray();
                $recommendations = $this->aiService->recommendProducts(
                    $orders->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'date' => $order->created_at,
                            'total' => $order->order_total_gross,
                        ];
                    })->toArray(),
                    $availableProducts
                );
                $this->line("Recommended ".count($recommendations)." products");
            }
        } else {
            $this->warn('No customers found in database. Skipping customer tests.');
        }
        $this->newLine();
    }

    private function testSearchSuggestions(): void
    {
        $this->info('🔍 Testing Search Suggestions...');

        $testQueries = [
            'electrical cables',
            'industrial tools',
            'safety equipment',
            'pipe fittings'
        ];

        foreach ($testQueries as $query) {
            $suggestions = $this->aiService->generateSearchSuggestions($query);
            $this->line("Query: {$query}");
            $this->line("Suggestions: ".implode(', ', $suggestions));
            $this->newLine();
        }
    }
}
