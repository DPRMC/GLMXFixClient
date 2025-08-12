<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Allocation group entry.
 * This is a common group structure used across multiple message types.
 * Note: This class is a generic representation. Specific message types might have
 * additional fields within their Allocation groups, which would necessitate
 * creating a specialized Allocation class for that message type.
 * For this implementation, I'm making it generic based on the most common fields.
 */
class Allocation {
    public readonly string  $allocAccount;        // Tag 79
    public readonly float   $allocQty;            // Tag 80
    public readonly ?string $individualAllocID;   // Tag 467 (Optional, appears in ER, AllocInstruction, AllocReport)
    public readonly ?int    $allocAcctIDSource;   // Tag 661 (Optional, appears in ER, AllocInstruction)

    /**
     * @param string $allocAccount Account/fund identifier or portfolio
     * @param float $allocQty Quantity of allocation
     * @param string|null $individualAllocID Unique identifier for specific allocation instance
     * @param int|null $allocAcctIDSource Account ID source (e.g., 99 for custom)
     */
    public function __construct(
        string  $allocAccount,
        float   $allocQty,
        ?string $individualAllocID = NULL,
        ?int    $allocAcctIDSource = NULL
    ) {
        $this->allocAccount      = $allocAccount;
        $this->allocQty          = $allocQty;
        $this->individualAllocID = $individualAllocID;
        $this->allocAcctIDSource = $allocAcctIDSource;
    }
}
