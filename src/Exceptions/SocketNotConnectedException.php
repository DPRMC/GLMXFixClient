<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class SocketNotConnectedException extends Exception {


  public function __construct() {
     parent::__construct("Socket is not connected.");
  }


}