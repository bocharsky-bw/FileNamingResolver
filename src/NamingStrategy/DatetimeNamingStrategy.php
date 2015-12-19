<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class DatetimeNamingStrategy extends AbstractNamingStrategy
{
    const FORMAT_DIR_YEAR_MONTH_DAY = 'Y/m/d';
    const FORMAT_DIR_YEAR_MONTH = 'Y/m';
    const FORMAT_DIR_YEAR = 'Y';
    const FORMAT_FILE_DAY_TIME_MICROSECONDS = 'd-H-i-s-u';
    const FORMAT_FILE_DAY_TIME_SECONDS = 'd-H-i-s';
    const FORMAT_FILE_TIME_MICROSECONDS = 'H-i-s-u';
    const FORMAT_FILE_TIME_SECONDS = 'H-i-s';
    const FORMAT_FILE_TIMESTAMP_MICROSECONDS = 'U-u';
    const FORMAT_FILE_TIMESTAMP_SECONDS = 'U';

    /**
     * @var string Datetime directory format
     */
    protected $dirFormat;

    /**
     * @var string Datetime file format
     */
    protected $fileFormat;

    /**
     * @param string $dirFormat
     * @param string $fileFormat
     */
    public function __construct($dirFormat = self::FORMAT_DIR_YEAR_MONTH, $fileFormat = self::FORMAT_FILE_TIME_MICROSECONDS)
    {
        $this->dirFormat = (string)$dirFormat;
        $this->fileFormat = (string)$fileFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $datetime = \DateTime::createFromFormat('U.u', microtime(true));

        $pathSuffix = FileInfo::purifyPath($datetime->format($this->dirFormat));
        $dstFileInfo = $srcFileInfo
            ->changePath($srcFileInfo->getPath().FileInfo::SEPARATOR_DIRECTORY.$pathSuffix)
            ->changeBasename($datetime->format($this->fileFormat))
        ;

        return $dstFileInfo->toString();
    }

    /**
     * @return string
     */
    public function getDirFormat()
    {
        return $this->dirFormat;
    }

    /**
     * @return string
     */
    public function getFileFormat()
    {
        return $this->fileFormat;
    }
}
