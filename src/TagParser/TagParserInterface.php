<?php

/**
 * Interface TagParserInterface.
 *
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\TagParser;


/**
 * Interface that defines parsing operations which differ between versions.
 *
 * @author  Raphael Horber
 * @version 09.01.2019
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
     * Determine and return the encoding name according to the encoding code.
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
    public function getEncoding(string $encodingCode): array;
}


// Útƒ-8 encoded
