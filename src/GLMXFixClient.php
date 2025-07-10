<?php

namespace DPRMC\GLMXFixClient;

use Carbon\Carbon;
use DPRMC\GLMXFixClient\Exceptions\ClientNotRunningException;
use DPRMC\GLMXFixClient\Exceptions\ConnectionClosedByPeerException;
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

    protected int $nextOutgoingMsgSeqNum = 1;

    // Add a parser property to handle incoming messages during login
    protected FixMessageParser $parser;


    protected bool $running = TRUE;
    protected int  $lastSentActivity;

    protected bool $debug = FALSE;
    protected int  $lastReceivedActivity;


    public function __construct( string $senderCompID = 'EXAMPLE',
                                 string $password = '<PASSWORD>',
                                 string $socketConnectHost = 'fixgw.stg.glmx.com',
                                 int    $socketConnectPort = 4303,
                                 string $targetCompID = 'GLMX',
                                 int    $heartBtInt = 30,
                                 string $beginString = 'FIX.4.4',
                                 bool   $socketUseSSL = TRUE,
                                 string $enabledProtocols = 'TLSv1.2' ) {
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
        if ( !stream_set_blocking( $this->socket, FALSE ) ):
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
    public function login(): float {
        echo "Sending Logon (A) message...\n";

        $logonFields = [
            '108' => (string)$this->heartBtInt, // HeartBtInt
            '554' => $this->password,           // Password
            //'98'  => '0',                       // EncryptMethod = None (as required by GLMX admin)
        ];

        $message = $this->generateFixMessage( FixMessage::Logon, $logonFields );

        //dd($message);
        $this->sendRaw( $message );

        $logonAckReceived    = FALSE;
        $testRequestReceived = FALSE;
        $testReqId           = NULL;
        $startTime           = microtime( TRUE ); // Use microtime for more precise timeout checking
        $timeout             = 10;                // Max 10 seconds for login handshake

        echo "Waiting for Logon Acknowledge and TestRequest...\n";

        while ( microtime( TRUE ) - $startTime < $timeout && (!$logonAckReceived || !$testRequestReceived) ):
            $read_streams   = [ $this->socket ];
            $write_streams  = NULL;
            $except_streams = NULL;

            $num_changed_streams = @stream_select( $read_streams, $write_streams, $except_streams, 1 );

            if ( $num_changed_streams === FALSE ):
                throw new Exception( "stream_select error during login." );
            endif;

            if ( $num_changed_streams > 0 && in_array( $this->socket, $read_streams ) ):
                $rawData = $this->readRaw();
                if ( $rawData === FALSE ):
                    throw new Exception( "Connection read error during login handshake." );
                endif;
                if ( $rawData === '' ):
                    //echo "No raw data immediately available, continuing select.\n";
                    continue;
                endif;

                echo "Raw data received during login. Appending to parser...\n";
                $this->parser->appendData( $rawData );

                try {
                    while ( ($parsedMessage = $this->parser->parseNextMessage()) !== NULL ):
                        echo "--- Message Parsed During Login Handshake ---\n";
                        print_r( $parsedMessage );


                        if ( !isset( $parsedMessage[ '35' ] ) ):
                            continue;
                        endif;


                        $msgType = $parsedMessage[ '35' ];
                        if ( $msgType === 'A' ): // Logon Acknowledge
                            $logonAckReceived = TRUE;
                            echo "Logon Acknowledge received.\n";
                        elseif ( $msgType === '1' ): // TestRequest
                            $testRequestReceived = TRUE;
                            if ( isset( $parsedMessage[ '112' ] ) ):
                                $testReqId = $parsedMessage[ '112' ];
                                echo "TestRequest received with ID: " . $testReqId . ".\n";
                            else:
                                echo "TestRequest received without ID.\n";
                            endif;
                        endif;

                        echo "-------------------------------------------\n";
                    endwhile;
                } catch ( Exception $e ) {
                    echo "Parsing error during login: " . $e->getMessage() . "\n";
                    echo "Current parser buffer content (HEX): " . bin2hex( $this->parser->getBuffer() ) . "\n";
                }
            endif;

            if ( $logonAckReceived && $testRequestReceived && $testReqId !== NULL ):
                echo "Logon handshake complete: Responding to TestRequest with Heartbeat...\n";
                $this->sendHeartbeat( $testReqId );
                return microtime( TRUE ); // Return current microtime after sending heartbeat
            endif;
        endwhile;


        if ( !($logonAckReceived && $testRequestReceived) ):
            $reason = '';
            if ( !$logonAckReceived ): $reason .= "No Logon Acknowledge received. "; endif;
            if ( !$testRequestReceived ): $reason .= "No TestRequest received. "; endif;
            throw new Exception( "Login handshake failed: " . $reason . "Timeout reached." );
        endif;

        return microtime( TRUE ); // Fallback return if somehow control reaches here (should not for success)
    }


    /**
     * @return void
     * @throws \Exception
     *
     * 8=FIX.4.2^9=79^35=4^49=SellSide^56=BuySide^34=1^52=20250709-17:30:00.000^36=100^10=104^
     */
    public function sequenceReset() {
        //
        $fields = [
            '108' => (string)$this->heartBtInt, // HeartBtInt
            '554' => $this->password,           // Password
            //'98'  => '0',                       // EncryptMethod = None (as required by GLMX admin)
        ];


        $message = $this->generateFixMessage( FixMessage::SequenceReset, $fields );
        $this->sendRaw( $message );

        FixMessage::SequenceReset;
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
        if ( !$this->socket ):
            throw new Exception( "Socket not connected." );
        endif;
        $bytesWritten = @fwrite( $this->socket, $message );
        if ( $bytesWritten === FALSE ):
            throw new Exception( "Failed to write to socket." );
        endif;
        echo "Sent: " . str_replace( self::SOH, '|', $message ) . "\n";

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
        if ( !$this->socket ):
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
        return str_pad( (string)($sum % 256), 3, '0', STR_PAD_LEFT );
    }

    /**
     * Generates a complete FIX message string.
     *
     * @param string                $msgType The MsgType (tag 35).
     * @param array<string, string> $fields  An associative array of FIX tags and their values (excluding header and trailer).
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
            '98', //
            // Add other standard header fields here if needed, in their numerical order
        ];

        $headerFields = [];
        foreach ( $standardHeaderOrder as $tag ):
            // Populate header fields from class properties
            switch ( $tag ):
                case '35':
                    $headerFields[ $tag ] = $msgType;
                    break;
                case '34':
                    $headerFields[ $tag ] = (string)$this->nextOutgoingMsgSeqNum;
                    break;
                case '49':
                    $headerFields[ $tag ] = $this->senderCompID;
                    break;
                case '52':
                    $headerFields[ $tag ] = gmdate( 'Ymd-H:i:s.v' );
                    break; // UTC timestamp
                case '56':
                    $headerFields[ $tag ] = $this->targetCompID;
                    break;
                case '98':
                    $headerFields[ $tag ] = '0';
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

        $message = $this->generateFixMessage( '0', $heartbeatFields );
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

        $message = $this->generateFixMessage( '1', $testRequestFields );

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
            echo "Disconnected from FIX server.\n";
        endif;
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
        $timeout = $this->heartBtInt - (time() - $this->lastSentActivity);
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
}