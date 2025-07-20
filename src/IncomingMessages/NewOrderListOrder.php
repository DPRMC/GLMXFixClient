<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents an Orders group entry specific to NewOrderList.
 * This encapsulates the details of each order within the list.
 */
class NewOrderListOrder {
    public readonly string  $cIOrdID;                // Tag 11
    public readonly ?int    $listSeqNo;              // Tag 67
    public readonly array   $execInst;               // Tag 18 (MultiValueString, array of strings)
    public readonly int     $noPartyIDs;             // Tag 453
    public readonly array   $partyIDs;               // Group: Party (448, 447, 452)
    public readonly int     $noAllocs;               // Tag 78
    public readonly array   $allocations;            // Group: Alloc (79, 661, 80, 5009, 5010, 5059, 5060, 5061, 5051, 5072)
    public readonly ?string $symbol;                 // Tag 55
    public readonly ?int    $product;                // Tag 460
    public readonly ?string $securityType;           // Tag 167
    public readonly ?string $securitySubType;        // Tag 762
    public readonly ?int    $terminationType;        // Tag 788
    public readonly ?string $startDate;              // Tag 916
    public readonly ?string $settlDate;              // Tag 64
    public readonly ?string $endDate;                // Tag 917
    public readonly ?string $maturityDate;           // Tag 541
    public readonly ?int    $deliveryType;           // Tag 919
    public readonly ?float  $marginRatio;            // Tag 898
    public readonly int     $noUnderlyings;          // Tag 711
    public readonly array   $underlyings;            // Group: UnderlyingInstrument (309, 305, 810, 1039)
    public readonly string  $side;                   // Tag 54
    public readonly string  $transactTime;           // Tag 60
    public readonly float   $orderQty;               // Tag 38
    public readonly string  $ordType;                // Tag 40
    public readonly ?float  $price;                  // Tag 44
    public readonly ?int    $priceType;              // Tag 423
    public readonly ?string $benchmarkCurveCurrency; // Tag 220
    public readonly ?string $benchmarkCurveName;     // Tag 221
    public readonly ?string $benchmarkCurvePoint;    // Tag 222
    public readonly string  $currency;               // Tag 15
    public readonly ?string $settlCurrency;          // Tag 120
    public readonly int     $noEvents;               // Tag 864
    public readonly array   $events;                 // Group: Event (865, 866, 1826, 1827)
    public readonly ?int    $haircutFormula;         // Tag 5002
    public readonly ?string $gcType;                 // Tag 5006
    public readonly int     $noGLMXStips;            // Tag 5016
    public readonly array   $gLMXStips;              // Group: GLMXStip (5017, 5018)
    public readonly ?bool   $gLMXMatrix;             // Tag 5025
    public readonly ?bool   $gLMXPin;                // Tag 5056
    public readonly ?string $gLMXDealType;           // Tag 5052
    public readonly ?string $gLMXCollateralType;     // Tag 5039
    public readonly ?string $gLMXCollateralDesc;     // Tag 5040
    public readonly ?string $gLMXSBLTripartyAgent;   // Tag 5055
    public readonly ?string $gLMXFeeType;            // Tag 5041
    public readonly ?bool   $gLMXIsBroadBasket;      // Tag 5065
    public readonly int     $noStipulations;         // Tag 232
    public readonly array   $stipulations;           // Group: Stipulations (233, 234)
    public readonly ?float  $gLMXCoupDivReq;         // Tag 5071

