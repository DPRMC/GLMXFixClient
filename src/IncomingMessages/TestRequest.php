<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the TestRequest (MsgType=1) message.
 * Sent by GLMX to ensure the opposing end is processing messages.
 */
class TestRequest extends AbstractIncomingMessage {
    public readonly string $testReqID; // Tag 112

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $testReqID Unique identifier for the test request
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
        string $testReqID
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