<?php

/**
 * Test class for class FrameParserFactory.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 06.01.2019
 */
namespace Rhorber\ID3rw\Tests\FrameParser;

use PHPUnit\Framework\TestCase;
use Rhorber\ID3rw\FrameParser\FrameParserFactory;


/**
 * Class FrameParserFactoryTest.
 *
 * @coversDefaultClass \Rhorber\ID3rw\FrameParser\FrameParserFactory
 */
class FrameParserFactoryTest extends TestCase
{
    public function testUfidFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("UFID");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UfidFrame::class, $parser);
    }

    public function testTit1Frame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("TIT1");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    public function testTit2Frame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("TIT2");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    public function testTalbFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("TALB");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    public function testTxxxFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("TXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TxxxFrame::class, $parser);
    }

    public function testWcomFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("WCOM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    public function testWcopFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("WCOP");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    public function testWxxxFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("WXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\WxxxFrame::class, $parser);
    }

    public function testMcdiFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("MCDI");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\BaseFrameParser::class, $parser);
    }

    public function testEtcoFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("ETCO");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\EtcoFrame::class, $parser);
    }

    public function testUsltFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("USLT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UsltFrame::class, $parser);
    }

    public function testCommFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("COMM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\CommFrame::class, $parser);
    }

    public function testApicFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("APIC");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\ApicFrame::class, $parser);
    }

    public function testPcntFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("PCNT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PcntFrame::class, $parser);
    }

    public function testPopmFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("POPM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PopmFrame::class, $parser);
    }

    public function testUserFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("USER");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UserFrame::class, $parser);
    }

    public function testPrivFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("PRIV");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PrivFrame::class, $parser);
    }

    public function testSignFrame()
    {
        // Act.
        $parser = FrameParserFactory::createParser("SIGN");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\SignFrame::class, $parser);
    }
}


// Útƒ-8 encoded
