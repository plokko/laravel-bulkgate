<?php

namespace Plokko\LaravelBulkgate\Data\Message;

use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage\WhatsappFile;
use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage\WhatsappLocation;
use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage\WhatsappMessageObject;
use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage\WhatsappOtp;
use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage\WhatsappTemplate;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html
 */
class WhatsappMessage extends BaseMessage
{
    public function __construct(
        public string $sender,
        /** Time limit after which alternative channel will be used (seconds) */
        public ?int $expiration,
        public ?WhatsappTemplate $template = null,
        public ?WhatsappMessageObject $message = null,
        public ?WhatsappFile $file = null,
        public ?WhatsappOtp $otp = null,
        public ?WhatsappLocation $location = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'sender' => $this->sender,
            'expiration' => $this->expiration,
            'template' => $this->template,
            'message' => $this->message,
            'file' => $this->file,
            'otp' => $this->otp,
            'location' => $this->location,
        ]);
    }
}
