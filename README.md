# GradiWapp PHP SDK

Official PHP SDK for GradiWapp External API - Multi-tenant WhatsApp SaaS Messaging Platform.

## Overview

The GradiWapp PHP SDK provides a simple and elegant way to integrate with the GradiWapp External API. It supports all message types (text, image, media, location, link, file, contact, reply), scheduled messages with timezone support, webhook management, and more.

### Features

- ✅ **Framework-agnostic core** - Works with any PHP 7.4+ application
- ✅ **First-class Laravel integration** - Supports Laravel 7, 8, 9, 10, 11, and 12
- ✅ **All message types** - Text, Image, Media, Location, Link, File, Contact, Reply
- ✅ **Timezone-aware scheduling** - Schedule messages in any timezone
- ✅ **Webhook management** - Create, update, and delete webhooks
- ✅ **Type-safe** - Full type hints and PHPDoc
- ✅ **Error handling** - Comprehensive exception handling
- ✅ **HMAC authentication** - Automatic signature generation

## Requirements

- PHP >= 7.4
- For Laravel integration: Laravel 7, 8, 9, 10, 11, or 12

## Installation

### Plain PHP / Non-Laravel

If you're not using Laravel, you can install the SDK via Composer:

```bash
composer require gradiwapp/gradiwapp-php-sdk
```

Or if installing from a VCS repository (before publishing to Packagist):

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-org/gradiwapp-php-sdk.git"
        }
    ],
    "require": {
        "gradiwapp/gradiwapp-php-sdk": "dev-main"
    }
}
```

### Laravel 7-12

Install via Composer:

```bash
composer require gradiwapp/gradiwapp-php-sdk
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="GradiWapp\Sdk\Laravel\GradiWappServiceProvider" --tag=config
```

This will create `config/gradiwapp.php`. Configure your API credentials in `.env`:

```env
GRADIWAPP_API_KEY=your_api_key_here
GRADIWAPP_API_SECRET=your_api_secret_here
GRADIWAPP_TIMEOUT=30
GRADIWAPP_MAX_RETRIES=1
GRADIWAPP_VERIFY_SSL=true
```

> **Note:** The base URL is now internal and non-configurable. All requests are automatically sent to `https://api.gradiwapp.com/external/v1`.

The SDK will be auto-discovered by Laravel (Laravel 5.5+).

## Usage

### Plain PHP

```php
<?php

use GradiWapp\Sdk\Client;
use GradiWapp\Sdk\Config;
use GradiWapp\Sdk\Support\ScheduleOptions;
use DateTimeImmutable;

// Create configuration
$config = new Config(
    apiKey: 'your_api_key',
    apiSecret: 'your_api_secret',
    timeout: 30
);

// Create client
$client = new Client($config);

// Send a text message
$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'Hello from GradiWapp SDK!'
);

echo "Message ID: " . $message['id'] . "\n";
```

### Laravel - Dependency Injection

```php
<?php

namespace App\Http\Controllers;

use GradiWapp\Sdk\Client;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Client $client)
    {
        $message = $client->messages()->sendText(
            to: '+1234567890',
            body: 'Hello from Laravel!'
        );

        return response()->json($message);
    }
}
```

### Laravel - Facade

```php
<?php

namespace App\Http\Controllers;

use GradiWapp\Sdk\Laravel\Facades\GradiWapp;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send()
    {
        $message = GradiWapp::messages()->sendText(
            to: '+1234567890',
            body: 'Hello from Facade!'
        );

        return response()->json($message);
    }
}
```

## Message Types

### Text Messages

```php
$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'Hello, World!',
    priority: 'normal' // or 'high', 'low'
);
```

### Image Messages

```php
// Using URL
$message = $client->messages()->sendImage(
    to: '+1234567890',
    imageUrlOrBase64: 'https://example.com/image.jpg',
    caption: 'Check out this image!'
);

// Using base64
$message = $client->messages()->sendImage(
    to: '+1234567890',
    imageUrlOrBase64: 'data:image/jpeg;base64,/9j/4AAQSkZJRg...',
    caption: 'Image sent via base64'
);
```

### Media Messages

```php
$message = $client->messages()->sendMedia(
    to: '+1234567890',
    mediaUrl: 'https://example.com/video.mp4',
    caption: 'Check out this video!'
);
```

### Location Messages

```php
$message = $client->messages()->sendLocation(
    to: '+1234567890',
    latitude: 40.7128,
    longitude: -74.0060,
    name: 'New York City',
    address: 'New York, NY, USA'
);
```

### Link Preview Messages

```php
$message = $client->messages()->sendLink(
    to: '+1234567890',
    url: 'https://example.com/article',
    caption: 'Check out this article!'
);
```

### File Messages

```php
$message = $client->messages()->sendFile(
    to: '+1234567890',
    fileUrl: 'https://example.com/document.pdf',
    filename: 'document.pdf',
    body: 'Please find the attached document'
);
```

### Contact vCard Messages

```php
$message = $client->messages()->sendContact(
    to: '+1234567890',
    contactsId: 'contact_id_from_whatsapp'
);
```

### Reply Messages

```php
$message = $client->messages()->sendReply(
    to: '+1234567890',
    body: 'This is a reply',
    replyToMessageId: '01ARZ3NDEKTSV4RRFFQ69G5FBD'
);
```

### Text + Media Combination

```php
$message = $client->messages()->sendBoth(
    to: '+1234567890',
    body: 'Check this out!',
    mediaUrl: 'https://example.com/image.jpg',
    caption: 'Image caption'
);
```

## Scheduling Messages

The SDK supports timezone-aware message scheduling. You can schedule messages in two ways:

