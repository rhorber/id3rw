<?php

/**
 * Class TextInformationFrames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing Text information frames (T000 - TZZZ).
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class TextInformationFrames extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    string
     */
    public $encoding = "";

    /**
     * Frame's "Information".
     *
     * @access public
     * @var    string
     */
    public $information = "";


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
        $strings  = Helpers::splitString($encoding['delimiter'], $content);

        if (count($strings) === 1) {
            $strings = $strings[0];
        }

        if (in_array($this->frameId, ["TMCL", "TIPL"]) === true) {
            $map = [];
            while (count($strings) > 0) {
                $key   = array_shift($strings);
                $value = array_shift($strings);

                $map[$key] = $value;
            }
            $strings = $map;
        }

        $this->encoding    = $encoding['encoding'];
        $this->information = $strings;
    }
}


// Útƒ-8 encoded
