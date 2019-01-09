<?php

/**
 * Test class for class EtcoFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\EtcoFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class EtcoFrameTest.
 *
 * The frame specification has no differences between Version 2.3.0 and 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\EtcoFrame
 */
class EtcoFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "ETCO";

    /** @dataProvider tagParserDataProvider */
    public function testWellFormed(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'format'     => "02",
            'codes'      => [
                '01' => 32,
                '03' => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }

    /** @dataProvider tagParserDataProvider */
    public function testByteMissing(TagParserInterface $tagParser)
    {
        // Arrange.
        $rawContent = "\x02\x01\x00\x00\x00\x20\x03\x00\x01\xaf";

        // Act.
        $parser = new EtcoFrame($tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'format'     => "02",
            'codes'      => [
                '01' => 32,
                '03' => 431,
            ],
        ];

        $this->assertResult($parser, $array);
    }

    /** Returns parsers of the different versions. */
    public function tagParserDataProvider()
    {
        return [
            'Version 2.3.0' => [$GLOBALS['TAG_PARSER_VERSION_3']],
            'Version 2.4.0' => [$GLOBALS['TAG_PARSER_VERSION_4']],
        ];
    }


    private function assertResult(EtcoFrame $parser, $expectedArray)
    {
        self::assertSame(self::$_frameId, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
