<?php

/**
 * Class EtcoFrame.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\FrameParser;


/**
 * Class for parsing "ETCO" (Event timing codes) frames.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
class EtcoFrame extends BaseFrameParser
{
    /**
     * Frame's "Time stamp format" value.
     *
     * @access public
     * @var    string
     */
    public $format = "";

    /**
     * Frame's event codes.
     *
     * <code>
     * $format = [
     *   'type of event' => "Time stamp",
     * ];
     * </code>
     *
     * @access public
     * @var    array
     */
    public $codes = [];


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

        $format  = $rawContent{0};
        $content = substr($rawContent, 1);

        $codes  = str_split($content, 5);
        $parsed = [];

        foreach ($codes as $code) {
            $type      = $code{0};
            $timestamp = hexdec(bin2hex(substr($code, 1)));

            $parsed[$type] = $timestamp;
        }

        $this->format = $format;
        $this->codes  = $parsed;
    }
}


// Útƒ-8 encoded
