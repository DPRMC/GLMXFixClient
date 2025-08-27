<?php

namespace DPRMC\GLMXFixClient\Exceptions;

use Exception;

class NumberOfMessagesToBeResentIsSuspiciouslyHighException extends Exception {

    protected array $messagesToBeResent;

  public function __construct(array $messagesToBeResent) {
      parent::__construct("Number of messages to be resent is suspiciously high.");
      $this->messagesToBeResent = $messagesToBeResent;
  }


  public function getMessagesToBeResent(): array {
      return $this->messagesToBeResent;
  }

  public function getNumberOfMessagesToBeResent(): int {
      return count( $this->messagesToBeResent );
  }

}