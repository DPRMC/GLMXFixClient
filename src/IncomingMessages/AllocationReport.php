<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the AllocationReport (MsgType=AS) message.
 * Sent by GLMX when a Fill Inquiry created from an Allocation Instruction is agreed.
 */
class AllocationReport extends AbstractIncomingMessage {


    public readonly string  $allocReportID;          // Tag 755
    public readonly string  $allocID;                // Tag 70
    public readonly string  $allocTransType;         // Tag 71
    public readonly ?string $allocReportRefID;       // Tag 795
    public readonly ?string $allocCancReplaceReason; // Tag 796
    public readonly ?string $secondaryAllocID;       // Tag 793
    public readonly int     $allocReportType;        // Tag 794
    public readonly int     $allocStatus;            // Tag 87
    public readonly ?int    $allocRejCode;           // Tag 88
    public readonly ?string $relAllocID;             // Tag 72
    public readonly int     $allocNoOrdersType;      // Tag 857
    public readonly ?string $securityIDSource;       // Tag 22
    public readonly ?string $securityID;             // Tag 48
    public readonly ?int    $terminationType;        // Tag 788
    public readonly string  $startDate;              // Tag 916
    public readonly string  $endDate;                // Tag 917
    public readonly int     $deliveryType;           // Tag 919
    public readonly ?float  $avgPx;                  // Tag 6
    public readonly ?int    $priceType;              // Tag 423
    public readonly string  $lastMkt;                // Tag 30
    public readonly float   $quantity;               // Tag 53
    public readonly string  $tradeDate;              // Tag 75
    public readonly int     $allocType;              // Tag 626
    public readonly ?string $settlCurrency;          // Tag 120
    public readonly ?float  $netMoney;               // Tag 118
    public readonly int     $noAllocs;               // Tag 78
    public readonly array   $allocsGrp;              // Group: AllocsGrp (79, 80, 467, 154, 1908)
    public readonly int     $noRegulatoryTradeIDs;   // Tag 1907
    public readonly array   $regulatoryTradeIDs;     // Group: RegulatoryTradeIDGrp (1903, 1905, 1904, 1906)

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $allocReportID
     * @param string $allocID
     * @param string $transactTime
     * @param string $allocTransType
     * @param string|null $allocReportRefID
     * @param string|null $allocCancReplaceReason
     * @param string|null $secondaryAllocID
     * @param int $allocReportType
     * @param int $allocStatus
     * @param int|null $allocRejCode
     * @param string|null $relAllocID
     * @param int $allocNoOrdersType
     * @param string $side
     * @param string|null $symbol
     * @param string|null $securityIDSource
     * @param string|null $securityID
     * @param string $securityType
     * @param int|null $terminationType
     * @param string $startDate
     * @param string $endDate
     * @param int $deliveryType
     * @param float|null $avgPx
     * @param int|null $priceType
     * @param string $currency
     * @param string $lastMkt
     * @param float $quantity
     * @param string $tradeDate
     * @param int $allocType
     * @param string|null $settlCurrency
     * @param float|null $netMoney
     * @param int $noAllocs
     * @param AllocationReportAllocation[] $allocsGrp
     * @param array $partyIDs
     * @param int $noRegulatoryTradeIDs
     * @param AllocationReportRegulatoryTradeID[] $regulatoryTradeIDs
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
        string  $allocReportID,
        string  $allocID,
        string  $transactTime,
        string  $allocTransType,
        ?string $allocReportRefID,
        ?string $allocCancReplaceReason,
        ?string $secondaryAllocID,
        int     $allocReportType,
        int     $allocStatus,
        ?int    $allocRejCode,
        ?string $relAllocID,
        int     $allocNoOrdersType,
        string  $side,
        ?string $symbol,
        ?string $securityIDSource,
        ?string $securityID,
        string  $securityType,
        ?int    $terminationType,
        string  $startDate,
        string  $endDate,
        int     $deliveryType,
        ?float  $avgPx,
        ?int    $priceType,
        string  $currency,
        string  $lastMkt,
        float   $quantity,
        string  $tradeDate,
        int     $allocType,
        ?string $settlCurrency,
        ?float  $netMoney,
        int     $noAllocs,
        array   $allocsGrp,
        array   $partyIDs,
        int     $noRegulatoryTradeIDs,
        array   $regulatoryTradeIDs
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
            NULL, // Product not listed for AllocationReport
            $securityType,
            NULL, // OrderQty not listed for AllocationReport
            $currency,
            $partyIDs
        );
        $this->allocReportID          = $allocReportID;
        $this->allocID                = $allocID;
        $this->allocTransType         = $allocTransType;
        $this->allocReportRefID       = $allocReportRefID;
        $this->allocCancReplaceReason = $allocCancReplaceReason;
        $this->secondaryAllocID       = $secondaryAllocID;
        $this->allocReportType        = $allocReportType;
        $this->allocStatus            = $allocStatus;
        $this->allocRejCode           = $allocRejCode;
        $this->relAllocID             = $relAllocID;
        $this->allocNoOrdersType      = $allocNoOrdersType;
        $this->securityIDSource       = $securityIDSource;
        $this->securityID             = $securityID;
        $this->terminationType        = $terminationType;
        $this->startDate              = $startDate;
        $this->endDate                = $endDate;
        $this->deliveryType           = $deliveryType;
        $this->avgPx                  = $avgPx;
        $this->priceType              = $priceType;
        $this->lastMkt                = $lastMkt;
        $this->quantity               = $quantity;
        $this->tradeDate              = $tradeDate;
        $this->allocType              = $allocType;
        $this->settlCurrency          = $settlCurrency;
        $this->netMoney               = $netMoney;
        $this->noAllocs               = $noAllocs;
        $this->allocsGrp              = $allocsGrp;
        $this->noRegulatoryTradeIDs   = $noRegulatoryTradeIDs;
        $this->regulatoryTradeIDs     = $regulatoryTradeIDs;
    }
}