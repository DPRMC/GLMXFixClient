<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an AllocRegulatory TradeID group entry specific to NewOrderSingleAllocation.
 */
class NewOrderSingleAllocRegulatoryTradeID {
    public readonly string $allocRegulatoryTradeID;         // Tag 1909
    public readonly string $allocRegulatoryTradeIDSource;   // Tag 1910
    public readonly int    $allocRegulatoryTradeIDType;     // Tag 1912

    /**
     * @param string $allocRegulatoryTradeID GLMX Trade Id of the trade to be modified
     * @param string $allocRegulatoryTradeIDSource GLMX
     * @param int $allocRegulatoryTradeIDType Trading venue transaction identifier (5)
     */
    public function __construct(
        string $allocRegulatoryTradeID,
        string $allocRegulatoryTradeIDSource,
        int    $allocRegulatoryTradeIDType
    ) {
        $this->allocRegulatoryTradeID       = $allocRegulatoryTradeID;
        $this->allocRegulatoryTradeIDSource = $allocRegulatoryTradeIDSource;
        $this->allocRegulatoryTradeIDType   = $allocRegulatoryTradeIDType;
    }
}