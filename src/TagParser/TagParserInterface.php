<?php

/**
 * Interface TagParserInterface.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\TagParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;


/**
 * Interface that defines parsing operations which differ between versions.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
interface TagParserInterface
{
    /**
     * Return major version of the parser (3 or 4).
     *
     * @return  integer Major version (3 or 4).
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getMajorVersion(): int;

    /**
     * Calculate and return frame size from binary string.
     *
     * @param string $frameSize Frame size to process (binary string).
     *
     * @return  integer Calculated frame size.
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getFrameSize(string $frameSize): int;

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
    public function getEncoding(string $encodingCode): EncodingInterface;
}


// Útƒ-8 encoded
