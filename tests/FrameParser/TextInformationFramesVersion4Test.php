<?php

/**
 * Test class for class TextInformationFrames, Version 2.4.0.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Encoding\EncodingFactory;
use Rhorber\ID3rw\FrameParser\TextInformationFrames;
use Rhorber\ID3rw\TagParser\TagParserInterface;


/**
 * Class TextInformationFramesVersion4Test.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\TextInformationFrames
 */
class TextInformationFramesVersion4Test extends TestCase
{
    /** @var TagParserInterface */
    private static $_tagParser;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$_tagParser = $GLOBALS['TAG_PARSER_VERSION_4'];
    }

    /** @covers ::parse */
    public function testIsoOneString()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = "ISO-8859-1 Text";
        $rawContent  = "\x00".$information;

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => $information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testIsoOneStringTerminated()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = "ISO-8859-1 Text";
        $rawContent  = "\x00".$information."\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => $information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testIsoMultipleStrings()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = [
            "ISO-8859-1 Text 1",
            "ISO-8859-1 Text 2",
        ];
        $rawContent  = "\x00".$information[0]."\x00".$information[1];

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => $information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testIsoMultipleStringsTerminated()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = [
            "ISO-8859-1 Text 1",
            "ISO-8859-1 Text 2",
        ];
        $rawContent  = "\x00".$information[0]."\x00".$information[1]."\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => $information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testIsoEmptyString()
    {
        // Arrange.
        $identifier = "TIT2";
        $rawContent = "\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => "",
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testUtfOneString()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = mb_convert_encoding("UTF-16LE Text", "UTF-16LE");
        $rawContent  = "\x01\xff\xfe".$information;

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => "\xff\xfe".$information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testUtfOneStringTerminated()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = mb_convert_encoding("UTF-16LE Text", "UTF-16LE");
        $rawContent  = "\x01\xff\xfe".$information."\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => "\xff\xfe".$information,
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testUtfMultipleStrings()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = [
            mb_convert_encoding("UTF-16LE Text 1", "UTF-16LE"),
            mb_convert_encoding("UTF-16LE Text 2", "UTF-16LE"),
        ];
        $rawContent  = "\x01\xff\xfe".$information[0]."\x00\x00\xff\xfe".$information[1];

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => [
                "\xff\xfe".$information[0],
                "\xff\xfe".$information[1],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testUtfMultipleStringsTerminated()
    {
        // Arrange.
        $identifier  = "TIT2";
        $information = [
            mb_convert_encoding("UTF-16LE Text 1", "UTF-16LE"),
            mb_convert_encoding("UTF-16LE Text 2", "UTF-16LE"),
        ];
        $rawContent  = "\x01\xff\xfe".$information[0]."\x00\x00\xff\xfe".$information[1]."\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => [
                "\xff\xfe".$information[0],
                "\xff\xfe".$information[1],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testUtfEmptyString()
    {
        // Arrange.
        $identifier = "TIT2";
        $rawContent = "\x01\xff\xfe\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => "\xff\xfe",
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testTmclFrameIso()
    {
        // Arrange.
        $identifier  = "TMCL";
        $information = [
            "Saxophone",
            "Raphael Horber",
            "Piano",
            "Stefan Horber",
        ];
        $rawContent  = "\x00".$information[0]."\x00".$information[1]."\x00";
        $rawContent  .= $information[2]."\x00".$information[3]."\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => [
                $information[0] => $information[1],
                $information[2] => $information[3],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testTmclFrameUtf()
    {
        // Arrange.
        $identifier  = "TMCL";
        $information = [
            mb_convert_encoding("Saxophone", "UTF-16LE"),
            mb_convert_encoding("Raphael Horber", "UTF-16LE"),
            mb_convert_encoding("Piano", "UTF-16LE"),
            mb_convert_encoding("Stefan Horber", "UTF-16LE"),
        ];
        $rawContent  = "\x01\xff\xfe".$information[0]."\x00\x00\xff\xfe".$information[1]."\x00\x00";
        $rawContent  .= "\xff\xfe".$information[2]."\x00\x00\xff\xfe".$information[3]."\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => [
                "\xff\xfe".$information[0] => "\xff\xfe".$information[1],
                "\xff\xfe".$information[2] => "\xff\xfe".$information[3],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testTiplFrameIso()
    {
        // Arrange.
        $identifier  = "TIPL";
        $information = [
            "Writer",
            "Raphael Horber",
            "Producer",
            "Stefan Horber",
        ];
        $rawContent  = "\x00".$information[0]."\x00".$information[1]."\x00";
        $rawContent  .= $information[2]."\x00".$information[3]."\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getIso88591(),
            'information' => [
                $information[0] => $information[1],
                $information[2] => $information[3],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }

    /** @covers ::parse */
    public function testTiplFrameUtf()
    {
        // Arrange.
        $identifier  = "TIPL";
        $information = [
            mb_convert_encoding("Writer", "UTF-16LE"),
            mb_convert_encoding("Raphael Horber", "UTF-16LE"),
            mb_convert_encoding("Producer", "UTF-16LE"),
            mb_convert_encoding("Stefan Horber", "UTF-16LE"),
        ];
        $rawContent  = "\x01\xff\xfe".$information[0]."\x00\x00\xff\xfe".$information[1]."\x00\x00";
        $rawContent  .= "\xff\xfe".$information[2]."\x00\x00\xff\xfe".$information[3]."\x00\x00";

        // Act.
        $parser = new TextInformationFrames(self::$_tagParser, $identifier);
        $parser->parse($rawContent);

        // Assert.
        $array = [
            'frameId'     => $identifier,
            'rawContent'  => $rawContent,
            'encoding'    => EncodingFactory::getUtf16(),
            'information' => [
                "\xff\xfe".$information[0] => "\xff\xfe".$information[1],
                "\xff\xfe".$information[2] => "\xff\xfe".$information[3],
            ],
        ];

        $this->assertResult($parser, $identifier, $array);
    }


    private function assertResult(TextInformationFrames $parser, $expectedArrayKey, $expectedArray)
    {
        self::assertSame($expectedArrayKey, $parser->getArrayKey());
        self::assertSame($expectedArray, $parser->getFrameArray());
    }
}


// Útƒ-8 encoded
