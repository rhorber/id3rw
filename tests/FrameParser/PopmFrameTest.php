<?php

/**
 * Test class for class PopmFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PopmFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class PopmFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PopmFrame
 */
class PopmFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "POPM";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseMinLength(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "rhorber@example.com\x00\x80\x00\x00\x01\x42";

        // Act.
        $parser = new PopmFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'email'      => "rhorber@example.com",
            'rating'     => 128,
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
        $rawContent = "rhorber@example.com\x00\x80\x01\x00\x00\x01\x42";

        // Act.
        $parser = new PopmFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'email'      => "rhorber@example.com",
            'rating'     => 128,
            'counter'    => 4294967618,
        ];

        $this->assertResult($parser, $array);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseCounterOmitted(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "rhorber@example.com\x00\x80";

        // Act.
        $parser = new PopmFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'email'      => "rhorber@example.com",
            'rating'     => 128,
            'counter'    => 0,
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
        $email  = "rhorber@example.com";
        $parser = new PopmFrame($tagParser, self::$_frameId);

        $parser->email   = $email;
        $parser->rating  = 128;
        $parser->counter = 322;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = $email."\x00\x80\x00\x00\x01\x42";
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildAdditionalByte(TagParserInterface $tagParser)
    {
        // Arrange.
        $email  = "rhorber@example.com";
        $parser = new PopmFrame($tagParser, self::$_frameId);

        $parser->email   = $email;
        $parser->rating  = 128;
        $parser->counter = 4294967618;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = $email."\x00\x80\x01\x00\x00\x01\x42";
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildValuesOmitted(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new PopmFrame($tagParser, self::$_frameId);

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00\x00\x00\x00\x00\x00";
        self::assertSame($rawContent, $content);
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(PopmFrame $parser, $expectedArray)
    {
        self::assertSame("POPM-rhorber@example.com", $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
