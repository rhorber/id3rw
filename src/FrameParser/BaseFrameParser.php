<?php

/**
 * Class BaseFrameParser.
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */

namespace Rhorber\ID3rw\FrameParser;


/**
 * Basic frame parser (saves raw content).
 *
 * @package Rhorber\ID3rw\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
class BaseFrameParser
{
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
     * @param string $frameId Frame ID of the frame to parse.
     *
     * @access  public
     * @author  Raphael Horber
     * @version 02.01.2019
     */
    public function __construct(string $frameId)
    {
        $this->frameId = $frameId;
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
     * @version 02.01.2019
     */
    public function getFrameArray(): array
    {
        try {
            $reflectionClass = new \ReflectionClass($this);
        } catch (\ReflectionException $e) {
            return [];
        }

        $privateProperties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

        $result = [
            'frameId'    => $this->frameId,
            'rawContent' => $this->rawContent,
        ];

        foreach ($privateProperties as $property) {
            $name  = $property->getName();
            $value = $property->getValue($this);

            $result[$name] = $value;
        }

        return $result;
    }
}


// Útƒ-8 encoded
