<?php

/**
 * Test class for class Reader.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 24.12.2018
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

    public function testUfidFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/ufidFrame.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "UFID-http://www.id3.org/dummy/ufid.html";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => null,
            'content'  => "id-42",
        ];
        $this->assertFrameContent($frame, $expected);
    }

    public function testTextFrameMultipleStrings()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TIT1";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => "UTF-16LE",
            'content'  => [
                "UTF-16LE Text 1",
                "UTF-16LE Text 2",
            ],
        ];
        $this->assertTextFrame($frame, $expected);
    }

    public function testTextFrameMultipleStringsTerminated()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TIT2";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => "UTF-16LE",
            'content'  => [
                "UTF-16LE Text 1",
                "UTF-16LE Text 2",
            ],
        ];
        $this->assertTextFrame($frame, $expected);
    }

    public function testTextFrameOneString()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TIT3";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => "UTF-16LE",
            'content'  => "UTF-16LE Text",
        ];
        $this->assertTextFrame($frame, $expected);
    }

    public function testTextFrameEmptyString()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TALB";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => "UTF-16LE",
            'content'  => "",
        ];
        $this->assertTextFrame($frame, $expected);
    }

    public function testTmclFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TMCL";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => 'UTF-16LE',
            'content'  => [
                'Saxophone' => 'Raphael Horber',
                'Piano'     => 'Stefan Horber',
            ],
        ];
        $this->assertTextFrame($frame, $expected, true);
    }

    public function testTxxxFrameIso()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TXXX-ISO-8859-1";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => 'ISO-8859-1',
            'content'  => [
                'description' => 'ISO-8859-1',
                'value'       => 'TXXX frame with ISO encoding.',
            ],
        ];
        $this->assertTextFrame($frame, $expected);
    }

    public function testTxxxFrameUtf()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/textFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "TXXX-UTF-16";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'encoding' => 'UTF-16BE',
            'content'  => [
                'description' => 'UTF-16',
                'value'       => 'TXXX frame with UTF-16BE encoding.',
            ],
        ];
        $this->assertTextFrame($frame, $expected);
    }

    /** Verify that a content array with only one element is reduced to a string. */
    public function testUrlFrame()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "WOAR";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'content' => "http://www.example.com/no-extra-text.html",
        ];
        $this->assertFrameContent($frame, $expected);
    }

    public function testUrlFrameWithSuperfluousContent()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "WCOM";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'content' => [
                0 => "http://www.example.com/first.html",
                1 => "http://www.example.com/second.html",
            ],
        ];
        $this->assertFrameContent($frame, $expected);
    }

    public function testWxxxFrameIso()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "WXXX-ISO";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'content' => [
                'description' => "ISO",
                'url'         => "http://www.example.com/iso.html",
            ],
        ];
        $this->assertFrameContent($frame, $expected);
    }

    public function testWxxxFrameUtf()
    {
        // Act.
        $reader = new Reader(__DIR__."/files/urlFrames.mp3");
        $frames = $reader->getFrames();

        // Assert.
        $identifier = "WXXX-UTF-16";
        $this->assertFrameExists($frames, $identifier);

        $frame    = $frames[$identifier];
        $expected = [
            'content' => [
                'description' => mb_convert_encoding("UTF-16", "UTF-16LE"),
                'url'         => "http://www.example.com/utf-16-html",
            ],
        ];
        $this->assertFrameContent($frame, $expected);
    }


    private function assertFrameExists($frames, $identifier)
    {
        self::assertArrayHasKey($identifier, $frames);
        self::assertSame(substr($identifier, 0, 4), $frames[$identifier]['identifier']);
    }

    private function assertFrameContent($frame, $expected)
    {
        self::assertArrayHasKey('content', $expected, "Expected array does not have key 'content'.");
        self::assertArrayHasKey('content', $frame, "Frame does not have key 'content'.");

        $expectedContent = $expected['content'];
        $frameContent    = $frame['content'];

        if (is_array($expectedContent) === true) {
            self::assertTrue(is_array($expectedContent), "Expected content to be of type 'array'.");
            self::assertCount(count($expectedContent), $frameContent);

            foreach ($expectedContent as $key => $value) {
                self::assertArrayHasKey($key, $frameContent);
                self::assertSame($value, $frameContent[$key]);
            }
        } elseif (is_string($expectedContent) === true) {
            self::assertTrue(is_string($expectedContent), "Expected content to be of type 'string'.");
            self::assertSame($expectedContent, $frameContent);
        }
    }

    private function assertTextFrame($frame, $expected, $convertKeys = false)
    {
        self::assertArrayHasKey('encoding', $expected, "Expected array does not have key 'encoding'.");
        self::assertArrayHasKey('content', $expected, "Expected array does not have key 'content'.");
        self::assertArrayHasKey('encoding', $frame, "Frame does not have key 'encoding'.");
        self::assertArrayHasKey('content', $frame, "Frame does not have key 'content'.");

        $encoding = $expected['encoding'];
        self::assertSame($encoding, $frame['encoding'], "Frame's encoding does not match expected one.");

        $expectedContent = $expected['content'];
        $frameContent    = $frame['content'];

        if (is_array($expectedContent) === true) {
            self::assertTrue(is_array($expectedContent), "Expected content to be of type 'array'.");
            self::assertCount(count($expectedContent), $frameContent);

            foreach ($expectedContent as $key => $value) {
                if ($convertKeys === true) {
                    $key = mb_convert_encoding($key, $encoding);
                }
                $value = mb_convert_encoding($value, $encoding);

                self::assertArrayHasKey($key, $frameContent);
                self::assertSame($value, $frameContent[$key]);
            }
        } elseif (is_string($expectedContent) === true) {
            self::assertTrue(is_string($expectedContent), "Expected content to be of type 'string'.");
            self::assertSame(mb_convert_encoding($expectedContent, $encoding), $frameContent);
        }
    }
}


// Útƒ-8 encoded
