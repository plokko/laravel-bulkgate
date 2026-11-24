<?php

namespace Plokko\LaravelBulkgate\Data;

use Carbon\Carbon;

/**
 * Transactional SMS request for advanced API
 *
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html
 */
class TransactionalSmsRequest extends ApiRequest
{
    public function getEndpoint(): string
    {
        return '/api/2.0/advanced/transactional';
    }

    public function __construct(
        /**
         * Number of BulkGate administrator receiving notification.
         */
        public string|array $number,

        /**
         * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-admin.html
         * TBD: required?
         */
        public ?int $admin,
        /**
         * Text of SMS message (max. 612 characters, or 268 characters if Unicode is used), UTF-8 encoding. It is possible to add variables to the template from the variables array (another parameter) Hello <first_name> <last_name> ....
         */
        public string $text,
        /**
         * Associative array to add variables to text, for e.g.: {"first_name": "John", "last_name": "Doe"}
         */
        public ?array $variables = [],
        /**
         * Alternative channels. Channels are listed in a cascade, if we are unable to deliver your message via highest priority channel, channels lower in the cascade list will be used. If none of them manages to deliver sms will be used instead.
         */
        public ?array $channels = null,
        /**
         * Provide the recipients' numbers in an international format (with prefix, e.g. 44) or add the country code in ISO 3166-1 alpha-2 format (777777777 + GB = 44777777777). See the country example request. If null, your set timezone will be used to fill the information
         */
        public ?string $country = null,
        /**
         * Schedule the sending time and date
         */
        public ?Carbon $schedule = null,
        /**
         * Select on to prevent sending duplicate messages to the same phone number. Messages with the same text sent to the same number will be removed if there is a time interval shorter than 5 minutes. If off no duplicates will be removed.
         */
        public ?bool $duplicates_check = null,
        /**
         * Message label for subsequent retrieval of the user.
         */
        public ?string $tag = null,
    ) {}

    public function toArray()
    {
        return array_filter([
            // ...
            'number' => $this->number,
            'admin' => $this->admin,
            'text' => $this->text,
            'variables' => $this->variables,
            'channel' => $this->channels,
            'country' => $this->country,
            'schedule' => $this->schedule?->toIso8601String(),
            'duplicates_check' => $this->duplicates_check,
        ]);
    }
}
