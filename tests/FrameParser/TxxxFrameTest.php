<?php

/**
 * Test class for class TxxxFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
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

    /** @dataProvider tagParserDataProvider */
    public function testIso(TagParserInterface $tagParser)
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
            'encoding'    => "ISO-8859-1",
            'description' => $description,
            'value'       => $value,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /** @dataProvider tagParserDataProvider */
    public function testUtf(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $value       = "TXXX frame with UTF-16LE encoding.";
        $rawContent  = "\x01\xff\xfe".$description."\x00\x00".$value;

        // Act.
        $parser = new TxxxFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "TXXX-UTF-16LE";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => "UTF-16",
            'description' => "\xff\xfe".$description,
            'value'       => $value,
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


    private function assertResult(TxxxFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
