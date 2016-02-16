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

        // assert equals hashes of the same source file
        $srcFileInfo2 = new FileInfo(__FILE__);
        $dstFileInfo2 = $strategy->provideName($srcFileInfo2);
        $this->assertEquals($dstFileInfo->toString(), $dstFileInfo2->toString());
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
}
