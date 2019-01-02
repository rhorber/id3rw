<?php

/**
 * Test class for class WxxxFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\WxxxFrame;


/**
 * Class WxxxFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\WxxxFrame
 */
class WxxxFrameTest extends TestCase
{
    public function testIso()
    {
        // Arrange.
        $description = "ISO-8859-1";
        $url         = "http://www.example.com/iso.html";
        $rawContent  = "\x00".$description."\x00".$url;

        // Act.
        $parser = new WxxxFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WXXX-ISO-8859-1";
        $array    = [
            'frameId'     => "WXXX",
            'rawContent'  => $rawContent,
            'encoding'    => "ISO-8859-1",
            'description' => $description,
            'url'         => $url,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testUtf()
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $url         = "http://www.example.com/utf-16.html";
        $rawContent  = "\x01\xff\xfe".$description."\x00\x00".$url;

        // Act.
        $parser = new WxxxFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WXXX-UTF-16LE";
        $array    = [
            'frameId'     => "WXXX",
            'rawContent'  => $rawContent,
            'encoding'    => "UTF-16",
            'description' => "\xff\xfe".$description,
            'url'         => $url,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }


    private function assertResult(WxxxFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
