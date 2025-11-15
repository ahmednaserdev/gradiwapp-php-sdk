<?php

namespace GradiWapp\Sdk\Tests;

use GradiWapp\Sdk\Support\SignatureGenerator;
use PHPUnit\Framework\TestCase;

class SignatureGeneratorTest extends TestCase
{
    public function testSignatureGeneration()
    {
        $body = '{"to_msisdn":"+1234567890","type":"text","body":"Hello"}';
        $secret = 'test_secret';
        
        $signature = SignatureGenerator::generate($body, $secret);
        
        $this->assertNotEmpty($signature);
        $this->assertEquals(64, strlen($signature)); // SHA256 hex is 64 chars
        $this->assertEquals(
            hash_hmac('sha256', $body, $secret),
            $signature
        );
    }

    public function testTimestampGeneration()
    {
        $timestamp = SignatureGenerator::generateTimestamp();
        
        $this->assertNotEmpty($timestamp);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\+\-]\d{2}:\d{2}$/', $timestamp);
    }
}

