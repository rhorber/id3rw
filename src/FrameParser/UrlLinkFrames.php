<?php

/**
 * Class UrlLinkFrames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing URL link frames (W000 - WZZZ).
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
class UrlLinkFrames extends BaseFrameParser
{
    /**
     * Number of parsed "WCOM" frames.
     *
     * @access private
     * @var    integer
     */
    private static $_wcomCounter = 0;

    /**
     * Number of parsed "WOAR" frames.
     *
     * @access private
     * @var    integer
     */
    private static $_woarCounter = 0;

    /**
     * Frame's "URL".
     *
     * @access public
     * @var    string
     */
    public $url = "";

    /**
     * Frame's array key (frame ID and depending on the frame type, a dash and the counter value is added).
     *
     * @access private
     * @var    string
     */
    private $_arrayKey = "";


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

        $strings = Helpers::splitString("\x00", $rawContent, 2);

        $this->_arrayKey = $this->frameId;
        $this->url       = $strings[0];

        if ($this->frameId === "WCOM") {
            self::$_wcomCounter++;
            $this->_arrayKey = "WCOM-".self::$_wcomCounter;
        }

        if ($this->frameId === "WOAR") {
            self::$_woarCounter++;
            $this->_arrayKey = "WOAR-".self::$_woarCounter;
        }
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
        return $this->_arrayKey;
    }

    /**
     * Builds and returns the binary string of the frame, for writing into a file.
     *
     * @return  string Frame's content (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 31.07.2019
     */
    public function build(): string
    {
        return $this->url;
    }
}


// Útƒ-8 encoded
