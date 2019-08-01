<?php

/**
 * Class CommFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "COMM" (Comments) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class CommFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    EncodingInterface
     */
    public $encoding = null;

    /**
     * Frame's "Language" value.
     *
     * @access public
     * @var    string
     */
    public $language = "";

    /**
     * Frame's "Description".
     *
     * @access public
     * @var    string
     */
    public $description = "";

    /**
     * Frame's "actual text".
     *
     * @access public
     * @var    string
     */
    public $text = "";


    /**
     * Parses the frame according to spec.
     *
     * @param string $rawContent Content to parse (binary string).
     *
     * @return  void
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function parse(string $rawContent)
    {
        parent::parse($rawContent);

        $encoding = $this->tagParser->getEncoding($rawContent{0});
        $language = substr($rawContent, 1, 3);
        $content  = substr($rawContent, 4);
        $strings  = Helpers::splitString($encoding->getDelimiter(), $content, 2);

        $this->encoding    = $encoding;
        $this->language    = $language;
        $this->description = $strings[0];
        $this->text        = $strings[1];
    }

    /**
     * Returns the unique/array key of this frame for storing in the array (handles frame's multiplicity).
     *
     * @return  string Frame's unique/array key.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getArrayKey(): string
    {
        $encoded = mb_convert_encoding($this->description, mb_internal_encoding(), $this->encoding->getName());

        return $this->frameId."-".$this->language."-".$encoded;
    }
}


// Útƒ-8 encoded
