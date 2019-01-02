<?php

/**
 * Test class for class Helpers.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 28.12.2018
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

    public function testSplitString()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "2222\x003333");

        // Assert.
        $expected = [
            "2222",
            "3333",
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringDelimiterAtEnd()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "2222\x003333\x00");

        // Assert.
        $expected = [
            "2222",
            "3333",
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringDelimiterOnly()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "\x00");

        // Assert.
        $expected = [
            "",
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringLimitElements()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "2222\x003333\x004444", 2);

        // Assert.
        $expected = [
            "2222",
            "3333\x004444",
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringPaddingElements()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "2222", 3);

        // Assert.
        $expected = [
            "2222",
            "",
            "",
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringUtf16Be()
    {
        // Act.
        $actual = Helpers::splitString("\x00\x00", "\x00\x32\x00\x32\x00\x32\x00\x00\x00\x33\x00\x33\x00\x33");

        // Assert.
        $expected = [
            mb_convert_encoding("222", "UTF-16BE"),
            mb_convert_encoding("333", "UTF-16BE"),
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringUtf16Le()
    {
        // Act.
        $actual = Helpers::splitString("\x00\x00", "\x32\x00\x32\x00\x32\x00\x00\x00\x33\x00\x33\x00\x33\x00");

        // Assert.
        $expected = [
            mb_convert_encoding("222", "UTF-16LE"),
            mb_convert_encoding("333", "UTF-16LE"),
        ];
        self::assertSame($expected, $actual);
    }

    public function testSplitStringUtf8()
    {
        // Act.
        $actual = Helpers::splitString("\x00", "2222\x003333");

        // Assert.
        $expected = [
            "2222",
            "3333",
        ];
        self::assertSame($expected, $actual);
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

    /** @covers ::getEncoding2 */
    public function testGetEncoding2Code0()
    {
        // Act.
        $actual = Helpers::getEncoding2("\x00");

        // Assert.
        $expected = [
            'encoding'  => "ISO-8859-1",
            'delimiter' => "\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding2 */
    public function testGetEncoding2Code1()
    {
        // Act.
        $actual = Helpers::getEncoding2("\x01");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16",
            'delimiter' => "\x00\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding2 */
    public function testGetEncoding2Code2()
    {
        // Act.
        $actual = Helpers::getEncoding2("\x02");

        // Assert.
        $expected = [
            'encoding'  => "UTF-16BE",
            'delimiter' => "\x00\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding2 */
    public function testGetEncoding2Code3()
    {
        // Act.
        $actual = Helpers::getEncoding2("\x03");

        // Assert.
        $expected = [
            'encoding'  => "UTF-8",
            'delimiter' => "\x00",
        ];
        self::assertSame($expected, $actual);
    }

    /** @covers ::getEncoding2 */
    public function testGetEncoding2InvalidCode()
    {
        // Assert.
        self::expectException("UnexpectedValueException");
        self::expectExceptionMessage("Invalid text encoding, got: 04");

        // Act.
        Helpers::getEncoding2("\x04Invalid Code");
    }
}


// Útƒ-8 encoded
