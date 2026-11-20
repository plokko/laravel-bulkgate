<?php

namespace Plokko\LaravelBulkgate\Data;

/**
 * Phone number used in NumberCheckerRequest
 *
 * @see https://help.bulkgate.com/docs/en/http-integration-check-phone-numbers.html#numbers
 */
class PhoneNumber extends ApiData
{
    public function __construct(
        public readonly string $phone_number,
        public readonly ?string $country = null,

    ) {}

    public function toArray()
    {
        return array_filter([
            'phone_number' => $this->phone_number,
            'country' => $this->country,
        ]);
    }
}
