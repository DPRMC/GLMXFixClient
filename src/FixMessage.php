<?php

namespace DPRMC\GLMXFixClient;

use Exception;

class FixMessage {

    public array $content = [];

    // FIX Session Level Messages
    const Logon         = 'A';
    const Logout        = '5';
    const Heartbeat     = '0';
    const TestRequest   = '1';
    const ResendRequest = '2';
    const Reject        = '3';
    const SequenceReset = '4';


    // FIX Application Level Messages
    const BusinessRejectMessage    = 'j';
    const NewOrderSingle           = 'D';
    const NewOrderList             = 'E';
    const OrderCancelRequest       = 'F';
    const ExecutionReport          = '8';
    const TradeCaptureReport       = 'AE';
    const AllocationInstruction    = 'J';
    const AllocationInstructionAck = 'P';
    const AllocationReport         = 'AS';


    public static array $messageTypes = [
        self::Logon         => 'Logon',
        self::Logout        => 'Logout',
        self::Heartbeat     => 'Heartbeat',
        self::TestRequest   => 'TestRequest',
        self::ResendRequest => 'ResendRequest',
        self::Reject        => 'Reject',
        self::SequenceReset => 'SequenceReset',

        self::BusinessRejectMessage    => 'BusinessRejectMessage',
        self::NewOrderSingle           => 'NewOrderSingle',
        self::NewOrderList             => 'NewOrderList',
        self::OrderCancelRequest       => 'OrderCancelRequest',
        self::ExecutionReport          => 'ExecutionReport',
        self::TradeCaptureReport       => 'TradeCaptureReport',
        self::AllocationInstruction    => 'AllocationInstruction',
        self::AllocationInstructionAck => 'AllocationInstructionAck',
        self::AllocationReport         => 'AllocationReport',
    ];


    public static array $administrativeMessageTypes = [
        self::Logon         => 'Logon',
        self::Logout        => 'Logout',
        self::Heartbeat     => 'Heartbeat',
        self::TestRequest   => 'TestRequest',
        self::ResendRequest => 'ResendRequest',
        self::Reject        => 'Reject',
        self::SequenceReset => 'SequenceReset',
    ];

    /**
     * Retrieves the name of a FIX message type given its code.
     * @param string $messageCode The FIX message code.
     * @return string|null The message name or null if not found.
     */
    public static function getMessageName( string $messageCode ): ?string {
        return self::$messageTypes[ $messageCode ] ?? NULL;
    }

    // FIX TAGS
    // FIX Session Level Fields (Section: FIX SESSION HANDLING)
    const BEGIN_STRING       = 8;
    const BODY_LENGTH        = 9;
    const MSG_TYPE           = 35;
    const SENDER_COMP_ID     = 49;
    const TARGET_COMP_ID     = 56;
    const MSG_SEQ_NUM        = 34;
    const SENDING_TIME       = 52;
    const CHECK_SUM          = 10;
    const PASSWORD           = 554;
    const HEART_BT_INT       = 108;
    const RESET_SEQ_NUM_FLAG = 141;
    const TEST_REQ_ID        = 112;
    const BEGIN_SEQ_NO       = 7;
    const END_SEQ_NO         = 16;
    const GAP_FILL_FLAG      = 123;
    const NEW_SEQ_NO         = 36;
    const POSS_DUP_FLAG      = 43;

    // FIX Application Level Fields (Sections: New Order Single, New Order List, Order Cancel Request, Execution Report, Allocation Instruction, Allocation Instruction Ack, Allocation Report)

    // Common Order/Inquiry Fields
    const CI_ORD_ID                = 11;
    const EXEC_INST                = 18;
    const TRANSACT_TIME            = 60;
    const SIDE                     = 54;
    const ORDER_QTY                = 38;
    const ORD_TYPE                 = 40;
    const SYMBOL                   = 55;
    const PRODUCT                  = 460;
    const SECURITY_TYPE            = 167;
    const SECURITY_SUB_TYPE        = 762;
    const CURRENCY                 = 15;
    const TERMINATION_TYPE         = 788;
    const START_DATE               = 916;
    const SETTL_DATE               = 64;
    const END_DATE                 = 917;
    const MATURITY_DATE            = 541;
    const DELIVERY_TYPE            = 919;
    const SETTL_CURRENCY           = 120;
    const PRICE                    = 44;
    const PRICE_TYPE               = 423;
    const BENCHMARK_CURVE_CURRENCY = 220;
    const BENCHMARK_CURVE_NAME     = 221;
    const BENCHMARK_CURVE_POINT    = 222;
    const MARGIN_RATIO             = 898;

