<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the Logon (MsgType=A) message.
 * Sent by GLMX to confirm login.
 */
class Logon extends AbstractIncomingMessage {
    public readonly ?int    $heartBtInt;      // Tag 108
    public readonly ?string $password;        // Tag 554
    public readonly ?bool   $resetSeqNumFlag; // Tag 141

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param int|null $heartBtInt Heartbeat interval in seconds
     * @param string|null $password Password supplied by GLMX
     * @param bool|null $resetSeqNumFlag Reset sequence number flag ('Y' or 'N')
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
        ?int    $heartBtInt = NULL,
        ?string $password = NULL,
        ?bool   $resetSeqNumFlag = NULL
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
        $this->heartBtInt      = $heartBtInt;
        $this->password        = $password;
        $this->resetSeqNumFlag = $resetSeqNumFlag;
    }
}