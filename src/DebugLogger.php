<?php

namespace DPRMC\GLMXFixClient;

class DebugLogger implements LogInterface {


    public function logParsed( array $content ) {
        print_r( $content );
    }

    public function logRaw( string $message ) {
        echo $message . "\n";
    }
}