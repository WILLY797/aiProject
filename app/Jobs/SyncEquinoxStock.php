<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Stock;
use App\Services\EquinoxClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SyncEquinoxStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 2;

    public function handle(EquinoxClient $api): void
    {
        // If Equinox supports paging here, adapt paginate('stock', ...)
        $pages = $api->paginate('stock', 200); // endpoint name may be 'stock' or 'products/stock'; keep consistent with your server

        foreach ($pages as $items) {
            foreach (array_chunk($items, 50) as $chunk) {
                DB::transaction(function () use ($chunk) {
                    foreach ($chunk as $row) {
                        $pid = (string) ($row['productId'] ?? '');
                        if ($pid === '')
                            continue;

                        $product = Product::where('equinox_id', $pid)->first();
                        if (! $product)
                            continue;

                        $unprocessed = (int) ($row['unprocessedOrderQty'] ?? 0);

                        foreach (($row['stockLevels'] ?? []) as $level) {
                            $branchName = (string) ($level['branchName'] ?? '');
                            if ($branchName === '')
                                continue;

                            $branch = Branch::firstOrCreate(
                                ['name' => $branchName],
                                ['is_active' => true]
                            );

                            Stock::updateOrCreate(
                                ['product_id' => $product->id, 'branch_id' => $branch->id],
                                [
                                    'stock' => (int) ($level['quantity'] ?? 0),
                                    'unprocessed_order_qty' => $unprocessed,
                                ]
                            );
                        }
                    }
                }, 1);
            }
        }
    }
}
