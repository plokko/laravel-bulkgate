<?php

namespace Plokko\LaravelBulkgate\Data;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Http\Client\Response;
use Plokko\LaravelBulkgate\Data\CheckPhoneNumbersResponse\PhoneNumberCheck;

/**
 * Transactional SMS response
 *
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#response-to-this-command-may-be
 */
class CheckPhoneNumbersResponse extends ApiResponse implements ArrayAccess
{
    public function __construct(
        protected readonly array $numbers,
    ) {}

    public function toArray()
    {
        return $this->numbers;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromResponse(Response $response): static
    {
        $data = $response->json('data');

        return new CheckPhoneNumbersResponse(
            numbers: array_map(
                fn ($v) => new PhoneNumberCheck(
                    phone_number: $v['phone_number'],
                    valid: $v['valid'] ?? false,
                    country: $v['country'] ?? null,
                    call_prefix: $v['call_prefix'] ?? null,
                    network_code: $v['network_code'] ?? null,
                    network_name: $v['network_name'] ?? null,
                ),
                $data
            )
        );
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->numbers[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->numbers[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->numbers[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->numbers[$offset]);
    }
}
