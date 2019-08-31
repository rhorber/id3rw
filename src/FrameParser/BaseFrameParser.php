<?php

/**
 * Class BaseFrameParser.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 * @todo    Class name is not accurate any more (with build).
 */
namespace Rhorber\ID3rw\FrameParser;

use Rhorber\ID3rw\Encoding\EncodingInterface;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Basic frame parser (saves raw content).
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
class BaseFrameParser
{
    /**
     * Tag parser instance.
     *
     * @access protected
     * @var    TagParserInterface
     */
    protected $tagParser;

    /**
     * Frame's "Frame ID".
     *
     * @access protected
     * @var    string
     */
    protected $frameId = "";

    /**
     * Frame's raw content (binary string).
     *
     * @access protected
     * @var    string
     */
    protected $rawContent = "";


    /**
     * Constructor: Initializes the parser.
     *
     * @param TagParserInterface $tagParser Tag parser instance.
     * @param string $frameId Frame ID of the frame to parse.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function __construct(TagParserInterface $tagParser, string $frameId)
    {
        $this->tagParser = $tagParser;
        $this->frameId   = $frameId;
    }

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
        $this->rawContent = $rawContent;
    }

    /**
     * Returns the unique/array key of this frame for storing in the array (handles frame's multiplicity).
     *
     * @return  string Frame's unique/array key.
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function getArrayKey(): string
    {
        return $this->frameId;
    }

    /**
     * Returns the frame's fields as an array.
     *
     * @return  array Frame's fields.
     * @access  public
     * @author  Raphael Horber
     * @version 09.01.2019
     */
    public function getFrameArray(): array
    {
        $result = [
            'frameId'    => $this->frameId,
            'rawContent' => $this->rawContent,
        ];

        try {
            $reflectionClass = new \ReflectionClass($this);
        } catch (\ReflectionException $e) {
            return $result;
        }

        $privateProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($privateProperties as $property) {
            $name  = $property->getName();
            $value = $property->getValue($this);

            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * Builds and returns the binary string of the frame, for writing into a file.
     *
     * @return  string Frame's content (binary string).
     * @access  public
     * @author  Raphael Horber
     * @version 31.07.2019
     */
    public function build(): string
    {
        return $this->rawContent;
    }

    /**
     * Converts the passed string into the internal encoding.
     *
     * @param string $string String to convert.
     * @param EncodingInterface $fromEncoding Encoding in which the string is encoded.
     *
     * @return  string The encoded string.
     * @access  protected
     * @author  Raphael Horber
     * @version 01.08.2019
     */
    protected function convertToInternal(string $string, EncodingInterface $fromEncoding): string
    {
        return mb_convert_encoding($string, mb_internal_encoding(), $fromEncoding->getName());
    }

    /**
     * Verifies if the passed encoding uses a BOM and if so, if the passed string has a valid one.
     *
     * @param EncodingInterface $encoding Encoding to check if a BOM is used.
     * @param string $string String to check for a valid BOM (if required).
     *
     * @return  void
     * @throws  \UnexpectedValueException If the encoding uses a BOM and the string contains an invalid one.
     * @access  protected
     * @author  Raphael Horber
     * @version 31.08.2019
     */
    protected function verifyBom(EncodingInterface $encoding, string $string)
    {
        if ($encoding->hasBom()) {
            $bom = substr($string, 0, 2);
            if ($bom !== "\xff\xfe" && $bom !== "\xfe\xff") {
                throw new \UnexpectedValueException("Invalid BOM, got: ".bin2hex($bom));
            }
        }
    }
}


// Útƒ-8 encoded
