<?php

namespace GradiWapp\Sdk\Support;

/**
 * HMAC Signature Generator for External API authentication
 */
class SignatureGenerator
{
    /**
     * Generate HMAC SHA256 signature for request body
     *
     * @param string $body Raw request body
     * @param string $secret API secret
     * @return string HMAC signature (hex)
     */
    public static function generate(string $body, string $secret): string
    {
        return hash_hmac('sha256', $body, $secret);
    }

    /**
     * Generate ISO8601 timestamp for Date header
     *
     * @return string ISO8601 timestamp
     */
    public static function generateTimestamp(): string
    {
        return (new \DateTime('now', new \DateTimeZone('UTC')))->format('c');
    }
}

