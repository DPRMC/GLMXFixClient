<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the Logout (MsgType=5) message.
 * Sent by GLMX to confirm logout.
 */
class Logout extends AbstractIncomingMessage {
    public readonly ?string $text; // Tag 58

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string|null $text Optional text describing the reason for logout
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
        $this->text = $text;
    }
}