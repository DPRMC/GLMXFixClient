<?php

namespace DPRMC\GLMXFixClient;

class DebugMessageSequenceNumberManager implements MessageSequenceNumberManagerInterface {


    public function getLastMessageSequenceNumber( int $lastMessageSequenceNumber = NULL ): int {
        return $lastMessageSequenceNumber;
    }
}