    /**
     * @param string $cIOrdID
     * @param int|null $listSeqNo
     * @param array $execInst
     * @param int $noPartyIDs
     * @param Party[] $partyIDs
     * @param int $noAllocs
     * @param NewOrderListAllocation[] $allocations
     * @param string|null $symbol
     * @param int|null $product
     * @param string|null $securityType
     * @param string|null $securitySubType
     * @param int|null $terminationType
     * @param string|null $startDate
     * @param string|null $settlDate
     * @param string|null $endDate
     * @param string|null $maturityDate
     * @param int|null $deliveryType
     * @param float|null $marginRatio
     * @param int $noUnderlyings
     * @param NewOrderListUnderlying[] $underlyings
     * @param string $side
     * @param string $transactTime
     * @param float $orderQty
     * @param string $ordType
     * @param float|null $price
     * @param int|null $priceType
     * @param string|null $benchmarkCurveCurrency
     * @param string|null $benchmarkCurveName
     * @param string|null $benchmarkCurvePoint
     * @param string $currency
     * @param string|null $settlCurrency
     * @param int $noEvents
     * @param Event[] $events
     * @param int|null $haircutFormula
     * @param string|null $gcType
     * @param int $noGLMXStips
     * @param GLMXStip[] $gLMXStips
     * @param bool|null $gLMXMatrix
     * @param bool|null $gLMXPin
     * @param string|null $gLMXDealType
     * @param string|null $gLMXCollateralType
     * @param string|null $gLMXCollateralDesc
     * @param string|null $gLMXSBLTripartyAgent
     * @param string|null $gLMXFeeType
     * @param bool|null $gLMXIsBroadBasket
     * @param int $noStipulations
     * @param Stipulation[] $stipulations
     * @param float|null $gLMXCoupDivReq
     */
    public function __construct(
        string  $cIOrdID,
        ?int    $listSeqNo,
        array   $execInst,
        int     $noPartyIDs,
        array   $partyIDs,
        int     $noAllocs,
        array   $allocations,
        ?string $symbol,
        ?int    $product,
        ?string $securityType,
        ?string $securitySubType,
        ?int    $terminationType,
        ?string $startDate,
        ?string $settlDate,
        ?string $endDate,
        ?string $maturityDate,
        ?int    $deliveryType,
        ?float  $marginRatio,
        int     $noUnderlyings,
        array   $underlyings,
        string  $side,
        string  $transactTime,
        float   $orderQty,
        string  $ordType,
        ?float  $price,
        ?int    $priceType,
        ?string $benchmarkCurveCurrency,
        ?string $benchmarkCurveName,
        ?string $benchmarkCurvePoint,
        string  $currency,
        ?string $settlCurrency,
        int     $noEvents,
        array   $events,
        ?int    $haircutFormula,
        ?string $gcType,
        int     $noGLMXStips,
        array   $gLMXStips,
        ?bool   $gLMXMatrix,
        ?bool   $gLMXPin,
        ?string $gLMXDealType,
        ?string $gLMXCollateralType,
        ?string $gLMXCollateralDesc,
        ?string $gLMXSBLTripartyAgent,
        ?string $gLMXFeeType,
        ?bool   $gLMXIsBroadBasket,
        int     $noStipulations,
        array   $stipulations,
        ?float  $gLMXCoupDivReq
    ) {
        $this->cIOrdID                = $cIOrdID;
        $this->listSeqNo              = $listSeqNo;
        $this->execInst               = $execInst;
        $this->noPartyIDs             = $noPartyIDs;
        $this->partyIDs               = $partyIDs;
        $this->noAllocs               = $noAllocs;
        $this->allocations            = $allocations;
        $this->symbol                 = $symbol;
        $this->product                = $product;
        $this->securityType           = $securityType;
        $this->securitySubType        = $securitySubType;
        $this->terminationType        = $terminationType;
        $this->startDate              = $startDate;
        $this->settlDate              = $settlDate;
        $this->endDate                = $endDate;
        $this->maturityDate           = $maturityDate;
        $this->deliveryType           = $deliveryType;
        $this->marginRatio            = $marginRatio;
        $this->noUnderlyings          = $noUnderlyings;
        $this->underlyings            = $underlyings;
        $this->side                   = $side;
        $this->transactTime           = $transactTime;
        $this->orderQty               = $orderQty;
        $this->ordType                = $ordType;
        $this->price                  = $price;
        $this->priceType              = $priceType;
        $this->benchmarkCurveCurrency = $benchmarkCurveCurrency;
        $this->benchmarkCurveName     = $benchmarkCurveName;
        $this->benchmarkCurvePoint    = $benchmarkCurvePoint;
        $this->currency               = $currency;
        $this->settlCurrency          = $settlCurrency;
        $this->noEvents               = $noEvents;
        $this->events                 = $events;
        $this->haircutFormula         = $haircutFormula;
        $this->gcType                 = $gcType;
        $this->noGLMXStips            = $noGLMXStips;
        $this->gLMXStips              = $gLMXStips;
        $this->gLMXMatrix             = $gLMXMatrix;
        $this->gLMXPin                = $gLMXPin;
        $this->gLMXDealType           = $gLMXDealType;
        $this->gLMXCollateralType     = $gLMXCollateralType;
        $this->gLMXCollateralDesc     = $gLMXCollateralDesc;
        $this->gLMXSBLTripartyAgent   = $gLMXSBLTripartyAgent;
        $this->gLMXFeeType            = $gLMXFeeType;
        $this->gLMXIsBroadBasket      = $gLMXIsBroadBasket;
        $this->noStipulations         = $noStipulations;
        $this->stipulations           = $stipulations;
        $this->gLMXCoupDivReq         = $gLMXCoupDivReq;
    }
}