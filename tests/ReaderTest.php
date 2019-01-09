<?php

/**
 * Test class for class Reader.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Reader;


/**
 * Class ReaderTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\Reader
 */
class ReaderTest extends TestCase
{
    public function setUp()
    {
        $class = new \ReflectionClass("\\Rhorber\\ID3rw\\FrameParser\\UrlLinkFrames");

        $wcom = $class->getProperty("_wcomCounter");
        $wcom->setAccessible(true);
        $wcom->setValue(0);

        $woar = $class->getProperty("_woarCounter");
        $woar->setAccessible(true);
        $woar->setValue(0);
    }

    public function testInvalidFlags()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid header flags, got: ff");

        // Act.
        new Reader(__DIR__."/files/invalidFlags.mp3");
    }

    public function testUnknownFlags()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Unsupported header flags, got: f0");

        // Act.
        new Reader(__DIR__."/files/unknownFlags.mp3");
    }

    public function testVersion3(){
        // Act.
        $reader = new Reader(__DIR__."/files/version3.mp3");

        // Assert.
        self::assertSame(3, $reader->getVersion());
    }

    public function testVersion4(){
        // Act.
        $reader = new Reader(__DIR__."/files/version4.mp3");

        // Assert.
        self::assertSame(4, $reader->getVersion());
    }

    public function testUfidFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/ufidFrame.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "UFID-http://www.id3.org/dummy/ufid.html";
        $array    = [
            'frameId'    => "UFID",
            'owner'      => "http://www.id3.org/dummy/ufid.html",
            'identifier' => "id-42"
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTextFrameMultipleStrings()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TIT1";
        $array    = [
            'frameId'     => "TIT1",
            'encoding'    => "UTF-16",
            'information' => [
                0 => "\xff\xfe".mb_convert_encoding("UTF-16LE Text 1", "UTF-16LE"),
                1 => "\xff\xfe".mb_convert_encoding("UTF-16LE Text 2", "UTF-16LE"),
            ],
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTextFrameMultipleStringsTerminated()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TIT2";
        $array    = [
            'frameId'     => "TIT2",
            'encoding'    => "UTF-16",
            'information' => [
                0 => "\xff\xfe".mb_convert_encoding("UTF-16LE Text 1", "UTF-16LE"),
                1 => "\xff\xfe".mb_convert_encoding("UTF-16LE Text 2", "UTF-16LE"),
            ],
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTextFrameOneString()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TIT3";
        $array    = [
            'frameId'     => "TIT3",
            'encoding'    => "UTF-16",
            'information' => "\xff\xfe".mb_convert_encoding("UTF-16LE Text", "UTF-16LE"),
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTextFrameEmptyString()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TALB";
        $array    = [
            'frameId'     => "TALB",
            'encoding'    => "UTF-16",
            'information' => "\xff\xfe",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTmclFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TMCL";
        $key1     = "\xff\xfe".mb_convert_encoding("Saxophone", "UTF-16LE");
        $key2     = "\xff\xfe".mb_convert_encoding("Piano", "UTF-16LE");
        $array    = [
            'frameId'     => "TMCL",
            'encoding'    => "UTF-16",
            'information' => [
                $key1 => "\xff\xfe".mb_convert_encoding("Raphael Horber", "UTF-16LE"),
                $key2 => "\xff\xfe".mb_convert_encoding("Stefan Horber", "UTF-16LE"),
            ],
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTxxxFrameIso()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TXXX-ISO-8859-1";
        $array    = [
            'frameId'     => "TXXX",
            'encoding'    => "ISO-8859-1",
            'description' => "ISO-8859-1",
            'value'       => "TXXX frame with ISO encoding.",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testTxxxFrameUtf()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "TXXX-UTF-16";
        $array    = [
            'frameId'     => "TXXX",
            'encoding'    => "UTF-16BE",
            'description' => mb_convert_encoding("UTF-16", "UTF-16BE"),
            'value'       => mb_convert_encoding("TXXX frame with UTF-16BE encoding.", "UTF-16BE"),
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    /** Verify that a content array with only one element is reduced to a string. */
    public function testUrlFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "WOAR-1";
        $array    = [
            'frameId' => "WOAR",
            'url'     => "http://www.example.com/no-extra-text.html",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testUrlFrameWithSuperfluousContent()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "WCOM-1";
        $array    = [
            'frameId' => "WCOM",
            'url'     => "http://www.example.com/first.html",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testWxxxFrameIso()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "WXXX-ISO";
        $array    = [
            'frameId'     => "WXXX",
            'encoding'    => "ISO-8859-1",
            'description' => "ISO",
            'url'         => "http://www.example.com/iso.html",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }

    public function testWxxxFrameUtf()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $arrayKey = "WXXX-UTF-16";
        $array    = [
            'frameId'     => "WXXX",
            'encoding'    => "UTF-16",
            'description' => "\xff\xfe".mb_convert_encoding("UTF-16", "UTF-16LE"),
            'url'         => "http://www.example.com/utf-16.html",
        ];

        self::assertArrayHasKey($arrayKey, $frames);
        $this->assertFrame($array, $frames[$arrayKey]);
    }


    private function assertFrame($expectedFrame, $parsedFrame)
    {
        $actualFrame = $parsedFrame;
        unset($actualFrame['rawContent']);

        self::assertSame($expectedFrame, $actualFrame);
    }
}


// Útƒ-8 encoded
