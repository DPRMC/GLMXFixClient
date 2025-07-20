<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Allocation Regulatory Trade IDGrp group entry specific to AllocationReportAllocation.
 */
class AllocationReportAllocRegulatoryTradeID {
    public readonly string $allocRegulatoryTradeID;         // Tag 1909
    public readonly string $allocRegulatoryTradeIDSource;   // Tag 1910
    public readonly int    $allocRegulatoryTradeIDEvent;    // Tag 1911
    public readonly int    $allocRegulatoryTradeIDType;     // Tag 1912

    /**
     * @param string $allocRegulatoryTradeID Unique trade identifier
     * @param string $allocRegulatoryTradeIDSource GLMX
     * @param int $allocRegulatoryTradeIDEvent Allocation (1)
     * @param int $allocRegulatoryTradeIDType GLMX UTI (0) or GLMX Trade Identifier (5)
     */
    public function __construct(
        string $allocRegulatoryTradeID,
        string $allocRegulatoryTradeIDSource,
        int    $allocRegulatoryTradeIDEvent,
        int    $allocRegulatoryTradeIDType
    ) {
        $this->allocRegulatoryTradeID       = $allocRegulatoryTradeID;
        $this->allocRegulatoryTradeIDSource = $allocRegulatoryTradeIDSource;
        $this->allocRegulatoryTradeIDEvent  = $allocRegulatoryTradeIDEvent;
        $this->allocRegulatoryTradeIDType   = $allocRegulatoryTradeIDType;
    }
}