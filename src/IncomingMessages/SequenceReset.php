<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the SequenceReset (MsgType=4) message.
 * Sent by GLMX to reset sequence numbers.
 *
 * There are two scenarios under which this message is sent.
 *
 * First, it may be sent as part of the reply sequence of a Resend Request (2) message with
 * the GapFillFlag (123) set to “Y” in order to skip over some administrative messages.
 *
 * As there may be multiple administrative gaps in processing such a resend request, this
 * message may be sent multiple times, once each time that a gap is hit.
 * In this case, the NewSeqNo (36) tag should be set the MsgSeqNum of the next non-administrative message to be retransmitted.
 *
 * Secondly, it may be sent without the GapFillFlag (123) or with it set to “N”, and
 * similarly for the PossDupFlag (43) tag in order to recover from catastrophic failures.
 * In such a case, the NewSeqNo (36) tag should be set to the next MsgSeqNum to be transmitted, and
 * the receiving system will treat any messages in between the previous MsgSeqNum as lost and unrecoverable.
 * Note that in this case, the MsgSeqNum of the message itself is ignored and not validated, since
 * the whole sequence is presumed to have been messed up.
 * In neither situation should an acknowledgement message be expected.
 *
 *  8 => "FIX.4.4"
 *  9 => "64"
 *  35 => "2"
 *  34 => "2"
 *  49 => "GLMX"
 *  52 => "20250721-15:23:37.651"
 *  56 => "DEERPARK"
 *  7 => "1"
 *  16 => "0"
 *  10 => "222"
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