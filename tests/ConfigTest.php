<?php

namespace GradiWapp\Sdk\Tests;

use GradiWapp\Sdk\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigCreation()
    {
        $config = new Config(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            timeout: 30,
            maxRetries: 2,
            verifySsl: true
        );

        // Base URL is now a constant and cannot be overridden
        $this->assertEquals(Config::BASE_URL, $config->getBaseUrl());
        $this->assertEquals('https://api.gradiwapp.com/external/v1', $config->getBaseUrl());
        $this->assertEquals('test_key', $config->getApiKey());
        $this->assertEquals('test_secret', $config->getApiSecret());
        $this->assertEquals(30, $config->getTimeout());
        $this->assertEquals(2, $config->getMaxRetries());
        $this->assertTrue($config->getVerifySsl());
    }

    public function testConfigFromArray()
    {
        $config = Config::fromArray([
            'api_key' => 'test_key',
            'api_secret' => 'test_secret',
            'timeout' => 60,
            'max_retries' => 3,
            'verify_ssl' => false,
        ]);

        // Base URL is always the constant value
        $this->assertEquals(Config::BASE_URL, $config->getBaseUrl());
        $this->assertEquals('https://api.gradiwapp.com/external/v1', $config->getBaseUrl());
        $this->assertEquals('test_key', $config->getApiKey());
        $this->assertEquals('test_secret', $config->getApiSecret());
        $this->assertEquals(60, $config->getTimeout());
        $this->assertEquals(3, $config->getMaxRetries());
        $this->assertFalse($config->getVerifySsl());
    }

    public function testBaseUrlIsConstant()
    {
        $config1 = new Config('key1', 'secret1');
        $config2 = new Config('key2', 'secret2');

        // Both should return the same constant BASE_URL
        $this->assertEquals(Config::BASE_URL, $config1->getBaseUrl());
        $this->assertEquals(Config::BASE_URL, $config2->getBaseUrl());
        $this->assertEquals($config1->getBaseUrl(), $config2->getBaseUrl());
    }

    public function testBaseUrlConstantValue()
    {
        $this->assertEquals('https://api.gradiwapp.com/external/v1', Config::BASE_URL);
    }
}

