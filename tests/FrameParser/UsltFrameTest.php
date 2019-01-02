<?php

/**
 * Test class for class UsltFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UsltFrame;


/**
 * Class UsltFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UsltFrame
 */
class UsltFrameTest extends TestCase
{
    public function testIso()
    {
        // Arrange.
        $description = "ISO-8859-1";
        $text        = "Test lyrics with ISO-8859-1 encoding.";
        $rawContent  = "\x00eng".$description."\x00".$text;

        // Act.
        $parser = new UsltFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USLT-eng-ISO-8859-1";
        $array    = [
            'frameId'     => "USLT",
            'rawContent'  => $rawContent,
            'encoding'    => "ISO-8859-1",
            'language'    => "eng",
            'description' => $description,
            'text'        => $text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testUtf()
    {
        // Arrange.
        $description = mb_convert_encoding("UTF-16LE", "UTF-16LE");
        $text        = mb_convert_encoding("Test lyrics with UTF-16LE encoding.", "UTF-16LE");
        $rawContent  = "\x01eng\xff\xfe".$description."\x00\x00\xff\xfe".$text;

        // Act.
        $parser = new UsltFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USLT-eng-UTF-16LE";
        $array    = [
            'frameId'     => "USLT",
            'rawContent'  => $rawContent,
            'encoding'    => "UTF-16",
            'language'    => "eng",
            'description' => "\xff\xfe".$description,
            'text'        => "\xff\xfe".$text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }


    private function assertResult(UsltFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
