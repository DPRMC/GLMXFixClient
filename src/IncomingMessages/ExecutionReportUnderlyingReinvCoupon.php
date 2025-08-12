<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Underlying ReinvCoupon group entry, specific to ExecutionReportUnderlying.
 */
class ExecutionReportUnderlyingReinvCoupon {
    public readonly string $underlyingReinvCouponDate; // Tag 6224
    public readonly float  $underlyingReinvCouponRate; // Tag 6225
    public readonly float  $underlyingReinvCouponAmt;  // Tag 6226

    /**
     * @param string $underlyingReinvCouponDate Coupon date
     * @param float $underlyingReinvCouponRate Coupon reinvestment rate
     * @param float $underlyingReinvCouponAmt Coupon amount
     */
    public function __construct(
        string $underlyingReinvCouponDate,
        float  $underlyingReinvCouponRate,
        float  $underlyingReinvCouponAmt
    ) {
        $this->underlyingReinvCouponDate = $underlyingReinvCouponDate;
        $this->underlyingReinvCouponRate = $underlyingReinvCouponRate;
        $this->underlyingReinvCouponAmt  = $underlyingReinvCouponAmt;
    }
}