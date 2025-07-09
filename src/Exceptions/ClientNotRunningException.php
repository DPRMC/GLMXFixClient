<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class ClientNotRunningException extends Exception {


  public function __construct() {
     parent::__construct("Client is not running.");
  }


}