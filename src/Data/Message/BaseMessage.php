<?php

namespace Plokko\LaravelBulkgate\Data\Message;

use Plokko\LaravelBulkgate\Data\ApiData;

/**
 * Base SMS/RCS/Viber/WhatsApp message object
 *
 * @see https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#sms-object
 */
abstract class BaseMessage extends ApiData {}
