<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use DPRMC\GLMXFixClient\FixMessage;
use Exception;

class GenericFixMessageException extends Exception {

    public FixMessage $fixMessage;

  public function __construct(FixMessage $fixMessage) {
     parent::__construct("Generic exception for FixMessage Check the FIX message details.");

     $this->fixMessage = $fixMessage;
  }


}