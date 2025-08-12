<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a Stipulation group entry.
 * This is a common group structure used across multiple message types.
 */
class Stipulation
{
    public readonly string $stipulationType;  // Tag 233
    public readonly string $stipulationValue; // Tag 234

    /**
     * @param string $stipulationType  Type of stipulation (e.g., 'SUBSTITUTION', 'MAXSUBS')
     * @param string $stipulationValue Value of the stipulation
     */
    public function __construct(
        string $stipulationType,
        string $stipulationValue
    ) {
        $this->stipulationType  = $stipulationType;
        $this->stipulationValue = $stipulationValue;
    }
}