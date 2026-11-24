<?php

namespace Plokko\LaravelBulkgate\Data;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;

/**
 * Transactional SMS response
 *
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#response-to-this-command-may-be
 */
class CreditBalanceResponse extends ApiResponse
{
    public function __construct(
        public readonly string $wallet,
        public readonly float $credit,
        public readonly string $currency,
        public readonly int $free_messages,
        public readonly Carbon $datetime,
        protected readonly array $rawData,
    ) {}

    public function toArray()
    {
        return [
            'wallet' => $this->wallet,
            'credit' => $this->credit,
            'currency' => $this->currency,
            'free_messages' => $this->free_messages,
            'datetime' => $this->datetime?->toDateTimeString(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromResponse(Response $response): static
    {
        $data = $response->json('data');

        // /TODO: parse other data
        return new CreditBalanceResponse(
            wallet: $data['wallet'],
            credit: $data['credit'],
            currency: $data['currency'],
            free_messages: $data['free_messages'],
            datetime: Carbon::parse($data['datetime']),
            rawData: $data,
        );
    }
}
