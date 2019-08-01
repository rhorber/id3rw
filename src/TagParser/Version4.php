<?php

/**
 * Class Version4.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\TagParser;

use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\Helpers;


/**
 * Implementation of TagParserInterface for Version 2.4.0.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class Version4 implements TagParserInterface
{
    /**
     * Returns major version of this parser (4).
     *
     * @return  integer Major version (4).
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getMajorVersion(): int
    {
        return 4;
    }

    /**
     * Calculates and returns frame size from binary string.
     *
     * @param string $frameSize Frame size to process (binary string).
     *
     * @return  integer Calculated frame size.
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getFrameSize(string $frameSize): int
    {
        return Helpers::removeSynchSafeBits($frameSize);
    }

    /**
     * Determine and return the encoding according to the encoding code.
     *
     * @param string $encodingCode Encoding code to process (binary string).
     *
     * @return  EncodingInterface Determined encoding.
     * @throws  \UnexpectedValueException If the found encoding code is invalid.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getEncoding(string $encodingCode): EncodingInterface
    {
        switch ($encodingCode) {
            case "\x00":
                return EncodingFactory::getIso88591();

            case "\x01":
                return EncodingFactory::getUtf16();

            case "\x02":
                return EncodingFactory::getUtf16BigEndian();

            case "\x03":
                return EncodingFactory::getUtf8();


            default:
                throw new \UnexpectedValueException("Invalid text encoding, got: ".bin2hex($encodingCode));
        }
    }
}


// Útƒ-8 encoded