    const GLMX_TRADE_TYPE          = 5001;
    const HAIRCUT_FORMULA          = 5002;
    const GC_TYPE                  = 5006;
    const GLMX_DEAL_TYPE           = 5052;
    const GLMX_COLLATERAL_TYPE     = 5039;
    const GLMX_COLLATERAL_DESC     = 5040;
    const GLMX_SBL_TRIPARTY_AGENT  = 5055;
    const GLMX_FEE_TYPE            = 5041;
    const GLMX_DIRECT_SEND         = 5054;
    const GLMX_IS_BROAD_BASKET     = 5065;
    const GLMX_COUP_DIV_REQ        = 5071;

    // Underlying Instrument Group Fields
    const NO_UNDERLYINGS                 = 711;
    const UNDERLYING_SECURITY_ID         = 309;
    const UNDERLYING_SECURITY_ID_SOURCE  = 305;
    const UNDERLYING_PX                  = 810;
    const UNDERLYING_SETTL_METHOD        = 1039;
    const UNDERLYING_SYMBOL              = 311;
    const UNDERLYING_CURRENCY            = 318;
    const UNDERLYING_FACTOR              = 246;
    const UNDERLYING_QTY                 = 879;
    const UNDERLYING_START_VALUE         = 884;
    const UNDERLYING_DIRTY_PRICE         = 882;
    const UNDERLYING_CONTRACT_MULTIPLIER = 436;
    const UNDERLYING_END_PRICE           = 883;

    // Underlying Reinvestment Coupon Group Fields
    const NO_UNDERLYING_REINV_COUPON   = 6223;
    const UNDERLYING_REINV_COUPON_DATE = 6224;
    const UNDERLYING_REINV_COUPON_RATE = 6225;
    const UNDERLYING_REINV_COUPON_AMT  = 6226;

    // Allocation Group Fields
    const NO_ALLOCS                = 78;
    const ALLOC_ACCOUNT            = 79;
    const ALLOC_ACCT_ID_SOURCE     = 661;
    const ALLOC_QTY                = 80;
    const GLMX_STRATEGY            = 5009;
    const GLMX_STRATEGY2           = 5010;
    const GLMX_STRATEGY3           = 5059;
    const GLMX_STRATEGY4           = 5060;
    const GLMX_STRATEGY5           = 5061;
    const GLMX_ALLOC_SECURITY_TYPE = 5051;
    const GLMX_ALLOC_COUP_DIV_REQ  = 5072;
    const INDIVIDUAL_ALLOC_ID      = 467;
    const ALLOC_NET_MONEY          = 154;
    const GLMX_GC_BASKET           = 5066;

    // Party ID Group Fields
    const NO_PARTY_IDS    = 453;
    const PARTY_ID        = 448;
    const PARTY_ID_SOURCE = 447;
    const PARTY_ROLE      = 452;

    // Party Sub ID Group Fields (Nested within Party ID Group in Execution Report)
    const NO_PARTY_SUB_IDS  = 802;
    const PARTY_SUB_ID      = 523;
    const PARTY_SUB_ID_TYPE = 803;

    // GLMX Stipulation Group Fields
    const NO_GLMX_STIPS   = 5016;
    const GLMX_STIP_TYPE  = 5017;
    const GLMX_STIP_VALUE = 5018;

    // Event Group Fields
    const NO_EVENTS         = 864;
    const EVENT_TYPE        = 865;
    const EVENT_DATE        = 866;
    const EVENT_TIME_UNIT   = 1826;
    const EVENT_TIME_PERIOD = 1827;

    // Regulatory Trade ID Group Fields
    const NO_REGULATORY_TRADE_IDS    = 1907;
    const REGULATORY_TRADE_ID        = 1903;
    const REGULATORY_TRADE_ID_SOURCE = 1905;
    const REGULATORY_TRADE_ID_EVENT  = 1904; // Corrected tag number based on Allocation Instruction
    const REGULATORY_TRADE_ID_TYPE   = 1906;

    // Allocation Regulatory Trade ID Group Fields
    const NO_ALLOC_REGULATORY_TRADE_IDS    = 1908;
    const ALLOC_REGULATORY_TRADE_ID        = 1909;
    const ALLOC_REGULATORY_TRADE_ID_SOURCE = 1910;
    const ALLOC_REGULATORY_TRADE_ID_EVENT  = 1911;
    const ALLOC_REGULATORY_TRADE_ID_TYPE   = 1912;

    // New Order List Specific Fields
    const LIST_ID              = 66;
    const TOT_NO_ORDERS        = 68;
    const NO_ORDERS            = 73;
    const LIST_SEQ_NO          = 67;
    const GLMX_CONTINGENT_LIST = 5021;

