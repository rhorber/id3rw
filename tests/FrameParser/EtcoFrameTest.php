<?php

/**
 * Test class for class EtcoFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 05.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\EtcoFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class EtcoFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\EtcoFrame
 */
class EtcoFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "ETCO";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseWellFormed(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'format'     => "\x02",
            'codes'      => [
                "\x01" => 32,
                "\x03" => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseByteMissing(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'format'     => "\x02",
            'codes'      => [
                "\x01" => 32,
                "\x03" => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildWellFormed(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new EtcoFrame($tagParser, self::$_frameId);

        $parser->format = "\x02";
        $parser->codes  = [
            "\x01" => 32,
            "\x03" => 431,
        ];

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x02\x01\x20\x03\x01\xaf";
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildWrongFormat(TagParserInterface $tagParser)
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid time stamp format, got: 10");

        // Arrange.
        $parser = new EtcoFrame($tagParser, self::$_frameId);

        $parser->format = "\x10";
        $parser->codes  = [
            "\x01" => 32,
            "\x03" => 431,
        ];

        // Act.
        $parser->build();
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(EtcoFrame $parser, $expectedArray)
    {
        self::assertSame(self::$_frameId, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
