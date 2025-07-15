<?php

namespace DPRMC\GLMXFixClient;

interface LogInterface {

    public function logParsed( array $content );

    public function logRaw( string $message );

    public function log( FixMessage $fixMessage );
}