    // Execution Report Specific Fields
    const ORDER_ID                   = 37;
    const EXEC_ID                    = 17;
    const EXEC_REF_ID                = 19;
    const EXEC_TYPE                  = 150;
    const ORD_STATUS                 = 39;
    const CUM_QTY                    = 14;
    const LEAVES_QTY                 = 151;
    const LAST_PX                    = 31;
    const LAST_QTY                   = 32;
    const TOT_NUM_REPORTS            = 911;
    const AGREEMENT_DATE             = 915;
    const TRADE_DATE                 = 75;
    const INTEREST_AT_MATURITY       = 738;
    const ALLIN_PRICE                = 5005;
    const START_CASH                 = 921;
    const END_CASH                   = 922;
    const COMMISSION                 = 12;
    const DAY_COUNT                  = 5003;
    const TRADING_BOOK               = 5004;
    const SETTL_CURR_AMT             = 119;
    const SETTL_CURR_FX_RATE         = 155;
    const SETTL_CURR_FX_RATE_CALC    = 156;
    const GLMX_CHANGED_FROM_ORIGINAL = 5027;
    const GLMX_ACTION_REASON         = 5011;
    const GLMX_LINKED_TRADE_ID       = 5012;
    const NO_LEGS                    = 555;
    const LEG_SYMBOL                 = 600;
    const LEG_SIDE                   = 624;
    const LEG_QTY                    = 687;
    const LEG_REF_ID                 = 654;
    const GLMX_LEG_STRATEGY2         = 5063;
    const NO_NESTED_PARTY_IDS        = 539;
    const NESTED_PARTY_ID            = 524;
    const NESTED_PARTY_ID_SOURCE     = 525;
    const NESTED_PARTY_ROLE          = 538;
    const GLMX_INQUIRY_ID            = 5022;
    const NO_GLMX_COMPETING_QUOTES   = 5028;
    const GLMX_COUNTERPARTY          = 5029;
    const GLMX_COUNTERPARTY_ENTITY   = 5030;
    const GLMX_QUOTE_STATUS          = 5032;
    const GLMX_QUOTE_PRICE_TYPE      = 5033;
    const GLMX_QUOTE_PRICE           = 5034;
    const GLMX_QUOTE_SIZE            = 5035;
    const GLMX_QUOTE_HAIRCUT         = 5036;
    const GLMX_QUOTE_AIP             = 5037;

    const TRADE_LINK_ID = 820;
    const TRD_TYPE                   = 828;
    const GLMX_INDEX_TYPE            = 5053;
    const ADJUSTED_END_CASH          = 6379;
    const SECONDARY_ORDER_ID         = 198;
    const GLMX_LAST_ACTION_SOURCE    = 5056;
    const GLMX_TRADE_CATEGORY        = 5046;
    const GLMX_COLLECTION_ID         = 5069;
    const GLMX_RATE_TYPE             = 5020;

    // Allocation Instruction Specific Fields
    const ALLOC_ID                  = 70;
    const ALLOC_TRANS_TYPE          = 71;
    const ALLOC_TYPE                = 626;
    const SECONDARY_ALLOC_ID        = 793;
    const REF_ALLOC_ID              = 72;
    const ALLOC_CANC_REPLACE_REASON = 796;
    const ALLOC_NO_ORDERS_TYPE      = 857;
    const SECURITY_ID               = 48;
    const SECURITY_ID_SOURCE        = 22;
    const ISSUE_DATE                = 225;
    const SECURITY_DESC             = 107;
    const QUANTITY                  = 53;
    const LAST_MKT                  = 30;
    const AVG_PX                    = 6;
    const GROSS_TRADE_AMT           = 381;
    const NET_MONEY                 = 118; // Re-used from Execution Report, but also relevant here

    // Allocation Instruction Ack Specific Fields
    const ALLOC_STATUS              = 87;
    const ALLOC_REJ_CODE            = 88;
    const TEXT                      = 58;
    const INDIVIDUAL_ALLOC_REJ_CODE = 776;

    // Allocation Report Specific Fields
    const ALLOC_REPORT_ID     = 755;
    const ALLOC_REPORT_REF_ID = 795;
    const ALLOC_REPORT_TYPE   = 794;

    const ENCRYPT_METHOD = 98;

    // Reject message constants.
    const REF_MSG_TYPE = 372;
    //const REF_MSG_SEQ_NUM       = 373;
    const SESSION_REJECT_REASON = 373;
    const REF_MSG_SEQ_NUM       = 45;


    //const SESSION_REJECT_REASON = 371;
    const REF_TAG_ID = 371;

    const REF_TRADE = 233;

    public function __construct( array $content = [] ) {
        $this->content = $content;
    }


    public function getMessageType(): string {
        if ( isset( $this->content[ self::MSG_TYPE ] ) ):
            return (string)$this->content[ self::MSG_TYPE ];
        endif;
    }


    public function getContent(): array {
        return $this->content;
    }


}