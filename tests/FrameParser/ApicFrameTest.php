<?php

/**
 * Test class for class ApicFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\ApicFrame;


/**
 * Class ApicFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\ApicFrame
 */
class ApicFrameTest extends TestCase
{
    public function testIso()
    {
        // Arrange.
        $description = "ISO-8859-1";
        $pictureData = "Sample data.";
        $rawContent  = "\x00image/png\x00\x03".$description."\x00".$pictureData;

        // Act.
        $parser = new ApicFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-ISO-8859-1";
        $array    = [
            'frameId'     => "APIC",
            'rawContent'  => $rawContent,
            'encoding'    => "ISO-8859-1",
            'mimeType'    => "image/png",
            'pictureType' => "\x03",
            'description' => $description,
            'pictureData' => $pictureData,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testUtf()
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $pictureData = "Sample data.";
        $rawContent  = "\x01image/jpeg\x00\x05\xff\xfe".$description."\x00\x00".$pictureData;

        // Act.
        $parser = new ApicFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-UTF-16LE";
        $array    = [
            'frameId'     => "APIC",
            'rawContent'  => $rawContent,
            'encoding'    => "UTF-16",
            'mimeType'    => "image/jpeg",
            'pictureType' => "\x05",
            'description' => "\xff\xfe".$description,
            'pictureData' => $pictureData,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testMimeTypeOmitted()
    {
        // Arrange.
        $description = "No MIME type";
        $pictureData = "Sample data.";
        $rawContent  = "\x00\x00\x03".$description."\x00".$pictureData;

        // Act.
        $parser = new ApicFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "APIC-No MIME type";
        $array    = [
            'frameId'     => "APIC",
            'rawContent'  => $rawContent,
            'encoding'    => "ISO-8859-1",
            'mimeType'    => "image/",
            'pictureType' => "\x03",
            'description' => $description,
            'pictureData' => $pictureData,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }


    private function assertResult(ApicFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
