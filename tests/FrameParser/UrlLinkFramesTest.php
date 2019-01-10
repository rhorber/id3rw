<?php

/**
 * Test class for class UrlLinkFrames.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 10.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UrlLinkFrames;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class UrlLinkFramesTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UrlLinkFrames
 */
class UrlLinkFramesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $class = new \ReflectionClass("\\Rhorber\\ID3rw\\FrameParser\\UrlLinkFrames");

        $wcom = $class->getProperty("_wcomCounter");
        $wcom->setAccessible(true);
        $wcom->setValue(0);

        $woar = $class->getProperty("_woarCounter");
        $woar->setAccessible(true);
        $woar->setValue(0);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testValid(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "http://www.example.com/no-extra-text.html";

        // Act.
        $parser = new UrlLinkFrames($tagParser, "WCOP");
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

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testSuperfluousContent(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "http://www.example.com/with-extra-text.html\x00Content that should be ignored.\x00";

        // Act.
        $parser = new UrlLinkFrames($tagParser, "WCOP");
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

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testMultipleWcom(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent1 = "http://www.example.com/first.html";
        $rawContent2 = "http://www.example.com/second.html";

        // Act.
        $parser1 = new UrlLinkFrames($tagParser, "WCOM");
        $parser1->parse($rawContent1);
        $parser2 = new UrlLinkFrames($tagParser, "WCOM");
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

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testMultipleWoar(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent1 = "http://www.example.com/first.html";
        $rawContent2 = "http://www.example.com/second.html";

        // Act.
        $parser1 = new UrlLinkFrames($tagParser, "WOAR");
        $parser1->parse($rawContent1);
        $parser2 = new UrlLinkFrames($tagParser, "WOAR");
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

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(UrlLinkFrames $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
