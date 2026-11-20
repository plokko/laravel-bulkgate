<?php

namespace Plokko\LaravelBulkgate\RequestBuilders;

use Plokko\LaravelBulkgate\Data\ApiRequest;
use Plokko\LaravelBulkgate\Data\ApiResponse;

abstract class MessageBuilder
{
    /**
     * Create the request payload
     *
     * @return ApiRequest Payload request
     */
    abstract public function toApiRequest(): ApiRequest;

    /**
     * Send the payload
     *
     * @return ApiResponse result
     */
    abstract public function send(): ApiResponse;

    public function when($condition, \Closure $callback): static
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
