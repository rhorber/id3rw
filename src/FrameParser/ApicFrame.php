<?php

/**
 * Class ApicFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing APIC (Attached picture) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class ApicFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    string
     */
    public $encoding = "";

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
     * Constructor: Initializes the parser.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function __construct()
    {
        parent::__construct("APIC");
    }

    /**
     * Parses the frame according to spec.
     *
     * @param string $rawContent Content to parse (binary string).
     *
     * @return  void
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function parse(string $rawContent)
    {
        parent::parse($rawContent);

        $encoding  = Helpers::getEncoding2($rawContent{0});
        $content   = substr($rawContent, 1);
        $typeParts = Helpers::splitString("\x00", $content, 2);

        $this->encoding    = $encoding['encoding'];
        $this->mimeType    = $typeParts[0];
        $this->pictureType = $typeParts[1]{0};

        $string  = substr($typeParts[1], 1);
        $strings = Helpers::splitString($encoding['delimiter'], $string, 2);

        $this->description = $strings[0];
        $this->pictureData = $strings[1];

        if ($this->mimeType === "") {
            $this->mimeType = "image/";
        }

        // TODO: If MIME type is "-->", the picture data is a URL.
    }

    /**
     * Returns the unique/array key of this frame for storing in the array (handles frame's multiplicity).
     *
     * @return  string Frame's unique/array key.
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function getArrayKey(): string
    {
        $encoded = mb_convert_encoding($this->description, mb_internal_encoding(), $this->encoding);

        return $this->frameId."-".$encoded;
    }
}


// Útƒ-8 encoded
