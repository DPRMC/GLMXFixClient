<?php
namespace DPRMC\GLMXFixClient\IncomingMessages;

/**
 * Represents a PartyID group entry specific to ExecutionReport.
 * Includes PartySubIDs which are nested within Party only for Execution Report.
 */
class ExecutionReportParty extends Party
{
    public readonly int $noPartySubIDs;    // Tag 802
    public readonly array $partySubIDs;    // Group: PartySubID (523, 803)

    /**
     * @param string $partyID
     * @param string $partyIDSource
     * @param int $partyRole
     * @param int $noPartySubIDs
     * @param ExecutionReportPartySub[] $partySubIDs
     */
    public function __construct(
        string $partyID,
        string $partyIDSource,
        int $partyRole,
        int $noPartySubIDs,
        array $partySubIDs
    ) {
        parent::__construct($partyID, $partyIDSource, $partyRole);
        $this->noPartySubIDs = $noPartySubIDs;
        $this->partySubIDs    = $partySubIDs;
    }
}