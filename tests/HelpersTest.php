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
}


// Útƒ-8 encoded
