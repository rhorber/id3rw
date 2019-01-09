<?php

/**
 * Class Version3.
 *
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\TagParser;


/**
 * Implementation of TagParserInterface for Version 2.3.0.
 *
 * @author  Raphael Horber
 * @version 09.01.2019
 */
class Version3 implements TagParserInterface
{
    /**
     * Returns major version of this parser (3).
     *
     * @return  integer Major version (3).
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getMajorVersion(): int
    {
        return 3;
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
        return hexdec(bin2hex($frameSize));
    }

    /**
     * Determines and returns the encoding name according to the encoding code.
     * <br /><br />
     * <code>
     * $resultArray = [
     *   'encoding'  => "Name of the encoding",
     *   'delimiter' => "Delimiter specific to encoding (\x00 or \x00\x00)",
     * ]
     * </code>
     *
     * @param string $encodingCode Encoding code to process (binary string).
     *
     * @return  array                     Result array with information about the encoding.
     * @throws  \UnexpectedValueException If the found encoding code is invalid.
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getEncoding(string $encodingCode): array
    {
        switch ($encodingCode) {
            case "\x00":
                $encoding  = "ISO-8859-1";
                $delimiter = "\x00";
                break;

            case "\x01":
                $encoding  = "UTF-16";
                $delimiter = "\x00\x00";
                break;


            default:
                throw new \UnexpectedValueException("Invalid text encoding, got: ".bin2hex($encodingCode));
        }

        return [
            'encoding'  => $encoding,
            'delimiter' => $delimiter,
        ];
    }
}


// Útƒ-8 encoded
