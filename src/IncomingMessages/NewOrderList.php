<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the NewOrderList (MsgType=E) message.
 * Used for creating package and substitution inquiries.
 */
class NewOrderList extends AbstractIncomingMessage {
    public readonly string $listID;             // Tag 66
    public readonly int    $totNoOrders;        // Tag 68
    public readonly int    $noOrders;           // Tag 73
    public readonly array  $orders;             // Group: Orders (complex group)
    public readonly ?bool  $gLMXContingentList; // Tag 5021
    public readonly ?bool  $gLMXDirectSend;     // Tag 5054

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $listID Unique identifier for the order list
     * @param int $totNoOrders Total number of orders in the list
     * @param int $noOrders Total number of orders in the list (repeats TotNoOrders)
     * @param NewOrderListOrder[] $orders Array of NewOrderListOrder objects
     * @param bool|null $gLMXContingentList Is this a contingent list (package)
     * @param bool|null $gLMXDirectSend Directly send to counterparty without review
     */
    public function __construct(
        string $beginString,
        int    $bodyLength,
        string $msgType,
        string $senderCompID,
        string $targetCompID,
        int    $msgSeqNum,
        string $sendingTime,
        string $checkSum,
        string $listID,
        int    $totNoOrders,
        int    $noOrders,
        array  $orders,
        ?bool  $gLMXContingentList = NULL,
        ?bool  $gLMXDirectSend = NULL
    ) {
        parent::__construct(
            $beginString,
            $bodyLength,
            $msgType,
            $senderCompID,
            $targetCompID,
            $msgSeqNum,
            $sendingTime,
            $checkSum
        );
        $this->listID             = $listID;
        $this->totNoOrders        = $totNoOrders;
        $this->noOrders           = $noOrders;
        $this->orders             = $orders;
        $this->gLMXContingentList = $gLMXContingentList;
        $this->gLMXDirectSend     = $gLMXDirectSend;
    }
}