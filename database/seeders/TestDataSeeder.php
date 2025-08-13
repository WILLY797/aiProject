<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::firstOrCreate([
            'email' => 'test@aiproject.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create test customers first (accounts need customer_id)
        $customers = [
            [
                'equinox_id' => 'CUST001',
                'name' => 'Industrial Solutions Ltd',
                'email' => 'contact@industrialsolutions.com',
                'phone' => '+1-555-1001',
                'billing_address' => json_encode([
                    'street' => '456 Manufacturing Street',
                    'city' => 'Industrial Park',
                    'state' => 'CA',
                    'zip' => '90210'
                ]),
                'shipping_address' => json_encode([
                    'street' => '456 Manufacturing Street',
                    'city' => 'Industrial Park',
                    'state' => 'CA',
                    'zip' => '90210'
                ]),
            ],
            [
                'equinox_id' => 'CUST002',
                'name' => 'ElectroPro Services',
                'email' => 'info@electropro.com',
                'phone' => '+1-555-2002',
                'billing_address' => json_encode([
                    'street' => '789 Electrical Avenue',
                    'city' => 'Tech District',
                    'state' => 'CA',
                    'zip' => '90211'
                ]),
                'shipping_address' => json_encode([
                    'street' => '789 Electrical Avenue',
                    'city' => 'Tech District',
                    'state' => 'CA',
                    'zip' => '90211'
                ]),
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customer = Customer::firstOrCreate([
                'equinox_id' => $customerData['equinox_id']
            ], $customerData);
            $createdCustomers[] = $customer;
        }

        // Create test account for first customer
        $account = Account::firstOrCreate([
            'equinox_id' => 'TEST001'
        ], [
            'customer_id' => $createdCustomers[0]->id,
            'name' => 'Test Account',
            'code' => 'TEST001',
            'credit_limit' => 10000.00,
            'balance' => 2500.00,
        ]);

        // Create test branch
        $branch = Branch::firstOrCreate([
            'name' => 'Main Branch'
        ], [
            'location' => '123 Industrial Way, Business City',
            'is_active' => true,
        ]);

        // Create test products
        $products = [
            [
                'equinox_id' => 'PROD001',
                'sku' => 'CAB-ETH-50M',
                'mpn' => 'ETH50CAT6',
                'name' => 'Ethernet Cable CAT6 50m Industrial Grade',
                'description' => 'High-quality CAT6 ethernet cable suitable for industrial environments with enhanced shielding and durability for harsh conditions.',
                'base_price' => 89.99,
                'last_updated' => now(),
                'metadata' => json_encode([
                    'category' => 'Networking Cables',
                    'supplier' => 'TechCorp',
                    'weight' => 2.5,
                    'dimensions' => '50m x 8mm',
                    'color' => 'Blue',
                    'rating' => 'CAT6A'
                ]),
            ],
            [
                'equinox_id' => 'PROD002',
                'sku' => 'PWR-INV-5KW',
                'mpn' => 'INV5000P',
                'name' => '5KW Power Inverter Industrial',
                'description' => 'Robust 5KW power inverter designed for continuous industrial applications with advanced protection features.',
                'base_price' => 1299.99,
                'last_updated' => now(),
                'metadata' => json_encode([
                    'category' => 'Power Equipment',
                    'supplier' => 'PowerMax',
                    'weight' => 15.2,
                    'dimensions' => '300x200x100mm',
                    'input_voltage' => '24V DC',
                    'output_voltage' => '240V AC'
                ]),
            ],
            [
                'equinox_id' => 'PROD003',
                'sku' => 'SEN-TEMP-Digital',
                'mpn' => 'TEMP101D',
                'name' => 'Digital Temperature Sensor IP67',
                'description' => 'Precision digital temperature sensor with IP67 rating for outdoor and industrial monitoring applications.',
                'base_price' => 45.50,
                'last_updated' => now(),
                'metadata' => json_encode([
                    'category' => 'Sensors',
                    'supplier' => 'SensorTech',
                    'weight' => 0.3,
                    'dimensions' => '50x30x20mm',
                    'operating_temp' => '-40°C to +125°C',
                    'accuracy' => '±0.5°C'
                ]),
            ],
            [
                'equinox_id' => 'PROD004',
                'sku' => 'MTR-SERVO-2NM',
                'mpn' => 'SRV2000NM',
                'name' => '2Nm Servo Motor with Encoder',
                'description' => 'High precision servo motor with integrated encoder perfect for automation and robotics applications.',
                'base_price' => 567.89,
                'last_updated' => now(),
                'metadata' => json_encode([
                    'category' => 'Motors',
                    'supplier' => 'MotorPro',
                    'weight' => 3.8,
                    'dimensions' => '120x120x80mm',
                    'torque' => '2.0 Nm',
                    'speed' => '3000 RPM'
                ]),
            ],
            [
                'equinox_id' => 'PROD005',
                'sku' => 'SW-IND-8PORT',
                'mpn' => 'IND8SW24V',
                'name' => '8-Port Industrial Ethernet Switch',
                'description' => 'Rugged 8-port managed ethernet switch designed for industrial networks with extended temperature range.',
                'base_price' => 234.75,
                'last_updated' => now(),
                'metadata' => json_encode([
                    'category' => 'Networking',
                    'supplier' => 'NetWork Industrial',
                    'weight' => 1.2,
                    'dimensions' => '150x100x45mm',
                    'ports' => '8x RJ45 10/100Mbps',
                    'power' => '24V DC'
                ]),
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate([
                'equinox_id' => $productData['equinox_id']
            ], $productData);
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Created: '.User::count().' users');
        $this->command->info('Created: '.Customer::count().' customers');
        $this->command->info('Created: '.Product::count().' products');
    }
}
