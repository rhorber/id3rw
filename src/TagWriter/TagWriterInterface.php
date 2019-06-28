<?php

/**
 * Interface TagWriterInterface.
 *
 * @author  Raphael Horber
 * @version 28.06.2019
 */

namespace Rhorber\ID3rw\TagWriter;


/**
 * Interface that defines writing operations which differ between versions.
 *
 * @author Raphael Horber
 * @version 28.06.2019
 */
interface TagWriterInterface
{
    /**
     * Return version bytes (0x0300 or 0x0400).
     *
     * @return  string Version bytes.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getVersion(): string;

    /**
     * Return frame size as binary string.
     *
     * @param integer $frameSize Frame size to process.
     *
     * @return  string Frame size (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getFrameSize(int $frameSize): string;

    /**
     * Return the supported text encodings (as names).
     *
     * @return  string[] Names of the supported text encodings.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getSupportedTextEncodings(): array;

    /**
     * Determine and return the encoding code according to the encoding name.
     *
     * <code>
     * $resultArray = [
     *   'code' => "Code of the encoding",
     *   'bom'  => "BOM specific to encoding (nothing, \xfe\xff, or \xff\xfe)",
     * ]
     * </code>
     *
     * @param string $encodingName Encoding name to process.
     *
     * @return  array                     Result array with information about the encoding.
     * @throws  \UnexpectedValueException If the found encoding name is invalid.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getEncoding(string $encodingName): array;
}
