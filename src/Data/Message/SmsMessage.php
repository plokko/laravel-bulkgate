<?php

namespace Plokko\LaravelBulkgate\Data\Message;

use Plokko\LaravelBulkgate\Enums\SenderIdType;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#sms-object
 */
class SmsMessage extends BaseMessage
{
    public function __construct(
        public string $text,
        /**
         * Sender ID
         *
         * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#sender-id-type-sender-id
         */
        public ?SenderIdType $sender_id = null,
        /**
         * Sender value - gOwn (e.g. "420 777 777 777"), gText (e.g. "BulkGate"), gProfile (e.g. "423"), gMobile or gPush (KEY)
         */
        public ?string $sender_id_value = null,
        /**
         * Yes/true/1 for Unicode SMS, no/false/0 for 7bit SMS
         */
        public ?bool $unicode = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            'text' => $this->text,
            'sender_id' => $this->sender_id?->value,
            'sender_id_value' => $this->sender_id_value,
            'unicode' => $this->unicode,
        ]);
    }
}
