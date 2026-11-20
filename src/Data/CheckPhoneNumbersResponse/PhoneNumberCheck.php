<?php

namespace Plokko\LaravelBulkgate\Data\CheckPhoneNumbersResponse;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 * Phone number validity response
 *
 * @see https://help.bulkgate.com/docs/en/http-integration-check-phone-numbers.html#the-response-to-this-command-can-be
 */
class PhoneNumberCheck extends ApiData
{
    public function __construct(
        public readonly string $phone_number,
        public readonly bool $valid,
        public readonly ?string $country = null,
        public readonly ?int $call_prefix = null,
        public readonly ?string $network_code = null,
        public readonly ?string $network_name = null,

    ) {}

    public function toArray()
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
}
