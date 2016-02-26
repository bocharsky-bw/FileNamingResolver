<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class AggregateNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testProvideName()
    {
        $strategies = array(
            // 1st naming strategy
            new CallbackNamingStrategy(function(FileInfo $srcFileInfo) {
                return $srcFileInfo
                    ->changePath($srcFileInfo->getPath().'/dir1')
                    ->changeBasename('file1')
                ;
            }),
            // 2nd naming strategy
            new CallbackNamingStrategy(function(FileInfo $srcFileInfo) {
                return $srcFileInfo
                    ->changePath($srcFileInfo->getPath().'/dir2')
                    ->changeBasename('file2')
                ;
            }),
        );
        $srcFileInfo = new FileInfo('/dir/file.ext');

        $strategy = new AggregateNamingStrategy($strategies, AggregateNamingStrategy::MODE_FORWARD);
        $dstFileInfo = $strategy->provideName($srcFileInfo);
        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertEquals('/dir/dir1/dir2/file2.ext', $dstFileInfo->toString());

        $strategy = new AggregateNamingStrategy($strategies, AggregateNamingStrategy::MODE_REVERSE);
        $dstFileInfo = $strategy->provideName($srcFileInfo);
        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertEquals('/dir/dir2/dir1/file1.ext', $dstFileInfo->toString());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProvideNameException()
    {
        new AggregateNamingStrategy(array(
            new \stdClass(),
        ));
    }

    public function testGetStrategies()
    {
        $strategies = array(
            new HashNamingStrategy(),
            new DatetimeNamingStrategy(),
        );
        $strategy = new AggregateNamingStrategy($strategies);
        $this->assertSame($strategies, $strategy->getStrategies());
    }

    public function testIsForwardMode()
    {
        $strategy = new AggregateNamingStrategy(array());
        $this->assertSame(true, $strategy->isForwardMode());

        $strategy = new AggregateNamingStrategy(array(), AggregateNamingStrategy::MODE_FORWARD);
        $this->assertSame(true, $strategy->isForwardMode());
    }

    public function testIsReverseMode()
    {
        $strategy = new AggregateNamingStrategy(array(), AggregateNamingStrategy::MODE_REVERSE);
        $this->assertSame(true, $strategy->isReverseMode());
    }
}
