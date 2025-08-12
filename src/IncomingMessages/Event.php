<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Event group entry.
 * This is a common group structure used across multiple message types.
 */
class Event {
    public readonly int     $eventType;        // Tag 865
    public readonly ?string $eventDate;        // Tag 866
    public readonly ?string $eventTimeUnit;    // Tag 1826
    public readonly ?int    $eventTimePeriod;  // Tag 1827

    /**
     * @param int $eventType Type of event (e.g., 20 for Min Notice Period)
     * @param string|null $eventDate Date of the event
     * @param string|null $eventTimeUnit Time unit for the period ('H' for Hours, 'D' for Days)
     * @param int|null $eventTimePeriod Number of units for the call option
     */
    public function __construct(
        int     $eventType,
        ?string $eventDate = NULL,
        ?string $eventTimeUnit = NULL,
        ?int    $eventTimePeriod = NULL
    ) {
        $this->eventType       = $eventType;
        $this->eventDate       = $eventDate;
        $this->eventTimeUnit   = $eventTimeUnit;
        $this->eventTimePeriod = $eventTimePeriod;
    }
}