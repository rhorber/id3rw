<?php

/**
 * Class PrivFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing PRIV (Private) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class PrivFrame extends BaseFrameParser
{
    /**
     * Number of parsed "PRIV" frames.
     *
     * @access private
     * @var    integer
     */
    private static $_counter = 0;

    /**
     * Frame's "Owner identifier".
     *
     * @access public
     * @var    string
     */
    public $owner = "";

    /**
     * Frame's "Private data".
     *
     * @access public
     * @var    string
     */
    public $privateData = "";

    /**
     * Frame's array key (frame ID, dash, and current counter value).
     *
     * @access private
     * @var    string
     */
    private $_arrayKey = "";


    /**
     * Constructor: Initializes the parser.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function __construct()
    {
        parent::__construct("PRIV");
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

        $strings = Helpers::splitString("\x00", $rawContent, 2);
        self::$_counter++;

        $this->owner       = $strings[0];
        $this->privateData = $strings[1];
        $this->_arrayKey   = "PRIV-".self::$_counter;
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
}


// Útƒ-8 encoded
