<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the Heartbeat (MsgType=0) message.
 * Sent by GLMX to confirm activity or in response to a TestRequest.
 */
class Heartbeat extends AbstractIncomingMessage {
    public readonly ?string $testReqID; // Tag 112

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string|null $testReqID Test Request ID if in response to a TestRequest
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
        ?string $testReqID = NULL
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
        $this->testReqID = $testReqID;
    }
}