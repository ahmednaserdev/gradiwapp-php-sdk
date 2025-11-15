<?php

namespace GradiWapp\Sdk\Tests;

use GradiWapp\Sdk\Support\ScheduleOptions;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ScheduleOptionsTest extends TestCase
{
    public function testScheduleFromIso8601()
    {
        $iso8601 = '2025-11-15T08:02:00+03:00';
        $schedule = ScheduleOptions::fromIso8601($iso8601);

        $this->assertEquals($iso8601, $schedule->getScheduleAt());
        $this->assertNull($schedule->getTimezone());
        $this->assertTrue($schedule->isIso8601());
    }

    public function testScheduleFromDateTimeWithTimezone()
    {
        $dateTime = new DateTimeImmutable('2025-11-15 08:02:00');
        $timezone = 'Africa/Cairo';
        $schedule = ScheduleOptions::at($dateTime, $timezone);

        $this->assertNotNull($schedule->getScheduleAt());
        $this->assertEquals($timezone, $schedule->getTimezone());
        $this->assertFalse($schedule->isIso8601());
    }

    public function testScheduleFromDateTimeWithoutTimezone()
    {
        $dateTime = new DateTimeImmutable('2025-11-15 08:02:00');
        $schedule = ScheduleOptions::at($dateTime);

        $this->assertNotNull($schedule->getScheduleAt());
        $this->assertNull($schedule->getTimezone());
        $this->assertFalse($schedule->isIso8601());
        // Should be ISO8601 format with timezone offset
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\+\-]\d{2}:\d{2}$/', $schedule->getScheduleAt());
    }
}

