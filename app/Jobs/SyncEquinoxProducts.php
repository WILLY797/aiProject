<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Services\EquinoxClient;
use Illuminate\Support\Arr;

class SyncEquinoxProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 900;
    public int $tries = 2;

    public function handle(EquinoxClient $api): void
    {
        foreach ($api->paginate('products', 200) as $items) {     // products[] has: id, sku, description, mpn, lastUpdated, retailPrice
            foreach (array_chunk($items, 100) as $chunk) {
                DB::transaction(function () use ($chunk) {
                    foreach ($chunk as $row) {
                        $extId = (string) ($row['id'] ?? '');
                        if ($extId === '')
                            continue;

                        Product::updateOrCreate(
                            ['equinox_id' => $extId],
                            [
                                'sku' => $row['sku'] ?? null,
                                'mpn' => $row['mpn'] ?? null,
                                // Equinox example has no "name"; use description for display name
                                'name' => $row['description'] ?? ($row['sku'] ?? 'Unnamed'),
                                'description' => $row['description'] ?? null,
                                'base_price' => (float) ($row['retailPrice'] ?? 0), // retailPrice in JSON
                                'last_updated' => ! empty($row['lastUpdated']) ? $row['lastUpdated'] : null,
                                'metadata' => $row,
                                'is_active' => true,
                            ]
                        );
                    }
                }, 1);
            }
        }
        cache()->tags(['products'])->flush();
    }
}
