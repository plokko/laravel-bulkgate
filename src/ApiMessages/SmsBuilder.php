<?php

namespace Plokko\LaravelBulkgate\ApiMessages;

use BulkGate\Sdk\Message\Component\PhoneNumber;
use BulkGate\Sdk\Message\Component\SimpleText;
use BulkGate\Sdk\Message\Send;
use BulkGate\Sdk\Message\Sms;
use Plokko\LaravelBulkgate\BulkgateApi;

class SmsBuilder extends ApiMessage
{
    public function __construct(
        protected BulkgateApi $api,
        public PhoneNumber|string|null $number = null,
        public SimpleText|string|null $message = null
    ) {
        parent::__construct($api);
    }

    protected function toBulkgateMessage(): Send
    {
        return new Sms($this->number, $this->message);
    }
}
