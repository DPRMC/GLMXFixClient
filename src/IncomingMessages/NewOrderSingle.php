<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the NewOrderSingle (MsgType=D) message.
 * Used to stage single inquiry details to GLMX.
 */
class NewOrderSingle extends AbstractIncomingMessage {
    public readonly string  $cIOrdID;                // Tag 11
    public readonly array   $execInst;               // Tag 18 (MultiValueString, array of strings)
    public  float|null   $orderQty;               // Tag 38 (Overridden from AbstractIncomingMessage to be non-nullable)
    public readonly int     $noUnderlyings;          // Tag 711
    public readonly array   $underlyings;            // Group: UnderlyingInstrument (309, 305, 810, 1039)
    public readonly string  $ordType;                // Tag 40
    public readonly ?float  $price;                  // Tag 44
    public readonly ?int    $priceType;              // Tag 423
    public readonly ?string $benchmarkCurveCurrency; // Tag 220
    public readonly ?string $benchmarkCurveName;     // Tag 221
    public readonly ?string $benchmarkCurvePoint;    // Tag 222
    public readonly ?string $settleCurrency;          // Tag 120
    public readonly ?int    $terminationType;        // Tag 788 (Overridden from AbstractIncomingMessage to be non-nullable)
    public readonly string  $startDate;              // Tag 916
    public readonly ?string $settlDate;              // Tag 64
    public readonly ?string $endDate;                // Tag 917
    public readonly ?string $maturityDate;           // Tag 541
    public readonly ?int    $deliveryType;           // Tag 919
    public readonly int     $noAllocs;               // Tag 78
    public readonly array   $allocations;            // Group: Alloc (79, 661, 80, 5009, 5010, 5059, 5060, 5061, 1908, 5051, 5072)
    public readonly int     $noPartyIDs;             // Tag 453 (Overridden from AbstractIncomingMessage to be non-nullable)
    public  array   $partyIDs;               // Group: Party (448, 447, 452) (Overridden from AbstractIncomingMessage to be non-nullable)
    public readonly int     $noGLMXStips;            // Tag 5016
    public readonly array   $gLMXStips;              // Group: GLMXStip (5017, 5018)
    public readonly int     $noEvents;               // Tag 864
    public readonly array   $events;                 // Group: Event (865, 866, 1826, 1827)
    public readonly ?float  $marginRatio;            // Tag 898
    public readonly ?int    $haircutFormula;         // Tag 5002
    public readonly ?string $gcType;                 // Tag 5006
    public readonly ?bool   $gLMXMatrix;             // Tag 5025
    public readonly ?bool   $gLMXPin;                // Tag 5026
    public readonly ?int    $tradeContinuation;      // Tag 1937
    public readonly ?string $tradeContinuationText;  // Tag 2374
    public readonly ?string $gLMXDealType;           // Tag 5052
    public readonly ?string $gLMXCollateralType;     // Tag 5039
    public readonly ?string $gLMXCollateralDesc;     // Tag 5040
    public readonly ?string $gLMXSBLTripartyAgent;   // Tag 5055
    public readonly ?string $gLMXFeeType;            // Tag 5041
    public readonly ?bool   $gLMXDirectSend;         // Tag 5054
    public readonly ?bool   $gLMXIsBroadBasket;      // Tag 5065
    public readonly int     $noStipulations;         // Tag 232
    public readonly array   $stipulations;           // Group: Stipulations (233, 234)
    public readonly int     $noRegulatoryTradeIDs;   // Tag 1907
    public readonly array   $regulatoryTradeIDs;     // Group: RegulatoryTradeIDGrp (1903, 1905, 1907)
    public readonly ?float  $gLMXCoupDivReq;         // Tag 5071
    
    
    public readonly ?string $securitySubType;

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $cIOrdID
     * @param array $execInst
     * @param string $transactTime
     * @param string $side
     * @param float $orderQty
     * @param int $noUnderlyings
     * @param NewOrderSingleUnderlying[] $underlyings
     * @param string $ordType
     * @param string|null $symbol
     * @param int|null $product
     * @param string|null $securityType
     * @param string|null $securitySubType
     * @param string $currency
     * @param int|null $terminationType
     * @param string $startDate
     * @param string|null $settlDate
     * @param string|null $endDate
     * @param string|null $maturityDate
     * @param int|null $deliveryType
     * @param string|null $settleCurrency
     * @param float|null $price
     * @param int|null $priceType
     * @param string|null $benchmarkCurveCurrency
     * @param string|null $benchmarkCurveName
     * @param string|null $benchmarkCurvePoint
     * @param int $noAllocs
     * @param NewOrderSingleAllocation[] $allocations
     * @param int $noPartyIDs
     * @param Party[] $partyIDs
     * @param int $noGLMXStips
     * @param GLMXStip[] $gLMXStips
     * @param int $noEvents
     * @param Event[] $events
     * @param float|null $marginRatio
     * @param int|null $haircutFormula
     * @param string|null $gcType
     * @param bool|null $gLMXMatrix
     * @param bool|null $gLMXPin
     * @param int|null $tradeContinuation
     * @param string|null $tradeContinuationText
     * @param string|null $gLMXDealType
     * @param string|null $gLMXCollateralType
     * @param string|null $gLMXCollateralDesc
     * @param string|null $gLMXSBLTripartyAgent
     * @param string|null $gLMXFeeType
     * @param bool|null $gLMXDirectSend
     * @param bool|null $gLMXIsBroadBasket
     * @param int $noStipulations
     * @param Stipulation[] $stipulations
     * @param int $noRegulatoryTradeIDs
     * @param NewOrderSingleRegulatoryTradeID[] $regulatoryTradeIDs
     * @param float|null $gLMXCoupDivReq
     */
    public function __construct(
        string  $beginString,
        int     $bodyLength,
        string  $msgType,
        string  $senderCompID,
        string  $targetCompID,
        int     $msgSeqNum,
        string  $sendingTime,
        string  $checkSum,
        string  $cIOrdID,
        array   $execInst,
        string  $transactTime,
        string  $side,
        float   $orderQty,
        int     $noUnderlyings,
        array   $underlyings,
        string  $ordType,
        ?string $symbol,
        ?int    $product,
        ?string $securityType,
        ?string $securitySubType,
        string  $currency,
        ?int    $terminationType,
        string  $startDate,
        ?string $settlDate,
        ?string $endDate,
        ?string $maturityDate,
        ?int    $deliveryType,
        int     $noAllocs,
        array   $allocations,
        int     $noPartyIDs,
        array   $partyIDs,
        int     $noGLMXStips,
        array   $gLMXStips,
        int     $noEvents,
        array   $events,
        ?float  $marginRatio,
        ?int    $haircutFormula,
        ?string $gcType,
        ?bool   $gLMXMatrix,
        ?bool   $gLMXPin,
        ?int    $tradeContinuation,
        ?string $tradeContinuationText,
        ?string $gLMXDealType,
        ?string $gLMXCollateralType,
        ?string $gLMXCollateralDesc,
        ?string $gLMXSBLTripartyAgent,
        ?string $gLMXFeeType,
        ?bool   $gLMXDirectSend,
        ?bool   $gLMXIsBroadBasket,
        int     $noStipulations,
        array   $stipulations,
        int     $noRegulatoryTradeIDs,
        array   $regulatoryTradeIDs,
        ?float  $gLMXCoupDivReq,

        // Manually added
        ?string $settleCurrency,
        ?float $price,
        ?int    $priceType,
        ?string $benchmarkCurveCurrency,
        ?string $benchmarkCurveName,
        ?string $benchmarkCurvePoint,
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

        $this->cIOrdID                = $cIOrdID;
        $this->execInst               = $execInst;
        $this->noUnderlyings          = $noUnderlyings;
        $this->underlyings            = $underlyings;
        $this->ordType                = $ordType;
        $this->securitySubType        = $securitySubType;
        $this->terminationType        = $terminationType;
        $this->startDate              = $startDate;
        $this->settlDate              = $settlDate;
        $this->endDate                = $endDate;
        $this->maturityDate           = $maturityDate;
        $this->deliveryType           = $deliveryType;
        $this->settleCurrency          = $settleCurrency;
        $this->price                  = $price;
        $this->priceType              = $priceType;
        $this->benchmarkCurveCurrency = $benchmarkCurveCurrency;
        $this->benchmarkCurveName     = $benchmarkCurveName;
        $this->benchmarkCurvePoint    = $benchmarkCurvePoint;
        $this->noAllocs               = $noAllocs;
        $this->allocations            = $allocations;
        $this->noPartyIDs             = $noPartyIDs;
        $this->noGLMXStips            = $noGLMXStips;
        $this->gLMXStips              = $gLMXStips;
        $this->noEvents               = $noEvents;
        $this->events                 = $events;
        $this->marginRatio            = $marginRatio;
        $this->haircutFormula         = $haircutFormula;
        $this->gcType                 = $gcType;
        $this->gLMXMatrix             = $gLMXMatrix;
        $this->gLMXPin                = $gLMXPin;
        $this->tradeContinuation      = $tradeContinuation;
        $this->tradeContinuationText  = $tradeContinuationText;
        $this->gLMXDealType           = $gLMXDealType;
        $this->gLMXCollateralType     = $gLMXCollateralType;
        $this->gLMXCollateralDesc     = $gLMXCollateralDesc;
        $this->gLMXSBLTripartyAgent   = $gLMXSBLTripartyAgent;
        $this->gLMXFeeType            = $gLMXFeeType;
        $this->gLMXDirectSend         = $gLMXDirectSend;
        $this->gLMXIsBroadBasket      = $gLMXIsBroadBasket;
        $this->noStipulations         = $noStipulations;
        $this->stipulations           = $stipulations;
        $this->noRegulatoryTradeIDs   = $noRegulatoryTradeIDs;
        $this->regulatoryTradeIDs     = $regulatoryTradeIDs;
        $this->gLMXCoupDivReq         = $gLMXCoupDivReq;
    }
}