<?php

namespace Plokko\LaravelBulkgate\RequestBuilders;

use Plokko\LaravelBulkgate\BulkgateApi;
use Plokko\LaravelBulkgate\Data\CheckPhoneNumbersRequest;
use Plokko\LaravelBulkgate\Data\CheckPhoneNumbersResponse;
use Plokko\LaravelBulkgate\Data\PhoneNumber;

class NumberCheckerBuilder
{
    public function __construct(
        protected BulkgateApi $api,
        /**
         * @var PhoneNumber[]
         */
        public array $numbers = [],
    ) {}

    /**
     * Add PhoneNumber array  to the checklist
     */
    public function setNumbers(array $numbers): NumberCheckerBuilder
    {
        $this->numbers = array_map(fn ($v) => is_string($v) ? new PhoneNumber($v) : $v, $numbers);

        return $this;
    }

    /**
     * Add PhoneNumber  to the checklist
     */
    public function addPhoneNumber(PhoneNumber $number): NumberCheckerBuilder
    {
        $this->numbers[] = $number;

        return $this;
    }

    /**
     * Add Phone number (string/country) to the checklist
     */
    public function addNumber(string|array $number, ?string $country = null): NumberCheckerBuilder
    {
        foreach (is_array($number) ? $number : [$number] as $num) {
            $this->addPhoneNumber(new PhoneNumber(
                phone_number: $num,
                country: $country,
            ));
        }

        return $this;
    }

    /**
     * Get account balance
     *
     * @see https://help.bulkgate.com/docs/en/http-advanced-check-credit-balance.html
     */
    public function check(): CheckPhoneNumbersResponse
    {
        $response = $this->api->postRequest(new CheckPhoneNumbersRequest(numbers: $this->numbers));

        return CheckPhoneNumbersResponse::fromResponse($response);
    }
}
