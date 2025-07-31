<?php

namespace DPRMC\GLMXFixClient;

use DPRMC\GLMXFixClient\Exceptions\ParseException;
use Exception;

class FixMessageParser {
    const SOH = "\x01"; //[cite_start]// Standard delimiter for FIX fields [cite: 32]

    protected string $buffer = '';
    protected string $fixVersion;

    public function __construct( string $fixVersion = 'FIX.4.4' ) {
        $this->fixVersion = $fixVersion;
    }

    /**
     * Appends raw data received from the socket to the internal buffer.
     * @param string $data The raw data string.
     * @return void
     */
    public function appendData( string $data ): void {
        $this->buffer .= $data;
    }

    /**
     * Attempts to extract and parse the next complete FIX message from the buffer.
     * @return array|null An associative array of parsed FIX tags and values, or null if no complete message is found.
     * @throws ParseException If a message is malformed or checksum is invalid.
     */
    public function parseNextMessage(): ?array {
        // A minimal FIX message structure is 8=FIX.X.X<SOH>9=XXX<SOH>35=Y<SOH>...10=XXX<SOH>
        // We need at least the header (8, 9, 35) and checksum (10) to determine message boundaries.

        $soh               = self::SOH;
        $beginStringTag    = '8=' . $this->fixVersion . $soh;
        $beginStringLength = strlen( $beginStringTag );

        // 1. Find the start of a FIX message (BeginString 8)
        $beginStringPos = strpos( $this->buffer, $beginStringTag );

        if ( FALSE === $beginStringPos ):
            // No BeginString found yet, or partial. Clear buffer if it's junk before BeginString.
            // In a real system, you might want more sophisticated junk handling.
            $this->buffer = '';
            return NULL;
        endif;

        // If BeginString is not at the start, trim the buffer to remove leading junk.
        if ( $beginStringPos > 0 ):
            $this->buffer   = substr( $this->buffer, $beginStringPos );
            $beginStringPos = 0; // Reset position to 0 after trimming
        endif;

        // Now buffer should start with 8=FIX.X.X<SOH>
        // 2. Find BodyLength (tag 9)
        $bodyLengthTagPos = strpos( $this->buffer, '9=', $beginStringPos + $beginStringLength );
        if ( FALSE === $bodyLengthTagPos ) :
            // BodyLength tag not found yet in the current buffer segment
            return NULL;
        endif;

        $bodyLengthEndPos = strpos( $this->buffer, $soh, $bodyLengthTagPos );
        if ( FALSE === $bodyLengthEndPos ) :
            // BodyLength value is not complete (SOH not found after '9=')
            return NULL;
        endif;

        $bodyLengthStr = substr( $this->buffer, $bodyLengthTagPos + 2, $bodyLengthEndPos - ( $bodyLengthTagPos + 2 ) );
        $bodyLength    = (int)$bodyLengthStr;

        if ( ! is_numeric( $bodyLengthStr ) || $bodyLength < 0 ):
            // Malformed BodyLength. Discard current buffer segment to avoid infinite loop.
            $this->buffer = substr( $this->buffer, $bodyLengthEndPos + 1 );
            throw new ParseException( "Malformed BodyLength in FIX message." );
        endif;

        // The BodyLength (9) field itself is immediately followed by the MsgType (35) field.
        // The value of BodyLength (9) is the number of characters from the start of the MsgType (35) field
        //[cite_start]// up to and including the SOH delimiter immediately preceding the CheckSum (10) field. [cite: 36]
        $messageBodyStartPos = $bodyLengthEndPos + 1; // Position right after '9=XXX<SOH>'

        // 3. Check if we have enough data for the full message (including checksum)
        // Total message length = (length up to BodyLengthTagEndPos) + BodyLength + (length of "10=XXX<SOH>")
        // We need to account for the '10=XXX<SOH>' part (usually 3 chars for checksum + 3 for "10=" + 1 for SOH = 7 chars)
        // A minimum checksum "10=000<SOH>" is 7 characters.
        $minChecksumLength   = 7;
        $expectedTotalLength = $messageBodyStartPos + $bodyLength + $minChecksumLength;

        if ( strlen( $this->buffer ) < $expectedTotalLength ):
            // Not enough data in buffer to form a complete message.
            return NULL;
        endif;

        // Try to locate CheckSum (tag 10) to confirm message end
        $checkSumTagStartPos = $messageBodyStartPos + $bodyLength;
        $checkSumTagString   = substr( $this->buffer, $checkSumTagStartPos, 3 ); // Should be "10="

        if ( $checkSumTagString !== '10=' ):
            // Checksum tag not where it's expected. Message is malformed or not complete.
            // This is a common error point in partial message reception.
            // Discard the potentially problematic message part.
            $this->buffer = substr( $this->buffer, $checkSumTagStartPos + 1 ); // Move past what we thought was the end
            throw new ParseException( "Expected CheckSum tag '10=' not found at calculated position." );
        endif;

        $checkSumEndPos = strpos( $this->buffer, $soh, $checkSumTagStartPos );
        if ( FALSE === $checkSumEndPos ):
            // Checksum value is not complete (SOH not found after '10=')
            return NULL;
        endif;

        $fullMessageLength = $checkSumEndPos + 1;
        $rawMessage        = substr( $this->buffer, 0, $fullMessageLength );

        // 4. Validate CheckSum
        $checkSumValue      = substr( $rawMessage, $checkSumTagStartPos + 3, $checkSumEndPos - ( $checkSumTagStartPos + 3 ) );
        $messageForChecksum = substr( $rawMessage, 0, $checkSumTagStartPos ); // All chars before '10='

        $calculatedCheckSum = $this->calculateCheckSum( $messageForChecksum );

        if ( $calculatedCheckSum !== $checkSumValue ) {
            // Checksum mismatch. Message is corrupted. Discard.
            $this->buffer = substr( $this->buffer, $fullMessageLength );
            throw new ParseException( "Checksum mismatch. Expected: {$calculatedCheckSum}, Received: {$checkSumValue}" );
        }

        // 5. Parse the complete message into an array
//        $parsedFields = $this->parseFixFields( $rawMessage );
        $parsedFields = self::parseFixFieldsFromRaw( $rawMessage );

        // Remove the processed message from the buffer
        $this->buffer = substr( $this->buffer, $fullMessageLength );

        return $parsedFields;
    }


