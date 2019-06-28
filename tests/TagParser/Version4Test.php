<?php

/**
 * Test class for class Version4.
 *
 * @package Rhorber\ID3rw\Tests\TagParser
 * @author  Raphael Horber
 * @version 28.06.2019
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
        $expected = [
            'encoding'  => "ISO-8859-1",
            'delimiter' => "\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode1()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x01");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16",
            'delimiter' => "\x00\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode2()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x02");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16BE",
            'delimiter' => "\x00\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode3()
    {
        // Act.
        $actual = self::$_parser->getEncoding("\x03");

        // Assert.
        $expected = [
            'encoding'  => "UTF-8",
            'delimiter' => "\x00",
        ];
        self::assertSame($expected, $actual);
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
