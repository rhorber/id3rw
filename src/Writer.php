<?php

/**
 * Class Writer.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 28.06.2019
 */
namespace Rhorber\ID3rw;

use Rhorber\ID3rw\TagWriter\TagWriterInterface;


/**
 * Class for writing an ID3 tag (and its frames) to a file (currently only v2.3.0 and v2.4.0).
 *
 * - {@link __construct}: Initialises the writer (opens target file handle).
 * - {@link writeNewFile}: Writes a new file with the passed frames as ID3 tag.
 * - {@link _parseFrames}: Parses and validates the passed frames.
 * - {@link _calculateTagSize}: Calculates the resulting tag size.
 * - {@link _writeHeader}: Writes the ID3 header to the target file.
 * - {@link _writeFrames}: Writes the parsed ID3 frames to the target file.
 * - {@link _writePadding}: Writes the calculated padding, if configured.
 * - {@link _writeFileContent}: Writes the content from the source file to the target file.
 *
 * @package Rhorber\ID3rw
 * @author  Raphael Horber
 * @version 28.06.2019
 */
class Writer
{
    /**
     * Tag writer instance (for the target file's major version).
     *
     * @access private
     * @var    TagWriterInterface
     */
    private $_tagWriter = null;

    /**
     * Handle of the target file.
     *
     * @access private
     * @var    resource
     */
    private $_targetHandle = null;

    /**
     * Total size of the tag (excluding header, including padding).
     *
     * @access private
     * @var    integer
     */
    private $_tagSize = 0;

    /**
     * Frames to write.
     *
     * @access private
     * @var    array[]
     */
    private $_frames = [];

    /**
     * Minimal total tag size (including header, including padding).
     *
     * @access private
     * @var    integer
     */
    private $_minTotalTagSize = null;

    /**
     * Calculated padding size.
     *
     * @access private
     * @var    integer
     */
    private $_paddingSize = 0;


