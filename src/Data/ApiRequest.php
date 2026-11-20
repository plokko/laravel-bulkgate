<?php

namespace Plokko\LaravelBulkgate\Data;

/**
 * Base API data
 */
abstract class ApiRequest extends ApiData
{
    abstract public function getEndpoint(): string;
}
