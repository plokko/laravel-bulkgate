# Laravel BulkGate API integration

[BulkGate](https://www.bulkgate.com) API integration for Laravel.

This package provides:

- a **Laravel notification channel** for sending SMS, RCS, WhatsApp and Viber messages through BulkGate;
- a **fluent facade** for sending transactional messages, checking phone numbers and reading your credit balance;
- **Artisan commands** for quick checks from the terminal.

## Requirements

- PHP >= 8.2
- Laravel 12 or 13

## Installation

Install via Composer:

```bash
composer require plokko/laravel-bulkgate
```

The service provider and the `BulkGate` facade alias are registered automatically through package discovery.

Optionally, publish the configuration file to `config/laravel-bulkgate.php`:

```bash
php artisan vendor:publish --tag=laravel-bulkgate:config
```

## Configuration

Add your BulkGate application credentials to `.env`. You can find them in the BulkGate portal under *Modules & APIs → Advanced API*.

```dotenv
BULKGATE_APPLICATION_ID=12345
BULKGATE_APPLICATION_TOKEN=your-application-token

# Optional: log messages instead of sending them
BULKGATE_SIMULATE_SEND=false
```

| Key                 | Env variable                 | Default | Description                                                                 |
|---------------------|------------------------------|---------|-----------------------------------------------------------------------------|
| `application_id`    | `BULKGATE_APPLICATION_ID`    | –       | Your BulkGate application ID                                                |
| `application_token` | `BULKGATE_APPLICATION_TOKEN` | –       | Your BulkGate application token                                             |
| `timeout`           | –                            | `30`    | HTTP request timeout, in seconds                                            |
| `debug`             | –                            | `false` | Enable Guzzle debug output for every API request                            |
| `simulate_send`     | `BULKGATE_SIMULATE_SEND`     | `false` | Don't send any message. Log the request payload at `notice` level instead   |

> **Note:** When `simulate_send` is `true`, **no message is sent**. This is useful in local and staging environments.

## Usage

### Sending messages with the facade

`BulkGate::Transactional()` returns a fluent `TransactionalMessageBuilder`. Call `send()` to dispatch the message.

```php
use Plokko\LaravelBulkgate\Facades\BulkGate;

$response = BulkGate::Transactional()
    ->setRecipients(['393331234567'])
    ->setText('Your verification code is 1234')
    ->send();
```

#### Builder methods

| Method                                       | Description                                                                                       |
|----------------------------------------------|---------------------------------------------------------------------------------------------------|
| `setRecipients(array $numbers)`              | Replace the recipient list                                                                        |
| `addRecipient(string $number)`               | Add a single recipient                                                                            |
| `setText(string $text)`                      | Set the message text (required)                                                                   |
| `setVariables(?array $variables)`            | Set template variables to substitute into the text                                                |
| `setCountry(?string $country)`               | Default country for numbers without an international prefix (e.g. `'it'` or `'0039'`)            |
| `schedule(?Carbon $date)`                    | Schedule the message for a later date                                                             |
| `smsOptions(?SmsMessage $options)`           | Configure the SMS channel                                                                         |
| `rcsOptions(?RcsMessage $options)`           | Configure the RCS channel                                                                         |
| `whatsappOptions(?WhatsappMessage $options)` | Configure the WhatsApp channel                                                                    |
| `viberOptions(?ViberMessage $options)`       | Configure the Viber channel                                                                       |
| `channelOptions(MessageChannelType, ?BaseMessage)` | Configure any channel. Pass `null` to remove it                                             |
| `channelPriority(?array $channels)`          | Order the configured channels by fallback priority (array of `MessageChannelType`)                |
| `simulateSend(bool $simulate)`               | Override the `simulate_send` config for this message                                              |
| `onSubmit(?Closure $callback)`               | Callback run after a successful send: `fn (TransactionalSmsResponse $response) => ...`            |
| `onError(?Closure $callback)`                | Callback run when sending fails: `fn (Throwable $error) => ...`. The error is re-thrown afterwards |
| `when($condition, Closure $callback)`        | Run `$callback($builder)` only if `$condition` is truthy                                          |
| `send()`                                     | Send the message and return a `TransactionalSmsResponse`                                          |

`send()` throws an `UnexpectedValueException` if no recipient or no text is set.

#### Multi-channel messages and fallback

BulkGate can try several channels in order (e.g. RCS first, then SMS). Configure each channel you want to use, then set the priority with `channelPriority()`:

```php
use Plokko\LaravelBulkgate\Data\Message\RcsMessage;
use Plokko\LaravelBulkgate\Data\Message\SmsMessage;
use Plokko\LaravelBulkgate\Enums\MessageChannelType;
use Plokko\LaravelBulkgate\Enums\SenderIdType;
use Plokko\LaravelBulkgate\Facades\BulkGate;

$text = 'Your order #1234 has shipped.';

BulkGate::Transactional()
    ->setRecipients(['393331234567'])
    ->setText($text)
    ->setCountry('it')
    ->rcsOptions(new RcsMessage(sender: 'MyRcsSender', expiration: 3600))
    ->smsOptions(new SmsMessage(
        text: $text,
        sender_id: SenderIdType::textSender,
        sender_id_value: 'MyShop',
        unicode: false,
    ))
    ->channelPriority([MessageChannelType::rcs, MessageChannelType::sms])
    ->send();
```

Available channel option classes, all in the `Plokko\LaravelBulkgate\Data\Message` namespace:

| Class             | Channel  | BulkGate docs                                                                                              |
|-------------------|----------|------------------------------------------------------------------------------------------------------------|
| `SmsMessage`      | SMS      | [SMS object](https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#sms-object)             |
| `RcsMessage`      | RCS      | [RCS](https://help.bulkgate.com/docs/en/http-advanced-rcs.html)                                            |
| `WhatsappMessage` | WhatsApp | [WhatsApp](https://help.bulkgate.com/docs/en/http-advanced-whatsapp.html)                                  |
| `ViberMessage`    | Viber    | [Viber object](https://help.bulkgate.com/docs/en/http-advanced-transactional-v2.html#viber-object)         |

Sender ID types for SMS are available in the `Plokko\LaravelBulkgate\Enums\SenderIdType` enum (`textSender` = `gText`, `ownNumber` = `gOwn`, `systemNumber` = `gSystem`, `bulkGateProfile` = `gProfile`, and others).

#### Submit and error callbacks

Use `onSubmit()` and `onError()` to record the outcome of each message, for example in a delivery log:

```php
BulkGate::Transactional()
    ->setRecipients([$phone])
    ->setText($text)
    ->onSubmit(fn ($response) => logger()->info('SMS sent', $response->toArray()))
    ->onError(fn (Throwable $e) => logger()->error('SMS failed: ' . $e->getMessage()))
    ->send();
```

### Sending messages as Laravel notifications

1. Implement the `BulkgateNotification` contract on your notification.
2. Add `BulkgateSmsChannel::class` to `via()`.
3. Return a configured message builder from `toBulkGate()`, or `null` to skip sending.

You don't call `send()` yourself. The channel sends the builder for you.

```php
<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Plokko\LaravelBulkgate\Channels\BulkgateSmsChannel;
use Plokko\LaravelBulkgate\Contracts\BulkgateNotification;
use Plokko\LaravelBulkgate\Data\Message\SmsMessage;
use Plokko\LaravelBulkgate\Enums\MessageChannelType;
use Plokko\LaravelBulkgate\Facades\BulkGate;
use Plokko\LaravelBulkgate\RequestBuilders\MessageBuilder;

class AppointmentReminder extends Notification implements BulkgateNotification
{
    use Queueable;

    public function __construct(
        public readonly Appointment $appointment,
    ) {}

    public function via(object $notifiable): array
    {
        $via = [];

        if ($notifiable->wants_email) {
            $via[] = 'mail';
        }
        if ($notifiable->wants_sms) {
            $via[] = BulkgateSmsChannel::class;
        }

        return $via;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Appointment reminder')
            ->line("Your appointment is on {$this->appointment->date->format('d/m/Y H:i')}.");
    }

    /**
     * Return a message builder for sending to BulkGate,
     * or null to skip sending.
     */
    public function toBulkGate(object $notifiable): ?MessageBuilder
    {
        $phone = $this->appointment->customer->phone;
        if (! $phone) {
            return null;
        }

        $text = "Reminder: your appointment is on {$this->appointment->date->format('d/m/Y')}"
            . " at {$this->appointment->date->format('H:i')}.";

        return BulkGate::Transactional()
            ->setRecipients([$phone])
            ->setText($text)
            ->setCountry('it')
            ->smsOptions(new SmsMessage(text: $text))
            ->channelPriority([MessageChannelType::sms])
            ->onSubmit(function ($response) {
                $this->appointment->update(['sms_sent_at' => now()]);
            })
            ->onError(function (\Throwable $error) {
                logger()->error('Reminder SMS failed', ['error' => $error->getMessage()]);
            });
    }
}
```

Send the notification as usual:

```php
$customer->notify(new AppointmentReminder($appointment));
```

If you don't have a notifiable model (e.g. the recipient is passed through the constructor), use an on-demand notification.
Never pass `null` as the notifiable: Laravel calls `via()` with it and fails with a `TypeError`.

```php
use Illuminate\Support\Facades\Notification;
use Plokko\LaravelBulkgate\Channels\BulkgateSmsChannel;

Notification::route(BulkgateSmsChannel::class, $phone)
    ->notify(new AppointmentReminder($appointment));
```

> **Tip:** `toBulkGate()` receives the notifiable (an `AnonymousNotifiable` for on-demand notifications), so you can read the recipient from it or pass everything through the notification constructor.
> If the notification is in `via()` but doesn't implement `BulkgateNotification`, the channel throws an exception.

### Checking phone numbers

Check whether numbers are valid and find their country and network:

```php
use Plokko\LaravelBulkgate\Facades\BulkGate;

$result = BulkGate::CheckNumbers()
    ->addNumber(['3331234567', '3339876543'], 'it')
    ->addNumber('+420777777777')
    ->check();

foreach ($result->toArray() as $check) {
    // Plokko\LaravelBulkgate\Data\CheckPhoneNumbersResponse\PhoneNumberCheck
    echo $check->phone_number, ' ', $check->valid ? 'valid' : 'invalid', ' ', $check->network_name, PHP_EOL;
}
```

Each `PhoneNumberCheck` exposes `phone_number`, `valid`, `country`, `call_prefix`, `network_code` and `network_name`.

You can also pass a list of numbers with `setNumbers(['...', '...'])`, or add `PhoneNumber` objects with `addPhoneNumber(new PhoneNumber('...', 'it'))`.

### Credit balance

```php
use Plokko\LaravelBulkgate\Facades\BulkGate;

$balance = BulkGate::Info()->getBalance();

$balance->credit;        // float
$balance->currency;      // e.g. "credits"
$balance->wallet;        // wallet ID
$balance->free_messages; // int
$balance->datetime;      // Carbon
```

## Artisan commands

```bash
# Show the current credit balance
php artisan bulkgate:get-balance

# Check one or more phone numbers, with an optional default country
php artisan bulkgate:check-numbers 3331234567 3339876543 --country=it
```

## Error handling

When the BulkGate API returns an error response, the package throws `Plokko\LaravelBulkgate\Exceptions\ApiError`, which exposes the API error details:

```php
use Plokko\LaravelBulkgate\Exceptions\ApiError;

try {
    BulkGate::Transactional()->setRecipients([$phone])->setText($text)->send();
} catch (ApiError $e) {
    $e->getCode(); // BulkGate error code
    $e->type;      // e.g. "invalid_phone_number"
    $e->error;     // error message
    $e->detail;    // optional detail
    $e->toArray(); // all of the above as an array
}
```

Connection failures throw Laravel's `Illuminate\Http\Client\ConnectionException`.