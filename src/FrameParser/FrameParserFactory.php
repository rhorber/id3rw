<?php

/**
 * Class FrameParserFactory.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class for creating a frame parser according to the identifier.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
class FrameParserFactory
{
    /**
     * Tag parser instance (for the source file's major version).
     *
     * @access private
     * @var    TagParserInterface
     */
    private $_tagParser;


    /**
     * Constructor: Initializes the factory.
     *
     * @param TagParserInterface $tagParser Tag parser instance.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function __construct(TagParserInterface $tagParser)
    {
        $this->_tagParser = $tagParser;
    }

    /**
     * Returns a frame parser to parse a frame with the passed identifier.
     *
     * @param string $frameId Identifier of the frame to parse.
     *
     * @return  BaseFrameParser
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function createParser(string $frameId)
    {
        $knownIdentifiers = [
            "UFID",
            "TXXX",
            "WXXX",
            "ETCO",
            "USLT",
            "COMM",
            "APIC",
            "PCNT",
            "POPM",
            // TODO: Version 2.3.0.
            "USER",
            "PRIV",
        ];

        if ($this->_tagParser->getMajorVersion() === 4) {
            $knownIdentifiers[] = "SIGN";
        }

        if (in_array($frameId, $knownIdentifiers)) {
            $ucFirst   = ucfirst(strtolower($frameId));
            $className = "\\Rhorber\\ID3rw\\FrameParser\\".$ucFirst."Frame";
            return new $className($this->_tagParser, $frameId);
        }

        $firstChar = $frameId{0};
        switch ($firstChar) {
            case "T":
                return new TextInformationFrames($this->_tagParser, $frameId);

            case "W":
                return new UrlLinkFrames($this->_tagParser, $frameId);

            case "X":
            case "Y":
            case "Z":
                // TODO: Add info: Experimental Frame.
                return new BaseFrameParser($this->_tagParser, $frameId);
        }

        if ($frameId === "MCDI") {
            // TODO: "MCDI" requires "TRCK" (add warning if missing).
            return new BaseFrameParser($this->_tagParser, $frameId);
        }

        return new BaseFrameParser($this->_tagParser, $frameId);
    }
}


// Útƒ-8 encoded
