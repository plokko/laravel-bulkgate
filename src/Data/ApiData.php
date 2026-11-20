<?php

namespace Plokko\LaravelBulkgate\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Base API data
 */
abstract class ApiData implements Arrayable, JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
