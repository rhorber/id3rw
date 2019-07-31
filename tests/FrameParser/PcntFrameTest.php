<?php

/**
 * Test class for class PcntFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PcntFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class PcntFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PcntFrame
 */
class PcntFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "PCNT";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseMinLength(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x00\x00\x01\x42";

        // Act.
        $parser = new PcntFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'counter'    => 322,
        ];

        $this->assertResult($parser, $array);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseAdditionalByte(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x01\x00\x00\x00\x00";

        // Act.
        $parser = new PcntFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'counter'    => 4294967296,
        ];

        $this->assertResult($parser, $array);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildMinLength(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new PcntFrame($tagParser, self::$_frameId);

        $parser->counter = 322;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00\x00\x01\x42";
        self::assertSame($content, $rawContent);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildAdditionalByte(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new PcntFrame($tagParser, self::$_frameId);

        $parser->counter = 4294967296;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x01\x00\x00\x00\x00";
        self::assertSame($content, $rawContent);
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(PcntFrame $parser, $expectedArray)
    {
        self::assertSame(self::$_frameId, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
