<?php

/**
 * Test class for class PcntFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PcntFrame;


/**
 * Class PcntFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PcntFrame
 */
class PcntFrameTest extends TestCase
{
    public function testMinLength()
    {
        // Arrange.
        $rawContent = "\x00\x00\x01\x42";

        // Act.
        $parser = new PcntFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "PCNT",
            'rawContent' => $rawContent,
            'counter'    => 322,
        ];

        $this->assertResult($parser, $array);
    }

    public function testAdditionalByte()
    {
        // Arrange.
        $rawContent = "\x01\x00\x00\x00\x00";

        // Act.
        $parser = new PcntFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "PCNT",
            'rawContent' => $rawContent,
            'counter'    => 4294967296,
        ];

        $this->assertResult($parser, $array);
    }


    private function assertResult(PcntFrame $parser, $expectedArray)
    {
        self::assertSame("PCNT", $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
