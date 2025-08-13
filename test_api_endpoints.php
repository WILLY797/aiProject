<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Test API endpoints
$baseUrl = 'http://127.0.0.1:8000/api';

echo "=== Testing AI Project API Endpoints ===\n\n";

// Test public products endpoint
echo "1. Testing Public Products API...\n";
try {
    $response = Http::get("{$baseUrl}/products");
    if ($response->successful()) {
        $data = $response->json();
        echo "✅ Products API working - Found ".count($data['data'] ?? [])." products\n";
    } else {
        echo "❌ Products API failed - Status: ".$response->status()."\n";
    }
} catch (Exception $e) {
    echo "❌ Products API error: ".$e->getMessage()."\n";
}

// Test AI status endpoint (this requires auth, so it should fail)
echo "\n2. Testing AI Status API (should require auth)...\n";
try {
    $response = Http::get("{$baseUrl}/ai/status");
    if ($response->status() === 401) {
        echo "✅ AI API properly protected - Returns 401 without auth\n";
    } else {
        echo "❌ AI API not properly protected - Status: ".$response->status()."\n";
    }
} catch (Exception $e) {
    echo "❌ AI API error: ".$e->getMessage()."\n";
}

// Test Equinox client health
echo "\n3. Testing Equinox Client...\n";
try {
    $equinoxClient = new \App\Services\EquinoxClient();
    $health = $equinoxClient->health();
    if ($health) {
        echo "✅ Equinox API connection working\n";
    } else {
        echo "⚠️ Equinox API connection failed (expected if no API key configured)\n";
    }
} catch (Exception $e) {
    echo "⚠️ Equinox API error: ".$e->getMessage()."\n";
}

// Test database models
echo "\n4. Testing Database Models...\n";
try {
    require_once 'bootstrap/app.php';
    $app = require_once 'bootstrap/app.php';

    // Test Product model
    $productCount = \App\Models\Product::count();
    echo "✅ Product model working - ".$productCount." products in database\n";

    // Test User model
    $userCount = \App\Models\User::count();
    echo "✅ User model working - ".$userCount." users in database\n";

    // Test Order model
    $orderCount = \App\Models\Order::count();
    echo "✅ Order model working - ".$orderCount." orders in database\n";

} catch (Exception $e) {
    echo "❌ Database models error: ".$e->getMessage()."\n";
}

echo "\n=== Test Complete ===\n";
