<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncEquinoxProducts;

class EquinoxSync extends Command
{
    protected $signature = 'equinox:sync {--section=* : products, stock}';
    protected $description = 'Sync from Equinox API';

    public function handle(): int
    {
        $sections = $this->option('section') ?: ['products'];
        foreach ($sections as $s) {
            match ($s) {
                'products', 'stock' => dispatch(new SyncEquinoxProducts()),
                default => $this->warn("Unknown section: {$s}"),
            };
        }
        $this->info('Dispatched sync jobs.');
        return self::SUCCESS;
    }
}
