<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;
use FileNamingResolver\NamingStrategy\CallbackNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class CallbackNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testProvideName()
    {
        $strategy = new CallbackNamingStrategy(function(FileInfo $srcFileInfo) {
            return $srcFileInfo
                ->changePath($srcFileInfo->getPath().'/dir1')
                ->changeBasename('file1')
            ;
        });
        $srcFileInfo = new FileInfo('/dir/file.ext');
        $dstFileInfo = $strategy->provideName($srcFileInfo);
        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertEquals('/dir/dir1/file1.ext', $dstFileInfo->toString());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testProvideNameException()
    {
        $strategy = new CallbackNamingStrategy(function(FileInfo $fileInfo) {
            return $fileInfo->toString();
        });
        $srcFileInfo = new FileInfo(__FILE__);
        $strategy->provideName($srcFileInfo);
    }

    public function testGetFunc()
    {
        $func = function(FileInfo $fileInfo) {
            return $fileInfo->toString();
        };
        $strategy = new CallbackNamingStrategy($func);

        $this->assertSame($func, $strategy->getCallback());
    }
}
