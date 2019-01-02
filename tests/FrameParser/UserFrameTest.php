<?php

/**
 * Test class for class UserFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UserFrame;


/**
 * Class UserFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UserFrame
 */
class UserFrameTest extends TestCase
{
    public function testIso()
    {
        // Arrange.
        $text       = "Test terms of use with ISO-8859-1 encoding.";
        $rawContent = "\x00eng".$text;

        // Act.
        $parser = new UserFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => "USER",
            'rawContent' => $rawContent,
            'encoding'   => "ISO-8859-1",
            'language'   => "eng",
            'text'       => $text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testUtf()
    {
        // Arrange.
        $text       = mb_convert_encoding("Test terms of use with UTF-16LE encoding.", "UTF-16LE");
        $rawContent = "\x01eng\xff\xfe".$text;

        // Act.
        $parser = new UserFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => "USER",
            'rawContent' => $rawContent,
            'encoding'   => "UTF-16",
            'language'   => "eng",
            'text'       => "\xff\xfe".$text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }


    private function assertResult(UserFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
