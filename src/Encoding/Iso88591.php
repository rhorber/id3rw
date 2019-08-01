<?php

/**
 * Class Iso88591.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Implementation of EncodingInterface for 'ISO-8859-1'.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class Iso88591 implements EncodingInterface
{
    /**
     * Returns the encoding's code (0x00).
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getCode(): string
    {
        return "\x00";
    }

    /**
     * Returns the encoding's name ('ISO-8859-1').
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getName(): string
    {
        return "ISO-8859-1";
    }

    /**
     * Returns the encoding's delimiter (0x00).
     *
     * @return  string Delimiter byte(s).
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getDelimiter(): string
    {
        return "\x00";
    }

    /**
     * Returns whether a BOM is used with this encoding or not.
     *
     * @return  false
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function hasBom(): bool
    {
        return false;
    }
}
