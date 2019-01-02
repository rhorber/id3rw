<?php

/**
 * Test class for class TxxxFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\TxxxFrame;


/**
 * Class TxxxFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\TxxxFrame
 */
class TxxxFrameTest extends TestCase
{
    public function testIso()
    {
        // Arrange.
        $description = "ISO-8859-1";
        $value       = "TXXX frame with ISO encoding.";
        $rawContent  = "\x00".$description."\x00".$value;

        // Act.
        $parser = new TxxxFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "TXXX-ISO-8859-1";
        $array    = [
            'frameId'     => "TXXX",
            'rawContent'  => $rawContent,
            'encoding'    => "ISO-8859-1",
            'description' => $description,
            'value'       => $value,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testUtf()
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $value       = "TXXX frame with UTF-16LE encoding.";
        $rawContent  = "\x01\xff\xfe".$description."\x00\x00".$value;

        // Act.
        $parser = new TxxxFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "TXXX-UTF-16LE";
        $array    = [
            'frameId'     => "TXXX",
            'rawContent'  => $rawContent,
            'encoding'    => "UTF-16",
            'description' => "\xff\xfe".$description,
            'value'       => $value,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }


    private function assertResult(TxxxFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
