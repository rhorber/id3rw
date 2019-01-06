<?php

/**
 * Class FrameParserFactory.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 06.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;


/**
 * Class for creating a frame parser according to the identifier.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 06.01.2019
 */
class FrameParserFactory
{
    /**
     * Returns a frame parser to parse a frame with the passed identifier.
     *
     * @param string $frameId Identifier of the frame to parse.
     *
     * @return BaseFrameParser
     */
    public static function createParser(string $frameId)
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
            "USER",
            "PRIV",
            "SIGN",
        ];
        if (in_array($frameId, $knownIdentifiers)) {
            $ucFirst   = ucfirst(strtolower($frameId));
            $className = "Rhorber\\ID3rw\\FrameParser\\".$ucFirst."Frame";
            return new $className();
        }

        $firstChar = $frameId{0};
        switch ($firstChar) {
            case "T":
                return new TextInformationFrames($frameId);

            case "W":
                return new UrlLinkFrames($frameId);

            case "X":
            case "Y":
            case "Z":
                // TODO: Add info: Experimental Frame.
                return new BaseFrameParser($frameId);
        }

        if ($frameId === "MCDI") {
            // TODO: MCDI requires TRCK (add warning if missing).
            return new BaseFrameParser($frameId);
        }

        return new BaseFrameParser($frameId);
    }
}


// Útƒ-8 encoded