    /**
     * Constructor: Initialises the writer (opens target file handle).
     *
     * @param integer $version Major version to use for the target file.
     * @param integer|null $minTotalTagSize Minimal total size of the tag, including header and optional padding.
     *                                      If the passed frames do not need that much space, a padding will be added.
     *                                      Pass `null` to write no padding.
     *
     * @throws  \UnexpectedValueException If an unsupported major version is requested.
     * @access  public
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    public function __construct(int $version = 4, int $minTotalTagSize = null)
    {
        if ($version === 4) {
            $this->_tagWriter = new TagWriter\Version4();
        } elseif ($version === 3) {
            $this->_tagWriter = new TagWriter\Version3();
        } else {
            throw new \UnexpectedValueException("Unsupported version, got: ".$version);
        }

        $this->_minTotalTagSize = $minTotalTagSize;
    }

    /**
     * Writes a new file with the passed frames as ID3 tag.
     *
     * Format of $frames:
     * <code>
     * 'identifier (optional)' => [
     *   'identifier' => 'Four character identifier of the frame, if not set as key mandatory',
     *   'content' => 'Text frames only, content to write',
     *   'encoding' => 'Optional for text frames, encoding to use for writing',
     *   'raw' => 'Required for non-text frames, RAW content to write (binary)',
     * ]
     * </code>
     *
     * @param array[] $frames Frames to write (modified frames from {@link Reader}).
     * @param string $targetFilename Name of the file to write (optionally with path).
     * @param string $sourceFilename Name of the content source file
     *                               (content after its ID3 tag will be used as content for the target file).
     *
     * @return  void
     * @throws  \InvalidArgumentException If the target file handle could not be opened.
     * @throws  \UnexpectedValueException If a frame requests an unsupported encoding.
     * @access  public
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    public function writeNewFile(array $frames, string $targetFilename, string $sourceFilename)
    {
        $this->_targetHandle = @fopen($targetFilename, "wb");
        if ($this->_targetHandle === false) {
            throw new \InvalidArgumentException("Target file could not be opened!");
        }

        $this->_parseFrames($frames);
        $this->_calculateTagSize();

        $this->_writeHeader();
        $this->_writeFrames();
        $this->_writePadding();

        $this->_writeFileContent($sourceFilename);

        fclose($this->_targetHandle);
    }

    /**
     * Parses and validates the passed frames.
     *
     * @param array[] $frames Frames to parse/validate.
     *
     * @return  void
     * @throws  \UnexpectedValueException If a frame requests an unsupported encoding.
     * @access  private
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    private function _parseFrames(array $frames)
    {
        // TODO: Throw exceptions/errors instead of continue! (best would be, to display all at once)
        foreach ($frames as $identifier => $frame) {
            if (isset($frame['identifier']) === true) {
                $identifier = $frame['identifier'];
            }

            if (strlen($identifier) !== 4) {
                continue;
            }

            // Text frames must have content, as default encoding UTF-16LE will be used.
            // Non-Text frames must have raw element.
            if ($identifier{0} === "T") {
                if (isset($frame['content']) === false) {
                    continue;
                }

                if (isset($frame['encoding']) === false || $frame['encoding'] === null) {
                    $frame['encoding'] = "UTF-16LE";
                    $frame['content']  = mb_convert_encoding($frame['content'], "UTF-16LE");
                } elseif (in_array($frame['encoding'], $this->_tagWriter->getSupportedTextEncodings()) === false) {
                    throw new \UnexpectedValueException("Invalid text encoding, got: ".$frame['encoding']);
                }
            } else {
                if (isset($frame['raw']) === false) {
                    continue;
                }
            }

            $this->_frames[$identifier] = $frame;
        }
    }

    /**
     * Calculates the resulting tag size.
     *
     * @return  void
     * @throws  \UnexpectedValueException If a frame requests an unsupported encoding.
     * @access  private
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    private function _calculateTagSize()
    {
        foreach ($this->_frames as $identifier => $frame) {
            if ($identifier{0} === "T") {
                $encoding = $frame['encoding'];
                $content  = $frame['content'];

                switch ($encoding) {
                    case "UTF-16LE":
                    case "UTF-16BE":
                        // Encoding, BOM, Delimiter; 2 Bytes per character.
                        $header = 3;
                        $factor = 2;
//                        $delimiter = "\x00\x00";
                        break;

                    case "ISO-8859-1":
                    case "UTF-8":
                        // Encoding, no BOM, Delimiter; 1 Byte per character.
                        $header = 1;
                        $factor = 1;
//                        $delimiter = "\x00";
                        break;


                    default:
                        throw new \UnexpectedValueException("Invalid text encoding, got: ".$encoding);
                }

                $frameSize = $header + $factor * mb_strlen($content, $encoding);
            } else {
                $frameSize = strlen($frame['raw']);
            }

            $this->_frames[$identifier]['size'] = $frameSize;

            // Frame-Header.
            $this->_tagSize += 10;
            // Frame-Content.
            $this->_tagSize += $frameSize;
        }

        if ($this->_minTotalTagSize !== null) {
            $paddingSize = $this->_minTotalTagSize - $this->_tagSize - 10;

            if ($paddingSize > 0) {
                $this->_paddingSize = $paddingSize;
                $this->_tagSize     = ($this->_minTotalTagSize - 10);
            }
        }
    }

    /**
     * Writes the ID3 header to the target file.
     *
     * @return  void
     * @access  private
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    private function _writeHeader()
    {
        // Identifier.
        fwrite($this->_targetHandle, "ID3");

        // Version.
        fwrite($this->_targetHandle, $this->_tagWriter->getVersion());

        // Flags.
        fwrite($this->_targetHandle, "\x00");

        // Tag-Size.
        $sizeSynchSafe = Helpers::addSynchSafeBits($this->_tagSize);
        fwrite($this->_targetHandle, $sizeSynchSafe);
    }

    /**
     * Writes the parsed ID3 frames to the target file.
     *
     * @return  void
     * @throws  \UnexpectedValueException If a frame requests an unsupported encoding.
     * @access  private
     * @author  Raphael Horber
     * @version 28.06.2019
     */
    private function _writeFrames()
    {
        foreach ($this->_frames as $identifier => $frame) {
            // Identifier.
            fwrite($this->_targetHandle, $identifier);

            // Size.
            fwrite($this->_targetHandle, $this->_tagWriter->getFrameSize($frame['size']));

            // Flags.
            fwrite($this->_targetHandle, "\x00\x00");

            // Content.
            if ($identifier{0} === "T") {
                $encoding = $this->_tagWriter->getEncoding($frame['encoding']);

                $code = $encoding['code'];
                $bom  = $encoding['bom'];

                // TODO: Support multiple strings per frame (v2.4.0).
                $content = $frame['content'];
//                fwrite($this->targetHandle, $code.$bom.$content.$delimiter);
                fwrite($this->_targetHandle, $code.$bom.$content);
            } else {
                fwrite($this->_targetHandle, $frame['raw']);
            }
        }
    }

    /**
     * Writes the calculated padding, if configured.
     *
     * @return  void
     * @access  private
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    private function _writePadding()
    {
        if ($this->_paddingSize <= 0) {
            return;
        }

        $padding = str_repeat("\x00", $this->_paddingSize);
        fwrite($this->_targetHandle, $padding);
    }

    /**
     * Writes the content from the source file to the target file.
     *
     * @param string $sourceFilename Name of the content source file.
     *
     * @return  void
     * @access  private
     * @author  Raphael Horber
     * @version 21.10.2018
     */
    private function _writeFileContent(string $sourceFilename)
    {
        $sourceFileReader = new Reader($sourceFilename);
        $sourceTagSize    = $sourceFileReader->getTagSize();

        $sourceHandle = @fopen($sourceFilename, "rb");
        if ($sourceHandle === false) {
            throw new \InvalidArgumentException("Source file could not be opened!");
        }

        // Seek header + tag.
        $totalSize = 10 + $sourceTagSize;
        fseek($sourceHandle, $totalSize);

        while (feof($sourceHandle) === false) {
            $contents = fread($sourceHandle, 8192);
            fwrite($this->_targetHandle, $contents);
        }

        fclose($sourceHandle);
    }
}


// Útƒ-8 encoded
