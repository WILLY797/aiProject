<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\PriceBreak;
use App\Services\EquinoxClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SyncEquinoxStockAndPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 2;

    /** @var int|null Optional filter for a single account */
    public function __construct(public ?int $accountId = null)
    {
    }

    public function handle(EquinoxClient $api): void
    {
        // Endpoint must return items shaped like the examples you posted.
        // If your API paginates, adapt 'stock-pricing' path accordingly.
        foreach ($api->paginate('stock-pricing', 200) as $items) {
            foreach (array_chunk($items, 50) as $chunk) {
                DB::transaction(function () use ($chunk) {
                    foreach ($chunk as $row) {
                        // Example row keys:
                        // productId, productCode, unprocessedOrderQty, stockLevels[], (optional) accountId, accountCode, priceBreaks[]
                        $extProdId = (string) ($row['productId'] ?? '');
                        $sku = (string) ($row['productCode'] ?? '');
                        if ($extProdId === '' && $sku === '') {
                            continue;
                        }

                        // Find local product by equinox_id or sku
                        $product = Product::query()
                            ->when($extProdId !== '', fn ($q) => $q->where('equinox_id', $extProdId))
                            ->when($extProdId === '' && $sku !== '', fn ($q) => $q->orWhere('sku', $sku))
                            ->first();

                        if (! $product) {
                            // If product sync hasn’t run yet, skip gracefully
                            continue;
                        }

                        $unprocessed = (int) ($row['unprocessedOrderQty'] ?? 0);

                        // STOCK LEVELS
                        foreach ((array) ($row['stockLevels'] ?? []) as $level) {
                            $branchName = trim((string) ($level['branchName'] ?? ''));
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

                        // PRICE BREAKS (optional part of payload)
                        $accountId = $row['accountId'] ?? null;
                        $accountCode = $row['accountCode'] ?? null;
                        if ($accountId && is_array($row['priceBreaks'] ?? null)) {
                            foreach ($row['priceBreaks'] as $pb) {
                                PriceBreak::updateOrCreate(
                                    [
                                        'product_id' => $product->id,
                                        'account_id' => (int) $accountId,
                                        'quantity' => (int) ($pb['quantity'] ?? 0),
                                    ],
                                    [
                                        'account_code' => $accountCode,
                                        'price' => (float) ($pb['price'] ?? 0),
                                    ]
                                );
                            }
                        }
                    }
                }, 1);
            }
        }

        // Optional cache bust
        cache()->tags(['products'])->flush();
    }
}
