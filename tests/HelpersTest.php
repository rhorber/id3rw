<?php

/**
 * Test class for class Helpers.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 09.01.2019
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

    /** @covers ::dec2bin */
    public function testDec2BinEven()
    {
        // Act.
        // Hex value is '8f42'.
        $actual = Helpers::dec2bin(36674);

        // Assert.
        self::assertSame("\x8f\x42", $actual);
    }

    /** @covers ::dec2bin */
    public function testDec2BinOdd()
    {
        // Act.
        // Hex value is 'f42'.
        $actual = Helpers::dec2bin(3906);

        // Assert.
        self::assertSame("\x0f\x42", $actual);
    }

    /** @covers ::dec2bin */
    public function testDec2BinEvenWithMinLength()
    {
        // Act.
        // Hex value is '8f42'.
        $actual = Helpers::dec2bin(36674, 8);

        // Assert.
        self::assertSame("\x00\x00\x8f\x42", $actual);
    }

    /** @covers ::dec2bin */
    public function testDec2BinOddWithMinLength()
    {
        // Act.
        // Hex value is 'f42'.
        $actual = Helpers::dec2bin(3906, 8);

        // Assert.
        self::assertSame("\x00\x00\x0f\x42", $actual);
    }

    /** @covers ::dec2bin */
    public function testDec2BinEvenLongerThanMinLength()
    {
        // Act.
        // Hex value is '8f42'.
        $actual = Helpers::dec2bin(36674, 2);

        // Assert.
        self::assertSame("\x8f\x42", $actual);
    }

    /** @covers ::dec2bin */
    public function testDec2BinOddLongerThanMinLength()
    {
        // Act.
        // Hex value is 'f42'.
        $actual = Helpers::dec2bin(3906, 2);

        // Assert.
        self::assertSame("\x0f\x42", $actual);
    }

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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

    /** @covers ::splitString */
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
}


// Útƒ-8 encoded
