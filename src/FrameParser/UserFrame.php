<?php

/**
 * Class UserFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;


/**
 * Class for parsing "USER" (Terms of use) frames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class UserFrame extends BaseFrameParser
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

        $this->encoding = $encoding;
        $this->language = $language;
        $this->text     = $content;
    }

    /**
     * Returns the unique/array key of this frame for storing in the array (handles frame's multiplicity).
     *
     * Version 2.4.0:
     * There may be more than one 'Terms of use' frame in a tag, but only one with the same 'Language'.
     * Version 2.3.0:
     * There may only be one "USER" frame in a tag.
     *
     * @return  string Frame's unique/array key.
     * @access  public
     * @author  Raphael Horber
     * @version 10.01.2019
     */
    public function getArrayKey(): string
    {
        if ($this->tagParser->getMajorVersion() === 4) {
            return $this->frameId."-".$this->language;
        } elseif ($this->tagParser->getMajorVersion() === 3) {
            return $this->frameId;
        }
    }
}


// Útƒ-8 encoded
