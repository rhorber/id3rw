<?php

/**
 * Test class for class WxxxFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\WxxxFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class WxxxFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\WxxxFrame
 */
class WxxxFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "WXXX";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testIso(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "ISO-8859-1";
        $url         = "http://www.example.com/iso.html";
        $rawContent  = "\x00".$description."\x00".$url;

        // Act.
        $parser = new WxxxFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WXXX-ISO-8859-1";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'description' => $description,
            'url'         => $url,
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
        $url         = "http://www.example.com/utf-16.html";
        $rawContent  = "\x01\xff\xfe".$description."\x00\x00".$url;

        // Act.
        $parser = new WxxxFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WXXX-UTF-16LE";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'description' => "\xff\xfe".$description,
            'url'         => $url,
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


    private function assertResult(WxxxFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
