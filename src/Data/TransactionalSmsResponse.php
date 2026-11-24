<?php

namespace Plokko\LaravelBulkgate\Data;

use Illuminate\Http\Client\Response;

/**
 * Transactional SMS response
 *
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#response-to-this-command-may-be
 */
class TransactionalSmsResponse extends ApiResponse
{
    public function __construct(
        public readonly array $rawData
    ) {}

    public function toArray()
    {
        return $this->rawData;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromResponse(Response $response): static
    {
        $rawData = $response->json();

        // /TODO: parse other data
        return new TransactionalSmsResponse(
            rawData: $rawData,
        );
    }
}
