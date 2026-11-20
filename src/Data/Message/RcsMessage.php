<?php

namespace Plokko\LaravelBulkgate\Data\Message;

use Plokko\LaravelBulkgate\Data\Message\RcsMessage\RcsCard;
use Plokko\LaravelBulkgate\Data\Message\RcsMessage\RcsCarousel;
use Plokko\LaravelBulkgate\Data\Message\RcsMessage\RcsFile;
use Plokko\LaravelBulkgate\Data\Message\RcsMessage\RcsMessageContent;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html
 */
class RcsMessage extends BaseMessage
{
    public function __construct(
        /**
         * Text sender. Needs to be registered first.
         */
        public string $sender,
        public ?int $expiration = null,
        public ?RcsMessageContent $message = null,
        public ?RcsFile $file = null,
        public ?RcsCard $card = null,
        public ?RcsCarousel $carousel = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'sender' => $this->sender,
            'expiration' => $this->expiration,
            'file' => $this->file?->toArray(),
            'card' => $this->card?->toArray(),
            'carousel' => $this->carousel?->toArray(),
        ]);
    }
}
