<?php

/**
 * Test class for class ApicFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\ApicFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class ApicFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\ApicFrame
 */
class ApicFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "APIC";

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testIso(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "ISO-8859-1";
        $pictureData = "Sample data.";
        $rawContent  = "\x00image/png\x00\x03".$description."\x00".$pictureData;

        // Act.
        $parser = new ApicFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-ISO-8859-1";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'mimeType'    => "image/png",
            'pictureType' => "\x03",
            'description' => $description,
            'pictureData' => $pictureData,
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
        $pictureData = "Sample data.";
        $rawContent  = "\x01image/jpeg\x00\x05\xff\xfe".$description."\x00\x00".$pictureData;

        // Act.
        $parser = new ApicFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-UTF-16LE";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'mimeType'    => "image/jpeg",
            'pictureType' => "\x05",
            'description' => "\xff\xfe".$description,
            'pictureData' => $pictureData,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testMimeTypeOmitted(TagParserInterface $tagParser)
    {
        // Arrange.
        $description = "No MIME type";
        $pictureData = "Sample data.";
        $rawContent  = "\x00\x00\x03".$description."\x00".$pictureData;

        // Act.
        $parser = new ApicFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-No MIME type";
        $array    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'mimeType'    => "image/",
            'pictureType' => "\x03",
            'description' => $description,
            'pictureData' => $pictureData,
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


    private function assertResult(ApicFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
