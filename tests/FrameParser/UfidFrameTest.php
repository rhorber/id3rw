<?php

/**
 * Test class for class UfidFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UfidFrame;


/**
 * Class UfidFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UfidFrame
 */
class UfidFrameTest extends TestCase
{
    public function testValid()
    {
        // Arrange.
        $rawContent = "http://www.id3.org/dummy/ufid.html\x00id-42";

        // Act.
        $parser = new UfidFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "UFID-http://www.id3.org/dummy/ufid.html";
        $array    = [
            'frameId'    => "UFID",
            'rawContent' => $rawContent,
            'owner'      => "http://www.id3.org/dummy/ufid.html",
            'identifier' => "id-42"
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testEmptyId()
    {
        // Arrange.
        $rawContent = "http://www.id3.org/dummy/ufid.html";

        // Act.
        $parser = new UfidFrame();
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "UFID-http://www.id3.org/dummy/ufid.html";
        $array    = [
            'frameId'    => "UFID",
            'rawContent' => $rawContent,
            'owner'      => "http://www.id3.org/dummy/ufid.html",
            'identifier' => ""
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testEmptyOwner()
    {
        // Assert.
        self::expectException("InvalidArgumentException");
        self::expectExceptionMessage("UFID frame: Owner MUST NOT be empty.");

        // Arrange.
        $rawContent = "\x00id-42";

        // Act.
        $parser = new UfidFrame();
        $parser->parse($rawContent);
    }


    private function assertResult(UfidFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
