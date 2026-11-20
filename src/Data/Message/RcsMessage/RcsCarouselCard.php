<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#file-object
 */
class RcsCarouselCard extends ApiData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?RcsCardMedia $media = null,
        public ?array $suggestions = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'media' => $this->media?->toArray(),
            'suggestions' => $this->suggestions,
        ]);
    }
}
