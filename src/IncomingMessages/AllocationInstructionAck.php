<?php


namespace DPRMC\GLMXFixClient\IncomingMessages;


/**
 * Represents the AllocationInstructionAck (MsgType=P) message.
 * Sent by GLMX in response to Allocation Instruction.
 */
class AllocationInstructionAck extends AbstractIncomingMessage {
    public readonly string  $allocID;              // Tag 70
    public readonly ?string $secondaryAllocID;     // Tag 793
    public readonly int     $allocStatus;          // Tag 87
    public readonly ?int    $allocRejCode;         // Tag 88
    public readonly ?string $text;                 // Tag 58
    public readonly ?int    $noAllocs;             // Tag 78
    public readonly array   $allocsGrp;            // Group: AllocsGrp (79, 467, 776)

    /**
     * @param string $beginString
     * @param int $bodyLength
     * @param string $msgType
     * @param string $senderCompID
     * @param string $targetCompID
     * @param int $msgSeqNum
     * @param string $sendingTime
     * @param string $checkSum
     * @param string $allocID Reference to AllocID in AllocationInstruction
     * @param string|null $secondaryAllocID Secondary allocation identifier
     * @param string $transactTime Date/Time message generated
     * @param int $allocStatus Status of allocation (0=Accepted, 1=Block Level Rejected, etc.)
     * @param int|null $allocRejCode Reason for rejection
     * @param string|null $text Required if AllocRejCode=99
     * @param int|null $noAllocs Number of Allocations in the Block
     * @param AllocationInstructionAckAllocation[] $allocsGrp Array of AllocationInstructionAckAllocation objects
     */
    public function __construct(
        string  $beginString,
        int     $bodyLength,
        string  $msgType,
        string  $senderCompID,
        string  $targetCompID,
        int     $msgSeqNum,
        string  $sendingTime,
        string  $checkSum,
        string  $allocID,
        ?string $secondaryAllocID,
        string  $transactTime,
        int     $allocStatus,
        ?int    $allocRejCode,
        ?string $text,
        ?int    $noAllocs,
        array   $allocsGrp
    ) {
        parent::__construct(
            $beginString,
            $bodyLength,
            $msgType,
            $senderCompID,
            $targetCompID,
            $msgSeqNum,
            $sendingTime,
            $checkSum,
            $transactTime,
            NULL, // Side not listed
            NULL, // Symbol not listed
            NULL, // Product not listed
            NULL, // SecurityType not listed
            NULL, // OrderQty not listed
            NULL, // Currency not listed
            []    // PartyIDs not listed
        );
        $this->allocID          = $allocID;
        $this->secondaryAllocID = $secondaryAllocID;
        $this->allocStatus      = $allocStatus;
        $this->allocRejCode     = $allocRejCode;
        $this->text             = $text;
        $this->noAllocs         = $noAllocs;
        $this->allocsGrp        = $allocsGrp;
    }
}