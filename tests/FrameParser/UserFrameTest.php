<?php

/**
 * Test class for class UserFrame.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 31.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\UserFrame;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class UserFrameTest.
 *
 * Version 2.4.0:
 * There may be more than one 'Terms of use' frame in a tag, but only one with the same 'Language'.
 * Version 2.3.0:
 * There may only be one "USER" frame in a tag.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\UserFrame
 */
class UserFrameTest extends TestCase
{
    // region properties, and set up functions
    /** @var string */
    private static $_frameId = "USER";
    /** @var TagParserInterface */
    private static $_tagParserVersion3 = null;
    /** @var TagParserInterface */
    private static $_tagParserVersion4 = null;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$_tagParserVersion3 = $GLOBALS['TAG_PARSER_VERSION_3'];
        self::$_tagParserVersion4 = $GLOBALS['TAG_PARSER_VERSION_4'];
    }
    // endregion


    // region Version 2.3.0, parse
    /** @covers ::parse */
    public function testParseIsoVersion3()
    {
        // Arrange.
        $text       = "Test terms of use with ISO-8859-1 encoding.";
        $rawContent = "\x00eng".$text;

        // Act.
        $parser = new UserFrame(self::$_tagParserVersion3, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'encoding'   => EncodingFactory::getIso88591(),
            'language'   => "eng",
            'text'       => $text,
        ];

        $this->assertResult($parser, self::$_frameId, $array);
    }

    /** @covers ::parse */
    public function testParseUtfVersion3()
    {
        // Arrange.
        $text       = mb_convert_encoding("Test terms of use with UTF-16LE encoding.", "UTF-16LE");
        $rawContent = "\x01eng\xff\xfe".$text;

        // Act.
        $parser = new UserFrame(self::$_tagParserVersion3, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'encoding'   => EncodingFactory::getUtf16(),
            'language'   => "eng",
            'text'       => "\xff\xfe".$text,
        ];

        $this->assertResult($parser, self::$_frameId, $array);
    }
    // endregion


    // region Version 2.4.0, parse
    /** @covers ::parse */
    public function testParseIsoVersion4()
    {
        // Arrange.
        $text       = "Test terms of use with ISO-8859-1 encoding.";
        $rawContent = "\x00eng".$text;

        // Act.
        $parser = new UserFrame(self::$_tagParserVersion4, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'encoding'   => EncodingFactory::getIso88591(),
            'language'   => "eng",
            'text'       => $text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }

    /** @covers ::parse */
    public function testParseUtfVersion4()
    {
        // Arrange.
        $text       = mb_convert_encoding("Test terms of use with UTF-16LE encoding.", "UTF-16LE");
        $rawContent = "\x01eng\xff\xfe".$text;

        // Act.
        $parser = new UserFrame(self::$_tagParserVersion4, self::$_frameId);
        $parser->parse($rawContent);

        // Assert.
        $arrayKey = "USER-eng";
        $array    = [
            'frameId'    => self::$_frameId,
            'rawContent' => $rawContent,
            'encoding'   => EncodingFactory::getUtf16(),
            'language'   => "eng",
            'text'       => "\xff\xfe".$text,
        ];

        $this->assertResult($parser, $arrayKey, $array);
    }
    // endregion


    // region Version 2.3.0, build
    /** @covers ::build */
    public function testBuildIsoVersion3()
    {
        // Arrange.
        $text   = "Test terms of use with ISO-8859-1 encoding.";
        $parser = new UserFrame(self::$_tagParserVersion3, self::$_frameId);

        $parser->encoding = EncodingFactory::getIso88591();
        $parser->language = "eng";
        $parser->text     = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00eng".$text;
        self::assertSame($rawContent, $content);
    }

    /** @covers ::build */
    public function testBuildUtfVersion3()
    {
        // Arrange.
        $text   = "\xff\xfe".mb_convert_encoding("Test terms of use with UTF-16LE encoding.", "UTF-16LE");
        $parser = new UserFrame(self::$_tagParserVersion3, self::$_frameId);

        $parser->encoding = EncodingFactory::getUtf16();
        $parser->language = "eng";
        $parser->text     = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x01eng".$text;
        self::assertSame($rawContent, $content);
    }
    // endregion


    // region Version 2.4.0, build
    /** @covers ::build */
    public function testBuildIsoVersion4()
    {
        // Arrange.
        $text   = "Test terms of use with ISO-8859-1 encoding.";
        $parser = new UserFrame(self::$_tagParserVersion4, self::$_frameId);

        $parser->encoding = EncodingFactory::getIso88591();
        $parser->language = "eng";
        $parser->text     = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x00eng".$text;
        self::assertSame($rawContent, $content);
    }

    /** @covers ::build */
    public function testBuildUtfVersion4()
    {
        // Arrange.
        $text   = "\xff\xfe".mb_convert_encoding("Test terms of use with UTF-16LE encoding.", "UTF-16LE");
        $parser = new UserFrame(self::$_tagParserVersion4, self::$_frameId);

        $parser->encoding = EncodingFactory::getUtf16();
        $parser->language = "eng";
        $parser->text     = $text;

        // Act.
        $content = $parser->build();

        // Assert.
        $rawContent = "\x01eng".$text;
        self::assertSame($rawContent, $content);
    }
    // endregion


    // region custom assertions
    private function assertResult(UserFrame $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
    // endregion
}


// Útƒ-8 encoded
