<?php

/**
 * Class Helpers.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 24.12.2018
 */
namespace Rhorber\ID3rw;


/**
 * Class containing helper methods.
 *
 * - {@link removeSynchSafeBits}: Returns the decimal value of a synch safe integer (as binary).
 * - {@link addSynchSafeBits}: Returns the synch safe HEX value (zero padded to four bytes) of a decimal value.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 24.12.2018
 */
class Helpers
{
    /**
     * Returns the decimal value of a synch safe integer (as binary).
     *
     * @param string $value Synch safe integer as binary value.
     *
     * @return  integer Decimal value of synch safe integer.
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public static function removeSynchSafeBits(string $value): int
    {
        $sizeHex = bin2hex($value);
        $binary  = base_convert($sizeHex, 16, 2);
        $padded  = sprintf("%032s", $binary);

        $bits = str_split($padded);
        unset($bits[0], $bits[8], $bits[16], $bits[24]);
        $withoutSynchBits = implode("", $bits);

        return bindec($withoutSynchBits);
    }

    /**
     * Returns the synch safe HEX value (zero padded to four bytes) of a decimal value.
     *
     * @param integer $value Value to get the synch safe integer of (as decimal integer).
     *
     * @return  string Synch safe integer as HEX (zero padded to four bytes).
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public static function addSynchSafeBits(int $value): string
    {
        $binary = decbin($value);
        $padded = sprintf("%028s", $binary);

        $parts         = str_split($padded, 7);
        $withSynchBits = "0".implode("0", $parts);

        $hexValue  = base_convert($withSynchBits, 2, 16);
        $paddedHex = sprintf("%08s", $hexValue);

        return hex2bin($paddedHex);
    }

    /**
     * Parses and returns the passed content for encoding and content.
     * <br /><br />
     * <code>
     * $resultArray = [
     *   'encoding' => "Name of the encoding",
     *   'delimiter' => "Delimiter specific to encoding (\x00 or \x00\x00)",
     *   'content' => "Content after encoding code (and eventual BOM),
     * ]
     * </code>
     *
     * @param string $rawContent RAW content to parse (binary string).
     *
     * @return  array Result array with information about the encoding.
     * @throws  \UnexpectedValueException If the found encoding code is invalid.
     * @throws  \UnexpectedValueException If code is \x01 and the found BOM is invalid.
     * @access  public
     * @author  Raphael Horber
     * @version 24.12.2018
     */
    public static function getEncoding(string $rawContent): array
    {
        $code    = substr($rawContent, 0, 1);
        $content = substr($rawContent, 1);

        switch ($code) {
            case "\x00":
                $encoding  = "ISO-8859-1";
                $delimiter = "\x00";
                break;

            case "\x01":
                $bom = substr($content, 0, 2);

                if ($bom === "\xff\xfe") {
                    $encoding = "UTF-16LE";
                } elseif ($bom === "\xfe\xff") {
                    $encoding = "UTF-16BE";
                } else {
                    throw new \UnexpectedValueException("Invalid BOM, got: ".bin2hex($bom));
                }

                $content   = substr($content, 2);
                $delimiter = "\x00\x00";
                break;

            case "\x02":
                $encoding  = "UTF-16BE";
                $delimiter = "\x00\x00";
                break;

            case "\x03":
                $encoding  = "UTF-8";
                $delimiter = "\x00";
                break;


            default:
                throw new \UnexpectedValueException("Invalid text encoding, got: ".bin2hex($code));
        }

        return [
            'encoding'  => $encoding,
            'delimiter' => $delimiter,
            'content'   => $content,
        ];
    }
}


// Útƒ-8 encoded
