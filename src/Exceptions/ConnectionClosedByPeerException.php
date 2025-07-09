<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class ConnectionClosedByPeerException extends Exception {


  public function __construct() {
     parent::__construct("Connection closed by peer.");
  }


}