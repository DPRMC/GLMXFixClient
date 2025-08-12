<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an UnderlyingInstrument group entry.
 * This is a common group structure used across multiple message types.
 * Note: The fields vary slightly between NewOrderSingle/List and ExecutionReport.
 * For this implementation, I'm creating a generic one and will specialize if needed.
 */
class Underlying
{
    public readonly string $underlyingSecurityID;      // Tag 309
    public readonly ?string $underlyingSecurityIDSource; // Tag 305
    public readonly ?float $underlyingPx;              // Tag 810
    public readonly ?string $underlyingSettlMethod;     // Tag 1039
    public readonly ?string $underlyingSymbol;          // Tag 311 (Appears in ER, but not always in NOS/NOL table)

    /**
     * @param string $underlyingSecurityID      SecurityID of the collateral
     * @param string|null $underlyingSecurityIDSource Source of SecurityID (e.g., '1' for CUSIP)
     * @param float|null  $underlyingPx              Optional clean price of the underlying bond
     * @param string|null $underlyingSettlMethod     Settlement type of the performance for TRS
     * @param string|null $underlyingSymbol          Underlying Symbol (e.g., '[N/A]' or actual symbol)
     */
    public function __construct(
        string $underlyingSecurityID,
        ?string $underlyingSecurityIDSource = null,
        ?float $underlyingPx = null,
        ?string $underlyingSettlMethod = null,
        ?string $underlyingSymbol = null
    ) {
        $this->underlyingSecurityID       = $underlyingSecurityID;
        $this->underlyingSecurityIDSource = $underlyingSecurityIDSource;
        $this->underlyingPx               = $underlyingPx;
        $this->underlyingSettlMethod      = $underlyingSettlMethod;
        $this->underlyingSymbol           = $underlyingSymbol;
    }
}