<?php

namespace GradiWapp\Sdk\Resources;

use DateTimeInterface;
use GradiWapp\Sdk\Client;
use GradiWapp\Sdk\Support\ScheduleOptions;

/**
 * Messages Resource
 */
class Messages
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send a text message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $body Message body
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendText(
        string $to,
        string $body,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'text',
            'body' => $body,
            'priority' => $priority,
        ];

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send an image message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $imageUrlOrBase64 Image URL or base64 encoded image
     * @param string|null $caption Optional caption
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendImage(
        string $to,
        string $imageUrlOrBase64,
        ?string $caption = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'image',
            'priority' => $priority,
        ];

        // Determine if it's a URL or base64
        if (filter_var($imageUrlOrBase64, FILTER_VALIDATE_URL)) {
            $payload['image_url'] = $imageUrlOrBase64;
        } else {
            $payload['image'] = $imageUrlOrBase64;
        }

        if ($caption !== null) {
            $payload['caption'] = $caption;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a media message (via URL)
     *
     * @param string $to Phone number (MSISDN)
     * @param string $mediaUrl Media URL
     * @param string|null $caption Optional caption
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendMedia(
        string $to,
        string $mediaUrl,
        ?string $caption = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'media',
            'media' => [
                'url' => $mediaUrl,
            ],
            'priority' => $priority,
        ];

        if ($caption !== null) {
            $payload['media']['caption'] = $caption;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a location message
     *
     * @param string $to Phone number (MSISDN)
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @param string|null $name Optional location name
     * @param string|null $address Optional address
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'location',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'priority' => $priority,
        ];

        if ($name !== null) {
            $payload['name'] = $name;
        }

        if ($address !== null) {
            $payload['address'] = $address;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a link preview message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $url URL for link preview
     * @param string|null $caption Optional caption
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendLink(
        string $to,
        string $url,
        ?string $caption = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'link',
            'url' => $url,
            'priority' => $priority,
        ];

        if ($caption !== null) {
            $payload['caption'] = $caption;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a file message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $fileUrl File URL
     * @param string|null $filename Optional filename
     * @param string|null $body Optional message body
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendFile(
        string $to,
        string $fileUrl,
        ?string $filename = null,
        ?string $body = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'file',
            'file_url' => $fileUrl,
            'priority' => $priority,
        ];

        if ($filename !== null) {
            $payload['filename'] = $filename;
        }

        if ($body !== null) {
            $payload['body'] = $body;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a contact vCard message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $contactsId Contact ID from WhatsApp
     * @param string|null $body Optional body
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendContact(
        string $to,
        string $contactsId,
        ?string $body = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'contact',
            'contacts_id' => $contactsId,
            'priority' => $priority,
        ];

        if ($body !== null) {
            $payload['body'] = $body;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a reply message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $body Message body
     * @param string $replyToMessageId Message ID to reply to
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendReply(
        string $to,
        string $body,
        string $replyToMessageId,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'reply',
            'body' => $body,
            'reply_to_message_id' => $replyToMessageId,
            'priority' => $priority,
        ];

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Send a text + media combination message
     *
     * @param string $to Phone number (MSISDN)
     * @param string $body Text body
     * @param string $mediaUrl Media URL
     * @param string|null $caption Optional caption
     * @param ScheduleOptions|null $schedule Optional scheduling
     * @param string|null $sessionId Optional session ID
     * @param string $priority Priority (normal, high, low)
     * @return array Message response
     */
    public function sendBoth(
        string $to,
        string $body,
        string $mediaUrl,
        ?string $caption = null,
        ?ScheduleOptions $schedule = null,
        ?string $sessionId = null,
        string $priority = 'normal'
    ): array {
        $payload = [
            'to_msisdn' => $to,
            'type' => 'both',
            'body' => $body,
            'media' => [
                'url' => $mediaUrl,
            ],
            'priority' => $priority,
        ];

        if ($caption !== null) {
            $payload['media']['caption'] = $caption;
        }

        if ($sessionId !== null) {
            $payload['session_id'] = $sessionId;
        }

        $this->addScheduleToPayload($payload, $schedule);

        $response = $this->client->request('POST', '/messages/send', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Get message status
     *
     * @param string $messageId Message ID (ULID)
     * @return array Status response
     */
    public function getStatus(string $messageId): array
    {
        $response = $this->client->request('GET', "/messages/{$messageId}/status");
        return $response['data'] ?? $response;
    }

    /**
     * Add schedule fields to payload
     *
     * @param array $payload
     * @param ScheduleOptions|null $schedule
     * @return void
     */
    private function addScheduleToPayload(array &$payload, ?ScheduleOptions $schedule): void
    {
        if ($schedule === null) {
            return;
        }

        $scheduleAt = $schedule->getScheduleAt();
        $timezone = $schedule->getTimezone();

        if ($scheduleAt !== null) {
            if ($schedule->isIso8601() || $timezone === null) {
                // Use ISO8601 format (includes timezone offset)
                $payload['schedule_at'] = $scheduleAt;
            } else {
                // Use local time + timezone
                $payload['schedule_at'] = $scheduleAt;
                $payload['timezone'] = $timezone;
            }
        }
    }
}

