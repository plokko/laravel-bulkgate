<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\WhatsappHeaderType;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html#template-object
 */
class WhatsappTemplateHeader extends ApiData
{
    public function __construct(
        public ?WhatsappHeaderType $type = null,
        public ?string $url = null,
        public ?string $text = null,
        public ?WhatsappLocation $location = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'type' => $this->type?->value,
            'url' => $this->url,
            'text' => $this->text,
            'location' => $this->location,
        ]);
    }

    public static function text(string $text): self
    {
        return new self(type: WhatsappHeaderType::text, text: $text);
    }

    public static function image(string $url): self
    {
        return new self(type: WhatsappHeaderType::image, url: $url);
    }

    public static function video(string $url): self
    {
        return new self(type: WhatsappHeaderType::video, url: $url);
    }

    public static function location(WhatsappLocation $location): self
    {
        return new self(type: WhatsappHeaderType::location, location: $location);
    }
}
