<?php

namespace DPRMC\GLMXFixClient;

use Carbon\Carbon;
use DPRMC\GLMXFixClient\Exceptions\ClientNotRunningException;
use DPRMC\GLMXFixClient\Exceptions\ConnectionClosedByPeerException;
use DPRMC\GLMXFixClient\Exceptions\GenericFixMessageException;
use DPRMC\GLMXFixClient\Exceptions\MsgSeqNumTooLowException;
use DPRMC\GLMXFixClient\Exceptions\NoDataException;
use DPRMC\GLMXFixClient\Exceptions\ParseException;
use DPRMC\GLMXFixClient\Exceptions\SocketNotConnectedException;
use DPRMC\GLMXFixClient\Exceptions\StreamSelectException;
use Exception;

class GLMXFixClient {

    const SOH = "\x01"; // ASCII 01 character

    protected string $senderCompID;
    protected string $password;
    protected string $socketConnectHost;
    protected int    $socketConnectPort;
    protected string $targetCompID;
    protected int    $heartBtInt;
    protected string $beginString;
    protected string $socketUseSSL;
    protected string $enabledProtocols;

    /**
     * @var resource|null The stream socket resource. Null if not connected.
     */
    protected $socket = NULL; // Initialize to null

    /**
     * @var int
     */
    protected int $nextOutgoingMsgSeqNum = 1;

    // Add a parser property to handle incoming messages during login
    protected FixMessageParser $parser;


    protected bool $running = TRUE;
    protected int  $lastSentActivity;

    protected bool $debug = FALSE;
    protected int  $lastReceivedActivity;


    protected LogInterface $logger;

    public function __construct( string       $senderCompID = 'EXAMPLE',
                                 string       $password = '<PASSWORD>',
                                 string       $socketConnectHost = 'fixgw.stg.glmx.com',
                                 int          $socketConnectPort = 4303,
                                 string       $targetCompID = 'GLMX',
                                 int          $heartBtInt = 30,
                                 string       $beginString = 'FIX.4.4',
                                 bool         $socketUseSSL = TRUE,
                                 string       $enabledProtocols = 'TLSv1.2',
                                 LogInterface $logger = NULL ) {
        $this->senderCompID      = $senderCompID;
        $this->password          = $password;
        $this->socketConnectHost = $socketConnectHost;
        $this->socketConnectPort = $socketConnectPort;
        $this->targetCompID      = $targetCompID;
        $this->heartBtInt        = $heartBtInt;
        $this->beginString       = $beginString;
        $this->socketUseSSL      = $socketUseSSL ? 'Y' : 'N';
        $this->enabledProtocols  = $enabledProtocols;

        // Initialize the parser here
        $this->parser = new FixMessageParser( $this->beginString );

        $this->lastSentActivity = time();

        if ( $logger === NULL ):
            $this->logger = new DebugLogger();
        else:
            $this->logger = $logger;
        endif;


    }


