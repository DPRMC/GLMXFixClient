<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class MsgSeqNumTooLowException extends Exception {


    protected int $expectedMsgSeqNum;
    protected int $receivedMsgSeqNum;

  public function __construct(string $message = "", int $expectedMsgSeqNum = 0, int $receivedMsgSeqNum = 0) {
     parent::__construct($message);

     $this->expectedMsgSeqNum = $expectedMsgSeqNum;
     $this->receivedMsgSeqNum = $receivedMsgSeqNum;
  }


  public function getExpectedMsgSeqNum(): int {
     return $this->expectedMsgSeqNum;
  }

  public function getReceivedMsgSeqNum(): int {
  }

}