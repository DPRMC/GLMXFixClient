<?php

namespace DPRMC\GLMXFixClient;

use Exception;

class FixMessage {


    public function __construct( array $message = NULL ) {
    }


    public function getMessageType(): int {
        return 0;
    }


}