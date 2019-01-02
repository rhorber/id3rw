<?php

/**
 * Class UserFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing USER (Terms of use) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class UserFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    string
     */
    public $encoding = "";

    /**
     * Frame's "Language" value.
     *
     * @access public
     * @var    string
     */
    public $language = "";

    /**
     * Frame's "actual text".
     *
     * @access public
     * @var    string
     */
    public $text = "";


    /**
     * Constructor: Initializes the parser.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function __construct()
    {
        parent::__construct("USER");
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
        $language = substr($rawContent, 1, 3);
        $content  = substr($rawContent, 4);

        $this->encoding = $encoding['encoding'];
        $this->language = $language;
        $this->text     = $content;
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
        return $this->frameId."-".$this->language;
    }
}


// Útƒ-8 encoded
