<?php

/**
 * Interface EncodingInterface.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Interface that defines operations to get encoding specific properties.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
interface EncodingInterface
{
    /**
     * Return the encoding's code (0x00, 0x01, ...).
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getCode(): string;

    /**
     * Return the encoding's name ('ISO-8859-1', 'UTF-16', ...).
     *
     * @return  string Code byte.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getName(): string;

    /**
     * Return the encoding's delimiter (0x00 or 0x0000).
     *
     * @return  string Delimiter byte(s).
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function getDelimiter(): string;

    /**
     * Return whether a BOM is used with this encoding or not.
     *
     * @return  boolean If a BOM is used with this encoding.
     * @access  public
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    public function hasBom(): bool;
}
