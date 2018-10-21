<?php

/**
 * Class Helpers.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 */
namespace Rhorber\ID3rw;


/**
 * Class containing helper methods.
 *
 * - {@link __construct}: ShortDescription.
 * - {@link method1}: ShortDescription.
 * - {@link method2}: ShortDescription.
 *
 * @package Rhorber\Templates
 * @author  Raphael Horber
 * @version 21.10.2018
 */
class Helpers
{
    /**
     * Returns the decimal value of a synch safe integer (as binary).
     *
     * @param string $value Synch safe integer as binary value.
     *
     * @return integer Decimal value of synch safe integer.
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
     * @return string Synch safe integer as HEX (zero padded to four bytes).
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
}


// Útƒ-8 encoded
