<?php

/**
 * Test class for class SignFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 10.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\SignFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class SignFrameTest.
 *
 * The "SIGN" frame was added in Version 2.4.0.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\SignFrame
 */
class SignFrameTest extends TestCase
{
    /** @var string */
    private static $_frameId = "SIGN";
    /** @var TagParserInterface */
    private static $_tagParser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$_tagParser = $GLOBALS['TAG_PARSER_VERSION_4'];
    }

    /** @covers ::parse */
    public function testMultiple()
    {
        // Arrange.
        $rawContent1 = "\x42\x00\xff\xfe\x38\x00\x38\x00";
        $rawContent2 = "\x42\x00\xff\xfe\x32\x00\x34\x00";

        // Act.
        $parser1 = new SignFrame(self::$_tagParser, self::$_frameId);
        $parser1->parse($rawContent1);
        $parser2 = new SignFrame(self::$_tagParser, self::$_frameId);
        $parser2->parse($rawContent2);

        // Assert.
        $arrayKey1 = "SIGN-1";
        $arrayKey2 = "SIGN-2";
        $array1    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent1,
            'groupSymbol' => "\x42",
            'signature'   => "\x00\xff\xfe\x38\x00\x38\x00",
        ];
        $array2    = [
            'frameId'     => self::$_frameId,
            'rawContent'  => $rawContent2,
            'groupSymbol' => "\x42",
            'signature'   => "\x00\xff\xfe\x32\x00\x34\x00",
        ];

        $this->assertResult($parser1, $arrayKey1, $array1);
        $this->assertResult($parser2, $arrayKey2, $array2);
    }


    private function assertResult(SignFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
