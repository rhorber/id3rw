<?php

/**
 * Class PcntFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "PCNT" (Play counter) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
class PcntFrame extends BaseFrameParser
{
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

        $this->counter = hexdec(bin2hex($rawContent));
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
        $counter = Helpers::dec2bin($this->counter, 8);

        return $counter;
    }
}


// Útƒ-8 encoded
