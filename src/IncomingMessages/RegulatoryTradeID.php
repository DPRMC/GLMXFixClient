<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a RegulatoryTradeID group entry.
 * This is a common group structure used across multiple message types.
 * Note: Fields vary slightly, so this is a more general version.
 */
class RegulatoryTradeID {
    public readonly string  $regulatoryTradeID;        // Tag 1903
    public readonly string  $regulatoryTradeIDSource;  // Tag 1905
    public readonly ?string $regulatoryTradeIDEvent;   // Tag 1904 (Optional)
    public readonly ?int    $regulatoryTradeIDType;    // Tag 1906 (Optional)

    /**
     * @param string $regulatoryTradeID Unique trade identifier
     * @param string $regulatoryTradeIDSource Reporting entity (e.g., 'GLMX')
     * @param string|null $regulatoryTradeIDEvent Event which caused origination (optional)
     * @param int|null $regulatoryTradeIDType Type of trade identifier (optional)
     */
    public function __construct(
        string  $regulatoryTradeID,
        string  $regulatoryTradeIDSource,
        ?string $regulatoryTradeIDEvent = NULL,
        ?int    $regulatoryTradeIDType = NULL
    ) {
        $this->regulatoryTradeID       = $regulatoryTradeID;
        $this->regulatoryTradeIDSource = $regulatoryTradeIDSource;
        $this->regulatoryTradeIDEvent  = $regulatoryTradeIDEvent;
        $this->regulatoryTradeIDType   = $regulatoryTradeIDType;
    }
}
