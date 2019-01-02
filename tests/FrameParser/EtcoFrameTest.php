<?php

/**
 * Test class for class EtcoFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\EtcoFrame;


/**
 * Class EtcoFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\EtcoFrame
 */
class EtcoFrameTest extends TestCase
{
    public function testWellFormed()
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "ETCO",
            'rawContent' => $rawContent,
            'format'     => "02",
            'codes'      => [
                '01' => 32,
                '03' => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }

    public function testByteMissing()
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame();
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => "ETCO",
            'rawContent' => $rawContent,
            'format'     => "02",
            'codes'      => [
                '01' => 32,
                '03' => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }


    private function assertResult(EtcoFrame $parser, $expectedArray)
    {
        self::assertSame("ETCO", $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
