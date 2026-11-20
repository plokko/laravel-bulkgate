<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\WhatsappFileType;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html#file-object
 */
class WhatsappFile extends ApiData
{
    public function __construct(
        public string $url,
        public ?WhatsappFileType $type = null,
        public ?string $caption = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'url' => $this->url,
            'type' => $this->type?->value,
            'caption' => $this->caption,
        ]);
    }
}
