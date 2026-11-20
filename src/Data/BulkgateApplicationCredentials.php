<?php

namespace Plokko\LaravelBulkgate\Data;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class BulkgateApplicationCredentials implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $application_id,
        public string $application_token
    ) {}

    public function toArray()
    {
        return [
            'application_id' => $this->application_id,
            'application_token' => $this->application_token,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
