<?php

/**
 * Class EncodingFactory.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Class for returning the encoding singletons.
 *
 * @author  Raphael Horber
 * @version 01.08.2019
 */
class EncodingFactory
{
    /**
     * Contains the created encoding instances.
     *
     * @access private
     * @var    array
     */
    private static $_encodings = [];


    /**
     * Returns the encoding 'ISO-8859-1'.
     *
     * @return EncodingInterface Encoding 'ISO-8859-1'.
     */
    public static function getIso88591(): EncodingInterface
    {
        if (self::$_encodings['ISO-8859-1'] === null) {
            self::$_encodings['ISO-8859-1'] = new Iso88591();
        }

        return self::$_encodings['ISO-8859-1'];
    }

    /**
     * Returns the encoding 'UTF-16'.
     *
     * @return EncodingInterface Encoding 'UTF-16'.
     */
    public static function getUtf16(): EncodingInterface
    {
        if (self::$_encodings['UTF-16'] === null) {
            self::$_encodings['UTF-16'] = new Utf16();
        }

        return self::$_encodings['UTF-16'];
    }

    /**
     * Returns the encoding 'UTF-16BE'.
     *
     * @return EncodingInterface Encoding 'UTF-16BE'.
     */
    public static function getUtf16BigEndian(): EncodingInterface
    {
        if (self::$_encodings['UTF-16BE'] === null) {
            self::$_encodings['UTF-16BE'] = new Utf16BigEndian();
        }

        return self::$_encodings['UTF-16BE'];
    }

    /**
     * Returns the encoding 'UTF-8'.
     *
     * @return EncodingInterface Encoding 'UTF-8'.
     */
    public static function getUtf8(): EncodingInterface
    {
        if (self::$_encodings['UTF-8'] === null) {
            self::$_encodings['UTF-8'] = new Utf8();
        }

        return self::$_encodings['UTF-8'];
    }
}


// Útƒ-8 encoded
