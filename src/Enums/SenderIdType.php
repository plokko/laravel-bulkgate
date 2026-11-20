<?php

namespace Plokko\LaravelBulkgate\Enums;

enum SenderIdType: string
{
    case systemNumber = 'gSystem';     // System number
    case shortCode = 'gShort';       // Short Code
    case textSender = 'gText';         // Text sender
    case mobileConnect = 'gMobile';     // Mobile Connect
    case mobileConnectPush = 'gPush';         // Mobile Connect push - Sends a notification to the Mobile Connect app
    case ownNumber = 'gOwn';           // Own Number (number verification required)
    case bulkGateProfile = 'gProfile';   // BulkGate Profile ID
}
