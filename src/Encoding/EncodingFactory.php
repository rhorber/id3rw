<?php

/**
 * Class EncodingFactory.
 *
 * @author  Raphael Horber
 * @version 05.08.2019
 */
namespace Rhorber\ID3rw\Encoding;


/**
 * Class for returning the encoding singletons.
 *
 * @author  Raphael Horber
 * @version 05.08.2019
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
     * @return  EncodingInterface Encoding 'ISO-8859-1'.
     * @access  public
     * @author  Raphael Horber
     * @version 05.08.2019
     */
    public static function getIso88591(): EncodingInterface
    {
        if (isset(self::$_encodings['ISO-8859-1']) === false) {
            self::$_encodings['ISO-8859-1'] = new Iso88591();
        }

        return self::$_encodings['ISO-8859-1'];
    }

    /**
     * Returns the encoding 'UTF-16'.
     *
     * @return  EncodingInterface Encoding 'UTF-16'.
     * @access  public
     * @author  Raphael Horber
     * @version 05.08.2019
     */
    public static function getUtf16(): EncodingInterface
    {
        if (isset(self::$_encodings['UTF-16']) === false) {
            self::$_encodings['UTF-16'] = new Utf16();
        }

        return self::$_encodings['UTF-16'];
    }

    /**
     * Returns the encoding 'UTF-16BE'.
     *
     * @return  EncodingInterface Encoding 'UTF-16BE'.
     * @access  public
     * @author  Raphael Horber
     * @version 05.08.2019
     */
    public static function getUtf16BigEndian(): EncodingInterface
    {
        if (isset(self::$_encodings['UTF-16BE']) === false) {
            self::$_encodings['UTF-16BE'] = new Utf16BigEndian();
        }

        return self::$_encodings['UTF-16BE'];
    }

    /**
     * Returns the encoding 'UTF-8'.
     *
     * @return  EncodingInterface Encoding 'UTF-8'.
     * @access  public
     * @author  Raphael Horber
     * @version 05.08.2019
     */
    public static function getUtf8(): EncodingInterface
    {
        if (isset(self::$_encodings['UTF-8']) === false) {
            self::$_encodings['UTF-8'] = new Utf8();
        }

        return self::$_encodings['UTF-8'];
    }
}


// Útƒ-8 encoded
