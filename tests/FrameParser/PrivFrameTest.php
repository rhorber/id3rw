<?php

/**
 * Test class for class PrivFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.07.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\PrivFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class PrivFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\PrivFrame
 */
class PrivFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "PRIV";

    public function setUp()
    {
        parent::setUp();

        $class = new \ReflectionClass("\\Rhorber\\ID3rw\\FrameParser\\PrivFrame");

        $priv = $class->getProperty("_counter");
        $priv->setAccessible(true);
        $priv->setValue(0);
    }

    /**
     * @covers ::parse
     * @dataProvider tagParserDataProvider
     */
    public function testMultiple(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent1 = "rhorber@example.com\x00\xff\xfe\x38\x00\x38\x00";
        $rawContent2 = "rhorber@example.com\x00\xff\xfe\x32\x00\x34\x00";

        // Act.
        $parser1 = new PrivFrame($tagParser, self::$_frameId);
        $parser1->parse($rawContent1);
        $parser2 = new PrivFrame($tagParser, self::$_frameId);
        $parser2->parse($rawContent2);

        // Assert.
        $arrayKey1 = "PRIV-1";
        $arrayKey2 = "PRIV-2";
        $array1    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent1,
            'owner'       => "rhorber@example.com",
            'privateData' => "\xff\xfe\x38\x00\x38\x00",
        ];
        $array2    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent2,
            'owner'       => "rhorber@example.com",
            'privateData' => "\xff\xfe\x32\x00\x34\x00",
        ];

        $this->assertResult($parser1, $arrayKey1, $array1);
        $this->assertResult($parser2, $arrayKey2, $array2);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildValid(TagParserInterface $tagParser)
    {
        // Arrange.
        $owner  = "rhorber@example.com";
        $parser = new PrivFrame($tagParser, self::$_frameId);

        $parser->owner       = $owner;
        $parser->privateData = "\xff\xfe\x32\x00\x34\x00";

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = $owner."\x00\xff\xfe\x32\x00\x34\x00";
        self::assertSame($rawContent, $content);
    }

    /**
     * @covers ::build
     * @dataProvider tagParserDataProvider
     */
    public function testBuildValuesOmitted(TagParserInterface $tagParser)
    {
        // Arrange.
        $parser = new PrivFrame($tagParser, self::$_frameId);

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00";
        self::assertSame($rawContent, $content);
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(PrivFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
