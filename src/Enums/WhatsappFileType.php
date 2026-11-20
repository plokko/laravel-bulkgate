<?php

namespace Plokko\LaravelBulkgate\Enums;

enum WhatsappFileType: string
{
    case image = 'image';
    case video = 'video';
    case audio = 'audio';
    case document = 'document';
    case sticker = 'sticker';
}
