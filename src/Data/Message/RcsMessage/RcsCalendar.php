<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#calendar-object
 */
class RcsCalendar extends ApiData
{
    public function __construct(
        public ?string $start = null,
        public ?string $end = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $timezone = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'start' => $this->start,
            'end' => $this->end,
            'title' => $this->title,
            'description' => $this->description,
            'timezone' => $this->timezone,
        ]);
    }
}
