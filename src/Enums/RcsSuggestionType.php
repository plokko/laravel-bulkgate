<?php

namespace Plokko\LaravelBulkgate\Enums;

enum RcsSuggestionType: string
{
    case reply = 'reply'; // Quick reply
    case dial_number = 'dial_number'; // Pre dial number
    case view_location = 'view_location'; // Display received location
    case share_location = 'share_location'; // Share recipients location
    case open_url = 'open_url'; // Open received url
    case create_calendar_event = 'create_calendar_event'; // Create calendar event
}
