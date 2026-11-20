<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\RcsMediaHeight;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#card-object
 */
class RcsCardMedia extends ApiData
{
    public function __construct(
        public string $url,
        public ?bool $force_refresh = null,
        public ?RcsMediaHeight $height = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'url' => $this->url,
            'force_refresh' => $this->force_refresh,
            'height' => $this->height?->value,
        ]);
    }
}
