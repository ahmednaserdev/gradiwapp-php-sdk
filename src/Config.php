<?php

namespace GradiWapp\Sdk;

/**
 * SDK Configuration
 */
class Config
{
    private string $baseUrl;
    private string $apiKey;
    private string $apiSecret;
    private int $timeout;
    private int $maxRetries;
    private bool $verifySsl;

    public function __construct(
        string $baseUrl,
        string $apiKey,
        string $apiSecret,
        int $timeout = 30,
        int $maxRetries = 1,
        bool $verifySsl = true
    ) {
        $this->baseUrl = $baseUrl;
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
            $config['base_url'] ?? '',
            $config['api_key'] ?? '',
            $config['api_secret'] ?? '',
            $config['timeout'] ?? 30,
            $config['max_retries'] ?? 1,
            $config['verify_ssl'] ?? true
        );
    }

    public function getBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
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

