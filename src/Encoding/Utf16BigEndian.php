<?php

/**
 * Class Utf16BigEndian.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Implementation of EncodingInterface for 'UTF-16BE' (without BOM).
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class Utf16BigEndian implements EncodingInterface
{
    /**
     * Returns the encoding's code (0x02).
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getCode(): string
    {
        return "\x02";
    }

    /**
     * Returns the encoding's name ('UTF-16BE').
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getName(): string
    {
        return "UTF-16BE";
    }

    /**
     * Returns the encoding's delimiter (0x0000).
     *
     * @return  string Delimiter byte(s).
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getDelimiter(): string
    {
        return "\x00\x00";
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
