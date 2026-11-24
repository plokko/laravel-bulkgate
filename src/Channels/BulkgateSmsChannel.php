<?php

namespace Plokko\LaravelBulkgate\Channels;

use Illuminate\Notifications\Notification;
use Plokko\LaravelBulkgate\Contracts\BulkgateNotification;

class BulkgateSmsChannel
{
    public function __construct() {}

    public function send($notifiable, Notification $notification)
    {
        if (! $notification instanceof BulkgateNotification) {
            throw new \Exception('Notification '.$notification::class.' should implement BulkgateNotification contract.');
        }

        $messageBuilder = $notification->toBulkGate($notifiable);
        if ($messageBuilder) {
            $messageBuilder->send();
        }
    }
}
