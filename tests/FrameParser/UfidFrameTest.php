<?php

/**
 * Test class for class UfidFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UfidFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class UfidFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UfidFrame
 */
class UfidFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "UFID";

    /** @dataProvider tagParserDataProvider */
    public function testValid(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "http://www.id3.org/dummy/ufid.html\x00id-42";

        // Act.
        $parser = new UfidFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "UFID-http://www.id3.org/dummy/ufid.html";
        $array    = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'owner'      => "http://www.id3.org/dummy/ufid.html",
            'identifier' => "id-42"
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /** @dataProvider tagParserDataProvider */
    public function testEmptyId(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "http://www.id3.org/dummy/ufid.html";

        // Act.
        $parser = new UfidFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "UFID-http://www.id3.org/dummy/ufid.html";
        $array    = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'owner'      => "http://www.id3.org/dummy/ufid.html",
            'identifier' => ""
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /** @dataProvider tagParserDataProvider */
    public function testEmptyOwner(TagParserInterface $tagParser)
    {
        // Assert.
        self::expectException("InvalidArgumentException");
        self::expectExceptionMessage("UFID frame: Owner MUST NOT be empty.");

        // Arrange.
        $rawContent = "\x00id-42";

        // Act.
        $parser = new UfidFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(UfidFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