    /**
     * @return bool
     */
    public function serverIsOpen(): bool {
        $now        = Carbon::now( 'UTC' );
        $openAfter  = Carbon::create( $now->year, $now->month, $now->day, 0, 5, 0, 'UTC' );
        $openBefore = Carbon::create( $now->year, $now->month, $now->day, 23, 45, 0, 'UTC' );


        $this->_debug( "Server opens at: " . $openAfter->toDateTimeString() );
        $this->_debug( "Server closes at: " . $openBefore->toDateTimeString() );

        if ( $now->isBefore( $openAfter ) ):
            return FALSE;
        endif;

        if ( $now->isAfter( $openBefore ) ):
            return FALSE;
        endif;

        return TRUE;
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function connect(): void {
        echo "Attempting to connect to FIX server at {$this->socketConnectHost}:{$this->socketConnectPort}...\n";


        // 1. Build the target server address for stream_socket_client
        // Use 'tls://' prefix because SocketUseSSL is 'Y'
        $address = "tls://" . $this->socketConnectHost . ":" . $this->socketConnectPort;

        // 2. Create a stream context with SSL/TLS options
        $contextOptions = [
            'ssl' => [
                'verify_peer'      => TRUE,
                'verify_peer_name' => TRUE,
                'crypto_method'    => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
            ],
        ];
        $streamContext  = stream_context_create( $contextOptions );

        // 3. Establish the stream socket connection
        $this->socket = @stream_socket_client( // Using @ to suppress warnings for custom error handling
            $address,
            $errno,      // Will contain the error number if connection fails
            $errstr,     // Will contain the error message if connection fails
            30,          // Connection timeout in seconds
            STREAM_CLIENT_CONNECT,
            $streamContext
        );

        // 4. Check if the connection was successful
        if ( $this->socket === FALSE ):
            throw new Exception( "Failed to connect: ($errno) $errstr" );
        endif;

        // 5. Set the stream to non-blocking mode
        if ( ! stream_set_blocking( $this->socket, FALSE ) ):
            fclose( $this->socket );
            throw new Exception( "Failed to set stream to non-blocking mode." );
        endif;

        echo "Connection successful! Stream socket obtained.\n";
    }


    /**
     * Initiates a FIX session by sending a Logon (A) message and waiting for confirmation.
     *
     * @return float Microtime timestamp when the last message was sent during handshake, or throws exception.
     * @throws \Exception If connection is not established, message sending fails, or handshake times out/fails.
     */
    public function login( int $expectedSequenceNumber = NULL ): float {
        echo "Sending Logon (A) message...\n";

        $logonFields = [
            FixMessage::HEART_BT_INT   => (string)$this->heartBtInt, // HeartBtInt
            FixMessage::PASSWORD       => $this->password,           // Password
            FixMessage::ENCRYPT_METHOD => '0',                       // EncryptMethod = None (as required by GLMX admin)
        ];


        // A failed login attempt will increment the nextOutgoingMessageSequenceNumber in the GLMX system, but not in this class.
        if ( $expectedSequenceNumber ):
            $this->nextOutgoingMsgSeqNum = (string)$expectedSequenceNumber;
        endif;

        $message = $this->generateFixMessage( FixMessage::Logon, $logonFields );

        $this->sendRaw( $message );
        $this->logger->logRaw( $message );

        $logonAckReceived = FALSE;
        $startTime        = microtime( TRUE ); // Use microtime for more precise timeout checking
        $timeout          = 10;                // Max 10 seconds for logon acknowledge


        $this->_debug( "Waiting for Logon Acknowledge..." );

        while ( microtime( TRUE ) - $startTime < $timeout ):
            $read_streams   = [ $this->socket ];
            $write_streams  = NULL;
            $except_streams = NULL;

            $num_changed_streams = @stream_select( $read_streams,
                                                   $write_streams,
                                                   $except_streams,
                                                   1 );

            if ( FALSE === $num_changed_streams ):
                throw new Exception( "stream_select error during login." );
            endif;

            if ( $num_changed_streams > 0 && in_array( $this->socket, $read_streams ) ):
                $rawData = $this->readRaw();
                if ( FALSE === $rawData ):
                    throw new Exception( "Connection read error during login handshake." );
                endif;
                if ( '' === $rawData ):
                    //echo "No raw data immediately available, continuing select.\n"; // Keep this commented for cleaner output unless debugging
                    continue;
                endif;

                echo "Raw data received during login. Appending to parser...\n";
                $this->parser->appendData( $rawData );


                while ( ( $parsedMessage = $this->parser->parseNextMessage() ) !== NULL ):
                    $this->_debug( '--- Message Parsed During Login Handshake ---' );

                    $fixMessage = new FixMessage( $parsedMessage );
                    $this->logger->log( $fixMessage );

                    if ( $this->debug ):
                        print_r( $fixMessage );
                    endif;


                    switch ( $fixMessage->getMessageType() ):
                        case FixMessage::Logon:
                            $this->_debug( 'Logon' );
                            $this->_debug( "Logon Acknowledge received. Login handshake complete." );
                            return microtime( TRUE ); // Login successful, return timestamp

                        case FixMessage::Logout:
                            $this->_debug( 'Logout' );
                            $this->_debug( $fixMessage->content[ FixMessage::TEXT ] );
                            $text = $fixMessage->content[ FixMessage::TEXT ];

                            if ( str_starts_with( $text, 'MsgSeqNum too low, expecting' ) ):
                                preg_match_all( '/\d+/', $text, $matches );
                                throw new MsgSeqNumTooLowException( $text, $matches[ 0 ][ 0 ], $matches[ 0 ][ 1 ] );
                            endif;

                            throw new GenericFixMessageException( $fixMessage );

                        case FixMessage::Heartbeat:
                            $this->_debug( 'Heartbeat' );
                            // Do the thing.
                            break;

                        case FixMessage::TestRequest:
                            $this->_debug( 'TestRequest' );
                            // Do the thing.
                            break;

                        case FixMessage::ResendRequest:
                            $this->_debug( 'ResendRequest' );
                            // Do the thing.
                            break;

                        case FixMessage::Reject:
                            $this->_debug( 'Reject' );
                            // Do the thing.
                            break;

                        case FixMessage::SequenceReset:
                            $this->_debug( 'SequenceReset' );
                            // Do the thing.
                            break;

                        default:
                            $this->_debug( '----DEFAULT----' );
                            throw new Exception( 'Unknown message type: ' . $fixMessage->getMessageType() );

                    endswitch;
                endwhile;

            endif;
        endwhile;


        throw new Exception( "Login handshake failed: No Logon Acknowledge received within timeout." );
    }


    /**
     * @return void
     * @throws \Exception
     *
     * 8=FIX.4.2^9=79^35=4^49=SellSide^56=BuySide^34=1^52=20250709-17:30:00.000^36=100^10=104^
     */
    public function sequenceReset(): void {
        //
        $fields = [
            FixMessage::HEART_BT_INT   => (string)$this->heartBtInt, // HeartBtInt
            FixMessage::PASSWORD       => $this->password,           // Password
            FixMessage::ENCRYPT_METHOD => '0',
        ];

        $message = $this->generateFixMessage( FixMessage::SequenceReset, $fields );
        $this->sendRaw( $message );
    }


    /**
     * Returns the raw stream socket resource.
     *
     * @return resource|null
     */
    public function getSocketResource() {
        return $this->socket;
    }


    /**
     * Sends a raw message over the socket.
     *
     * @param string $message The message to send.
     *
     * @return int|false Number of bytes written or false on failure.
     * @throws \Exception
     */
    protected function sendRaw( string $message ): int|false {
        if ( ! $this->socket ):
            throw new Exception( "Socket not connected." );
        endif;
        $bytesWritten = @fwrite( $this->socket, $message );
        if ( $bytesWritten === FALSE ):
            throw new Exception( "Failed to write to socket." );
        endif;
        $this->_debug( "Sent: " . str_replace( self::SOH, '|', $message ) );

        $this->lastSentActivity = time();
        return $bytesWritten;
    }


    /**
     * Reads raw data from the socket.
     *
     * @param int $length The maximum number of bytes to read.
     *
     * @return string|false The read data or false on error/EOF.
     * @throws SocketNotConnectedException
     */
    public function readRaw( int $length = 2048 ): string|false {
        if ( ! $this->socket ):
            throw new SocketNotConnectedException();
        endif;
        $data = @fread( $this->socket, $length );
        if ( $data !== FALSE && $data !== '' ):
            $this->_debug( "Received RAW (ASCII): '" . str_replace( self::SOH, '[SOH]', $data ) );
            $this->_debug( "Received RAW (HEX):   '" . bin2hex( $data ) );
        endif;
        return $data;
    }

    /**
     * Calculates the BodyLength (tag 9) of a FIX message.
     *
     * @param string $messageBody The part of the message from tag 35 up to (but not including) tag 10.
     *
     * @return int
     */
    protected function calculateBodyLength( string $messageBody ): int {
        return strlen( $messageBody );
    }

    /**
     * Calculates the CheckSum (tag 10) of a FIX message.
     *
     * @param string $message The entire FIX message string up to (but not including) the CheckSum field.
     *
     * @return string Three-character checksum, 0-padded if necessary.
     */
    protected function calculateCheckSum( string $message ): string {
        $sum = 0;
        for ( $i = 0; $i < strlen( $message ); $i++ ):
            $sum += ord( $message[ $i ] );
        endfor;
        return str_pad( (string)( $sum % 256 ), 3, '0', STR_PAD_LEFT );
    }

    /**
     * Generates a complete FIX message string.
     *
     * @param string $msgType The MsgType (tag 35).
     * @param array<string, string> $fields An associative array of FIX tags and their values (excluding header and trailer).
     *
     * @return string The complete FIX message.
     */
    protected function generateFixMessage( string $msgType, array $fields = [] ): string {
        // Standard FIX header field order (relevant for MsgType 'A' to 'Z' excluding '8' and '9' which are inserted dynamically)
        // See: https://www.fixtrading.org/standards/tagvalue-online/
        $standardHeaderOrder = [
            '35', // MsgType
            '34', // MsgSeqNum
            '49', // SenderCompID
            '52', // SendingTime
            '56', // TargetCompID
            '98', // EncryptMethod (added as per GLMX admin feedback)
            // Add other standard header fields here if needed, in their numerical order
        ];

        $headerFields = [];
        foreach ( $standardHeaderOrder as $tag ):
            // Populate header fields from class properties
            switch ( $tag ):
                case FixMessage::MSG_TYPE:
                    $headerFields[ $tag ] = $msgType;
                    break;
                case FixMessage::MSG_SEQ_NUM:
                    $headerFields[ $tag ] = (string)$this->nextOutgoingMsgSeqNum;
                    break;
                case FixMessage::SENDER_COMP_ID:
                    $headerFields[ $tag ] = $this->senderCompID;
                    break;
                case FixMessage::SENDING_TIME:
                    $headerFields[ $tag ] = gmdate( 'Ymd-H:i:s.v' );
                    break; // UTC timestamp
                case FixMessage::TARGET_COMP_ID:
                    $headerFields[ $tag ] = $this->targetCompID;
                    break;
                case FixMessage::ENCRYPT_METHOD:
                    $headerFields[ $tag ] = '0'; // Fixed value as per admin feedback
                    break;
            endswitch;
        endforeach;

        // Add any message-specific fields from the $fields array
        // Order of application-level fields generally doesn't matter as strictly as header fields
        foreach ( $fields as $tag => $value ):
            $headerFields[ $tag ] = $value;
        endforeach;


        $bodyContent = '';
        foreach ( $headerFields as $tag => $value ):
            $bodyContent .= $tag . '=' . $value . self::SOH;
        endforeach;

        $bodyLength = $this->calculateBodyLength( $bodyContent );

        // Assemble the full message with BeginString, BodyLength, then the rest of the body, and finally CheckSum
        $fullMessage = "8=" . $this->beginString . self::SOH;
        $fullMessage .= "9=" . $bodyLength . self::SOH;
        $fullMessage .= $bodyContent; // This now includes MsgType, MsgSeqNum, SenderCompID, SendingTime, TargetCompID, and other app fields.

        $checksum    = $this->calculateCheckSum( $fullMessage );
        $fullMessage .= "10=" . $checksum . self::SOH;

        $this->nextOutgoingMsgSeqNum++;

        return $fullMessage;
    }


    /**
     * Sends a Heartbeat (0) message to the GLMX server.
     *
     * @param string|null $testReqId Optional. The TestReqID (112) to include if responding to a TestRequest.
     *
     * @return void
     * @throws \Exception If the connection is not established or message sending fails.
     */
    public function sendHeartbeat( ?string $testReqId = NULL ): void {
        echo "Sending Heartbeat (0) message...\n";

        $heartbeatFields = [];
        if ( $testReqId !== NULL ):
            $heartbeatFields[ '112' ] = $testReqId; // TestReqID
        endif;

        $message = $this->generateFixMessage( FixMessage::Heartbeat, $heartbeatFields );
        $this->sendRaw( $message );
    }

    /**
     * Sends a TestRequest (1) message to the GLMX server.
     *
     * @param string|null $testReqId Optional. A unique identifier for this test request. If null, one will be generated.
     *
     * @return string The TestReqID that was sent.
     * @throws \Exception If the connection is not established or message sending fails.
     */
    public function sendTestRequest( ?string $testReqId = NULL ): string {
        echo "Sending TestRequest (1) message...\n";

        if ( $testReqId === NULL ):
            $testReqId = 'TESTREQ-' . time() . '-' . uniqid();
        endif;

        $testRequestFields = [
            '112' => $testReqId, // TestReqID
        ];

        $message = $this->generateFixMessage( FixMessage::TestRequest, $testRequestFields );

        $this->sendRaw( $message );

        return $testReqId;
    }


    /**
     * @return void
     */
    public function disconnect(): void {
        if ( $this->socket ):
            fclose( $this->socket );
            $this->socket = NULL;
            $this->_debug( "Disconnected from FIX server." );
        endif;
    }


    public function setRunning( bool $running ) {
        $this->running = $running;
    }

    protected function _setRunning( bool $running ) {
        $this->running = $running;
    }

    public function isRunning(): bool {
        return $this->running;
    }


    /**
     * @throws \DPRMC\GLMXFixClient\Exceptions\ClientNotRunningException
     * @throws \DPRMC\GLMXFixClient\Exceptions\StreamSelectException
     * @throws \Exception
     * @throws \DPRMC\GLMXFixClient\Exceptions\NoDataException
     */
    public function getMessage() {

        if ( FALSE === $this->isRunning() ):
            throw new ClientNotRunningException();
        endif;


        $read_sockets   = [ $this->getSocketResource() ]; // Get the raw socket resource
        $write_sockets  = NULL;
        $except_sockets = NULL;


        // Use stream_select to wait for data or timeout for periodic actions
        // Timeout is set to a short interval (e.g., 1 second) or remaining time till next heartbeat
        $timeout = $this->heartBtInt - ( time() - $this->lastSentActivity );
        if ( $timeout < 1 ) $timeout = 1; // Don't block indefinitely if heartbeat is due


        /**
         * On success stream_select() returns the number of stream resources
         * contained in the modified arrays, which
         * may be zero if the timeout expires before anything interesting happens.
         * On error false is returned, and
         * a warning raised (this can happen if the system call is interrupted by an incoming signal).
         */
        $num_changed_sockets = @stream_select( $read_sockets,
                                               $write_sockets,
                                               $except_sockets,
                                               $timeout );

        if ( $num_changed_sockets === FALSE ):
            throw new StreamSelectException( "stream_select error: " . error_get_last()[ 'message' ] );
        endif;

        // --- Handle Incoming Data ---
        if ( $num_changed_sockets > 0 && in_array( $this->getSocketResource(), $read_sockets ) ):
            $rawData = $this->readRaw();

            $this->logger->logRaw( $rawData );

            if ( $rawData === FALSE ):
                // Connection closed or error
                $this->_debug( "Connection read error or closed by peer." );
                $this->_setRunning( FALSE );
                throw new ConnectionClosedByPeerException();
            endif;


            if ( '' === $rawData ):
                $this->_debug( "No data yet..." );
            endif;

            $this->parser->appendData( $rawData );
            $this->lastReceivedActivity = time(); // Update activity time after receiving data
        endif;

        // --- Process Parsed Messages ---
        $content = $this->parser->parseNextMessage();

        if ( $content ):
            return new FixMessage( $content );
        endif;

        throw new NoDataException();
    }


    public function setDebug( bool $debug ) {
        $this->debug = $debug;
    }

    protected function _debug( string $message ): void {
        if ( $this->debug ):
            echo $message . "\n";
        endif;
    }


    /**
     * @return int
     */
    public function getNextOutgoingMsgSeqNum(): int {
        return $this->nextOutgoingMsgSeqNum;
    }
}