<?php

namespace GradiWapp\Sdk;

/**
 * SDK Configuration
 */
class Config
{
    /**
     * Base URL for GradiWapp External API
     * This is internal and cannot be overridden.
     */
    public const BASE_URL = 'https://api.gradiwapp.com/external/v1';

    private string $apiKey;
    private string $apiSecret;
    private int $timeout;
    private int $maxRetries;
    private bool $verifySsl;

    public function __construct(
        string $apiKey,
        string $apiSecret,
        int $timeout = 30,
        int $maxRetries = 1,
        bool $verifySsl = true
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->timeout = $timeout;
        $this->maxRetries = $maxRetries;
        $this->verifySsl = $verifySsl;
    }

    /**
     * Create Config from array
     */
    public static function fromArray(array $config): self
    {
        return new self(
            $config['api_key'] ?? '',
            $config['api_secret'] ?? '',
            $config['timeout'] ?? 30,
            $config['max_retries'] ?? 1,
            $config['verify_ssl'] ?? true
        );
    }

    /**
     * Get the base URL (internal constant, non-configurable)
     */
    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    public function getVerifySsl(): bool
    {
        return $this->verifySsl;
    }
}

