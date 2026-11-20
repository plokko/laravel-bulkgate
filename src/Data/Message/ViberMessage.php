<?php

namespace Plokko\LaravelBulkgate\Data\Message;

/**
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#viber-object
 */
class ViberMessage extends BaseMessage
{
    public function __construct(
        /**
         * Text of Viber message
         */
        public string $text,
        /**
         * sender
         */
        public string $sender,
        /**
         * Time limit after which alternative channel will be used (in senconds)
         */
        public ?int $expiration,
        /**
         * Parameter used in Belarus, Ukraine and Russia. In order to send SMS for price of Transactional SMS a preaproved template must be used, otherwise SMS will be send for price of Promotional SMS
         */
        public ?bool $use_template,
    ) {}

    public function toArray()
    {
        return array_filter([
            'text' => $this->text,
            'sender' => $this->sender,
            'expiration' => $this->expiration,
            'use_template' => $this->use_template,
        ]);
    }
}
