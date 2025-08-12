<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an AllocsGrp group entry specific to AllocationReport.
 */
class AllocationReportAllocation extends Allocation {
    public readonly ?float $allocNetMoney;                // Tag 154
    public readonly int    $noAllocRegulatoryTradeIDs;    // Tag 1908
    public readonly array  $allocRegulatoryTradeIDs;      // Group: Allocation Regulatory Trade IDGrp (1909, 1910, 1911, 1912)

    /**
     * @param string $allocAccount
     * @param float $allocQty
     * @param string|null $individualAllocID
     * @param int|null $allocAcctIDSource
     * @param float|null $allocNetMoney
     * @param int $noAllocRegulatoryTradeIDs
     * @param AllocationReportAllocRegulatoryTradeID[] $allocRegulatoryTradeIDs
     */
    public function __construct(
        string  $allocAccount,
        float   $allocQty,
        ?string $individualAllocID = NULL,
        ?int    $allocAcctIDSource = NULL,
        ?float  $allocNetMoney = NULL,
        int     $noAllocRegulatoryTradeIDs = 0,
        array   $allocRegulatoryTradeIDs = []
    ) {
        parent::__construct( $allocAccount, $allocQty, $individualAllocID, $allocAcctIDSource );
        $this->allocNetMoney             = $allocNetMoney;
        $this->noAllocRegulatoryTradeIDs = $noAllocRegulatoryTradeIDs;
        $this->allocRegulatoryTradeIDs   = $allocRegulatoryTradeIDs;
    }
}