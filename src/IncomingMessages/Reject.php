<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the Reject (MsgType=3) message.
 * Sent by GLMX when it cannot process a received message due to session-level rule violation.
 */
class Reject extends AbstractIncomingMessage {
    public readonly ?string $refSeqNum;           // Tag 45
    public readonly ?string $refMsgType;          // Tag 372
    public readonly ?int    $sessionRejectReason; // Tag 373
    public readonly ?string $text;                // Tag 58

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string|null $refSeqNum Reference sequence number of the rejected message
     * @param string|null $refMsgType Message type of the rejected message
     * @param int|null $sessionRejectReason Reason for session-level rejection
     * @param string|null $text Further information
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
        ?string $refSeqNum = NULL,
        ?string $refMsgType = NULL,
        ?int    $sessionRejectReason = NULL,
        ?string $text = NULL
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
        $this->refSeqNum           = $refSeqNum;
        $this->refMsgType          = $refMsgType;
        $this->sessionRejectReason = $sessionRejectReason;
        $this->text                = $text;
    }
}