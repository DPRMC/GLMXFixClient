<?php

/**
 * Represents a Legs group entry, specific to ExecutionReport.
 */

namespace DPRMC\GLMXFixClient\IncomingMessages;


class ExecutionReportLeg
{
    public readonly string $legSymbol;          // Tag 600
    public readonly string $legSide;            // Tag 624
    public readonly float $legQty;              // Tag 687
    public readonly ?string $legRefID;          // Tag 654
    public readonly ?string $gLMXLegStrategy2;  // Tag 5063
    public readonly int $noNestedPartyIDs;     // Tag 539
    public readonly array $nestedPartyIDs;     // Group: NestedPartyIDs (524, 525, 538)

    /**
     * @param string $legSymbol          Always [N/A]
     * @param string $legSide            Buy or Sell
     * @param float  $legQty             Nominal for the split
     * @param string|null $legRefID      Strategy name or unique identifier
     * @param string|null $gLMXLegStrategy2 Strategy2 for internal allocation
     * @param int    $noNestedPartyIDs Number of nested party IDs
     * @param ExecutionReportNestedParty[] $nestedPartyIDs Array of NestedPartyID objects
     */
    public function __construct(
        string $legSymbol,
        string $legSide,
        float $legQty,
        ?string $legRefID = null,
        ?string $gLMXLegStrategy2 = null,
        int $noNestedPartyIDs = 0,
        array $nestedPartyIDs = []
    ) {
        $this->legSymbol          = $legSymbol;
        $this->legSide            = $legSide;
        $this->legQty             = $legQty;
        $this->legRefID           = $legRefID;
        $this->gLMXLegStrategy2   = $gLMXLegStrategy2;
        $this->noNestedPartyIDs   = $noNestedPartyIDs;
        $this->nestedPartyIDs     = $nestedPartyIDs;
    }
}