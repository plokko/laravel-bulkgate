<?php

namespace Plokko\LaravelBulkgate\RequestBuilders;

use Plokko\LaravelBulkgate\BulkgateApi;
use Plokko\LaravelBulkgate\Data\CreditBalanceResponse;

class InfoBuilder
{
    public function __construct(
        protected BulkgateApi $api
    ) {}

    /**
     * Get account balance
     *
     * @see https://help.bulkgate.com/docs/en/http-advanced-check-credit-balance.html
     */
    public function getBalance(): CreditBalanceResponse
    {
        $response = $this->api->post('/api/2.0/advanced/info', []);

        return CreditBalanceResponse::fromResponse($response);
    }
}
