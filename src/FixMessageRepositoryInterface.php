<?php

namespace DPRMC\GLMXFixClient;

use Carbon\Carbon;

interface FixMessageRepositoryInterface {


    public function getMessageByMsgSeqNum( int $msgSeqNum ): array;


    /**
     * @param Carbon $date
     * @param int $msgSeqNumStart
     * @param int $msgSeqNumEnd Ex: if 0, then send all messages from msgSeqNumStart to the end for the given ordinal day.
     * @param int $direction Ex: -1 for outgoing, 1 for incoming, 0 for both directions
     * @return array
     */
    public function getMessagesBetweenMsgSeqNums(Carbon $date, int $msgSeqNumStart, int $msgSeqNumEnd, int $direction = -1 ): array;



}