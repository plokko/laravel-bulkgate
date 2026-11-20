<?php

namespace Plokko\LaravelBulkgate\Data\Message\RcsMessage;

use Plokko\LaravelBulkgate\Data\ApiData;
use Plokko\LaravelBulkgate\Enums\RcsSuggestionType;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-rcs.html#suggestions-object
 */
class RcsSuggestion extends ApiData
{
    public function __construct(
        public string $text,
        public ?RcsSuggestionType $type = null,
        public ?RcsLocation $location = null,
        public ?RcsCalendar $calendar = null,
        public ?string $phone_number = null,
        public ?string $url = null,
        public ?string $postback = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'text' => $this->text,
            'type' => $this->type?->value,
            'postback' => $this->postback,
            'location' => $this->location,
            'calendar' => $this->calendar,
            'phone_number' => $this->phone_number,
            'url' => $this->url,
        ]);
    }

    public static function openUrl(
        string $text,
        ?string $url = null
    ): RcsSuggestion {
        return new self(
            text: $text,
            type: RcsSuggestionType::open_url,
            url: $url,
        );
    }

    public static function viewLocation(
        string $text,
        ?RcsLocation $location
    ): RcsSuggestion {
        return new self(
            text: $text,
            type: RcsSuggestionType::view_location,
            location: $location,
        );
    }

    public static function createCalendarEvent(
        string $text,
        ?RcsCalendar $calendar
    ): RcsSuggestion {
        return new self(
            text: $text,
            type: RcsSuggestionType::create_calendar_event,
            calendar: $calendar,
        );
    }

    public static function dialNumber(
        string $text,
        string $phone_number
    ): RcsSuggestion {
        return new self(
            text: $text,
            type: RcsSuggestionType::dial_number,
            phone_number: $phone_number,
        );
    }

    public static function reply(
        string $text,
        string $postback
    ): RcsSuggestion {
        return new self(
            text: $text,
            type: RcsSuggestionType::reply,
            postback: $postback,
        );
    }
}
