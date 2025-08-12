<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a GLMXStip group entry.
 * This is a common group structure used across multiple message types.
 */
class GLMXStip {
    public readonly string $gLMXStipType;  // Tag 5017
    public readonly string $gLMXStipValue; // Tag 5018

    /**
     * @param string $gLMXStipType Type of GLMX stipulation (e.g., 'ROLL', 'STRUCT')
     * @param string $gLMXStipValue Value of the GLMX stipulation
     */
    public function __construct(
        string $gLMXStipType,
        string $gLMXStipValue
    ) {
        $this->gLMXStipType  = $gLMXStipType;
        $this->gLMXStipValue = $gLMXStipValue;
    }
}