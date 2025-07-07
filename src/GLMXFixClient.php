<?php

namespace DPRMC\GLMXFixClient;

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


        // DEBUG
        //$this->socketConnectHost = '98.245.102.80';
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
        if ( $this->socket === FALSE ) {
            throw new Exception( "Failed to connect: ($errno) $errstr" );
        }

        // 5. Set the stream to non-blocking mode
        if ( !stream_set_blocking( $this->socket, FALSE ) ) {
            fclose( $this->socket );
            throw new Exception( "Failed to set stream to non-blocking mode." );
        }

        echo "Connection successful! Stream socket obtained.\n";
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
     */
    protected function sendRaw( string $message ): int|false {
        if ( !$this->socket ) {
            throw new Exception( "Socket not connected." );
        }
        $bytesWritten = @fwrite( $this->socket, $message );
        if ( $bytesWritten === FALSE ) {
            throw new Exception( "Failed to write to socket." );
        }
        echo "Sent: " . str_replace( self::SOH, '|', $message ) . "\n";

        return $bytesWritten;
    }

    /**
     * Reads raw data from the socket.
     *
     * @param int $length The maximum number of bytes to read.
     *
     * @return string|false The read data or false on error/EOF.
     */
    public function readRaw( int $length = 2048 ): string|false {
        if ( !$this->socket ) {
            throw new Exception( "Socket not connected." );
        }
        $data = @fread( $this->socket, $length );
        if ( $data !== FALSE && $data !== '' ) {
            echo "Received RAW (ASCII): '" . str_replace( self::SOH, '[SOH]', $data ) . "'\n";
            echo "Received RAW (HEX):   '" . bin2hex( $data ) . "'\n";
        }
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
        for ( $i = 0; $i < strlen( $message ); $i++ ) {
            $sum += ord( $message[ $i ] );
        }
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
        $headerFields = [
            '8'  => $this->beginString, // BeginString
            '35' => $msgType,            // MsgType
            '49' => $this->senderCompID, // SenderCompID
            '56' => $this->targetCompID, // TargetCompID
            '34' => (string)$this->nextOutgoingMsgSeqNum, // MsgSeqNum
            '52' => gmdate( 'Ymd-H:i:s.v' ), // SendingTime (UTC timestamp)
        ];

        $allFields = [];
        foreach ( $headerFields as $tag => $value ) {
            if ( $tag != '8' && $tag != '9' ) {
                $allFields[] = $tag . '=' . $value;
            }
        }
        foreach ( $fields as $tag => $value ) {
            $allFields[] = $tag . '=' . $value;
        }

        $bodyContent = implode( self::SOH, $allFields ) . self::SOH;
        $bodyLength  = $this->calculateBodyLength( $bodyContent );

        $fullMessage = "8=" . $this->beginString . self::SOH;
        $fullMessage .= "9=" . $bodyLength . self::SOH;
        $fullMessage .= $bodyContent;

        $checksum    = $this->calculateCheckSum( $fullMessage );
        $fullMessage .= "10=" . $checksum . self::SOH;

        $this->nextOutgoingMsgSeqNum++;

        return $fullMessage;
    }


    /**
     * Initiates a FIX session by sending a Logon (A) message and waiting for confirmation.
     *
     * @return bool True if logon successful and session handshake complete, false otherwise.
     * @throws \Exception If connection is not established, message sending fails, or handshake times out/fails.
     */
    public function login(): bool {
        echo "Sending Logon (A) message...\n";

        $logonFields = [
            '108' => (string)$this->heartBtInt, // HeartBtInt
            '554' => $this->password,           // Password
        ];

        $message = $this->generateFixMessage( 'A', $logonFields );
        $this->sendRaw( $message );

        $logonAckReceived    = FALSE;
        $testRequestReceived = FALSE;
        $testReqId           = NULL;
        $startTime           = microtime( TRUE ); // Use microtime for more precise timeout checking
        $timeout             = 10;                // Max 10 seconds for login handshake

        echo "Waiting for Logon Acknowledge and TestRequest...\n";

        while ( microtime( TRUE ) - $startTime < $timeout && (!$logonAckReceived || !$testRequestReceived) ) {


            dump(stream_get_meta_data($this->getSocketResource()));
            $read_streams   = [ $this->socket ];
            $write_streams  = NULL;
            $except_streams = NULL;

            // Wait for activity on the stream, up to 1 second
            $num_changed_streams = @stream_select( $read_streams, $write_streams, $except_streams, 1 );

            if ( $num_changed_streams === FALSE ) {
                throw new Exception( "stream_select error during login." );
            }

            if ( $num_changed_streams > 0 && in_array( $this->socket, $read_streams ) ) {
                $rawData = $this->readRaw();
                if ( $rawData === FALSE ) { // false indicates an actual read error, not just no data
                    throw new Exception( "Connection read error during login handshake." );
                }
                if ( $rawData === '' ) { // empty string indicates no data immediately available in non-blocking mode
                    // This is expected, continue waiting
                    //echo "No raw data immediately available, continuing select.\n";
                    continue;
                }

                var_dump( $rawData);

                echo "Raw data received during login. Appending to parser...\n";
                $this->parser->appendData( $rawData );

                try {
                    while ( ($parsedMessage = $this->parser->parseNextMessage()) !== NULL ) {
                        echo "--- Message Parsed During Login Handshake ---\n";
                        print_r( $parsedMessage );

                        if ( isset( $parsedMessage[ '35' ] ) ) {
                            $msgType = $parsedMessage[ '35' ];
                            if ( $msgType === 'A' ) { // Logon Acknowledge
                                $logonAckReceived = TRUE;
                                echo "Logon Acknowledge received.\n";
                            } elseif ( $msgType === '1' ) { // TestRequest
                                $testRequestReceived = TRUE;
                                if ( isset( $parsedMessage[ '112' ] ) ) {
                                    $testReqId = $parsedMessage[ '112' ];
                                    echo "TestRequest received with ID: " . $testReqId . ".\n";
                                } else {
                                    echo "TestRequest received without ID.\n";
                                }
                            }
                        }
                        echo "-------------------------------------------\n";
                    }
                } catch ( Exception $e ) {
                    echo "Parsing error during login: " . $e->getMessage() . "\n";
                    echo "Current parser buffer content (HEX): " . bin2hex( $this->parser->getBuffer() ) . "\n"; // Get buffer content from parser for debugging
                    // It's crucial to debug FixMessageParser if this happens.
                }
            }

            // If TestRequest is received and we haven't responded yet
            if ( $logonAckReceived && $testRequestReceived && $testReqId !== NULL ) {
                echo "Logon handshake complete: Responding to TestRequest with Heartbeat...\n";
                $this->sendHeartbeat( $testReqId );
                return TRUE;
            }
        }

        // If we exit the loop without completing the handshake
        if ( !($logonAckReceived && $testRequestReceived) ) {
            $reason = '';
            if ( !$logonAckReceived ) $reason .= "No Logon Acknowledge received. ";
            if ( !$testRequestReceived ) $reason .= "No TestRequest received. ";
            throw new Exception( "Login handshake failed: " . $reason . "Timeout reached." );
        }

        return FALSE; // Should not reach here if successful
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
        if ( $testReqId !== NULL ) {
            $heartbeatFields[ '112' ] = $testReqId;
        }

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

        if ( $testReqId === NULL ) {
            $testReqId = 'TESTREQ-' . time() . '-' . uniqid();
        }

        $testRequestFields = [
            '112' => $testReqId,
        ];

        $message = $this->generateFixMessage( '1', $testRequestFields );
        $this->sendRaw( $message );

        return $testReqId;
    }


    /**
     * @return void
     */
    public function disconnect(): void {
        if ( $this->socket ) {
            fclose( $this->socket );
            $this->socket = null;
            echo "Disconnected from FIX server.\n";
        }
    }
}