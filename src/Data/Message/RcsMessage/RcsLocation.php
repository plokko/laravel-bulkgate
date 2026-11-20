<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#location-object
 */
class RcsLocation extends ApiData
{
    public function __construct(
        public ?string $query = null,
        public mixed $latitude = null,
        public mixed $longitude = null,
        public ?string $label = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'query' => $this->query,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'label' => $this->label,
        ]);
    }
}
