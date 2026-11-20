<?php

namespace Plokko\LaravelBulkgate\Enums;

enum WhatsappHeaderType: string
{
    case image = 'image';
    case video = 'video';
    case text = 'text';
    case location = 'location';
}
