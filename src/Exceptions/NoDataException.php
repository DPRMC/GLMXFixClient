<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class NoDataException extends Exception {


  public function __construct() {
      parent::__construct("No data was found.");
  }


}