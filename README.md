# Official PHP SDK for the GradiWapp External API (Multi-Tenant WhatsApp SaaS Messaging Platform)

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-blue" />
  <img src="https://img.shields.io/badge/Laravel-7--12-red" />
  <img src="https://img.shields.io/badge/License-MIT-green" />
  <img src="https://img.shields.io/badge/SDK-Stable-success" />
</p>

## 📘 Overview

The GradiWapp PHP SDK provides a clean, developer-friendly wrapper around the GradiWapp External API, powering multi-tenant WhatsApp messaging at scale.

It supports:

- All message types (text, image, media, file, location…)
- Webhooks
- Timezone-aware scheduling
- Multi-session messaging
- Laravel 7–12 integration
- Full HMAC authentication

This SDK is production-ready and built with Stripe/Twilio-level documentation quality.

## 📑 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
  - [Non-Laravel](#non-laravel-installation)
  - [Laravel 7–12](#laravel-installation)
- [Configuration](#-configuration)
- [Quick Start](#-quick-start)
  - [Plain PHP](#plain-php-example)
  - [Laravel DI](#laravel--dependency-injection)
  - [Laravel Facade](#laravel--facade)
- [Messaging](#-messaging)
  - [Text](#text-messages)
  - [Image](#image-messages)
  - [Media](#media-messages)
  - [Location](#location-messages)
  - [Link](#link-messages)
  - [File](#file-messages)
  - [Contact](#contact-vcard)
  - [Reply](#reply-messages)
  - [Combined](#text--media-combination)
- [Scheduling](#-timezone-aware-scheduling)
- [Message Status](#-message-status)
- [Webhooks](#-webhooks)
- [Error Handling](#-error-handling)
- [Advanced Usage](#-advanced-usage)
- [Response Format](#-response-format)
- [Versioning](#-versioning--sdk-stability)
- [Contributing](#-contributing)
- [License](#-license)

## 🚀 Features

- ✔ Framework-agnostic (PHP 7.4+)
- ✔ First-class Laravel 7–12 integration
- ✔ Fully typed methods + PHPDoc
- ✔ Timezone-aware scheduling (ISO8601 + IANA)
- ✔ Webhook creation + signature verification
- ✔ All message types supported
- ✔ Clean exceptions & error classes
- ✔ Internal base URL (cannot be changed)
- ✔ Production-ready architecture

## 🧩 Requirements

| Component | Version |
|-----------|---------|
| PHP | >= 7.4 |
| Laravel | 7 → 12 |
| Extensions | cURL + JSON |

## 📥 Installation

### Non-Laravel Installation

```bash
composer require gradiwapp/gradiwapp-php-sdk
```

**Before publishing on Packagist:**

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/ahmednaserdev/gradiwapp-php-sdk.git"
    }
  ],
  "require": {
    "gradiwapp/gradiwapp-php-sdk": "dev-main"
  }
}
```

### Laravel Installation

```bash
composer require gradiwapp/gradiwapp-php-sdk
```

**Publish config:**

```bash
php artisan vendor:publish --provider="GradiWapp\Sdk\Laravel\GradiWappServiceProvider" --tag=config
```

This generates:

- `config/gradiwapp.php`

**Add environment variables:**

```env
GRADIWAPP_API_KEY=your_key
GRADIWAPP_API_SECRET=your_secret
GRADIWAPP_TIMEOUT=30
GRADIWAPP_MAX_RETRIES=1
GRADIWAPP_VERIFY_SSL=true
```

> **Note:** Base URL is internal and cannot be changed.

## ⚡ Quick Start

### Plain PHP Example

```php
use GradiWapp\Sdk\Client;
use GradiWapp\Sdk\Config;

$config = new Config(
    apiKey: 'your_key',
    apiSecret: 'your_secret'
);

$client = new Client($config);

$response = $client->messages()->sendText(
    to: '+123456',
    body: 'Hello from GradiWapp!'
);
```

### Laravel — Dependency Injection

```php
public function send(GradiWapp\Sdk\Client $client)
{
    return $client->messages()->sendText(
        to: '+123456',
        body: 'Hello!'
    );
}
```

### Laravel — Facade

```php
use GradiWapp\Sdk\Laravel\Facades\GradiWapp;

GradiWapp::messages()->sendText('+123456', 'Hello!');
```

## 💬 Messaging

### Text Messages

```php
$client->messages()->sendText('+123', 'Hello!');
```

### Image Messages

```php
$client->messages()->sendImage(
    '+123',
    'https://example.com/image.jpg',
    caption: 'Check this out!'
);
```

### Media Messages

```php
$client->messages()->sendMedia('+123', 'https://example.com/video.mp4');
```

### Location Messages

```php
$client->messages()->sendLocation(
    '+123',
    40.7128,
    -74.0060,
    name: 'NYC',
    address: 'New York'
);
```

### File Messages

```php
$client->messages()->sendFile(
    '+123',
    fileUrl: 'https://example.com/doc.pdf',
    filename: 'Invoice.pdf'
);
```

### Contact (vCard)

```php
$client->messages()->sendContact(
    '+123',
    contactsId: 'whatsapp_contact_id'
);
```

### Reply Messages

```php
$client->messages()->sendReply(
    '+123',
    'This is a reply',
    replyToMessageId: '01ABC...'
);
```

### Text + Media Combination

```php
$client->messages()->sendBoth(
    '+123',
    'Hello!',
    mediaUrl: 'https://example.com/pic.jpg',
    caption: 'Nice image'
);
```

## ⏰ Timezone-Aware Scheduling

### ISO8601 Example

```php
ScheduleOptions::fromIso8601('2025-11-15T08:02:00+03:00');
```

### IANA Timezone

```php
ScheduleOptions::at(
    new DateTimeImmutable('2025-11-15 08:00'),
    'Africa/Cairo'
);
```

## 📡 Message Status

```php
$client->messages()->getStatus('01ABC...');
```

## 🧩 Webhooks

### Create Webhook

```php
$client->webhooks()->create(
    'delivery',
    'https://yourdomain.com/webhooks',
    secret: 'secret'
);
```

### Verify Signature

```php
Webhooks::verifySignature($payload, $signature, $secret);
```

## ❗ Error Handling

Built-in typed exceptions:

- `AuthenticationException`
- `ValidationException`
- `HttpException`
- `GradiWappException`

**Example:**

```php
try {
    $client->messages()->sendText('+123', 'Hi');
} catch (ValidationException $e) {
    print_r($e->getErrors());
}
```

## 🔧 Advanced Usage

### Multiple Sessions

```php
$client->messages()->sendText(
    '+123',
    'Hi',
    sessionId: 'session_01'
);
```

### Priority

```php
$client->messages()->sendText('+123', 'Hi', priority: 'high');
```

## 📦 Response Format

Your backend returns:

```json
{
  "success": true,
  "message": "Message queued successfully",
  "data": {
    "id": "01ARZ3N...",
    "status": "queued"
  }
}
```

## 🧭 Versioning & SDK Stability

This SDK follows Semantic Versioning (SemVer):

- **MAJOR** → Breaking changes
- **MINOR** → New features
- **PATCH** → Bug fixes

## 🤝 Contributing

Pull Requests are welcome!

## 📄 License

MIT License.
