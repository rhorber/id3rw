<?php

/**
 * Class TxxxFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing TXXX (User defined text information) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class TxxxFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    string
     */
    public $encoding = "";

    /**
     * Frame's "Description".
     *
     * @access public
     * @var    string
     */
    public $description = "";

    /**
     * Frame's "value".
     *
     * @access public
     * @var    string
     */
    public $value = "";


    /**
     * Constructor: Initializes the parser.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function __construct()
    {
        parent::__construct("TXXX");
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

        $encoding = Helpers::getEncoding2($rawContent{0});
        $content  = substr($rawContent, 1);
        $strings  = Helpers::splitString($encoding['delimiter'], $content, 2);

        $this->encoding    = $encoding['encoding'];
        $this->description = $strings[0];
        $this->value       = $strings[1];
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
