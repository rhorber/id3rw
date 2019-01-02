<?php

/**
 * Test class for class PopmFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PopmFrame;


/**
 * Class PopmFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PopmFrame
 */
class PopmFrameTest extends TestCase
{
    public function testMinLength()
    {
        // Arrange.
        $rawContent = "rhorber@example.com\x00\x80\x00\x00\x01\x42";

        // Act.
        $parser = new PopmFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "POPM",
            'rawContent' => $rawContent,
            'email'      => "rhorber@example.com",
            'rating'     => 128,
            'counter'    => 322,
        ];

        $this->assertResult($parser, $array);
    }

    public function testAdditionalByte()
    {
        // Arrange.
        $rawContent = "rhorber@example.com\x00\x80\x01\x00\x00\x01\x42";

        // Act.
        $parser = new PopmFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "POPM",
            'rawContent' => $rawContent,
            'email'      => "rhorber@example.com",
            'rating'     => 128,
            'counter'    => 4294967618,
        ];

        $this->assertResult($parser, $array);
    }


    private function assertResult(PopmFrame $parser, $expectedArray)
    {
        self::assertSame("POPM-rhorber@example.com", $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
