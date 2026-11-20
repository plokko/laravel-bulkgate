<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#location-object
 */
class WhatsappLocation extends ApiData
{
    public function __construct(
        public mixed $latitude = null,
        public mixed $longitude = null,
        public ?string $address = null,
        public ?string $name = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'name' => $this->name,
            'address' => $this->address,
        ]);
    }
}
