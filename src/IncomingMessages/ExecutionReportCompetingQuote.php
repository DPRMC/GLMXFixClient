<?php

/**
 * Represents a Legs group entry, specific to ExecutionReport.
 */

namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents a GLMXCompeting Quotes group entry, specific to ExecutionReport.
 */
class ExecutionReportCompetingQuote {
    public readonly string  $gLMXCounterparty;           // Tag 5029
    public readonly ?string $gLMXCounterpartyEntity;     // Tag 5030
    public readonly int     $gLMXQuoteStatus;            // Tag 5032
    public readonly ?int    $gLMXQuotePriceType;         // Tag 5033
    public readonly ?float  $gLMXQuotePrice;             // Tag 5034
    public readonly ?float  $gLMXQuoteSize;              // Tag 5035
    public readonly ?float  $gLMXQuoteHaircut;           // Tag 5036
    public readonly ?float  $gLMXQuoteAIP;               // Tag 5037

    /**
     * @param string $gLMXCounterparty Name of responding Counterparty
     * @param string|null $gLMXCounterpartyEntity Entity of responding Counterparty
     * @param int $gLMXQuoteStatus Status of Counterparty's response (0=No Response, 1=Pass, etc.)
     * @param int|null $gLMXQuotePriceType Counterparty's counter rate type
     * @param float|null $gLMXQuotePrice Counterparty's counter rate
     * @param float|null $gLMXQuoteSize Counterparty's counter quantity
     * @param float|null $gLMXQuoteHaircut Counterparty's counter haircut
     * @param float|null $gLMXQuoteAIP Counterparty's counter all-in price
     */
    public function __construct(
        string  $gLMXCounterparty,
        ?string $gLMXCounterpartyEntity = NULL,
        int     $gLMXQuoteStatus,
        ?int    $gLMXQuotePriceType = NULL,
        ?float  $gLMXQuotePrice = NULL,
        ?float  $gLMXQuoteSize = NULL,
        ?float  $gLMXQuoteHaircut = NULL,
        ?float  $gLMXQuoteAIP = NULL
    ) {
        $this->gLMXCounterparty       = $gLMXCounterparty;
        $this->gLMXCounterpartyEntity = $gLMXCounterpartyEntity;
        $this->gLMXQuoteStatus        = $gLMXQuoteStatus;
        $this->gLMXQuotePriceType     = $gLMXQuotePriceType;
        $this->gLMXQuotePrice         = $gLMXQuotePrice;
        $this->gLMXQuoteSize          = $gLMXQuoteSize;
        $this->gLMXQuoteHaircut       = $gLMXQuoteHaircut;
        $this->gLMXQuoteAIP           = $gLMXQuoteAIP;
    }
}