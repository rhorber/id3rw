<?php

/**
 * Test class for class PrivFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PrivFrame;


/**
 * Class PrivFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PrivFrame
 */
class PrivFrameTest extends TestCase
{
    public function testMultiple()
    {
        // Arrange.
        $rawContent1 = "rhorber@example.com\x00\xff\xfe\x38\x00\x38\x00";
        $rawContent2 = "rhorber@example.com\x00\xff\xfe\x32\x00\x34\x00";

        // Act.
        $parser1 = new PrivFrame();
        $parser1->parse($rawContent1);
        $parser2 = new PrivFrame();
        $parser2->parse($rawContent2);

        // Assert.
        $arrayKey1 = "PRIV-1";
        $arrayKey2 = "PRIV-2";
        $array1    = [
            'frameId'     => "PRIV",
            'rawContent'  => $rawContent1,
            'owner'       => "rhorber@example.com",
            'privateData' => "\xff\xfe\x38\x00\x38\x00",
        ];
        $array2    = [
            'frameId'     => "PRIV",
            'rawContent'  => $rawContent2,
            'owner'       => "rhorber@example.com",
            'privateData' => "\xff\xfe\x32\x00\x34\x00",
        ];

        $this->assertResult($parser1, $arrayKey1, $array1);
        $this->assertResult($parser2, $arrayKey2, $array2);
    }


    private function assertResult(PrivFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
