<?php

namespace Plokko\LaravelBulkgate\Contracts;

use Plokko\LaravelBulkgate\RequestBuilders\MessageBuilder;

interface BulkgateNotification
{
    /**
     * Return a message builder for sending to BulkGate
     */
    public function toBulkGate(object $notifiable): ?MessageBuilder;
}