### Option 1: ISO8601 Format with Timezone Offset

```php
use GradiWapp\Sdk\Support\ScheduleOptions;
use DateTimeImmutable;

// Schedule for a specific date/time with timezone offset
$schedule = ScheduleOptions::fromIso8601('2025-11-15T08:02:00+03:00');

$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'This message is scheduled',
    schedule: $schedule
);
```

### Option 2: DateTime + IANA Timezone

```php
use GradiWapp\Sdk\Support\ScheduleOptions;
use DateTimeImmutable;

// Schedule for 8:02 AM in Cairo timezone
$dateTime = new DateTimeImmutable('2025-11-15 08:02:00');
$schedule = ScheduleOptions::at($dateTime, 'Africa/Cairo');

$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'This message is scheduled for 8:02 AM Cairo time',
    schedule: $schedule
);
```

### Example: Schedule in User's Local Timezone

```php
// User wants message at 9:00 AM in their timezone (America/New_York)
$userTimezone = 'America/New_York';
$scheduledTime = new DateTimeImmutable('2025-11-15 09:00:00');
$schedule = ScheduleOptions::at($scheduledTime, $userTimezone);

$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'Good morning!',
    schedule: $schedule
);
```

## Message Status

Check the status of a sent message:

```php
$status = $client->messages()->getStatus('01ARZ3NDEKTSV4RRFFQ69G5FBD');

// Response:
// [
//     'status' => 'delivered',
//     'sent_at' => '2025-01-27T10:00:05.000000Z',
//     'delivered_at' => '2025-01-27T10:00:10.000000Z',
//     'read_at' => null
// ]
```

## Webhooks

### Create Webhook

```php
$webhook = $client->webhooks()->create(
    type: 'delivery', // or 'read', 'incoming_message'
    url: 'https://your-domain.com/webhooks/delivery',
    secret: 'your_webhook_secret',
    active: true
);
```

### Update Webhook

```php
$webhook = $client->webhooks()->update(
    webhookId: '01ARZ3NDEKTSV4RRFFQ69G5FBG',
    data: [
        'url' => 'https://your-domain.com/webhooks/delivery-updated',
        'active' => true
    ]
);
```

### Delete Webhook

```php
$client->webhooks()->delete('01ARZ3NDEKTSV4RRFFQ69G5FBG');
```

### Verify Webhook Signature (Laravel Example)

```php
<?php

namespace App\Http\Controllers;

use GradiWapp\Sdk\Resources\Webhooks;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleDelivery(Request $request)
    {
        $signature = $request->header('X-Webhook-Signature');
        $secret = config('gradiwapp.webhook_secret'); // Store this securely
        $payload = $request->getContent();

        if (!Webhooks::verifySignature($payload, $signature, $secret)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();
        
        // Process webhook data
        // $data['event'] - event type
        // $data['data'] - event data
        // $data['timestamp'] - event timestamp

        return response()->json(['received' => true]);
    }
}
```

## Error Handling

The SDK throws specific exceptions for different error types:

```php
use GradiWapp\Sdk\Exceptions\AuthenticationException;
use GradiWapp\Sdk\Exceptions\ValidationException;
use GradiWapp\Sdk\Exceptions\HttpException;
use GradiWapp\Sdk\Exceptions\GradiWappException;

try {
    $message = $client->messages()->sendText('+1234567890', 'Hello');
} catch (AuthenticationException $e) {
    // 401/403 - Invalid API key/secret or signature
    echo "Authentication failed: " . $e->getMessage();
} catch (ValidationException $e) {
    // 422 - Validation errors
    echo "Validation failed: " . $e->getMessage();
    $errors = $e->getErrors(); // Array of validation errors
} catch (HttpException $e) {
    // Other 4xx/5xx errors
    echo "HTTP error: " . $e->getMessage();
    echo "Status code: " . $e->getCode();
} catch (GradiWappException $e) {
    // Generic SDK exception
    echo "Error: " . $e->getMessage();
}
```

### Accessing Error Details

All exceptions provide access to:

- `getMessage()` - Error message
- `getCode()` - HTTP status code
- `getErrors()` - Validation errors (if applicable)
- `getMeta()` - Response metadata
- `getRawBody()` - Raw response body

## Advanced Usage

### Using Session ID

If you have multiple WhatsApp sessions, you can specify which session to use:

```php
$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'Hello',
    sessionId: '01ARZ3NDEKTSV4RRFFQ69G5FBC'
);
```

### Custom Priority

```php
$message = $client->messages()->sendText(
    to: '+1234567890',
    body: 'Urgent message',
    priority: 'high' // 'normal', 'high', or 'low'
);
```

## Response Format

All API responses follow the BaseResponse format:

```json
{
    "success": true,
    "message": "Message queued successfully",
    "data": {
        "id": "01ARZ3NDEKTSV4RRFFQ69G5FBD",
        "type": "text",
        "to_msisdn": "+1234567890",
        "body": "Hello, World!",
        "status": "queued",
        "created_at": "2025-01-27T10:00:00.000000Z",
        "delivery_status": {
            "is_sent": false,
            "is_delivered": false,
            "is_read": false,
            "is_queued": true
        }
    },
    "meta": {
        "request_id": "req_123456",
        "timestamp": "2025-01-27T10:00:00.000000Z",
        "locale": "en"
    }
}
```

## Versioning & Support

This SDK follows Semantic Versioning (SemVer). The SDK version aligns with the backend API version where applicable.

- **Breaking changes** will result in a major version bump
- **New features** will result in a minor version bump
- **Bug fixes** will result in a patch version bump

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This SDK is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support, please contact:
- Email: support@gradiwapp.com
- Documentation: https://docs.gradiwapp.com

