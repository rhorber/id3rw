<?php

/**
 * Class TxxxFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing "TXXX" (User defined text information) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class TxxxFrame extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    EncodingInterface
     */
    public $encoding = null;

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
        $content  = substr($rawContent, 1);
        $strings  = Helpers::splitString($encoding->getDelimiter(), $content, 2);

        $this->encoding    = $encoding;
        $this->description = $strings[0];
        $this->value       = $strings[1];
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
        $encoded = $this->convertToInternal($this->description, $this->encoding);

        return $this->frameId."-".$encoded;
    }
}


// Útƒ-8 encoded
