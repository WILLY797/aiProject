<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get user and create token
$user = App\Models\User::first();
if (! $user) {
    echo "No user found! Please run the seeder first.\n";
    exit(1);
}

$token = $user->createToken('test-token')->plainTextToken;
echo "API Token: {$token}\n";

// Test AI service status endpoint
$statusUrl = 'http://localhost/aiProject/public/api/ai/status';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $statusUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '.$token,
    'Accept: application/json'
]);

$statusResponse = curl_exec($ch);
$statusHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\nTesting AI Status Endpoint: {$statusUrl}\n";
echo "HTTP Code: {$statusHttpCode}\n";
echo "Response: {$statusResponse}\n";

// Test AI service endpoint with cURL
$url = 'http://localhost/aiProject/public/api/ai/products/1/description';
$data = [
    'style' => 'professional'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$token,
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\nTesting API Endpoint: {$url}\n";
echo "HTTP Code: {$httpCode}\n";
echo "Response: {$response}\n";
