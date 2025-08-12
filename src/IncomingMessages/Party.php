<?php

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a PartyID group entry.
 * This is a common group structure used across multiple message types.
 */
class Party {
    public readonly string $partyID;         // Tag 448
    public readonly string $partyIDSource;   // Tag 447
    public readonly int    $partyRole;       // Tag 452

    /**
     * @param string $partyID Party identifier
     * @param string $partyIDSource Proprietary Code ('D')
     * @param int $partyRole Role of the party (e.g., 11 for Order Origination Trader)
     */
    public function __construct(
        string $partyID,
        string $partyIDSource,
        int    $partyRole
    ) {
        $this->partyID       = $partyID;
        $this->partyIDSource = $partyIDSource;
        $this->partyRole     = $partyRole;
    }
}