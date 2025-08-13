<?php

namespace DPRMC\GLMXFixClient;

class DebugMessageSequenceNumberManager implements MessageSequenceNumberManagerInterface {


    public function getLastMessageSequenceNumber( int $lastMessageSequenceNumber = NULL ): int {
        return $lastMessageSequenceNumber;
    }

    public function getLastOutgoingMessageSequenceNumber(): int {
        // TODO: Implement getLastOutgoingMessageSequenceNumber() method.
    }

    public function getLastIncomingMessageSequenceNumber(): int {
        // TODO: Implement getLastIncomingMessageSequenceNumber() method.
    }
}