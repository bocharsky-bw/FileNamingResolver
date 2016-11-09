<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;
use FileNamingResolver\NamingStrategy\ContentHashNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class ContentHashNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testProvideName()
    {
        $strategy = new ContentHashNamingStrategy();
        $this->assertInstanceOf('FileNamingResolver\NamingStrategy\AbstractHashNamingStrategy', $strategy);

        $srcFileInfo = new FileInfo(__FILE__);
        $dstFileInfo = $strategy->provideName($srcFileInfo);
        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertStringStartsWith($srcFileInfo->getPath(), $dstFileInfo->toString());
        $this->assertStringEndsNotWith($srcFileInfo->getFilename(), $dstFileInfo->toString());

        // assert equals hashes of the same source file
        $srcFileInfo2 = new FileInfo(__FILE__);
        $dstFileInfo2 = $strategy->provideName($srcFileInfo2);
        $this->assertEquals($dstFileInfo->toString(), $dstFileInfo2->toString());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProvideNameException()
    {
        $strategy = new ContentHashNamingStrategy();
        $srcFileInfo = new FileInfo(__DIR__); // Specify not valid FQFN of source file (directory is NOT file)
        $strategy->provideName($srcFileInfo);
    }

    public function testGetAlgorithm()
    {
        $strategy = new ContentHashNamingStrategy();
        $this->assertEquals(ContentHashNamingStrategy::ALGORITHM_MD5, $strategy->getAlgorithm());

        $strategy = new ContentHashNamingStrategy(ContentHashNamingStrategy::ALGORITHM_SHA1);
        $this->assertEquals(ContentHashNamingStrategy::ALGORITHM_SHA1, $strategy->getAlgorithm());
    }

    public function testGetPartCount()
    {
        $strategy = new ContentHashNamingStrategy();
        $this->assertSame(2, $strategy->getPartCount());

        $strategy = new ContentHashNamingStrategy(ContentHashNamingStrategy::ALGORITHM_MD5, 3);
        $this->assertSame(3, $strategy->getPartCount());
    }

    public function testGetPartLength()
    {
        $strategy = new ContentHashNamingStrategy();
        $this->assertSame(2, $strategy->getPartLength());

        $strategy = new ContentHashNamingStrategy(ContentHashNamingStrategy::ALGORITHM_MD5, 2, 3);
        $this->assertSame(3, $strategy->getPartLength());
    }

    public function testShouldFullNameBeKept()
    {
        $strategy = new ContentHashNamingStrategy();
        $this->assertSame(false, $strategy->shouldFullNameBeKept());

        $strategy = new ContentHashNamingStrategy(ContentHashNamingStrategy::ALGORITHM_MD5, 2, 3, true);
        $this->assertSame(true, $strategy->shouldFullNameBeKept());
    }

    public function testFullNameIsNotKept()
    {
        $strategy = new ContentHashNamingStrategy();
        $srcFileInfo = new FileInfo(__FILE__);
        $dstFileInfo = $strategy->provideName($srcFileInfo);

        $hashFilename = str_replace(FileInfo::SEPARATOR_DIRECTORY, '', $dstFileInfo->getPathnameRelativeTo(__DIR__));
        $this->assertNotEquals($hashFilename, $dstFileInfo->getFilename());
        $this->assertStringEndsWith($dstFileInfo->getFilename(), $hashFilename);
    }

    public function testFullNameIsKept()
    {
        $strategy = new ContentHashNamingStrategy(ContentHashNamingStrategy::ALGORITHM_MD5, 2, 2, true);
        $srcFileInfo = new FileInfo(__FILE__);
        $dstFileInfo = $strategy->provideName($srcFileInfo);

        $hashPathname = str_replace(FileInfo::SEPARATOR_DIRECTORY, '', $dstFileInfo->getPathnameRelativeTo(__DIR__));
        $hashFilename = substr($hashPathname, 4); // remove prefix parts
        $this->assertEquals($hashFilename, $dstFileInfo->getFilename());
    }
}
