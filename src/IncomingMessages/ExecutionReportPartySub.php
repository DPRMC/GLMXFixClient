<?php
namespace DPRMC\GLMXFixClient\IncomingMessages;

/**
 * Represents a PartySubID group entry, specific to ExecutionReportParty.
 */
class ExecutionReportPartySub
{
    public readonly string $partySubID;    // Tag 523
    public readonly int $partySubIDType;  // Tag 803

    /**
     * @param string $partySubID    Sub-identifier for the party
     * @param int    $partySubIDType Type of sub-identifier (e.g., 1 for Firm, 8 for Email Address)
     */
    public function __construct(
        string $partySubID,
        int $partySubIDType
    ) {
        $this->partySubID     = $partySubID;
        $this->partySubIDType = $partySubIDType;
    }
}