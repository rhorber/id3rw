<?php

/**
 * Class PopmFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "POPM" (Popularimeter) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
class PopmFrame extends BaseFrameParser
{
    /**
     * Frame's "Email to user" value.
     *
     * @access public
     * @var    string
     */
    public $email = "";

    /**
     * Frame's "Rating" value.
     *
     * @access public
     * @var    integer
     */
    public $rating = 0;

    /**
     * Frame's "Counter" value.
     *
     * @access public
     * @var    integer
     */
    public $counter = 0;


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

        $strings    = Helpers::splitString("\x00", $rawContent, 2);
        $popularity = $strings[1];

        $this->email   = $strings[0];
        $this->rating  = hexdec(bin2hex($popularity{0}));
        $this->counter = hexdec(bin2hex(substr($popularity, 1)));
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
        return $this->frameId."-".$this->email;
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
        $rating  = Helpers::dec2bin($this->rating);
        $counter = Helpers::dec2bin($this->counter, 8);

        return $this->email."\x00".$rating.$counter;
    }
}


// Útƒ-8 encoded
