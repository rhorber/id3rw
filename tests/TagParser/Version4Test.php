<?php

/**
 * Test class for class Version4.
 *
 * @package Rhorber\ID3rw\Tests\TagParser
 * @author  Raphael Horber
 * @version 01.08.2019
 */
namespace Rhorber\ID3rw\Tests\TagParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\TagParser\Version4;


/**
 * Class Version4Test.
 *
 * @coversDefaultClass \Rhorber\ID3rw\TagParser\Version4
 */
class Version4Test extends TestCase
{
    /** @var Version4 */
    private static $_parser;

    public static function setUpBeforeClass()
    {
        self::$_parser = new Version4();
    }

    /** @covers ::getMajorVersion */
    public function testGetMajorVersion()
    {
        // Assert.
        self::assertSame(4, self::$_parser->getMajorVersion());
    }

    /** @covers ::getFrameSize */
    public function testGetFrameSize()
    {
        // Act.
        $frameSize = self::$_parser->getFrameSize("\x00\x01\x08\x7f");

        // Assert.
        self::assertSame(17535, $frameSize);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode0()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x00");

        // Assert.
        self::assertInstanceOf("\\Rhorber\\ID3rw\\Encoding\\Iso88591", $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode1()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x01");

        // Assert.
        self::assertInstanceOf("\\Rhorber\\ID3rw\\Encoding\\Utf16", $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode2()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x02");

        // Assert.
        self::assertInstanceOf("\\Rhorber\\ID3rw\\Encoding\\Utf16BigEndian", $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode3()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x03");

        // Assert.
        self::assertInstanceOf("\\Rhorber\\ID3rw\\Encoding\\Utf8", $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingInvalidCode()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid text encoding, got: 04");

        // Act.
        self::$_parser->getEncoding("\x04");
    }
}


// Útƒ-8 encoded
