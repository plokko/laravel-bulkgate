<?php

namespace Plokko\LaravelBulkgate;

use Plokko\LaravelBulkgate\Data\BulkgateApplicationCredentials;
use Plokko\LaravelBulkgate\RequestBuilders\InfoBuilder;
use Plokko\LaravelBulkgate\RequestBuilders\NumberCheckerBuilder;
use Plokko\LaravelBulkgate\RequestBuilders\TransactionalMessageBuilder;

class BulkgateInterface
{
    public function __construct(protected array $config) {}

    protected function getApplicationCredentials(): BulkgateApplicationCredentials
    {
        return new BulkgateApplicationCredentials(
            application_id: (int) ($this->config['application_id'] ?? null),
            application_token: $this->config['application_token'] ?? null,
        );
    }

    protected function getApi(): BulkgateApi
    {
        return new BulkgateApi(
            credentials: $this->getApplicationCredentials(),
            timeout: $this->config['timeout'] ?? 30,
            debug: $this->config['debug'] ?? false,
        );
    }

    public function Transactional(): TransactionalMessageBuilder
    {
        $api = $this->getApi();

        return new TransactionalMessageBuilder(
            $api,
            simulate_send: $this->config['simulate_send'] ?? false
        );
    }

    public function Info(): InfoBuilder
    {
        $api = $this->getApi();

        return new InfoBuilder($api);
    }

    public function CheckNumbers(): NumberCheckerBuilder
    {
        $api = $this->getApi();

        return new NumberCheckerBuilder($api);
    }
}
