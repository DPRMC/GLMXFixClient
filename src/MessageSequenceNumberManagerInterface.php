<?php

namespace DPRMC\GLMXFixClient;

interface MessageSequenceNumberManagerInterface {


    public function getLastMessageSequenceNumber( int $lastMessageSequenceNumber = NULL ): int;

}