<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an AllocRegulatory TradeID group entry, specific to ExecutionReportAllocation.
 */
class ExecutionReportAllocRegulatoryTradeID
{
    public readonly string $allocRegulatoryTradeID;     // Tag 1909
    public readonly string $allocRegulatoryTradeIDSource; // Tag 1910
    public readonly int $allocRegulatoryTradeIDEvent;    // Tag 1911
    public readonly int $allocRegulatoryTradeIDType;     // Tag 1912

    /**
     * @param string $allocRegulatoryTradeID     GLMX Trade Id or UTI
     * @param string $allocRegulatoryTradeIDSource GLMX
     * @param int    $allocRegulatoryTradeIDEvent  Initial Block Trade (0) or Allocation (1)
     * @param int    $allocRegulatoryTradeIDType   GLMX UTI (0) or GLMX Trade Identifier (5)
     */
    public function __construct(
        string $allocRegulatoryTradeID,
        string $allocRegulatoryTradeIDSource,
        int $allocRegulatoryTradeIDEvent,
        int $allocRegulatoryTradeIDType
    ) {
        $this->allocRegulatoryTradeID     = $allocRegulatoryTradeID;
        $this->allocRegulatoryTradeIDSource = $allocRegulatoryTradeIDSource;
        $this->allocRegulatoryTradeIDEvent  = $allocRegulatoryTradeIDEvent;
        $this->allocRegulatoryTradeIDType   = $allocRegulatoryTradeIDType;
    }
}