<?php

/**
 * Test class for class CommFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\CommFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class CommFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\CommFrame
 */
class CommFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "COMM";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseIso(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "ISO-8859-1";
        $text        = "Test comment with ISO-8859-1 encoding.";
        $rawContent  = "\x00eng".$description."\x00".$text;

        // Act.
        $parser = new CommFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "COMM-eng-ISO-8859-1";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'language'    => "eng",
            'description' => $description,
            'text'        => $text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseUtf(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $text        = mb_convert_encoding("Test comment with UTF-16LE encoding.", "UTF-16LE");
        $rawContent  = "\x01eng\xff\xfe".$description."\x00\x00\xff\xfe".$text;

        // Act.
        $parser = new CommFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "COMM-eng-UTF-16LE";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'language'    => "eng",
            'description' => "\xff\xfe".$description,
            'text'        => "\xff\xfe".$text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildIso(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "ISO-8859-1";
        $text        = "Test comment with ISO-8859-1 encoding.";

        $parser = new CommFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getIso88591();
        $parser->language    = "eng";
        $parser->description = $description;
        $parser->text        = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00eng".$description."\x00".$text;
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildUtf(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "\xff\xfe".mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $text        = "\xff\xfe".mb_convert_encoding("Test comment with UTF-16LE encoding.", "UTF-16LE");

        $parser = new CommFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->language    = "eng";
        $parser->description = $description;
        $parser->text        = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x01eng".$description."\x00\x00".$text;
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildInvalidBomDescription(TagParserInterface $tagParser)
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid BOM, got: fefe");

        // Arrange.
        $description = "\xfe\xfe".mb_convert_encoding("Invalid BOM.", "UTF-16LE");
        $text        = "\xff\xfe".mb_convert_encoding("Test comment with an invalid BOM.", "UTF-16LE");

        $parser = new CommFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->language    = "eng";
        $parser->description = $description;
        $parser->text        = $text;

        // Act.
        $parser->build();
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildInvalidBomText(TagParserInterface $tagParser)
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid BOM, got: fefe");

        // Arrange.
        $description = "\xff\xfe".mb_convert_encoding("Invalid BOM.", "UTF-16LE");
        $text        = "\xfe\xfe".mb_convert_encoding("Test comment with an invalid BOM.", "UTF-16LE");

        $parser = new CommFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->language    = "eng";
        $parser->description = $description;
        $parser->text        = $text;

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


    private function assertResult(CommFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
