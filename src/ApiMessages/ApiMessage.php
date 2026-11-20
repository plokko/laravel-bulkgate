<?php

namespace Plokko\LaravelBulkgate\ApiMessages;

use BulkGate\Sdk\Message\Component\PhoneNumber;
use BulkGate\Sdk\Message\Component\SimpleText;
use BulkGate\Sdk\Message\Send;
use Plokko\LaravelBulkgate\BulkgateApi;

abstract class ApiMessage
{
    public function __construct(
        protected BulkgateApi $api
    ) {}

    public function setNumber(PhoneNumber|string $number): ApiMessage
    {
        $this->number = $number;

        return $this;
    }

    public function setMessage(SimpleText|string $message): ApiMessage
    {
        $this->message = $message;

        return $this;
    }

    public function send(): Send
    {
        $sender = $this->api->getSender();

        return $sender->send($this->toBulkgateMessage());
    }

    abstract protected function toBulkgateMessage(): Send;
}
