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
}


// Útƒ-8 encoded
