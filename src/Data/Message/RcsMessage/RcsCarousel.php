<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\RcsMediaWidth;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#carousel-object
 */
class RcsCarousel extends ApiData
{
    public function __construct(
        public ?RcsMediaWidth $width = null,
        /**
         * @var RcsCarouselCard[]
         */
        public ?array $cards = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'width' => $this->width?->value,
            'cards' => $this->cards,
        ]);
    }
}
