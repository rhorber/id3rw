<?php

/**
 * Class Utf8.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Implementation of EncodingInterface for 'UTF-8'.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class Utf8 implements EncodingInterface
{
    /**
     * Returns the encoding's code (0x03).
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getCode(): string
    {
        return "\x03";
    }

    /**
     * Returns the encoding's name ('UTF-8').
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getName(): string
    {
        return "UTF-8";
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
