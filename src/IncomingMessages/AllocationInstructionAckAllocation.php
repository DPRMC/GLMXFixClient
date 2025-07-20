<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an AllocsGrp group entry specific to AllocationInstructionAck.
 */
class AllocationInstructionAckAllocation extends Allocation {
    public readonly ?string $individualAllocRejCode; // Tag 776

    /**
     * @param string $allocAccount
     * @param float $allocQty
     * @param string|null $individualAllocID
     * @param int|null $allocAcctIDSource
     * @param string|null $individualAllocRejCode Required if AllocStatus=2
     */
    public function __construct(
        string  $allocAccount,
        float   $allocQty,
        ?string $individualAllocID = NULL,
        ?int    $allocAcctIDSource = NULL,
        ?string $individualAllocRejCode = NULL
    ) {
        parent::__construct( $allocAccount, $allocQty, $individualAllocID, $allocAcctIDSource );
        $this->individualAllocRejCode = $individualAllocRejCode;
    }
}