<?php
namespace DPRMC\GLMXFixClient\IncomingMessages;

/**
 * Represents an Underlying group entry specific to ExecutionReport.
 * Contains additional fields not present in NewOrderSingle/List Underlying.
 */
class ExecutionReportUnderlying extends Underlying
{
    public readonly ?string $underlyingCurrency;        // Tag 318
    public readonly ?float $underlyingFactor;           // Tag 246
    public readonly ?float $underlyingQty;              // Tag 879
    public readonly ?float $underlyingStartValue;       // Tag 884
    public readonly ?float $underlyingDirtyPrice;       // Tag 882
    public readonly ?float $underlyingContractMultiplier; // Tag 436
    public readonly ?float $underlyingEndPrice;         // Tag 883
    public readonly int $noUnderlyingReinvCoupon;      // Tag 6223
    public readonly array $underlyingReinvCoupon;      // Group: Underlying ReinvCoupon (6224, 6225, 6226)

    /**
     * @param string $underlyingSecurityID
     * @param string|null $underlyingSecurityIDSource
     * @param float|null $underlyingPx
     * @param string|null $underlyingSettlMethod
     * @param string|null $underlyingSymbol
     * @param string|null $underlyingCurrency
     * @param float|null $underlyingFactor
     * @param float|null $underlyingQty
     * @param float|null $underlyingStartValue
     * @param float|null $underlyingDirtyPrice
     * @param float|null $underlyingContractMultiplier
     * @param float|null $underlyingEndPrice
     * @param int $noUnderlyingReinvCoupon
     * @param ExecutionReportUnderlyingReinvCoupon[] $underlyingReinvCoupon
     */
    public function __construct(
        string $underlyingSecurityID,
        ?string $underlyingSecurityIDSource = null,
        ?float $underlyingPx = null,
        ?string $underlyingSettlMethod = null,
        ?string $underlyingSymbol = null,
        ?string $underlyingCurrency = null,
        ?float $underlyingFactor = null,
        ?float $underlyingQty = null,
        ?float $underlyingStartValue = null,
        ?float $underlyingDirtyPrice = null,
        ?float $underlyingContractMultiplier = null,
        ?float $underlyingEndPrice = null,
        int $noUnderlyingReinvCoupon = 0,
        array $underlyingReinvCoupon = []
    ) {
        parent::__construct(
            $underlyingSecurityID,
            $underlyingSecurityIDSource,
            $underlyingPx,
            $underlyingSettlMethod,
            $underlyingSymbol
        );
        $this->underlyingCurrency           = $underlyingCurrency;
        $this->underlyingFactor             = $underlyingFactor;
        $this->underlyingQty                = $underlyingQty;
        $this->underlyingStartValue         = $underlyingStartValue;
        $this->underlyingDirtyPrice         = $underlyingDirtyPrice;
        $this->underlyingContractMultiplier = $underlyingContractMultiplier;
        $this->underlyingEndPrice           = $underlyingEndPrice;
        $this->noUnderlyingReinvCoupon      = $noUnderlyingReinvCoupon;
        $this->underlyingReinvCoupon        = $underlyingReinvCoupon;
    }
}