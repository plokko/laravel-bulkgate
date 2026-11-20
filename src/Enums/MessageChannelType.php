<?php

namespace Plokko\LaravelBulkgate\Enums;

enum MessageChannelType: string
{
    case sms = 'sms';
    case viber = 'viber';
    case rcs = 'rcs';
    case whatsapp = 'whatsapp';
}
