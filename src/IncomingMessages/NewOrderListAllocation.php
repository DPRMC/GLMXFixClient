<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Alloc group entry specific to NewOrderListOrder.
 * Contains additional GLMX strategy fields and AllocRegulatoryTradeIDs.
 */
class NewOrderListAllocation extends Allocation {
    public readonly ?string $gLMXStrategy;           // Tag 5009
    public readonly ?string $gLMXStrategy2;          // Tag 5010
    public readonly ?string $gLMXStrategy3;          // Tag 5059
    public readonly ?string $gLMXStrategy4;          // Tag 5060
    public readonly ?string $gLMXStrategy5;          // Tag 5061
    public readonly ?string $gLMXAllocSecurityType;  // Tag 5051
    public readonly ?float  $gLMXAllocCoupDivReq;    // Tag 5072

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
     * @param string|null $gLMXAllocSecurityType
     * @param float|null $gLMXAllocCoupDivReq
     */
    public function __construct(
        string  $allocAccount,
        float   $allocQty,
        ?string $individualAllocID = NULL,
        ?int    $allocAcctIDSource = NULL,
        ?string $gLMXStrategy = NULL,
        ?string $gLMXStrategy2 = NULL,
        ?string $gLMXStrategy3 = NULL,
        ?string $gLMXStrategy4 = NULL,
        ?string $gLMXStrategy5 = NULL,
        ?string $gLMXAllocSecurityType = NULL,
        ?float  $gLMXAllocCoupDivReq = NULL
    ) {
        parent::__construct( $allocAccount, $allocQty, $individualAllocID, $allocAcctIDSource );
        $this->gLMXStrategy          = $gLMXStrategy;
        $this->gLMXStrategy2         = $gLMXStrategy2;
        $this->gLMXStrategy3         = $gLMXStrategy3;
        $this->gLMXStrategy4         = $gLMXStrategy4;
        $this->gLMXStrategy5         = $gLMXStrategy5;
        $this->gLMXAllocSecurityType = $gLMXAllocSecurityType;
        $this->gLMXAllocCoupDivReq   = $gLMXAllocCoupDivReq;
    }
}