<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;

/**
 * Represents the ExecutionReport (MsgType=8) message.
 * This message is generated on execution of an inquiry.
 */
class ExecutionReport extends AbstractIncomingMessage {
    // Execution Report Specific Fields (from GLMX Inquiry Creation via FIX Integration Guide 1.25.pdf, page 21-29)
    public readonly ?string $orderID;
    public readonly ?string $cIOrdID;
    public readonly ?string $origCIOrdID;
    public readonly ?string $gLMXTradeType;
    public readonly ?string $execID;
    public readonly ?string $execRefID;
    public readonly ?string $execType;
    public readonly ?string $ordStatus;
    public readonly ?float  $cumQty;
    public readonly ?float  $leavesQty;
    public readonly ?float  $lastPx;
    public readonly ?float  $lastQty;
    public readonly ?int    $totNumReports;
    public readonly ?string $listID;
    public readonly ?string $settlDate;
    public readonly ?string $maturityDate;
    public readonly ?string $securitySubType;
    public readonly ?string $gcType;
    public readonly ?int    $terminationType;
    public readonly ?string $agreementDate;
    public readonly ?string $startDate;
    public readonly ?string $endDate;
    public readonly ?int    $deliveryType;
    public readonly ?int    $noUnderlyings;
    public readonly ?array  $underlyings;
    public readonly ?float  $price;
    public readonly ?int    $priceType;
    public readonly ?string $benchmarkCurveCurrency;
    public readonly ?string $benchmarkCurveName;
    public readonly ?string $benchmarkCurvePoint;
    public readonly ?int    $gLMXRateType;
    public readonly ?string $tradeDate;
    public readonly ?float  $interestAtMaturity;
    public readonly ?float  $allinPrice;
    public readonly ?float  $startCash;
    public readonly ?float  $endCash;
    public readonly ?int    $noAllocs;
    public readonly ?array  $allocations;
    public readonly ?float  $marginRatio;
    public readonly ?int    $haircutFormula;
    public readonly ?string $tradeLinkID;
    public readonly ?float  $commission;
    public readonly ?int    $dayCount;
    public readonly ?string $tradingBook;
    public readonly ?int    $tradeContinuation;
    public readonly ?string $tradeContinuationText;
    public readonly ?string $settlCurrency;
    public readonly ?float  $settlCurrAmt;
    public readonly ?float  $settlCurrFxRate;
    public readonly ?string $settlCurrFxRateCalc;
    public readonly ?int    $noEvents;
    public readonly ?array  $events;
    public readonly ?int    $noGLMXStips;
    public readonly ?array  $gLMXStips;
    public readonly ?bool   $gLMXChangedFromOriginal;
    public readonly ?string $gLMXActionReason;
    public readonly ?string $gLMXLinkedTradeID;
    public readonly ?int    $noLegs;
    public readonly ?array  $legs;
    public readonly ?string $gLMXInquiryID;
    public readonly ?int    $noGLMXCompetingQuotes;
    public readonly ?array  $gLMXCompetingQuotes;
    public readonly ?int    $trdType;
    public readonly ?int    $noStipulations;
    public readonly ?array  $stipulations;
    public readonly ?string $gLMXCollateralType;
    public readonly ?string $gLMXCollateralDesc;
    public readonly ?string $gLMXSBLTripartyAgent;
    public readonly ?string $gLMXFeeType;
    public readonly ?string $gLMXIndexType;
    public readonly ?float  $netMoney;
    public readonly ?float  $adjustedEndCash;
    public readonly ?string $allocID;
    public readonly ?string $secondaryOrderID;
    public readonly ?string $gLMXLastActionSource;
    public readonly ?string $gLMXTradeCategory;
    public readonly ?string $gLMXCollectionID;
    public readonly ?float  $gLMXCoupDivReq;

