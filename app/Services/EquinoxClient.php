<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;

class EquinoxClient
{
    protected string $base;
    protected string $key;
    protected string $authHeader;
    protected int $timeout;
    protected int $retryTimes;
    protected int $retrySleep;

    public function __construct()
    {
        $cfg = config('equinox');
        $this->base = rtrim((string) ($cfg['base_url'] ?? ''), '/');
        $this->key = (string) ($cfg['api_key'] ?? '');
        $this->authHeader = (string) ($cfg['auth']['header'] ?? 'x-EQUINOX-key');
        $this->timeout = (int) ($cfg['timeout'] ?? 30);
        $this->retryTimes = (int) ($cfg['retry']['times'] ?? 3);
        $this->retrySleep = (int) ($cfg['retry']['sleep'] ?? 200);
    }

    protected function http(): PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            $this->authHeader => $this->key,     // << send x-EQUINOX-key
        ])
            ->timeout($this->timeout)
            ->retry($this->retryTimes, $this->retrySleep)
            ->withOptions(['verify' => (bool) config('equinox.verify')])
            ->throw();
    }

    protected function url(string $path): string
    {
        return $this->base.'/'.ltrim($path, '/');
    }

    public function get(string $path, array $query = []): array
    {
        return $this->http()->get($this->url($path), $query)->json();
    }

    public function post(string $path, array $payload, ?string $idempotencyKey = null): array
    {
        $idempotencyKey = $idempotencyKey ?: Str::uuid()->toString();
        return $this->http()
            ->withHeaders(['Idempotency-Key' => $idempotencyKey])
            ->post($this->url($path), $payload)
            ->json();
    }

    public function paginate(string $path, int $limit = 200, array $query = []): \Generator
    {
        for ($page = 1; ; $page++) {
            $data = $this->get($path, $query + ['page' => $page, 'limit' => $limit]);
            $items = $data['data'] ?? $data['items'] ?? $data ?? [];
            if (! $items)
                break;
            yield $items;
            $last = $data['meta']['last_page'] ?? null;
            if ($last && $page >= (int) $last)
                break;
            if (count($items) < $limit)
                break;
        }
    }

    public function health(): bool
    {
        try {
            $res = $this->http()->get($this->url('health'));
            $j = $res->json();
            return (bool) ($j['ok'] ?? $res->successful());
        } catch (\Throwable) {
            try {
                return $this->http()
                    ->get($this->url('products'), ['page' => 1, 'limit' => 1])
                    ->successful();
            } catch (\Throwable) {
                return false;
            }
        }
    }
}
