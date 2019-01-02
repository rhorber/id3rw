<?php

/**
 * Class SignFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;


/**
 * Class for parsing SIGN (Signature) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class SignFrame extends BaseFrameParser
{
    /**
     * Number of parsed "SIGN" frames.
     *
     * @access private
     * @var    integer
     */
    private static $_counter = 0;

    /**
     * Frame's "Group symbol" value.
     *
     * @access public
     * @var    string
     */
    public $groupSymbol = "";

    /**
     * Frame's "Signature".
     *
     * @access public
     * @var    string
     */
    public $signature = "";

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
        parent::__construct("SIGN");
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

        self::$_counter++;

        $this->groupSymbol = $rawContent{0};
        $this->signature   = substr($rawContent, 1);
        $this->_arrayKey   = "SIGN-".self::$_counter;
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
