<?php

namespace GradiWapp\Sdk\Tests;

use GradiWapp\Sdk\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigCreation()
    {
        $config = new Config(
            baseUrl: 'https://api.example.com/external/v1',
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            timeout: 30,
            maxRetries: 2,
            verifySsl: true
        );

        $this->assertEquals('https://api.example.com/external/v1', $config->getBaseUrl());
        $this->assertEquals('test_key', $config->getApiKey());
        $this->assertEquals('test_secret', $config->getApiSecret());
        $this->assertEquals(30, $config->getTimeout());
        $this->assertEquals(2, $config->getMaxRetries());
        $this->assertTrue($config->getVerifySsl());
    }

    public function testConfigFromArray()
    {
        $config = Config::fromArray([
            'base_url' => 'https://api.example.com/external/v1',
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
            'timeout' => 60,
            'max_retries' => 3,
            'verify_ssl' => false,
        ]);

        $this->assertEquals('https://api.example.com/external/v1', $config->getBaseUrl());
        $this->assertEquals('test_key', $config->getApiKey());
        $this->assertEquals('test_secret', $config->getApiSecret());
        $this->assertEquals(60, $config->getTimeout());
        $this->assertEquals(3, $config->getMaxRetries());
        $this->assertFalse($config->getVerifySsl());
    }

    public function testConfigBaseUrlTrimming()
    {
        $config = new Config(
            baseUrl: 'https://api.example.com/external/v1/',
            apiKey: 'test_key',
            apiSecret: 'test_secret'
        );

        $this->assertEquals('https://api.example.com/external/v1', $config->getBaseUrl());
    }
}

