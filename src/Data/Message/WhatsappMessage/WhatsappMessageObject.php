<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html#message-object
 */
class WhatsappMessageObject extends ApiData
{
    public function __construct(
        public string $text,
        public ?bool $preview_url = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'text' => $this->text,
            'preview_url' => $this->preview_url,
        ]);
    }
}
