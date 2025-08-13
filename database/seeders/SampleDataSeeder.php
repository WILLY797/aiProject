<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample user if not exists
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create sample products
        $products = [
            [
                'equinox_id' => 'PROD001',
                'sku' => 'MTR-SERVO-1NM',
                'mpn' => 'SRV1000NM',
                'name' => '1Nm Servo Motor with Encoder',
                'description' => 'High precision servo motor with integrated encoder for industrial automation',
                'base_price' => 299.99,
                'metadata' => json_encode(['category' => 'Motors', 'weight' => '2.5kg']),
            ],
            [
                'equinox_id' => 'PROD002',
                'sku' => 'MTR-SERVO-2NM',
                'mpn' => 'SRV2000NM',
                'name' => '2Nm Servo Motor with Encoder',
                'description' => 'Industrial grade servo motor with high torque output',
                'base_price' => 567.89,
                'metadata' => json_encode(['category' => 'Motors', 'weight' => '3.2kg']),
            ],
            [
                'equinox_id' => 'PROD003',
                'sku' => 'VFD-1HP-240V',
                'mpn' => 'VFD1HP240',
                'name' => '1HP Variable Frequency Drive 240V',
                'description' => 'Single phase input, three phase output VFD for motor control',
                'base_price' => 199.50,
                'metadata' => json_encode(['category' => 'Drives', 'voltage' => '240V']),
            ],
            [
                'equinox_id' => 'PROD004',
                'sku' => 'PLC-CPU-100',
                'mpn' => 'CPU100IO',
                'name' => 'PLC CPU Module 100 I/O',
                'description' => 'Programmable Logic Controller CPU with 100 digital I/O points',
                'base_price' => 899.00,
                'metadata' => json_encode(['category' => 'PLCs', 'io_points' => 100]),
            ],
            [
                'equinox_id' => 'PROD005',
                'sku' => 'HMI-7IN-COLOR',
                'mpn' => 'HMI7C',
                'name' => '7" Color HMI Touchscreen',
                'description' => 'Industrial touchscreen HMI with color display',
                'base_price' => 456.78,
                'metadata' => json_encode(['category' => 'HMI', 'screen_size' => '7 inch']),
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['equinox_id' => $productData['equinox_id']],
                $productData
            );
        }

        // Create sample customer
        Customer::firstOrCreate(
            ['equinox_id' => 'CUST001'],
            [
                'name' => 'Industrial Automation Co.',
                'email' => 'contact@industrial-auto.com',
                'phone' => '+1-555-0123',
                'billing_address' => json_encode([
                    'street' => '123 Industrial Way',
                    'city' => 'Manufacturing City',
                    'state' => 'TX',
                    'postal_code' => '12345',
                    'country' => 'USA'
                ]),
                'metadata' => json_encode(['industry' => 'Manufacturing', 'annual_revenue' => '$5M']),
            ]
        );

        echo "Sample data created successfully!\n";
        echo "- User: test@example.com / password\n";
        echo "- Products: ".Product::count()." total\n";
        echo "- Customers: ".Customer::count()." total\n";
    }
}
