<?php

/**
 * Test class for class BaseFrameParser.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\BaseFrameParser;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class BaseFrameParserTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UfidFrame
 */
class BaseFrameParserTest extends TestCase
{
    /** @var string */
    private static $_frameId = "MCDI";

    /**
     * @covers ::__construct
     * @dataProvider tagParserDataProvider
     */
    public function testConstructTagParser(TagParserInterface $tagParser)
    {
        // Act.
        $parser = new BaseFrameParser($tagParser, self::$_frameId);

        // Assert.
        self::assertAttributeEquals($tagParser, "tagParser", $parser);
    }

    /**
     * @covers ::__construct
     * @dataProvider tagParserDataProvider
     */
    public function testConstructFrameId(TagParserInterface $tagParser)
    {
        // Act.
        $parser = new BaseFrameParser($tagParser, self::$_frameId);

        // Assert.
        self::assertAttributeEquals(self::$_frameId, "frameId", $parser);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParse(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "foobar\x0042";
        $parser = new BaseFrameParser($tagParser, self::$_frameId);

        // Act.
        $parser->parse($rawContent);

        // Assert.
        self::assertAttributeEquals($rawContent, "rawContent", $parser);
    }

    /**
     * @covers ::getArrayKey
     * @dataProvider tagParserDataProvider
     */
    public function testGetArrayKey(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new BaseFrameParser($tagParser, self::$_frameId);

        // Act.
        $arrayKey = $parser->getArrayKey();

        // Assert.
        self::assertSame(self::$_frameId, $arrayKey);
    }

    /**
     * @covers ::getFrameArray
     * @dataProvider tagParserDataProvider
     */
    public function testGetFrameArray(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "foobar\x0042";
        $parser     = new BaseFrameParser($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Act.
        $frameArray = $parser->getFrameArray();

        // Assert.
        $expected = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
        ];
        self::assertSame($expected, $frameArray);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuild(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "foobar\x0042";

        // Act.
        $parser = new BaseFrameParser($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        self::assertSame($rawContent, $parser->build());
    }


    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }
}


// Útƒ-8 encoded
