<?php

/**
 * Test class for class Version4.
 *
 * @package Rhorber\ID3rw\Tests\TagWriter
 * @author  Raphael Horber
 * @version 28.06.2019
 */
namespace Rhorber\ID3rw\Tests\TagWriter;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\TagWriter\Version4;


/**
 * Class Version4Test.
 *
 * @coversDefaultClass \Rhorber\ID3rw\TagWriter\Version4
 */
class Version4Test extends TestCase
{
    /** @var Version4 */
    private static $_writer;

    public static function setUpBeforeClass()
    {
        self::$_writer = new Version4();
    }

    /** @covers ::getVersion */
    public function testGetVersion()
    {
        // Assert.
        self::assertSame("\x04\x00", self::$_writer->getVersion());
    }

    /** @covers ::getFrameSize */
    public function testGetFrameSize()
    {
        // Act.
        $frameSize = self::$_writer->getFrameSize(17535);

        // Assert.
        self::assertSame("\x00\x01\x08\x7f", $frameSize);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingIso()
    {
        // Act.
        $actual = self::$_writer->getEncoding("ISO-8859-1");

        // Assert.
        $expected = [
            'code' => "\x00",
            'bom'  => "",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingUtf16Be()
    {
        // Act.
        $actual = self::$_writer->getEncoding("UTF-16BE");

        // Assert.
        $expected = [
            'code' => "\x01",
            'bom'  => "\xfe\xff",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingUtf16Le()
    {
        // Act.
        $actual = self::$_writer->getEncoding("UTF-16LE");

        // Assert.
        $expected = [
            'code' => "\x01",
            'bom'  => "\xff\xfe",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingUtf8()
    {
        // Act.
        $actual = self::$_writer->getEncoding("UTF-8");

        // Assert.
        $expected = [
            'code' => "\x03",
            'bom'  => "",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingInvalid()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid text encoding, got: invalid");

        // Act.
        self::$_writer->getEncoding("invalid");
    }
}


// Útƒ-8 encoded
