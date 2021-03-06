<?php

/**
 * Class ApicFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "APIC" (Attached picture) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
class ApicFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    EncodingInterface
     */
    public $encoding = null;

    /**
     * Frame's "MIME type" value.
     *
     * @access public
     * @var    string
     */
    public $mimeType = "";

    /**
     * Frame's "Picture type" value.
     *
     * @access public
     * @var    string
     */
    public $pictureType = "";

    /**
     * Frame's "Description".
     *
     * @access public
     * @var    string
     */
    public $description = "";

    /**
     * Frame's "Picture data".
     *
     * @access public
     * @var    string
     */
    public $pictureData = "";


    /**
     * Parses the frame according to spec.
     *
     * @param string $rawContent Content to parse (binary string).
     *
     * @return  void
     * @access  public
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    public function parse(string $rawContent)
    {
        parent::parse($rawContent);

        $encoding  = $this->tagParser->getEncoding($rawContent{0});
        $content   = substr($rawContent, 1);
        $typeParts = Helpers::splitString("\x00", $content, 2);

        $this->encoding    = $encoding;
        $this->mimeType    = $typeParts[0];
        $this->pictureType = $typeParts[1]{0};

        $string  = substr($typeParts[1], 1);
        $strings = Helpers::splitString($encoding->getDelimiter(), $string, 2);

        $this->description = $strings[0];
        $this->pictureData = $strings[1];

        $this->_handleDefaultValues();

        // TODO: If MIME type is "-->", the picture data is a URL.
    }

    /**
     * Returns the unique/array key of this frame for storing in the array (handles frame's multiplicity).
     *
     * @return  string Frame's unique/array key.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getArrayKey(): string
    {
        $encoded = $this->convertToInternal($this->description, $this->encoding);

        return $this->frameId."-".$encoded;
    }

    /**
     * Builds and returns the binary string of the frame, for writing into a file.
     *
     * @return  string Frame's content (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    public function build(): string
    {
        $this->verifyBom($this->encoding, $this->description);
        $this->_handleDefaultValues();

        $frame = $this->encoding->getCode();
        $frame .= $this->mimeType;
        $frame .= "\x00";
        $frame .= $this->pictureType;
        $frame .= $this->description;
        $frame .= $this->encoding->getDelimiter();
        $frame .= $this->pictureData;

        return $frame;
    }

    /**
     * Handles default values. If properties with a default value are empty, it will be set.
     *
     * @return  void
     * @access  private
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    private function _handleDefaultValues()
    {
        if ($this->mimeType === "") {
            $this->mimeType = "image/";
        }
    }
}


// Útƒ-8 encoded
