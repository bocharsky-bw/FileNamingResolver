<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class HashNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testProvideName()
    {
        $srcFileInfo = new FileInfo(__FILE__);
        $strategy = new HashNamingStrategy();
        $dstFileInfo = $strategy->provideName($srcFileInfo);

        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertStringStartsWith($srcFileInfo->getPath(), $dstFileInfo->toString());
        $this->assertStringEndsNotWith($srcFileInfo->getFilename(), $dstFileInfo->toString());
    }

    public function testGetAlgorithm()
    {
        $strategy = new HashNamingStrategy();
        $this->assertEquals(HashNamingStrategy::ALGORITHM_MD5, $strategy->getAlgorithm());

        $strategy = new HashNamingStrategy(HashNamingStrategy::ALGORITHM_SHA1);
        $this->assertEquals(HashNamingStrategy::ALGORITHM_SHA1, $strategy->getAlgorithm());
    }

    public function testGetPartCount()
    {
        $strategy = new HashNamingStrategy();
        $this->assertSame(2, $strategy->getPartCount());

        $strategy = new HashNamingStrategy(HashNamingStrategy::ALGORITHM_MD5, 3);
        $this->assertSame(3, $strategy->getPartCount());
    }

    public function testGetPartLength()
    {
        $strategy = new HashNamingStrategy();
        $this->assertSame(2, $strategy->getPartLength());

        $strategy = new HashNamingStrategy(HashNamingStrategy::ALGORITHM_MD5, 2, 3);
        $this->assertSame(3, $strategy->getPartLength());
    }
}
