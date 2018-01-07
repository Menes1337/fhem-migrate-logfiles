<?php

namespace FhemMigrateLogfiles\Entity;

use FhemMigrateLogfiles\ValueObject\Device;

class Event
{
    /** @var string */
    private $timestamp;
    /** @var Device */
    private $device;
    /** @var string */
    private $event;
    /** @var string */
    private $reading;
    /** @var string */
    private $value;

    /**
     * @param Device $device
     * @param string $timestamp
     * @param string $event
     * @param string $reading
     * @param string $value
     */
    public function __construct(
        Device $device,
        string $timestamp,
        string $event,
        string $reading,
        string $value
    ) {
        $this->timestamp = $timestamp;
        $this->device = $device;
        $this->event = $event;
        $this->reading = $reading;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getReading(): string
    {
        return $this->reading;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param Device $device
     */
    public function setDevice(Device $device): void
    {
        $this->device = $device;
    }

    /**
     * @param string $event
     */
    public function setEvent(string $event): void
    {
        $this->event = $event;
    }

    /**
     * @param string $reading
     */
    public function setReading(string $reading): void
    {
        $this->reading = $reading;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $values = [];
        foreach (get_object_vars($this) as $key => $value) {
            $values[] = $key . ':' . $value;
        }

        return implode(' ', $values);
    }
}