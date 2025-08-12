<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the OrderCancelRequest (MsgType=F) message.
 * Used to cancel a staged or active inquiry.
 */
class OrderCancelRequest extends AbstractIncomingMessage {
    public readonly string $cIOrdID;     // Tag 11
    public readonly string $origCIOrdID; // Tag 41

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $cIOrdID Unique ID of cancel request
     * @param string $origCIOrdID CIOrdID of the non-rejected order to be canceled
     * @param string|null $symbol From AbstractIncomingMessage
     * @param int|null $product From AbstractIncomingMessage
     * @param string|null $securityType From AbstractIncomingMessage
     * @param string|null $side From AbstractIncomingMessage
     * @param string|null $transactTime From AbstractIncomingMessage
     * @param float|null $orderQty From AbstractIncomingMessage
     * @param Party[] $partyIDs From AbstractIncomingMessage
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
        string  $origCIOrdID,
        ?string $symbol = NULL,
        ?int    $product = NULL,
        ?string $securityType = NULL,
        ?string $side = NULL,
        ?string $transactTime = NULL,
        ?float  $orderQty = NULL,
        array   $partyIDs = []
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
            NULL, // Currency is not listed for OrderCancelRequest
            $partyIDs
        );
        $this->cIOrdID     = $cIOrdID;
        $this->origCIOrdID = $origCIOrdID;
    }
}