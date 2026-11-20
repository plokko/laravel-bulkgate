<?php

namespace Plokko\LaravelBulkgate\Data;

use Illuminate\Http\Client\Response;

/**
 * Base API data
 */
abstract class ApiResponse extends ApiData
{
    abstract public static function fromResponse(Response $request): static;
}
