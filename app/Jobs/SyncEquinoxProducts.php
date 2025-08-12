<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Stock;
use App\Services\EquinoxClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncEquinoxProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 900;
    public int $tries = 2;

    public function handle(EquinoxClient $api): void
    {
        DB::transaction(function () use ($api) {
            foreach ($api->paginate('products', 200) as $page) {
                foreach ($page as $row) {
                    /** Map JSON -> DB columns (adjust to your schema) */
                    $p = Product::updateOrCreate(
                        ['equinox_id' => $row['id']],
                        [
                            'sku' => $row['code'] ?? null,
                            'name' => $row['name'] ?? '',
                            'description' => $row['description'] ?? null,
                            'price' => (float) ($row['price'] ?? 0),
                            'brand' => $row['brand'] ?? null,
                            'uom' => $row['uom'] ?? null,
                            'vat_rate' => (float) ($row['vat_rate'] ?? 0),
                            'is_active' => (bool) ($row['active'] ?? true),
                        ]
                    );

                    // stock per branch if present
                    foreach (($row['stock'] ?? []) as $s) {
                        $branch = Branch::firstOrCreate(['id' => $s['branch_id']], [
                            'name' => $s['branch_name'] ?? ('Branch '.$s['branch_id']),
                            'is_active' => true,
                        ]);
                        Stock::updateOrCreate(
                            ['product_id' => $p->id, 'branch_id' => $branch->id],
                            ['stock' => (int) ($s['on_hand'] ?? $s['qty'] ?? 0),
                                'unprocessed_order_qty' => (int) ($s['allocated'] ?? 0)]
                        );
                    }
                }
            }
        }, 3);
    }

    public function failed(Throwable $e): void
    {
        report($e);
    }
}
