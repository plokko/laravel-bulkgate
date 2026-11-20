<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#file-object
 */
class RcsFile extends ApiData
{
    public function __construct(
        public string $url,
        public ?bool $force_refresh,
        public ?array $suggestions = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'url' => $this->url,
            'force_refresh' => $this->force_refresh,
            'suggestions' => $this->suggestions,
        ]);
    }
}
