<?php

/**
 * Test class for class FrameParserFactory.
 *
 * @package Rhorber\ID3rw\Tests\FrameParser
 * @author  Raphael Horber
 * @version 10.01.2019
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
    // region properties, and set up functions
    /** @var FrameParserFactory */
    private static $_factoryVersion3 = null;
    /** @var FrameParserFactory */
    private static $_factoryVersion4 = null;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$_factoryVersion3 = new FrameParserFactory($GLOBALS['TAG_PARSER_VERSION_3']);
        self::$_factoryVersion4 = new FrameParserFactory($GLOBALS['TAG_PARSER_VERSION_4']);
    }
    // endregion


    // region Version 2.3.0
    /** @covers ::parse */
    public function testUfidFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("UFID");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UfidFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testTit1FrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("TIT1");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTit2FrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("TIT2");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTalbFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("TALB");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTxxxFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("TXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TxxxFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testWcomFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("WCOM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testWcopFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("WCOP");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testWxxxFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("WXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\WxxxFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testMcdiFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("MCDI");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\BaseFrameParser::class, $parser);
    }

    /** @covers ::parse */
    public function testEtcoFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("ETCO");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\EtcoFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testUsltFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("USLT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UsltFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testCommFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("COMM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\CommFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testApicFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("APIC");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\ApicFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPcntFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("PCNT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PcntFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPopmFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("POPM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PopmFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testUserFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("USER");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UserFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPrivFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("PRIV");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PrivFrame::class, $parser);
    }

    /**
     * Verifies that "SIGN" in Version 2.3.0 does not produce a specific parser.
     *
     * @covers ::parse
     */
    public function testSignFrameVersion3()
    {
        // Act.
        $parser = self::$_factoryVersion3->createParser("SIGN");

        // Assert.
        // The "SIGN" frame was added in Version 2.4.0.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\BaseFrameParser::class, $parser);
    }
    // endregion


    // region Version 2.4.0
    /** @covers ::parse */
    public function testUfidFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("UFID");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UfidFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testTit1FrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("TIT1");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTit2FrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("TIT2");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTalbFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("TALB");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TextInformationFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testTxxxFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("TXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\TxxxFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testWcomFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("WCOM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testWcopFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("WCOP");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UrlLinkFrames::class, $parser);
    }

    /** @covers ::parse */
    public function testWxxxFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("WXXX");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\WxxxFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testMcdiFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("MCDI");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\BaseFrameParser::class, $parser);
    }

    /** @covers ::parse */
    public function testEtcoFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("ETCO");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\EtcoFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testUsltFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("USLT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UsltFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testCommFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("COMM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\CommFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testApicFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("APIC");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\ApicFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPcntFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("PCNT");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PcntFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPopmFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("POPM");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PopmFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testUserFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("USER");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\UserFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testPrivFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("PRIV");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\PrivFrame::class, $parser);
    }

    /** @covers ::parse */
    public function testSignFrameVersion4()
    {
        // Act.
        $parser = self::$_factoryVersion4->createParser("SIGN");

        // Assert.
        self::assertInstanceOf(\Rhorber\ID3rw\FrameParser\SignFrame::class, $parser);
    }
    // endregion
}


// Útƒ-8 encoded
