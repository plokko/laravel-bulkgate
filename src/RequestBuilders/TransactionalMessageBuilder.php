<?php

namespace Plokko\LaravelBulkgate\RequestBuilders;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use Plokko\LaravelBulkgate\BulkgateApi;
use Plokko\LaravelBulkgate\Data\ApiRequest;
use Plokko\LaravelBulkgate\Data\Message\BaseMessage;
use Plokko\LaravelBulkgate\Data\Message\RcsMessage;
use Plokko\LaravelBulkgate\Data\Message\SmsMessage;
use Plokko\LaravelBulkgate\Data\Message\ViberMessage;
use Plokko\LaravelBulkgate\Data\Message\WhatsappMessage;
use Plokko\LaravelBulkgate\Data\TransactionalSmsRequest;
use Plokko\LaravelBulkgate\Data\TransactionalSmsResponse;
use Plokko\LaravelBulkgate\Enums\MessageChannelType;
use Throwable;
use UnexpectedValueException;

class TransactionalMessageBuilder extends MessageBuilder
{
    public function __construct(
        protected BulkgateApi $api,
        public array $recipients = [],
        public ?int $admin = null,
        public ?string $text = null,
        public ?array $variables = [],
        public array $channels = [],
        /** @var ?MessageChannelType[] */
        public ?array $channelPriorities = [],
        public ?string $country = null,
        public ?Carbon $schedule = null,
        public bool $simulate_send = false,
        public ?Closure $on_submit = null,
        public ?Closure $on_error = null,
    ) {}

    public function simulateSend(bool $simulate): static
    {
        $this->simulate_send = $simulate;

        return $this;
    }

    public function setRecipients(array $recipients): TransactionalMessageBuilder
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function addRecipient(string $number): TransactionalMessageBuilder
    {
        $this->recipients[] = $number;

        return $this;
    }

    public function setText(string $text): TransactionalMessageBuilder
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param  ?Closure  $callback  function(TransactionalSmsResponse $response): void
     */
    public function onSubmit(?Closure $callback): static
    {
        $this->on_submit = $callback;

        return $this;
    }

    /**
     * @param  ?Closure  $callback  function(Throwable $error): void
     */
    public function onError(?Closure $callback): static
    {
        $this->on_error = $callback;

        return $this;
    }

    /**
     * @property array[array<string,string>] $variables
     */
    public function setVariables(?array $variables): TransactionalMessageBuilder
    {
        $this->variables = $variables;

        return $this;
    }

    public function channelOptions(MessageChannelType $type, ?BaseMessage $messageOptions): TransactionalMessageBuilder
    {
        if ($messageOptions) {
            $this->channels[$type->value] = $messageOptions;
        } else {
            unset($this->channels[$type->value]);
        }

        return $this;
    }

    public function smsOptions(?SmsMessage $options): TransactionalMessageBuilder
    {
        return $this->channelOptions(MessageChannelType::sms, $options);
    }

    public function rcsOptions(?RcsMessage $options): TransactionalMessageBuilder
    {
        return $this->channelOptions(MessageChannelType::rcs, $options);
    }

    public function whatsappOptions(?WhatsappMessage $options): TransactionalMessageBuilder
    {
        return $this->channelOptions(MessageChannelType::whatsapp, $options);
    }

    public function viberOptions(?ViberMessage $options): TransactionalMessageBuilder
    {
        return $this->channelOptions(MessageChannelType::viber, $options);
    }

    /**
     * @param  MessageChannelType[]  $priorities
     * @return $this
     */
    public function channelPriority(?array $priorities): TransactionalMessageBuilder
    {
        $this->channelPriorities = $priorities;

        return $this;
    }

    public function schedule(?Carbon $schedule): TransactionalMessageBuilder
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function setCountry(?string $country): TransactionalMessageBuilder
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @throws UnexpectedValueException
     */
    public function toApiRequest(): ApiRequest
    {
        if (count($this->recipients) == 0) {
            throw new UnexpectedValueException('No recipients specified.');
        }
        if (empty($this->text)) {
            throw new UnexpectedValueException('No message text specified.');
        }

        $channels = count($this->channels) > 0 ? $this->channels : null;
        // Reorder channel based on priorty //
        if ($channels && $this->channelPriorities) {
            $channels = array_intersect_key(
                array_merge(array_flip(array_map(fn ($v) => $v->value, $this->channelPriorities)), $channels),
                $channels
            );
        }

        //
        return new TransactionalSmsRequest(
            number: $this->recipients,
            admin: $this->admin,
            text: $this->text,
            variables: $this->variables,
            channels: $channels,
            country: $this->country,
            schedule: $this->schedule,
        );
    }

    /**
     * @throws UnexpectedValueException
     */
    public function send(): TransactionalSmsResponse
    {
        $request = $this->toApiRequest();
        $response = null;

        try {
            if ($this->simulate_send) {
                Log::notice('Simulating Bulkgate.transactionalSms:', $request->toArray());
                // TODO: emulate API response
                $response = new TransactionalSmsResponse([]);
            } else {

                $response = $this->api->postRequest($request);

                $response = TransactionalSmsResponse::fromResponse($response);
            }

            if ($this->on_submit) {
                $callback = $this->on_submit;
                $callback($response);
            }

            return $response;
        } catch (Throwable $error) {
            if ($this->on_error) {
                $callback = $this->on_error;
                $callback($error);
            }
            throw $error;
        }
    }
}
