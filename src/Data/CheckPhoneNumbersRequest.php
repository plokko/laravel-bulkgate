<?php

namespace Plokko\LaravelBulkgate\Data;

/**
 * NumberCheck request
 *
 * @see https://help.bulkgate.com/docs/en/http-integration-check-phone-numbers.html
 */
class CheckPhoneNumbersRequest extends ApiRequest
{
    public function getEndpoint(): string
    {
        return '/api/1.0/integration/check-phone-numbers';
    }

    public function __construct(
        /** @var PhoneNumbers[] Numbers to be checked */
        public array $numbers,
    ) {}

    public function toArray()
    {
        return array_filter([
            'numbers' => $this->numbers,
        ]);
    }
}
