<?php

namespace DPRMC\GLMXFixClient;
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

    protected array $config = [
        'ConnectionType'    => NULL,
        'SocketConnectHost' => NULL,
        'SocketConnectPort' => NULL,
        'TargetCompID'      => NULL,
        'SenderCompID'      => NULL,
        'HeartBtInt'        => NULL,
        'BeginString'       => NULL,
        'Password'          => NULL,
        'SocketUseSSL'      => NULL,
        'EnabledProtocols'  => NULL,
    ];
    /**
     * @var false|resource
     */
    protected $socket;

    protected int $nextOutgoingMsgSeqNum = 1;


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

        $this->config = [
            'ConnectionType'    => 'initiator',
            'SocketConnectHost' => $this->socketConnectHost,
            'SocketConnectPort' => $this->socketConnectPort,
            'TargetCompID'      => $this->targetCompID,
            'SenderCompID'      => $this->senderCompID,
            'HeartBtInt'        => $this->heartBtInt,
            'BeginString'       => $this->beginString,
            'Password'          => $this->password,
            'SocketUseSSL'      => $this->socketUseSSL,
            'EnabledProtocols'  => $this->enabledProtocols,
        ];

    }


    /**
     * @return void
     * @throws \Exception
     */
    public function connect(): void {
        echo "Attempting to connect to FIX server at {$this->config['SocketConnectHost']}:{$this->config['SocketConnectPort']}...\n";


        // 1. Build the target server address
        // Use 'tls://' prefix because SocketUseSSL is 'Y'
        $address = "tls://" . $this->config[ 'SocketConnectHost' ] . ":" . $this->config[ 'SocketConnectPort' ];

        // 2. Create a stream context with SSL/TLS options
        // This is crucial for establishing a secure connection.
        $contextOptions = [
            'ssl' => [
                // UPDATED: Re-enabled peer verification for security.
                // This ensures the certificate is valid and the hostname matches.
                'verify_peer'      => TRUE,
                'verify_peer_name' => TRUE,

                // You may need to specify a certificate authority (CA) bundle
                // if PHP cannot find the system's default.
                // 'cafile' => '/path/to/your/cacert.pem',

                // Per your requirements, force TLSv1.2
                // Note: This constant requires PHP 5.6+
                'crypto_method'    => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
            ],
        ];
        $streamContext  = stream_context_create( $contextOptions );

        // 3. Establish the socket connection
        // We use stream_socket_client which is a more generic and powerful function.
        // The timeout is set to 30 seconds.
        $this->socket = stream_socket_client(
            $address,
            $errno,      // Will contain the error number if connection fails
            $errstr,     // Will contain the error message if connection fails
            30,          // Connection timeout in seconds
            STREAM_CLIENT_CONNECT,
            $streamContext
        );

        // 4. Check if the connection was successful
        if ( $this->socket === FALSE ) {
            throw new \Exception( "Failed to connect: ($errno) $errstr" );
        }

        echo "Connection successful!\n";
        echo "Socket connected to: " . stream_socket_get_name( $this->socket, TRUE ) . "\n";
    }


    /**
     * Sends a raw message over the socket.
     * @param string $message The message to send.
     * @return int|false Number of bytes written or false on failure.
     */
    protected function sendRaw( string $message ): int|false {
        if ( !$this->socket ) {
            throw new \Exception( "Socket not connected." );
        }
        $bytesWritten = fwrite( $this->socket, $message );
        if ( $bytesWritten === false ) {
            throw new \Exception( "Failed to write to socket." );
        }
        echo "Sent: " . str_replace( self::SOH, '|', $message ) . "\n";
        return $bytesWritten;
    }

    /**
     * Reads raw data from the socket.
     * @param int $length The maximum number of bytes to read.
     * @return string|false The read data or false on error/EOF.
     */
    public function readRaw( int $length = 2048 ): string|false {
        if ( !$this->socket ) {
            throw new \Exception( "Socket not connected." );
        }
        // Set the socket to non-blocking mode for reading to avoid indefinite waits.
        stream_set_blocking($this->socket, false);
        $data = fread( $this->socket, $length );
        if ( $data !== false && $data !== '' ) {
            echo "Received: " . str_replace( self::SOH, '|', $data ) . "\n";
        }
        return $data;
    }

    /**
     * Calculates the BodyLength (tag 9) of a FIX message.
     * @param string $messageBody The part of the message from tag 35 up to (but not including) tag 10.
     * @return int
     */
    protected function calculateBodyLength( string $messageBody ): int {
        return strlen( $messageBody );
    }

    /**
     * Calculates the CheckSum (tag 10) of a FIX message.
     * @param string $message The entire FIX message string up to (but not including) the CheckSum field.
     * @return string Three-character checksum, 0-padded if necessary.
     */
    protected function calculateCheckSum( string $message ): string {
        $sum = 0;
        for ( $i = 0; $i < strlen( $message ); $i++ ) {
            $sum += ord( $message[ $i ] );
        }
        return str_pad( (string)( $sum % 256 ), 3, '0', STR_PAD_LEFT );
    }

    /**
     * Generates a complete FIX message string.
     * @param string $msgType The MsgType (tag 35).
     * @param array<string, string> $fields An associative array of FIX tags and their values (excluding header and trailer).
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

        // Combine all fields for the body length and checksum calculation
        $allFields = [];
        foreach ( $headerFields as $tag => $value ) {
            if ($tag != '8' && $tag != '9') { // Exclude BeginString and BodyLength from this part of assembly
                $allFields[] = $tag . '=' . $value;
            }
        }
        foreach ( $fields as $tag => $value ) {
            $allFields[] = $tag . '=' . $value;
        }

        $bodyContent = implode( self::SOH, $allFields ) . self::SOH;
        $bodyLength = $this->calculateBodyLength( $bodyContent ); // BodyLength is calculated *after* MsgType

        // Now assemble the full message including BodyLength
        $fullMessage = "8=" . $this->beginString . self::SOH;
        $fullMessage .= "9=" . $bodyLength . self::SOH;
        $fullMessage .= $bodyContent; // This includes MsgType, SenderCompID, etc.

        $checksum = $this->calculateCheckSum( $fullMessage ); // Checksum is calculated over entire message before it
        $fullMessage .= "10=" . $checksum . self::SOH;

        $this->nextOutgoingMsgSeqNum++; // Increment sequence number for next message

        return $fullMessage;
    }


    /**
     * Initiates a FIX session by sending a Logon (A) message.
     * @return void
     * @throws \Exception If the connection is not established or message sending fails.
     */
    public function login(): void {
        echo "Sending Logon (A) message...\n";

        // Fields specific to the Logon message
        $logonFields = [
            '108' => (string)$this->heartBtInt, // HeartBtInt
            '554' => $this->password,           // Password
        ];

        $message = $this->generateFixMessage( 'A', $logonFields );
        $this->sendRaw( $message );

        // In a real FIX client, you would now wait for a Logon (A) response from GLMX
        // and then a TestRequest (1) message, and send a Heartbeat (0) in return.
    }


    /**
     * Sends a Heartbeat (0) message to the GLMX server.
     * @param string|null $testReqId Optional. The TestReqID (112) to include if responding to a TestRequest.
     * @return void
     * @throws \Exception If the connection is not established or message sending fails.
     */
    public function sendHeartbeat(?string $testReqId = null): void
    {
        echo "Sending Heartbeat (0) message...\n";

        $heartbeatFields = [];
        if ($testReqId !== null) {
            $heartbeatFields['112'] = $testReqId; // TestReqID
        }

        $message = $this->generateFixMessage('0', $heartbeatFields); //[cite: 36]
        $this->sendRaw($message);
    }


    /**
     * @return void
     */
    public function disconnect(): void {
        if ($this->socket) {
            fclose( $this->socket );
            $this->socket = null; // Clear the socket resource
            echo "Disconnected from FIX server.\n";
        }
    }
}