<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\RcsAlignment;
use Plokko\LaravelBulkgate\Enums\RcsOrientation;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#card-object
 */
class RcsCard extends ApiData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?RcsAlignment $alignment = null,
        public ?RcsOrientation $orientation = null,
        public ?RcsCardMedia $media = null,
        public ?array $suggestions = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'alignment' => $this->alignment?->value,
            'orientation' => $this->orientation?->value,
            'media' => $this->media,
            'suggestions' => $this->suggestions,
        ]);
    }
}
