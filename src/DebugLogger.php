<?php

namespace DPRMC\GLMXFixClient;

class DebugLogger implements LogInterface {


    public function logParsed( array $content ) {
        print_r( $content );
    }

    public function logRaw( string $message ) {
        echo $message . "\n";
    }

    public function log( FixMessage $fixMessage, array $meta = [] ) {
        print_r($fixMessage);
        print_r($meta);
    }
}