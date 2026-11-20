<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html#template-object
 */
class WhatsappTemplate extends ApiData
{
    public function __construct(
        public ?string $template = null,
        public ?string $language = null,
        public ?WhatsappTemplateHeader $header = null,
        public ?object $body = null,
        public ?array $buttons = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'template' => $this->template,
            'language' => $this->language,
            'header' => $this->header,
            'body' => $this->body,
            'buttons' => $this->buttons,
        ]);
    }
}
