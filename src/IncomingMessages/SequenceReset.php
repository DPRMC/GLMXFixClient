<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the SequenceReset (MsgType=4) message.
 * Sent by GLMX to reset sequence numbers.
 */
class SequenceReset extends AbstractIncomingMessage {
    public readonly int   $newSeqNo;     // Tag 36
    public readonly ?bool $gapFillFlag;  // Tag 123
    public readonly ?bool $possDupFlag;  // Tag 43

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param int $newSeqNo New sequence number to be transmitted
     * @param bool|null $gapFillFlag If 'Y', indicates a gap fill
     * @param bool|null $possDupFlag If 'Y', indicates a possible duplicate message
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
        int    $newSeqNo,
        ?bool  $gapFillFlag = NULL,
        ?bool  $possDupFlag = NULL
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
        $this->newSeqNo    = $newSeqNo;
        $this->gapFillFlag = $gapFillFlag;
        $this->possDupFlag = $possDupFlag;
    }
}