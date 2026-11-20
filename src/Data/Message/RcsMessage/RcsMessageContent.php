<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#message-object
 */
class RcsMessageContent extends ApiData
{
    public function __construct(
        public string $text,
        public ?array $suggestions = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'text' => $this->text,
            'suggestions' => $this->suggestions,
        ]);
    }
}
