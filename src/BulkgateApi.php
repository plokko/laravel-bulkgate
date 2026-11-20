<?php

namespace Plokko\LaravelBulkgate;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Plokko\LaravelBulkgate\Data\ApiRequest;
use Plokko\LaravelBulkgate\Data\BulkgateApplicationCredentials;
use Plokko\LaravelBulkgate\Exceptions\ApiError;

class BulkgateApi
{
    public function __construct(
        protected BulkgateApplicationCredentials $credentials,
        public readonly string $baseUrl = 'https://portal.bulkgate.com',
        public int $timeout = 30,
        public bool $debug = false,
    ) {}

    /**
     * @throws ApiError
     * @throws ConnectionException
     */
    public function post(string $endpoint, array $data = [], bool $auth = true): Response|PromiseInterface
    {
        try {
            return Http::baseUrl($this->baseUrl)
                ->withOptions([
                    'debug' => $this->debug,
                ])
                ->timeout($this->timeout)
                ->acceptJson()
                ->throw()
                ->post(
                    $endpoint,
                    $auth ? [
                        ...$this->credentials->toArray(),
                        ...$data,
                    ] : $data,
                );
        } catch (RequestException $e) {
            throw ApiError::fromRequestException($e);
        }
    }

    /**
     * Post an API request to the remote endpoint
     */
    public function postRequest(ApiRequest $request, bool $auth = true): Response
    {
        return $this->post($request->getEndpoint(), $request->toArray(), $auth);
    }
}
