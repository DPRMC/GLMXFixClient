<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the ExecutionReport (MsgType=8) message.
 * This message is generated on execution of an inquiry.
 */
class ExecutionReport extends AbstractIncomingMessage
{
    // Execution Report Specific Fields (from GLMX Inquiry Creation via FIX Integration Guide 1.25.pdf, page 21-29)
    public readonly string $orderID;                   // Tag 37
    public readonly string $cIOrdID;                   // Tag 11
    public readonly ?string $origCIOrdID;              // Tag 41
    public readonly string $gLMXTradeType;             // Tag 5001
    public readonly string $execID;                    // Tag 17
    public readonly ?string $execRefID;                // Tag 19
    public readonly string $execType;                  // Tag 150
    public readonly string $ordStatus;                 // Tag 39
    public readonly float $cumQty;                     // Tag 14
    public readonly float $leavesQty;                  // Tag 151
    public readonly ?float $lastPx;                    // Tag 31
    public readonly ?float $lastQty;                   // Tag 32
    public readonly int $totNumReports;                // Tag 911
    public readonly ?string $listID;                   // Tag 66
    public readonly ?string $settlDate;                // Tag 64
    public readonly ?string $maturityDate;             // Tag 541
    public readonly ?string $securitySubType;          // Tag 762
    public readonly ?string $gcType;                   // Tag 5006
    public readonly ?int $terminationType;             // Tag 788
    public readonly ?string $agreementDate;            // Tag 915
    public readonly ?string $startDate;                // Tag 916
    public readonly ?string $endDate;                  // Tag 917
    public readonly ?int $deliveryType;                // Tag 919
    public readonly int $noUnderlyings;                // Tag 711
    public readonly array $underlyings;                // Group: Underlying (309, 305, 810, 1039, 311, 318, 246, 879, 884, 882, 436, 883, 6223)
    public readonly ?float $price;                     // Tag 44
    public readonly ?int $priceType;                   // Tag 423
    public readonly ?string $benchmarkCurveCurrency;   // Tag 220
    public readonly ?string $benchmarkCurveName;       // Tag 221
    public readonly ?string $benchmarkCurvePoint;      // Tag 222
    public readonly ?int $gLMXRateType;                // Tag 5020
    public readonly ?string $tradeDate;                // Tag 75
    public readonly ?float $interestAtMaturity;        // Tag 738
    public readonly ?float $allinPrice;                // Tag 5005
    public readonly ?float $startCash;                 // Tag 921
    public readonly ?float $endCash;                   // Tag 922
    public readonly int $noAllocs;                     // Tag 78
    public readonly array $allocations;                // Group: Alloc (79, 467, 661, 80, 5009, 5010, 5059, 5060, 5061, 1908)
    public readonly ?float $marginRatio;               // Tag 898
    public readonly ?int $haircutFormula;              // Tag 5002
    public readonly ?string $tradeLinkID;              // Tag 820
    public readonly ?float $commission;                // Tag 12
    public readonly ?int $dayCount;                    // Tag 5003
    public readonly ?string $tradingBook;              // Tag 5004
    public readonly ?int $tradeContinuation;           // Tag 1937
    public readonly ?string $tradeContinuationText;    // Tag 2374
    public readonly ?string $settlCurrency;            // Tag 120
    public readonly ?float $settlCurrAmt;              // Tag 119
    public readonly ?float $settlCurrFxRate;           // Tag 155
    public readonly ?string $settlCurrFxRateCalc;      // Tag 156
    public readonly int $noEvents;                     // Tag 864
    public readonly array $events;                     // Group: Event (865, 866, 1827, 1826)
    public readonly int $noGLMXStips;                  // Tag 5016
    public readonly array $gLMXStips;                  // Group: GLMXStip (5017, 5018)
    public readonly ?bool $gLMXChangedFromOriginal;    // Tag 5027
    public readonly ?string $gLMXActionReason;         // Tag 5011
    public readonly ?string $gLMXLinkedTradeID;        // Tag 5012
    public readonly int $noLegs;                       // Tag 555
    public readonly array $legs;                       // Group: Legs (600, 624, 687, 654, 5063, 539)
    public readonly ?string $gLMXInquiryID;            // Tag 5022
    public readonly int $noGLMXCompetingQuotes;        // Tag 5028
    public readonly array $gLMXCompetingQuotes;        // Group: GLMXCompeting Quotes (5029, 5030, 5032, 5033, 5034, 5035, 5036, 5037)
    public readonly ?int $trdType;                     // Tag 828
    public readonly int $noStipulations;               // Tag 232
    public readonly array $stipulations;               // Group: NoStipulations (233, 234)
    public readonly ?string $gLMXCollateralType;       // Tag 5039
    public readonly ?string $gLMXCollateralDesc;       // Tag 5040
    public readonly ?string $gLMXSBLTripartyAgent;     // Tag 5055
    public readonly ?string $gLMXFeeType;              // Tag 5041
    public readonly ?string $gLMXIndexType;            // Tag 5053
    public readonly ?float $netMoney;                  // Tag 118
    public readonly ?float $adjustedEndCash;           // Tag 6379
    public readonly ?string $allocID;                  // Tag 70
    public readonly ?string $secondaryOrderID;         // Tag 198
    public readonly ?string $gLMXLastActionSource;     // Tag 5056
    public readonly ?string $gLMXTradeCategory;        // Tag 5046
    public readonly ?string $gLMXCollectionID;         // Tag 5069
    public readonly ?float $gLMXCoupDivReq;            // Tag 5071

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $orderID
     * @param string $cIOrdID
     * @param string|null $origCIOrdID
     * @param string $gLMXTradeType
     * @param string $execID
     * @param string|null $execRefID
     * @param string $execType
     * @param string $ordStatus
     * @param float $cumQty
     * @param float $leavesQty
     * @param float|null $lastPx
     * @param float|null $lastQty
     * @param int $totNumReports
     * @param array $partyIDs
     * @param string|null $listID
     * @param string|null $settlDate
     * @param string|null $maturityDate
     * @param string|null $symbol
     * @param int|null $product
     * @param string $securityType
     * @param string|null $securitySubType
     * @param string|null $gcType
     * @param int|null $terminationType
     * @param string|null $agreementDate
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $deliveryType
     * @param int $noUnderlyings
     * @param ExecutionReportUnderlying[] $underlyings
     * @param string $side
     * @param float|null $price
     * @param int|null $priceType
     * @param string|null $benchmarkCurveCurrency
     * @param string|null $benchmarkCurveName
     * @param string|null $benchmarkCurvePoint
     * @param int|null $gLMXRateType
     * @param string|null $currency
     * @param string|null $tradeDate
     * @param string|null $transactTime
     * @param float|null $interestAtMaturity
     * @param float|null $allinPrice
     * @param float|null $startCash
     * @param float|null $endCash
     * @param int $noAllocs
     * @param ExecutionReportAllocation[] $allocations
     * @param float|null $marginRatio
     * @param int|null $haircutFormula
     * @param string|null $tradeLinkID
     * @param float|null $commission
     * @param int|null $dayCount
     * @param string|null $tradingBook
     * @param int|null $tradeContinuation
     * @param string|null $tradeContinuationText
     * @param string|null $settlCurrency
     * @param float|null $settlCurrAmt
     * @param float|null $settlCurrFxRate
     * @param string|null $settlCurrFxRateCalc
     * @param int $noEvents
     * @param Event[] $events
     * @param int $noGLMXStips
     * @param GLMXStip[] $gLMXStips
     * @param bool|null $gLMXChangedFromOriginal
     * @param string|null $gLMXActionReason
     * @param string|null $gLMXLinkedTradeID
     * @param int $noLegs
     * @param ExecutionReportLeg[] $legs
     * @param string|null $gLMXInquiryID
     * @param int $noGLMXCompetingQuotes
     * @param ExecutionReportCompetingQuote[] $gLMXCompetingQuotes
     * @param int|null $trdType
     * @param int $noStipulations
     * @param Stipulation[] $stipulations
     * @param string|null $gLMXCollateralType
     * @param string|null $gLMXCollateralDesc
     * @param string|null $gLMXSBLTripartyAgent
     * @param string|null $gLMXFeeType
     * @param string|null $gLMXIndexType
     * @param float|null $netMoney
     * @param float|null $adjustedEndCash
     * @param string|null $allocID
     * @param string|null $secondaryOrderID
     * @param string|null $gLMXLastActionSource
     * @param string|null $gLMXTradeCategory
     * @param string|null $gLMXCollectionID
     * @param float|null $gLMXCoupDivReq
     */
    public function __construct(
        string $beginString,
        int $bodyLength,
        string $msgType,
        string $senderCompID,
        string $targetCompID,
        int $msgSeqNum,
        string $sendingTime,
        string $checkSum,
        string $orderID,
        string $cIOrdID,
        ?string $origCIOrdID,
        string $gLMXTradeType,
        string $execID,
        ?string $execRefID,
        string $execType,
        string $ordStatus,
        float $cumQty,
        float $leavesQty,
        ?float $lastPx,
        ?float $lastQty,
        int $totNumReports,
        array $partyIDs, // From AbstractIncomingMessage, but type-hinted here for clarity
        ?string $listID,
        ?string $settlDate,
        ?string $maturityDate,
        ?string $symbol, // From AbstractIncomingMessage
        ?int $product, // From AbstractIncomingMessage
        string $securityType, // From AbstractIncomingMessage
        ?string $securitySubType,
        ?string $gcType,
        ?int $terminationType,
        ?string $agreementDate,
        ?string $startDate,
        ?string $endDate,
        ?int $deliveryType,
        int $noUnderlyings,
        array $underlyings,
        string $side, // From AbstractIncomingMessage
        ?float $price,
        ?int $priceType,
        ?string $benchmarkCurveCurrency,
        ?string $benchmarkCurveName,
        ?string $benchmarkCurvePoint,
        ?int $gLMXRateType,
        ?string $currency, // From AbstractIncomingMessage
        ?string $tradeDate,
        ?string $transactTime, // From AbstractIncomingMessage
        ?float $interestAtMaturity,
        ?float $allinPrice,
        ?float $startCash,
        ?float $endCash,
        int $noAllocs,
        array $allocations,
        ?float $marginRatio,
        ?int $haircutFormula,
        ?string $tradeLinkID,
        ?float $commission,
        ?int $dayCount,
        ?string $tradingBook,
        ?int $tradeContinuation,
        ?string $tradeContinuationText,
        ?string $settlCurrency,
        ?float $settlCurrAmt,
        ?float $settlCurrFxRate,
        ?string $settlCurrFxRateCalc,
        int $noEvents,
        array $events,
        int $noGLMXStips,
        array $gLMXStips,
        ?bool $gLMXChangedFromOriginal,
        ?string $gLMXActionReason,
        ?string $gLMXLinkedTradeID,
        int $noLegs,
        array $legs,
        ?string $gLMXInquiryID,
        int $noGLMXCompetingQuotes,
        array $gLMXCompetingQuotes,
        ?int $trdType,
        int $noStipulations,
        array $stipulations,
        ?string $gLMXCollateralType,
        ?string $gLMXCollateralDesc,
        ?string $gLMXSBLTripartyAgent,
        ?string $gLMXFeeType,
        ?string $gLMXIndexType,
        ?float $netMoney,
        ?float $adjustedEndCash,
        ?string $allocID,
        ?string $secondaryOrderID,
        ?string $gLMXLastActionSource,
        ?string $gLMXTradeCategory,
        ?string $gLMXCollectionID,
        ?float $gLMXCoupDivReq,
        ?float $orderQty = null // From AbstractIncomingMessage
    ) {
        parent::__construct(
            $beginString,
            $bodyLength,
            $msgType,
            $senderCompID,
            $targetCompID,
            $msgSeqNum,
            $sendingTime,
            $checkSum,
            $transactTime,
            $side,
            $symbol,
            $product,
            $securityType,
            $orderQty,
            $currency,
            $partyIDs
        );

        $this->orderID                      = $orderID;
        $this->cIOrdID                      = $cIOrdID;
        $this->origCIOrdID                  = $origCIOrdID;
        $this->gLMXTradeType                = $gLMXTradeType;
        $this->execID                       = $execID;
        $this->execRefID                    = $execRefID;
        $this->execType                     = $execType;
        $this->ordStatus                    = $ordStatus;
        $this->cumQty                       = $cumQty;
        $this->leavesQty                    = $leavesQty;
        $this->lastPx                       = $lastPx;
        $this->lastQty                      = $lastQty;
        $this->totNumReports                = $totNumReports;
        $this->listID                       = $listID;
        $this->settlDate                    = $settlDate;
        $this->maturityDate                 = $maturityDate;
        $this->securitySubType              = $securitySubType;
        $this->gcType                       = $gcType;
        $this->terminationType              = $terminationType;
        $this->agreementDate                = $agreementDate;
        $this->startDate                    = $startDate;
        $this->endDate                      = $endDate;
        $this->deliveryType                 = $deliveryType;
        $this->noUnderlyings                = $noUnderlyings;
        $this->underlyings                  = $underlyings;
        $this->price                        = $price;
        $this->priceType                    = $priceType;
        $this->benchmarkCurveCurrency       = $benchmarkCurveCurrency;
        $this->benchmarkCurveName           = $benchmarkCurveName;
        $this->benchmarkCurvePoint          = $benchmarkCurvePoint;
        $this->gLMXRateType                 = $gLMXRateType;
        $this->tradeDate                    = $tradeDate;
        $this->interestAtMaturity           = $interestAtMaturity;
        $this->allinPrice                   = $allinPrice;
        $this->startCash                    = $startCash;
        $this->endCash                      = $endCash;
        $this->noAllocs                     = $noAllocs;
        $this->allocations                  = $allocations;
        $this->marginRatio                  = $marginRatio;
        $this->haircutFormula               = $haircutFormula;
        $this->tradeLinkID                  = $tradeLinkID;
        $this->commission                   = $commission;
        $this->dayCount                     = $dayCount;
        $this->tradingBook                  = $tradingBook;
        $this->tradeContinuation            = $tradeContinuation;
        $this->tradeContinuationText        = $tradeContinuationText;
        $this->settlCurrency                = $settlCurrency;
        $this->settlCurrAmt                 = $settlCurrAmt;
        $this->settlCurrFxRate              = $settlCurrFxRate;
        $this->settlCurrFxRateCalc          = $settlCurrFxRateCalc;
        $this->noEvents                     = $noEvents;
        $this->events                       = $events;
        $this->noGLMXStips                  = $noGLMXStips;
        $this->gLMXStips                    = $gLMXStips;
        $this->gLMXChangedFromOriginal      = $gLMXChangedFromOriginal;
        $this->gLMXActionReason             = $gLMXActionReason;
        $this->gLMXLinkedTradeID            = $gLMXLinkedTradeID;
        $this->noLegs                       = $noLegs;
        $this->legs                         = $legs;
        $this->gLMXInquiryID                = $gLMXInquiryID;
        $this->noGLMXCompetingQuotes        = $noGLMXCompetingQuotes;
        $this->gLMXCompetingQuotes          = $gLMXCompetingQuotes;
        $this->trdType                      = $trdType;
        $this->noStipulations               = $noStipulations;
        $this->stipulations                 = $stipulations;
        $this->gLMXCollateralType           = $gLMXCollateralType;
        $this->gLMXCollateralDesc           = $gLMXCollateralDesc;
        $this->gLMXSBLTripartyAgent         = $gLMXSBLTripartyAgent;
        $this->gLMXFeeType                  = $gLMXFeeType;
        $this->gLMXIndexType                = $gLMXIndexType;
        $this->netMoney                     = $netMoney;
        $this->adjustedEndCash              = $adjustedEndCash;
        $this->allocID                      = $allocID;
        $this->secondaryOrderID             = $secondaryOrderID;
        $this->gLMXLastActionSource         = $gLMXLastActionSource;
        $this->gLMXTradeCategory            = $gLMXTradeCategory;
        $this->gLMXCollectionID             = $gLMXCollectionID;
        $this->gLMXCoupDivReq               = $gLMXCoupDivReq;
    }
}