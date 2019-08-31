<?php

/**
 * Test class for class TxxxFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\TxxxFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class TxxxFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\TxxxFrame
 */
class TxxxFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "TXXX";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testParseIso(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "ISO-8859-1";
        $value       = "TXXX frame with ISO encoding.";
        $rawContent  = "\x00".$description."\x00".$value;

        // Act.
        $parser = new TxxxFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "TXXX-ISO-8859-1";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'description' => $description,
            'value'       => $value,
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
        $description = "\xff\xfe".mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $value       = "\xff\xfe".mb_convert_encoding("TXXX frame with UTF-16LE encoding.", "UTF-16LE");
        $rawContent  = "\x01".$description."\x00\x00".$value;

        // Act.
        $parser = new TxxxFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "TXXX-UTF-16LE";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'description' => $description,
            'value'       => $value,
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
        $value       = "TXXX frame with ISO encoding.";

        $parser = new TxxxFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getIso88591();
        $parser->description = $description;
        $parser->value       = $value;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00".$description."\x00".$value;
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
        $value       = "\xff\xfe".mb_convert_encoding("TXXX frame with UTF-16LE encoding.", "UTF-16LE");

        $parser = new TxxxFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->description = $description;
        $parser->value       = $value;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x01".$description."\x00\x00".$value;
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
        $value       = "\xff\xfe".mb_convert_encoding("TXXX frame with an invalid BOM.", "UTF-16LE");

        $parser = new TxxxFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->description = $description;
        $parser->value       = $value;

        // Act.
        $parser->build();
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildInvalidBomValue(TagParserInterface $tagParser)
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid BOM, got: fefe");

        // Arrange.
        $description = "\xff\xfe".mb_convert_encoding("Invalid BOM.", "UTF-16LE");
        $value       = "\xfe\xfe".mb_convert_encoding("TXXX frame with an invalid BOM.", "UTF-16LE");

        $parser = new TxxxFrame($tagParser, self::$_frameId);

        $parser->encoding    = EncodingFactory::getUtf16();
        $parser->description = $description;
        $parser->value       = $value;

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


    private function assertResult(TxxxFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
