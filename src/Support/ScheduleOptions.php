<?php

namespace GradiWapp\Sdk\Support;

use DateTimeInterface;

/**
 * Schedule Options for scheduled messages
 */
class ScheduleOptions
{
    private ?DateTimeInterface $dateTime = null;
    private ?string $timezone = null;
    private ?string $iso8601 = null;

    private function __construct() {}

    /**
     * Create schedule options from DateTimeInterface with optional timezone
     *
     * @param DateTimeInterface $dateTime
     * @param string|null $timezone IANA timezone string (e.g., 'Africa/Cairo', 'America/New_York')
     * @return self
     */
    public static function at(DateTimeInterface $dateTime, ?string $timezone = null): self
    {
        $instance = new self();
        $instance->dateTime = $dateTime;
        $instance->timezone = $timezone;
        return $instance;
    }

    /**
     * Create schedule options from ISO8601 string with timezone offset
     *
     * @param string $iso8601 ISO8601 format with timezone offset (e.g., '2025-11-15T08:02:00+03:00')
     * @return self
     */
    public static function fromIso8601(string $iso8601): self
    {
        $instance = new self();
        $instance->iso8601 = $iso8601;
        return $instance;
    }

    /**
     * Get schedule_at value for API
     *
     * @return string|null
     */
    public function getScheduleAt(): ?string
    {
        if ($this->iso8601 !== null) {
            return $this->iso8601;
        }

        if ($this->dateTime !== null) {
            if ($this->timezone !== null) {
                // Return local time as string (will be parsed with timezone on backend)
                $dt = \DateTimeImmutable::createFromInterface($this->dateTime);
                $dt = $dt->setTimezone(new \DateTimeZone($this->timezone));
                return $dt->format('Y-m-d\TH:i:s');
            } else {
                // Return ISO8601 with timezone offset
                $dt = \DateTimeImmutable::createFromInterface($this->dateTime);
                return $dt->format('c');
            }
        }

        return null;
    }

    /**
     * Get timezone value for API (if using local time + timezone)
     *
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        if ($this->iso8601 !== null) {
            return null; // ISO8601 already includes timezone
        }

        return $this->timezone;
    }

    /**
     * Check if this uses ISO8601 format
     *
     * @return bool
     */
    public function isIso8601(): bool
    {
        return $this->iso8601 !== null;
    }
}

