<?php

namespace Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 *  @see https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html#otp-object
 */
class WhatsappOtp extends ApiData
{
    public function __construct(
        public string $template,
        public string $code,
        public ?string $language = null,
    ) {}

    public function toArray()
    {
        return [
            'template' => $this->template,
            'code' => $this->code,
            'language' => $this->language,
        ];
    }
}
