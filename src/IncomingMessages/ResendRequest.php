<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;



/**
 * Represents the ResendRequest (MsgType=2) message.
 * Sent by GLMX in response to a client's resend request.
 */
class ResendRequest extends AbstractIncomingMessage
{
    public readonly int $beginSeqNo; // Tag 7
    public readonly int $endSeqNo;   // Tag 16

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param int $beginSeqNo Starting sequence number for resend
     * @param int $endSeqNo    Ending sequence number for resend (0 for until end)
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
        int $beginSeqNo,
        int $endSeqNo
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
        $this->beginSeqNo = $beginSeqNo;
        $this->endSeqNo   = $endSeqNo;
    }
}