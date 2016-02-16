<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;
use FileNamingResolver\NamingStrategy\DatetimeNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class DatetimeNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testProvideName()
    {
        $srcFileInfo = new FileInfo(__FILE__);
        $strategy = new DatetimeNamingStrategy();
        $dstFileInfo = $strategy->provideName($srcFileInfo);

        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
        $this->assertStringStartsWith($srcFileInfo->getPath(), $dstFileInfo->toString());
        $this->assertStringEndsNotWith($srcFileInfo->getFilename(), $dstFileInfo->toString());
    }

    public function testGetDirFormat()
    {
        $strategy = new DatetimeNamingStrategy();
        $this->assertEquals(DatetimeNamingStrategy::FORMAT_DIR_YEAR_MONTH, $strategy->getDirFormat());

        $strategy = new DatetimeNamingStrategy(DatetimeNamingStrategy::FORMAT_DIR_YEAR_MONTH_DAY);
        $this->assertEquals(DatetimeNamingStrategy::FORMAT_DIR_YEAR_MONTH_DAY, $strategy->getDirFormat());
    }

    public function testGetFileFormat()
    {
        $strategy = new DatetimeNamingStrategy(DatetimeNamingStrategy::FORMAT_DIR_YEAR_MONTH);
        $this->assertEquals(DatetimeNamingStrategy::FORMAT_FILE_TIME_MICROSECONDS, $strategy->getFileFormat());

        $strategy = new DatetimeNamingStrategy(DatetimeNamingStrategy::FORMAT_DIR_YEAR_MONTH, DatetimeNamingStrategy::FORMAT_FILE_TIMESTAMP_MICROSECONDS);
        $this->assertEquals(DatetimeNamingStrategy::FORMAT_FILE_TIMESTAMP_MICROSECONDS, $strategy->getFileFormat());
    }
}
