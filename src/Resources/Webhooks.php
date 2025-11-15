<?php

namespace GradiWapp\Sdk\Resources;

use GradiWapp\Sdk\Client;

/**
 * Webhooks Resource
 */
class Webhooks
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a webhook
     *
     * @param string $type Webhook type (delivery, read, incoming_message)
     * @param string $url Webhook URL
     * @param string $secret Webhook secret for signature verification
     * @param bool $active Whether webhook is active
     * @return array Webhook response
     */
    public function create(string $type, string $url, string $secret, bool $active = true): array
    {
        $payload = [
            'type' => $type,
            'url' => $url,
            'secret' => $secret,
            'active' => $active,
        ];

        $response = $this->client->request('POST', '/webhooks', ['json' => $payload]);
        return $response['data'] ?? $response;
    }

    /**
     * Update a webhook
     *
     * @param string $webhookId Webhook ID (ULID)
     * @param array $data Update data (url, secret, active)
     * @return array Webhook response
     */
    public function update(string $webhookId, array $data): array
    {
        $response = $this->client->request('PATCH', "/webhooks/{$webhookId}", ['json' => $data]);
        return $response['data'] ?? $response;
    }

    /**
     * Delete a webhook
     *
     * @param string $webhookId Webhook ID (ULID)
     * @return array Response
     */
    public function delete(string $webhookId): array
    {
        $response = $this->client->request('DELETE', "/webhooks/{$webhookId}");
        return $response;
    }

    /**
     * Verify webhook signature (helper method)
     *
     * @param string $payload Raw request body
     * @param string $signature Signature from X-Webhook-Signature header
     * @param string $secret Webhook secret
     * @return bool
     */
    public static function verifySignature(string $payload, string $signature, string $secret): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }
}

