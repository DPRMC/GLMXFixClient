<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Alloc group entry specific to ExecutionReport.
 * Contains additional GLMX strategy fields and AllocRegulatoryTradeIDs.
 */
class ExecutionReportAllocation extends Allocation
{
    public readonly ?string $gLMXStrategy;             // Tag 5009
    public readonly ?string $gLMXStrategy2;            // Tag 5010
    public readonly ?string $gLMXStrategy3;            // Tag 5059
    public readonly ?string $gLMXStrategy4;            // Tag 5060
    public readonly ?string $gLMXStrategy5;            // Tag 5061
    public readonly int $noAllocRegulatoryTradeIDs;    // Tag 1908
    public readonly array $allocRegulatoryTradeIDs;    // Group: AllocRegulatory TradeID (1909, 1910, 1911, 1912)

    /**
     * @param string $allocAccount
     * @param float $allocQty
     * @param string|null $individualAllocID
     * @param int|null $allocAcctIDSource
     * @param string|null $gLMXStrategy
     * @param string|null $gLMXStrategy2
     * @param string|null $gLMXStrategy3
     * @param string|null $gLMXStrategy4
     * @param string|null $gLMXStrategy5
     * @param int $noAllocRegulatoryTradeIDs
     * @param ExecutionReportAllocRegulatoryTradeID[] $allocRegulatoryTradeIDs
     */
    public function __construct(
        string $allocAccount,
        float $allocQty,
        ?string $individualAllocID = null,
        ?int $allocAcctIDSource = null,
        ?string $gLMXStrategy = null,
        ?string $gLMXStrategy2 = null,
        ?string $gLMXStrategy3 = null,
        ?string $gLMXStrategy4 = null,
        ?string $gLMXStrategy5 = null,
        int $noAllocRegulatoryTradeIDs = 0,
        array $allocRegulatoryTradeIDs = []
    ) {
        parent::__construct($allocAccount, $allocQty, $individualAllocID, $allocAcctIDSource);
        $this->gLMXStrategy              = $gLMXStrategy;
        $this->gLMXStrategy2             = $gLMXStrategy2;
        $this->gLMXStrategy3             = $gLMXStrategy3;
        $this->gLMXStrategy4             = $gLMXStrategy4;
        $this->gLMXStrategy5             = $gLMXStrategy5;
        $this->noAllocRegulatoryTradeIDs = $noAllocRegulatoryTradeIDs;
        $this->allocRegulatoryTradeIDs = $allocRegulatoryTradeIDs;
    }
}