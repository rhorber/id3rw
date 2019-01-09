<?php

/**
 * Class UfidFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "UFID" (Unique file identifier) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
class UfidFrame extends BaseFrameParser
{
    /**
     * Frame's "Owner identifier".
     *
     * @access public
     * @var    string
     */
    public $owner = "";

    /**
     * Frame's "Identifier" value.
     *
     * @access public
     * @var    string
     */
    public $identifier = "";


    /**
     * Parses the frame according to spec.
     *
     * @param string $rawContent Content to parse (binary string).
     *
     * @throws  \InvalidArgumentException If "Owner identifier" value is empty (or missing).
     * @return  void
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function parse(string $rawContent)
    {
        parent::parse($rawContent);

        $strings = Helpers::splitString("\x00", $rawContent, 2);

        $this->owner      = $strings[0];
        $this->identifier = $strings[1];

        if ($this->owner === "") {
            throw new \InvalidArgumentException("UFID frame: Owner MUST NOT be empty.");
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
        return $this->frameId."-".$this->owner;
    }
}


// Útƒ-8 encoded
