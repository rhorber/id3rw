<?php

/**
 * Class TextInformationFrames.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\Helpers;


/**
 * Class for parsing Text information frames (T000 - TZZZ).
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
class TextInformationFrames extends BaseFrameParser
{
    /**
     * Frame's "Text encoding" value.
     *
     * @access public
     * @var    EncodingInterface
     */
    public $encoding = null;

    /**
     * Frame's "Information".
     *
     * @access public
     * @var    string|string[]
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
     * @version 31.08.2019
     */
    public function parse(string $rawContent)
    {
        parent::parse($rawContent);

        $encoding = $this->tagParser->getEncoding($rawContent{0});
        $content  = substr($rawContent, 1);
        $strings  = Helpers::splitString($encoding->getDelimiter(), $content);

        if ($this->tagParser->getMajorVersion() === 4) {
            $strings = $this->_parseStringsVersion4($strings);
        } elseif ($this->tagParser->getMajorVersion() === 3) {
            $strings = $strings[0];
        }

        $this->encoding    = $encoding;
        $this->information = $strings;
    }

    /**
     * Builds and returns the binary string of the frame, for writing into a file.
     *
     * @return  string Frame's content (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    public function build(): string
    {
        $information = "";

        if (is_string($this->information) === true) {
            $this->verifyBom($this->encoding, $this->information);
            $information = $this->information;
        } elseif ($this->tagParser->getMajorVersion() === 4) {
            $information = $this->_buildStringsVersion4($this->information);
        } elseif ($this->tagParser->getMajorVersion() === 3) {
            $this->verifyBom($this->encoding, $this->information[0]);
            $information = $this->information[0];
        }

        $frame = $this->encoding->getCode();
        $frame .= $information;

        return $frame;
    }

    /**
     * Processes the parsed string according to the Version 2.4.0 specification and returns the result.
     *
     * @param string[] $strings Parsed strings to process.
     *
     * @return  string|string[]
     * @access  private
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    private function _parseStringsVersion4(array $strings)
    {
        if (count($strings) === 1) {
            return $strings[0];
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

        return $strings;
    }

    /**
     * Builds the information segment according to the Version 2.4.0 specification and returns it.
     *
     * @param string[] $strings Information strings to process.
     * @return  string
     * @access  private
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    private function _buildStringsVersion4(array $strings): string
    {
        $delimiter = $this->encoding->getDelimiter();
        $elements  = [];

        if (in_array($this->frameId, ["TMCL", "TIPL"]) === true) {
            foreach ($strings as $key => $value) {
                $this->verifyBom($this->encoding, $key);
                $this->verifyBom($this->encoding, $value);

                $elements[] = $key.$delimiter.$value;
            }
        } else {
            foreach ($strings as $value) {
                $this->verifyBom($this->encoding, $value);

                $elements[] = $value;
            }
        }

        return implode($delimiter, $elements);
    }
}


// Útƒ-8 encoded
