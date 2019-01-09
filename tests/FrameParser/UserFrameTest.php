<?php

/**
 * Test class for class UserFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\UserFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;
use Rhorber\ID3rw\TagParser\Version4;


/**
 * Class UserFrameTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UserFrame
 */
class UserFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "USER";
    /** @var TagParserInterface */
    private static $_tagParser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$_tagParser = new Version4();
    }

    public function testIso()
    {
        // Arrange.
        $text       = "Test terms of use with ISO-8859-1 encoding.";
        $rawContent = "\x00eng".$text;

        // Act.
        $parser = new UserFrame(self::$_tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => self::$_frameId,
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
        $parser = new UserFrame(self::$_tagParser, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => self::$_frameId,
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
