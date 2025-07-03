<?php

namespace DPRMC\GLMXFixClient;
class GLMXFixClient {


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
     * @return void
     */
    public function disconnect(): void {
        fclose( $this->socket );
    }


}