    /**
     * Parses a complete raw FIX message string into an associative array of tags and values.
     * @param string $rawMessage The complete FIX message string.
     * @return array<string, string> An associative array where keys are FIX tags and values are their corresponding values.
     *
     * @note I made this public static because the logging classes might need this functionality.
     */
    public static function parseFixFieldsFromRaw( string $rawMessage ): array {
        $fields = explode( self::SOH, trim( $rawMessage ) ); // Trim to remove trailing SOH if present from explode

        $parsed = [];
        foreach ( $fields as $field ) {
            if ( empty( $field ) ) continue; // Skip empty strings if multiple SOHs or trailing SOH

            $parts = explode( '=', $field, 2 );
            if ( count( $parts ) === 2 ) {
                $tag            = $parts[ 0 ];
                $value          = $parts[ 1 ];
                $parsed[ $tag ] = $value;
            }
        }
        return $parsed;
    }

    /**
     * Calculates the CheckSum (tag 10) of a FIX message part.
     * This is duplicated from GLMXFixClient for self-containment, but ideally,
     * common FIX utility functions would be in a shared helper.
     * @param string $message The part of the message to calculate checksum for.
     * @return string Three-character checksum, 0-padded if necessary.
     */
    protected function calculateCheckSum( string $message ): string {
        $sum = 0;
        for ( $i = 0; $i < strlen( $message ); $i++ ) {
            $sum += ord( $message[ $i ] );
        }
        return str_pad( (string)( $sum % 256 ), 3, '0', STR_PAD_LEFT );
    }
}