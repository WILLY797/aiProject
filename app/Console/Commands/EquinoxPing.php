<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EquinoxClient;

class EquinoxPing extends Command
{
    protected $signature = 'equinox:ping {endpoint=health} {--q=*}';
    protected $description = 'Ping Equinox API endpoint';

    public function handle(EquinoxClient $api): int
    {
        $endpoint = trim($this->argument('endpoint'), '/');
        $query = [];
        foreach ($this->option('q') as $pair) {
            if (str_contains($pair, '=')) {
                [$k, $v] = explode('=', $pair, 2);
                $query[$k] = $v;
            }
        }

        try {
            $data = $api->get($endpoint, $query);
            $this->info('OK');
            $this->line(json_encode($data, JSON_PRETTY_PRINT));
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
