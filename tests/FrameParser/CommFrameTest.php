<?php

/**
 * Test class for class CommFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
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
    public function testIso(TagParserInterface $tagParser)
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
    public function testUtf(TagParserInterface $tagParser)
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