    /**
     * @param string|null                          $beginString
     * @param int|null                             $bodyLength
     * @param string|null                          $msgType
     * @param string|null                          $senderCompID
     * @param string|null                          $targetCompID
     * @param int|null                             $msgSeqNum
     * @param string|null                          $sendingTime
     * @param string|null                          $checkSum
     * @param string|null                          $orderID
     * @param string|null                          $cIOrdID
     * @param string|null                          $origCIOrdID
     * @param string|null                          $gLMXTradeType
     * @param string|null                          $execID
     * @param string|null                          $execRefID
     * @param string|null                          $execType
     * @param string|null                          $ordStatus
     * @param float|null                           $cumQty
     * @param float|null                           $leavesQty
     * @param float|null                           $lastPx
     * @param float|null                           $lastQty
     * @param int|null                             $totNumReports
     * @param array|null                           $partyIDs
     * @param string|null                          $listID
     * @param string|null                          $settlDate
     * @param string|null                          $maturityDate
     * @param string|null                          $symbol
     * @param int|null                             $product
     * @param string|null                          $securityType
     * @param string|null                          $securitySubType
     * @param string|null                          $gcType
     * @param int|null                             $terminationType
     * @param string|null                          $agreementDate
     * @param string|null                          $startDate
     * @param string|null                          $endDate
     * @param int|null                             $deliveryType
     * @param int|null                             $noUnderlyings
     * @param ExecutionReportUnderlying[]|null     $underlyings
     * @param string|null                          $side
     * @param float|null                           $price
     * @param int|null                             $priceType
     * @param string|null                          $benchmarkCurveCurrency
     * @param string|null                          $benchmarkCurveName
     * @param string|null                          $benchmarkCurvePoint
     * @param int|null                             $gLMXRateType
     * @param string|null                          $currency
     * @param string|null                          $tradeDate
     * @param string|null                          $transactTime
     * @param float|null                           $interestAtMaturity
     * @param float|null                           $allinPrice
     * @param float|null                           $startCash
     * @param float|null                           $endCash
     * @param int|null                             $noAllocs
     * @param ExecutionReportAllocation[]|null     $allocations
     * @param float|null                           $marginRatio
     * @param int|null                             $haircutFormula
     * @param string|null                          $tradeLinkID
     * @param float|null                           $commission
     * @param int|null                             $dayCount
     * @param string|null                          $tradingBook
     * @param int|null                             $tradeContinuation
     * @param string|null                          $tradeContinuationText
     * @param string|null                          $settlCurrency
     * @param float|null                           $settlCurrAmt
     * @param float|null                           $settlCurrFxRate
     * @param string|null                          $settlCurrFxRateCalc
     * @param int|null                             $noEvents
     * @param Event[]|null                         $events
     * @param int|null                             $noGLMXStips
     * @param GLMXStip[]|null                      $gLMXStips
     * @param bool|null                            $gLMXChangedFromOriginal
     * @param string|null                          $gLMXActionReason
     * @param string|null                          $gLMXLinkedTradeID
     * @param int|null                             $noLegs
     * @param ExecutionReportLeg[]|null            $legs
     * @param string|null                          $gLMXInquiryID
     * @param int|null                             $noGLMXCompetingQuotes
     * @param ExecutionReportCompetingQuote[]|null $gLMXCompetingQuotes
     * @param int|null                             $trdType
     * @param int|null                             $noStipulations
     * @param Stipulation[]|null                   $stipulations
     * @param string|null                          $gLMXCollateralType
     * @param string|null                          $gLMXCollateralDesc
     * @param string|null                          $gLMXSBLTripartyAgent
     * @param string|null                          $gLMXFeeType
     * @param string|null                          $gLMXIndexType
     * @param float|null                           $netMoney
     * @param float|null                           $adjustedEndCash
     * @param string|null                          $allocID
     * @param string|null                          $secondaryOrderID
     * @param string|null                          $gLMXLastActionSource
     * @param string|null                          $gLMXTradeCategory
     * @param string|null                          $gLMXCollectionID
     * @param float|null                           $gLMXCoupDivReq
     * @param float|null                           $orderQty
     */
    public function __construct(
        ?string $beginString = NULL,
        ?int    $bodyLength = NULL,
        ?string $msgType = NULL,
        ?string $senderCompID = NULL,
        ?string $targetCompID = NULL,
        ?int    $msgSeqNum = NULL,
        ?string $sendingTime = NULL,
        ?string $checkSum = NULL,
        ?string $orderID = NULL,
        ?string $cIOrdID = NULL,
        ?string $origCIOrdID = NULL,
        ?string $gLMXTradeType = NULL,
        ?string $execID = NULL,
        ?string $execRefID = NULL,
        ?string $execType = NULL,
        ?string $ordStatus = NULL,
        ?float  $cumQty = NULL,
        ?float  $leavesQty = NULL,
        ?float  $lastPx = NULL,
        ?float  $lastQty = NULL,
        ?int    $totNumReports = NULL,
        ?array  $partyIDs = NULL,
        ?string $listID = NULL,
        ?string $settlDate = NULL,
        ?string $maturityDate = NULL,
        ?string $symbol = NULL,
        ?int    $product = NULL,
        ?string $securityType = NULL,
        ?string $securitySubType = NULL,
        ?string $gcType = NULL,
        ?int    $terminationType = NULL,
        ?string $agreementDate = NULL,
        ?string $startDate = NULL,
        ?string $endDate = NULL,
        ?int    $deliveryType = NULL,
        ?int    $noUnderlyings = NULL,
        ?array  $underlyings = NULL,
        ?string $side = NULL,
        ?float  $price = NULL,
        ?int    $priceType = NULL,
        ?string $benchmarkCurveCurrency = NULL,
        ?string $benchmarkCurveName = NULL,
        ?string $benchmarkCurvePoint = NULL,
        ?int    $gLMXRateType = NULL,
        ?string $currency = NULL,
        ?string $tradeDate = NULL,
        ?string $transactTime = NULL,
        ?float  $interestAtMaturity = NULL,
        ?float  $allinPrice = NULL,
        ?float  $startCash = NULL,
        ?float  $endCash = NULL,
        ?int    $noAllocs = NULL,
        ?array  $allocations = NULL,
        ?float  $marginRatio = NULL,
        ?int    $haircutFormula = NULL,
        ?string $tradeLinkID = NULL,
        ?float  $commission = NULL,
        ?int    $dayCount = NULL,
        ?string $tradingBook = NULL,
        ?int    $tradeContinuation = NULL,
        ?string $tradeContinuationText = NULL,
        ?string $settlCurrency = NULL,
        ?float  $settlCurrAmt = NULL,
        ?float  $settlCurrFxRate = NULL,
        ?string $settlCurrFxRateCalc = NULL,
        ?int    $noEvents = NULL,
        ?array  $events = NULL,
        ?int    $noGLMXStips = NULL,
        ?array  $gLMXStips = NULL,
        ?bool   $gLMXChangedFromOriginal = NULL,
        ?string $gLMXActionReason = NULL,
        ?string $gLMXLinkedTradeID = NULL,
        ?int    $noLegs = NULL,
        ?array  $legs = NULL,
        ?string $gLMXInquiryID = NULL,
        ?int    $noGLMXCompetingQuotes = NULL,
        ?array  $gLMXCompetingQuotes = NULL,
        ?int    $trdType = NULL,
        ?int    $noStipulations = NULL,
        ?array  $stipulations = NULL,
        ?string $gLMXCollateralType = NULL,
        ?string $gLMXCollateralDesc = NULL,
        ?string $gLMXSBLTripartyAgent = NULL,
        ?string $gLMXFeeType = NULL,
        ?string $gLMXIndexType = NULL,
        ?float  $netMoney = NULL,
        ?float  $adjustedEndCash = NULL,
        ?string $allocID = NULL,
        ?string $secondaryOrderID = NULL,
        ?string $gLMXLastActionSource = NULL,
        ?string $gLMXTradeCategory = NULL,
        ?string $gLMXCollectionID = NULL,
        ?float  $gLMXCoupDivReq = NULL,
        ?float  $orderQty = NULL
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

        $this->orderID                 = $orderID;
        $this->cIOrdID                 = $cIOrdID;
        $this->origCIOrdID             = $origCIOrdID;
        $this->gLMXTradeType           = $gLMXTradeType;
        $this->execID                  = $execID;
        $this->execRefID               = $execRefID;
        $this->execType                = $execType;
        $this->ordStatus               = $ordStatus;
        $this->cumQty                  = $cumQty;
        $this->leavesQty               = $leavesQty;
        $this->lastPx                  = $lastPx;
        $this->lastQty                 = $lastQty;
        $this->totNumReports           = $totNumReports;
        $this->listID                  = $listID;
        $this->settlDate               = $settlDate;
        $this->maturityDate            = $maturityDate;
        $this->securitySubType         = $securitySubType;
        $this->gcType                  = $gcType;
        $this->terminationType         = $terminationType;
        $this->agreementDate           = $agreementDate;
        $this->startDate               = $startDate;
        $this->endDate                 = $endDate;
        $this->deliveryType            = $deliveryType;
        $this->noUnderlyings           = $noUnderlyings;
        $this->underlyings             = $underlyings;
        $this->price                   = $price;
        $this->priceType               = $priceType;
        $this->benchmarkCurveCurrency  = $benchmarkCurveCurrency;
        $this->benchmarkCurveName      = $benchmarkCurveName;
        $this->benchmarkCurvePoint     = $benchmarkCurvePoint;
        $this->gLMXRateType            = $gLMXRateType;
        $this->tradeDate               = $tradeDate;
        $this->interestAtMaturity      = $interestAtMaturity;
        $this->allinPrice              = $allinPrice;
        $this->startCash               = $startCash;
        $this->endCash                 = $endCash;
        $this->noAllocs                = $noAllocs;
        $this->allocations             = $allocations;
        $this->marginRatio             = $marginRatio;
        $this->haircutFormula          = $haircutFormula;
        $this->tradeLinkID             = $tradeLinkID;
        $this->commission              = $commission;
        $this->dayCount                = $dayCount;
        $this->tradingBook             = $tradingBook;
        $this->tradeContinuation       = $tradeContinuation;
        $this->tradeContinuationText   = $tradeContinuationText;
        $this->settlCurrency           = $settlCurrency;
        $this->settlCurrAmt            = $settlCurrAmt;
        $this->settlCurrFxRate         = $settlCurrFxRate;
        $this->settlCurrFxRateCalc     = $settlCurrFxRateCalc;
        $this->noEvents                = $noEvents;
        $this->events                  = $events;
        $this->noGLMXStips             = $noGLMXStips;
        $this->gLMXStips               = $gLMXStips;
        $this->gLMXChangedFromOriginal = $gLMXChangedFromOriginal;
        $this->gLMXActionReason        = $gLMXActionReason;
        $this->gLMXLinkedTradeID       = $gLMXLinkedTradeID;
        $this->noLegs                  = $noLegs;
        $this->legs                    = $legs;
        $this->gLMXInquiryID           = $gLMXInquiryID;
        $this->noGLMXCompetingQuotes   = $noGLMXCompetingQuotes;
        $this->gLMXCompetingQuotes     = $gLMXCompetingQuotes;
        $this->trdType                 = $trdType;
        $this->noStipulations          = $noStipulations;
        $this->stipulations            = $stipulations;
        $this->gLMXCollateralType      = $gLMXCollateralType;
        $this->gLMXCollateralDesc      = $gLMXCollateralDesc;
        $this->gLMXSBLTripartyAgent    = $gLMXSBLTripartyAgent;
        $this->gLMXFeeType             = $gLMXFeeType;
        $this->gLMXIndexType           = $gLMXIndexType;
        $this->netMoney                = $netMoney;
        $this->adjustedEndCash         = $adjustedEndCash;
        $this->allocID                 = $allocID;
        $this->secondaryOrderID        = $secondaryOrderID;
        $this->gLMXLastActionSource    = $gLMXLastActionSource;
        $this->gLMXTradeCategory       = $gLMXTradeCategory;
        $this->gLMXCollectionID        = $gLMXCollectionID;
        $this->gLMXCoupDivReq          = $gLMXCoupDivReq;
    }
}