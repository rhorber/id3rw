<?php

/**
 * Class Reader.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 21.10.2018
 */
namespace Rhorber\ID3rw;


/**
 * Class for reading the ID3 tag (and its frames) of a file (currently only v2.4.0).
 *
 * - {@link __construct}: Opens the passed file and reads the tag (with its frames).
 * - {@link getFrames}: Returns the parsed frames.
 * - {@link getTagSize}: Returns the total size of the tag (excluding header, including padding).
 * - {@link _parseHeader}: Parses the ID3 header.
 * - {@link _parseFrames}: Parses the ID3 frames.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 21.10.2018
 */
class Reader
{
    /**
     * Handle of the source file.
     *
     * @access private
     * @var    resource
     */
    private $_fileHandle = null;

    /**
     * Major version of the source file (4).
     *
     * @access private
     * @var    integer
     */
    private $_version = 0;

    /**
     * Total size of the tag (excluding header, including padding).
     *
     * @access private
     * @var    integer
     */
    private $_tagSize = 0;

    /**
     * Parsed frames of the tag.
     *
     * @access private
     * @var    array[]
     */
    private $_frames = [];


    /**
     * Constructor: Opens the passed file and reads the tag (with its frames).
     *
     * @param string $filename Name of the file to read (optionally with path).
     *
     * @throws  \InvalidArgumentException If the file could not be opened.
     * @throws  \UnexpectedValueException If the file does not contain a valid ID3 tag.
     * @throws  \UnexpectedValueException If the tag does contain unsupported features (wrong version, flags).
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public function __construct(string $filename)
    {
        $this->_fileHandle = @fopen($filename, "rb");
        if ($this->_fileHandle === false) {
            throw new \InvalidArgumentException("File could not be opened!");
        }
        // declare(encoding="UTF-8");

        $this->_parseHeader();
        $this->_parseFrames();

        fclose($this->_fileHandle);
    }

    /**
     * Returns the parsed frames.
     *
     * Format:
     * <code>
     * 'identifier' => [
     *   'identifier' => 'Four character identifier of the frame',
     *   'content' => 'Text frames only, parsed content',
     *   'encoding' => 'Text frames only, text encoding of content',
     *   'raw' => 'RAW content of the frame (binary)',
     * ]
     * </code>
     *
     * @return  array[]
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public function getFrames(): array
    {
        return $this->_frames;
    }

    /**
     * Returns the total size of the tag (excluding header, including padding).
     *
     * @return  integer
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public function getTagSize(): int
    {
        return $this->_tagSize;
    }

    /**
     * Parses the ID3 header.
     *
     * @return  void
     * @throws  \UnexpectedValueException If the file does not contain a valid ID3 tag.
     * @throws  \UnexpectedValueException If the tag does contain unsupported features (wrong version, flags).
     * @access  private
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    private function _parseHeader()
    {
        $header = fread($this->_fileHandle, 10);
        if ($header === false || strlen($header) !== 10) {
            throw new \UnexpectedValueException("Could not read ID3 header!");
        }

        $identifier = substr($header, 0, 3);
        $version    = substr($header, 3, 2);
        $tagFlags   = substr($header, 5, 1);
        $tagSize    = substr($header, 6, 4);

        if ($identifier !== "ID3") {
            throw new \UnexpectedValueException("File does not contain ID3 header!");
        }

        // TODO: Support/Implement Version 2.3.0.
        if ($version === "\x04\x00") {
            $this->_version = 4;
//        } elseif ($version === "\x03\x00") {
//            $this->version = 3;
        } else {
            throw new \UnexpectedValueException("Unsupported version, got: ".bin2hex($version));
        }

        if ($tagFlags !== "\x00") {
            throw new \UnexpectedValueException("Unsupported header flags, got: ".$tagFlags);
        }

        $this->_tagSize = Helpers::removeSynchSafeBits($tagSize);
    }

    /**
     * Parses the ID3 frames.
     *
     * @return  void
     * @throws  \UnexpectedValueException If the file does not contain a valid ID3 tag.
     * @throws  \UnexpectedValueException If the tag does contain unsupported features (flags, encoding, BOM).
     * @access  private
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    private function _parseFrames()
    {
        $frames = fread($this->_fileHandle, $this->_tagSize);

        $stringToParse = $frames;
        while (strlen($stringToParse) > 0) {
            $header = substr($stringToParse, 0, 10);

            if (substr($header, 0, 1) === "\x00") {
                // Padding reached: End loop.
                $stringToParse = "";
                continue;
            }

            $identifier = substr($header, 0, 4);
            $frameSize  = substr($header, 4, 4);
            $frameFlags = substr($header, 8, 2);

            if ($frameFlags !== "\x00\x00") {
                throw new \UnexpectedValueException("Unsupported frame flags, got: ".bin2hex($frameFlags));
            }

            if ($this->_version === 4) {
                $frameSize = Helpers::removeSynchSafeBits($frameSize);
            } else {
                $frameSize = hexdec(bin2hex($frameSize));
            }

            $rawContent = substr($stringToParse, 10, $frameSize);

            // TODO: Improve/Add parsing of other frames than "text".
            // TODO: Text frames: Support multiple strings (v2.4.0).
            if ($identifier{0} === "T") {
                $encodingCode = substr($rawContent, 0, 1);
                $content      = substr($rawContent, 1);

                switch ($encodingCode) {
                    case "\x00":
                        $encodingName = "ISO-8859-1";
//                        $delimiter    = "\x00";
                        break;

                    case "\x01":
                        $bom = substr($content, 0, 2);

                        if ($bom === "\xff\xfe") {
                            $encodingName = "UTF-16LE";
                        } elseif ($bom === "\xfe\xff") {
                            $encodingName = "UTF-16BE";
                        } else {
                            throw new \UnexpectedValueException("Invalid BOM, got: ".bin2hex($bom));
                        }

                        $content = substr($content, 2);
//                        $delimiter = "\x00\x00";
                        break;

                    case "\x02":
                        $encodingName = "UTF-16BE";
//                        $delimiter    = "\x00\x00";
                        break;

                    case "\x03":
                        $encodingName = "UTF-8";
//                        $delimiter    = "\x00";
                        break;


                    default:
                        throw new \UnexpectedValueException("Invalid text encoding, got: ".bin2hex($encodingCode));
                }
                /*
                $delimiterLength = (-1) * strlen($delimiter);
                while (substr($content, $delimiterLength) === $delimiter) {
                    $content = substr($content, 0, $delimiterLength);
                }
                */
                $encoding = $encodingName;
            } else {
                $content  = null;
                $encoding = null;
            }

            $this->_frames[$identifier] = [
                'identifier' => $identifier,
                'content'    => $content,
                'encoding'   => $encoding,
                'raw'        => $rawContent,
            ];

            // Frame header + frame size.
            $totalFrameSize = 10 + $frameSize;
            $stringToParse  = substr($stringToParse, $totalFrameSize);
        }
    }
}


// Útƒ-8 encoded
