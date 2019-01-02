<?php

/**
 * Test class for class UrlLinkFrames.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 02.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UrlLinkFrames;


/**
 * Class UrlLinkFramesTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UrlLinkFrames
 */
class UrlLinkFramesTest extends TestCase
{
    public function testValid()
    {
        // Arrange.
        $rawContent = "http://www.example.com/no-extra-text.html";

        // Act.
        $parser = new UrlLinkFrames("WCOP");
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WCOP";
        $array    = [
            'frameId'    => "WCOP",
            'rawContent' => $rawContent,
            'url'        => "http://www.example.com/no-extra-text.html",
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testSuperfluousContent()
    {
        // Arrange.
        $rawContent = "http://www.example.com/with-extra-text.html\x00Content that should be ignored.\x00";

        // Act.
        $parser = new UrlLinkFrames("WCOP");
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "WCOP";
        $array    = [
            'frameId'    => "WCOP",
            'rawContent' => $rawContent,
            'url'        => "http://www.example.com/with-extra-text.html",
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    public function testMultipleWcom()
    {
        // Arrange.
        $rawContent1 = "http://www.example.com/first.html";
        $rawContent2 = "http://www.example.com/second.html";

        // Act.
        $parser1 = new UrlLinkFrames("WCOM");
        $parser1->parse($rawContent1);
        $parser2 = new UrlLinkFrames("WCOM");
        $parser2->parse($rawContent2);

        // Assert.
        $arrayKey1 = "WCOM-1";
        $arrayKey2 = "WCOM-2";
        $array1    = [
            'frameId'    => "WCOM",
            'rawContent' => $rawContent1,
            'url'        => "http://www.example.com/first.html",
        ];
        $array2    = [
            'frameId'    => "WCOM",
            'rawContent' => $rawContent2,
            'url'        => "http://www.example.com/second.html",
        ];

        $this->assertResult($parser1, $arrayKey1, $array1);
        $this->assertResult($parser2, $arrayKey2, $array2);
    }

    public function testMultipleWoar()
    {
        // Arrange.
        $rawContent1 = "http://www.example.com/first.html";
        $rawContent2 = "http://www.example.com/second.html";

        // Act.
        $parser1 = new UrlLinkFrames("WOAR");
        $parser1->parse($rawContent1);
        $parser2 = new UrlLinkFrames("WOAR");
        $parser2->parse($rawContent2);

        // Assert.
        $arrayKey1 = "WOAR-1";
        $arrayKey2 = "WOAR-2";
        $array1    = [
            'frameId'    => "WOAR",
            'rawContent' => $rawContent1,
            'url'        => "http://www.example.com/first.html",
        ];
        $array2    = [
            'frameId'    => "WOAR",
            'rawContent' => $rawContent2,
            'url'        => "http://www.example.com/second.html",
        ];

        $this->assertResult($parser1, $arrayKey1, $array1);
        $this->assertResult($parser2, $arrayKey2, $array2);
    }


    private function assertResult(UrlLinkFrames $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
