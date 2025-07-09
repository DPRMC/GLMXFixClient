<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class StreamSelectException extends Exception {


  public function __construct(string $message = "") {
     parent::__construct($message);
  }


}