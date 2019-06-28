<?php

/**
 * Class Version4.
 *
 * @author  Raphael Horber
 * @version 28.06.2019
 */
namespace Rhorber\ID3rw\TagWriter;

use Rhorber\ID3rw\Helpers;


/**
 * Implementation of TagWriterInterface for Version 2.4.0.
 *
 * @author  Raphael Horber
 * @version 28.06.2019
 */
class Version4 implements TagWriterInterface
{
    /**
     * Supported text encodings.
     *
     * @access private
     * @var    string[]
     */
    private $_supportedTextEncodings = ["ISO-8859-1", "UTF-16LE", "UTF-16BE", "UTF-8"];


    /**
     * Returns version bytes (0x0400).
     *
     * @return  string Version bytes.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getVersion(): string
    {
        return "\x04\x00";
    }

    /**
     * Returns frame size as binary string.
     *
     * @param integer $frameSize Frame size to process.
     *
     * @return  string Frame size (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getFrameSize(int $frameSize): string
    {
        return Helpers::addSynchSafeBits($frameSize);
    }

    /**
     * Returns the supported text encodings (as names).
     *
     * @return  string[] Names of the supported text encodings.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function getSupportedTextEncodings(): array
    {
        return $this->_supportedTextEncodings;
    }

    /**
     * Determines and returns the encoding code according to the encoding name.
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
    public function getEncoding(string $encodingName): array
    {
        switch ($encodingName) {
            case "ISO-8859-1":
                $code = "\x00";
                $bom  = "";
                break;

            case "UTF-16LE":
                $code = "\x01";
                $bom  = "\xff\xfe";
                break;

            case "UTF-16BE":
                $code = "\x01";
                $bom  = "\xfe\xff";
                break;

            case "UTF-8":
                $code = "\x03";
                $bom  = "";
                break;


            default:
                throw new \UnexpectedValueException("Invalid text encoding, got: ".$encodingName);
        }

        return [
            'code' => $code,
            'bom'  => $bom,
        ];
    }
}


// Útƒ-8 encoded
