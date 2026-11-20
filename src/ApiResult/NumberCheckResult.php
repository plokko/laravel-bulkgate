<?php

namespace Plokko\LaravelBulkgate\ApiResult;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class NumberCheckResult implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly string $phone_number,
        public readonly bool $valid,
        public readonly string $country,
        public readonly mixed $call_prefix,
        public readonly mixed $network_code,
        public readonly mixed $network_name,
    ) {}

    /**
     * Convert a list of result into a list of NumberCheckResult
     *
     * @return NumberCheckResult[]
     */
    public static function fromList(array $list): array
    {
        return array_map(fn ($el) => new NumberCheckResult(
            phone_number: $el['phone_number'] ?? null,
            valid: $el['valid'] ?? null, // <<???
            country: $el['country'] ?? null,
            call_prefix: $el['call_prefix'] ?? null,
            network_code: $el['network_code'] ?? null,
            network_name: $el['network_name'] ?? null,
        ), $list);
    }

    public function toArray(): array
    {
        return [
            'phone_number' => $this->phone_number,
            'valid' => $this->valid,
            'country' => $this->country,
            'call_prefix' => $this->call_prefix,
            'network_code' => $this->network_code,
            'network_name' => $this->network_name,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
