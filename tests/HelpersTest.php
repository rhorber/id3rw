<?php

/**
 * Test class for class Helpers.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 21.10.2018
 */
namespace Rhorber\ID3rw\Tests;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\Helpers;


/**
 * Class HelpersTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\Helpers
 */
class HelpersTest extends TestCase
{
    /** @covers ::removeSynchSafeBits */
    public function testRemoveSynchSafeBits()
    {
        // Act.
        $actual = Helpers::removeSynchSafeBits("\x1A\x46\x76");

        // Assert.
        self::assertSame(hexdec("6A376"), $actual);
    }

    /** @covers ::addSynchSafeBits */
    public function testAddSynchSafeBits()
    {
        // Act.
        $actual = Helpers::addSynchSafeBits(hexdec("6A376"));

        // Assert.
        self::assertSame("\x00\x1A\x46\x76", $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode0()
    {
        // Act.
        $actual = Helpers::getEncoding("\x00ISO-8859-1");

        // Assert.
        $expected = [
            'encoding'  => "ISO-8859-1",
            'delimiter' => "\x00",
            'content'   => "ISO-8859-1",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode1LE()
    {
        // Act.
        $actual = Helpers::getEncoding("\x01\xff\xfeUTF-16LE");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16LE",
            'delimiter' => "\x00\x00",
            'content'   => "UTF-16LE",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode1BE()
    {
        // Act.
        $actual = Helpers::getEncoding("\x01\xfe\xffUTF-16BE");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16BE",
            'delimiter' => "\x00\x00",
            'content'   => "UTF-16BE",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode2()
    {
        // Act.
        $actual = Helpers::getEncoding("\x02UTF-16BE");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16BE",
            'delimiter' => "\x00\x00",
            'content'   => "UTF-16BE",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode3()
    {
        // Act.
        $actual = Helpers::getEncoding("\x03UTF-8");

        // Assert.
        $expected = [
            'encoding'  => "UTF-8",
            'delimiter' => "\x00",
            'content'   => "UTF-8",
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
        Helpers::getEncoding("\x04Invalid Code");
    }

    /** @covers ::getEncoding */
    public function testGetEncodingCode1InvalidBom()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid BOM, got: ffff");

        // Act.
        Helpers::getEncoding("\x01\xff\xffInvalid BOM");
    }
}


// Útƒ-8 encoded
