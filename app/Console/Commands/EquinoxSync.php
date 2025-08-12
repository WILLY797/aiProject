<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncEquinoxProducts;
use App\Jobs\SyncEquinoxStockAndPrice;

class EquinoxSync extends Command
{
    protected $signature = 'equinox:sync {--section=* : products,stock-pricing} {--accountId=}';
    protected $description = 'Sync data from Equinox';

    public function handle(): int
    {
        $sections = $this->option('section') ?: ['products', 'stock-pricing'];
        $accountId = $this->option('accountId') ? (int) $this->option('accountId') : null;

        foreach ($sections as $s) {
            match ($s) {
                'products' => dispatch(new SyncEquinoxProducts()),
                'stock-pricing' => dispatch(new SyncEquinoxStockAndPrice($accountId)),
                default => $this->warn("Unknown section: {$s}"),
            };
        }

        $this->info('Dispatched Equinox sync jobs.');
        return self::SUCCESS;
    }
}
