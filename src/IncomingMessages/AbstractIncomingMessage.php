<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;

use DPRMC\GLMXFixClient\IncomingMessages\Party;

/**
 * Abstract base class for all incoming FIX messages from GLMX.
 * Defines common header fields and other fields that appear across multiple message types.
 */
abstract class AbstractIncomingMessage {
    // Common FIX Header Fields (from GLMX Inquiry Creation via FIX Integration Guide 1.25.pdf, page 4-5)
    public string $beginString;     // Tag 8
    public int    $bodyLength;      // Tag 9
    public string $msgType;         // Tag 35
    public string $senderCompID;    // Tag 49 (GLMX for incoming messages)
    public string $targetCompID;    // Tag 56 (Client's CompID for incoming messages)
    public int    $msgSeqNum;       // Tag 34
    public string $sendingTime;     // Tag 52
    public string $checkSum;        // Tag 10

    // Common Application-Level Fields (identified across multiple message types)
    public ?string $transactTime;   // Tag 60
    public ?string $side;           // Tag 54
    public ?string $symbol;         // Tag 55
    public ?int    $product;        // Tag 460
    public ?string $securityType;   // Tag 167
    public ?float  $orderQty;       // Tag 38
    public ?string $currency;       // Tag 15
    public array   $partyIDs;       // Tag 453 (Group)

    /**
     * @param string $beginString FIX.4.4
     * @param int $bodyLength Message length
     * @param string $msgType Message Type (e.g., '8' for Execution Report)
     * @param string $senderCompID GLMX
     * @param string $targetCompID Client's CompID
     * @param int $msgSeqNum Message Sequence Number
     * @param string $sendingTime Time of message transmission (UTC UTCTimestamp)
     * @param string $checkSum Checksum (sum modulo 256)
     * @param string|null $transactTime Transaction time (optional, but common)
     * @param string|null $side Side of the order/trade (optional, but common)
     * @param string|null $symbol Symbol (optional, but common)
     * @param int|null $product Product type (optional, but common)
     * @param string|null $securityType Security type (optional, but common)
     * @param float|null $orderQty Order quantity (optional, but common)
     * @param string|null $currency Currency (optional, but common)
     * @param Party[] $partyIDs Array of Party objects (optional, but common group)
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
        ?string $transactTime = NULL,
        ?string $side = NULL,
        ?string $symbol = NULL,
        ?int    $product = NULL,
        ?string $securityType = NULL,
        ?float  $orderQty = NULL,
        ?string $currency = NULL,
        array   $partyIDs = []
    ) {
        $this->beginString  = $beginString;
        $this->bodyLength   = $bodyLength;
        $this->msgType      = $msgType;
        $this->senderCompID = $senderCompID;
        $this->targetCompID = $targetCompID;
        $this->msgSeqNum    = $msgSeqNum;
        $this->sendingTime  = $sendingTime;
        $this->checkSum     = $checkSum;
        $this->transactTime = $transactTime;
        $this->side         = $side;
        $this->symbol       = $symbol;
        $this->product      = $product;
        $this->securityType = $securityType;
        $this->orderQty     = $orderQty;
        $this->currency     = $currency;
        $this->partyIDs     = $partyIDs;
    }
}
