<?php

/**
 * Represents a Legs group entry, specific to ExecutionReport.
 */

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a NestedPartyIDs group entry, specific to ExecutionReportLeg.
 */
class ExecutionReportNestedParty {
    public readonly string $nestedPartyID;          // Tag 524
    public readonly string $nestedPartyIDSource;    // Tag 525
    public readonly int    $nestedPartyRole;        // Tag 538

    /**
     * @param string $nestedPartyID Counterparty or Strategy name
     * @param string $nestedPartyIDSource Proprietary Code ('D')
     * @param int $nestedPartyRole Role of the nested party (e.g., 3 for Client ID)
     */
    public function __construct(
        string $nestedPartyID,
        string $nestedPartyIDSource,
        int    $nestedPartyRole
    ) {
        $this->nestedPartyID       = $nestedPartyID;
        $this->nestedPartyIDSource = $nestedPartyIDSource;
        $this->nestedPartyRole     = $nestedPartyRole;
    }
}