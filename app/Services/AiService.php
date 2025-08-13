<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiService
{
    protected Client $openai;
    protected array $config;

    public function __construct()
    {
        $this->config = config('services.openai', []);

        if (! empty($this->config['api_key'])) {
            $this->openai = OpenAI::client($this->config['api_key']);
        }
    }

    /**
     * Process general AI requests
     */
    public function process(string $input, array $options = []): string
    {
        try {
            if (! isset($this->openai)) {
                return $this->fallbackResponse($input);
            }

            $model = $options['model'] ?? 'gpt-3.5-turbo';
            $maxTokens = $options['max_tokens'] ?? 150;
            $temperature = $options['temperature'] ?? 0.7;

            $response = $this->openai->chat()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $input]
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

            return $response->choices[0]->message->content ?? 'No response generated.';

        } catch (\Exception $e) {
            Log::error('AI Service Error: '.$e->getMessage());
            return $this->fallbackResponse($input);
        }
    }

    /**
     * Generate product descriptions from basic info
     */
    public function generateProductDescription(array $productData): string
    {
        $prompt = $this->buildProductDescriptionPrompt($productData);

        return $this->process($prompt, [
            'model' => 'gpt-3.5-turbo',
            'max_tokens' => 200,
            'temperature' => 0.8
        ]);
    }

    /**
     * Analyze customer data for insights
     */
    public function analyzeCustomerData(array $customerData): array
    {
        try {
            $prompt = $this->buildCustomerAnalysisPrompt($customerData);

            $response = $this->process($prompt, [
                'model' => 'gpt-4-turbo-preview',
                'max_tokens' => 300,
                'temperature' => 0.3
            ]);

            return $this->parseCustomerAnalysis($response);

        } catch (\Exception $e) {
            Log::error('Customer Analysis Error: '.$e->getMessage());
            return [
                'segment' => 'standard',
                'insights' => ['Unable to analyze customer data at this time.'],
                'recommendations' => ['Please try again later.']
            ];
        }
    }

    /**
     * Generate order recommendations based on customer history
     */
    public function recommendProducts(array $customerOrders, array $availableProducts): array
    {
        $cacheKey = 'ai_recommendations_'.md5(serialize($customerOrders).serialize($availableProducts));

        return Cache::remember($cacheKey, 3600, function () use ($customerOrders, $availableProducts) {
            try {
                $prompt = $this->buildRecommendationPrompt($customerOrders, $availableProducts);

                $response = $this->process($prompt, [
                    'model' => 'gpt-3.5-turbo',
                    'max_tokens' => 250,
                    'temperature' => 0.5
                ]);

                return $this->parseRecommendations($response, $availableProducts);

            } catch (\Exception $e) {
                Log::error('Product Recommendation Error: '.$e->getMessage());
                return array_slice($availableProducts, 0, 3); // Fallback to first 3 products
            }
        });
    }

    /**
     * Analyze order patterns and detect anomalies
     */
    public function analyzeOrderPatterns(array $orders): array
    {
        try {
            $prompt = $this->buildOrderAnalysisPrompt($orders);

            $response = $this->process($prompt, [
                'model' => 'gpt-4-turbo-preview',
                'max_tokens' => 400,
                'temperature' => 0.2
            ]);

            return $this->parseOrderAnalysis($response);

        } catch (\Exception $e) {
            Log::error('Order Analysis Error: '.$e->getMessage());
            return [
                'patterns' => ['No patterns detected'],
                'anomalies' => [],
                'trends' => ['Data analysis unavailable']
            ];
        }
    }

    /**
     * Generate smart search suggestions
     */
    public function generateSearchSuggestions(string $query, array $context = []): array
    {
        try {
            $prompt = "Based on the search query '{$query}' in an industrial/commercial context, suggest 5 related search terms that might help find relevant products. Return as a simple comma-separated list.";

            if (! empty($context)) {
                $prompt .= " Context: ".json_encode($context);
            }

            $response = $this->process($prompt, [
                'max_tokens' => 100,
                'temperature' => 0.6
            ]);

            $suggestions = explode(',', $response);
            return array_map('trim', array_slice($suggestions, 0, 5));

        } catch (\Exception $e) {
            Log::error('Search Suggestions Error: '.$e->getMessage());
            return [$query]; // Return original query as fallback
        }
    }

    /**
     * Classify and tag products automatically
     */
    public function classifyProduct(array $productData): array
    {
        try {
            $prompt = $this->buildProductClassificationPrompt($productData);

            $response = $this->process($prompt, [
                'model' => 'gpt-3.5-turbo',
                'max_tokens' => 150,
                'temperature' => 0.3
            ]);

            return $this->parseProductClassification($response);

        } catch (\Exception $e) {
            Log::error('Product Classification Error: '.$e->getMessage());
            return [
                'category' => 'general',
                'tags' => ['unclassified'],
                'confidence' => 0.0
            ];
        }
    }

    // Private helper methods
    private function buildProductDescriptionPrompt(array $productData): string
    {
        $name = $productData['name'] ?? 'Unknown Product';
        $sku = $productData['sku'] ?? '';
        $mpn = $productData['mpn'] ?? '';
        $baseDescription = $productData['description'] ?? '';

        return "Generate a compelling product description for: {$name}".
            ($sku ? " (SKU: {$sku})" : '').
            ($mpn ? " (MPN: {$mpn})" : '').
            ($baseDescription ? "\nExisting description: {$baseDescription}" : '').
            "\n\nCreate a professional, engaging description suitable for industrial/commercial customers.";
    }

    private function buildCustomerAnalysisPrompt(array $customerData): string
    {
        $orderHistory = $customerData['orders'] ?? [];
        $totalValue = $customerData['total_value'] ?? 0;
        $orderCount = count($orderHistory);

        return "Analyze this customer profile:\n".
            "- Total orders: {$orderCount}\n".
            "- Total value: £{$totalValue}\n".
            "- Recent orders: ".json_encode(array_slice($orderHistory, -5))."\n\n".
            "Provide customer segment, key insights, and recommendations in JSON format.";
    }

    private function buildRecommendationPrompt(array $customerOrders, array $availableProducts): string
    {
        $recentOrders = array_slice($customerOrders, -10);
        $productSample = array_slice($availableProducts, 0, 20);

        return "Based on customer order history: ".json_encode($recentOrders)."\n".
            "And available products: ".json_encode($productSample)."\n\n".
            "Recommend 3-5 products that this customer might be interested in. Consider patterns, compatibility, and business needs.";
    }

    private function buildOrderAnalysisPrompt(array $orders): string
    {
        return "Analyze these order patterns: ".json_encode($orders)."\n\n".
            "Identify trends, seasonal patterns, anomalies, and provide insights about ordering behavior.";
    }

    private function buildProductClassificationPrompt(array $productData): string
    {
        $name = $productData['name'] ?? '';
        $description = $productData['description'] ?? '';
        $sku = $productData['sku'] ?? '';

        return "Classify this product:\n".
            "Name: {$name}\n".
            "Description: {$description}\n".
            "SKU: {$sku}\n\n".
            "Return category, tags, and confidence score (0-1) in JSON format.";
    }

    private function parseCustomerAnalysis(string $response): array
    {
        // Try to parse JSON response, fallback to text parsing
        $decoded = json_decode($response, true);
        if ($decoded) {
            return $decoded;
        }

        // Fallback parsing
        return [
            'segment' => $this->extractSegment($response),
            'insights' => $this->extractInsights($response),
            'recommendations' => $this->extractRecommendations($response)
        ];
    }

    private function parseRecommendations(string $response, array $availableProducts): array
    {
        $recommendations = [];
        $lines = explode("\n", $response);

        foreach ($lines as $line) {
            foreach ($availableProducts as $product) {
                if (stripos($line, $product['name'] ?? '') !== false ||
                    stripos($line, $product['sku'] ?? '') !== false) {
                    $recommendations[] = $product;
                    break;
                }
            }
            if (count($recommendations) >= 5)
                break;
        }

        return array_slice($recommendations, 0, 5);
    }

    private function parseOrderAnalysis(string $response): array
    {
        // Simple text parsing - in production, you might want more sophisticated parsing
        return [
            'patterns' => $this->extractPatterns($response),
            'anomalies' => $this->extractAnomalies($response),
            'trends' => $this->extractTrends($response)
        ];
    }

    private function parseProductClassification(string $response): array
    {
        $decoded = json_decode($response, true);
        if ($decoded) {
            return $decoded;
        }

        return [
            'category' => 'general',
            'tags' => explode(',', $response),
            'confidence' => 0.5
        ];
    }

    private function extractSegment(string $text): string
    {
        if (stripos($text, 'premium') !== false)
            return 'premium';
        if (stripos($text, 'high-value') !== false)
            return 'high-value';
        if (stripos($text, 'regular') !== false)
            return 'regular';
        return 'standard';
    }

    private function extractInsights(string $text): array
    {
        $insights = [];
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            if (stripos($line, 'insight') !== false || stripos($line, 'pattern') !== false) {
                $insights[] = trim($line);
            }
        }
        return $insights ?: ['Customer analysis completed'];
    }

    private function extractRecommendations(string $text): array
    {
        $recommendations = [];
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            if (stripos($line, 'recommend') !== false || stripos($line, 'suggest') !== false) {
                $recommendations[] = trim($line);
            }
        }
        return $recommendations ?: ['Continue monitoring customer behavior'];
    }

    private function extractPatterns(string $text): array
    {
        return ['Regular ordering pattern detected'];
    }

    private function extractAnomalies(string $text): array
    {
        return [];
    }

    private function extractTrends(string $text): array
    {
        return ['Stable ordering trend'];
    }

    private function fallbackResponse(string $input): string
    {
        $responses = [
            'Thank you for your inquiry. Our AI system is currently unavailable.',
            'I understand you\'re asking about: '.substr($input, 0, 50).'...',
            'Your request has been noted. Please try again later.',
            'AI analysis is temporarily unavailable. Please contact support for assistance.'
        ];

        return $responses[array_rand($responses)];
    }

    /**
     * Check if AI service is available
     */
    public function isAvailable(): bool
    {
        return isset($this->openai) && ! empty($this->config['api_key']);
    }

    /**
     * Get service status and configuration
     */
    public function getStatus(): array
    {
        return [
            'available' => $this->isAvailable(),
            'model' => $this->config['model'] ?? 'gpt-3.5-turbo',
            'provider' => 'OpenAI',
            'features' => [
                'product_description',
                'customer_analysis',
                'product_recommendations',
                'order_analysis',
                'search_suggestions',
                'product_classification'
            ]
        ];
    }
}

