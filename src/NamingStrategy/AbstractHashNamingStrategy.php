<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
abstract class AbstractHashNamingStrategy extends AbstractNamingStrategy
{
    const ALGORITHM_MD5 = 'md5';
    const ALGORITHM_SHA1 = 'sha1';

    /**
     * @var string Hash algorithm
     */
    protected $algorithm;

    /**
     * @var int Count of parts
     */
    protected $partCount;

    /**
     * @var int Length of each part
     */
    protected $partLength;

    /**
     * @var bool
     */
    protected $shouldFullFilenameBeKept;

    /**
     * @param string $algorithm
     * @param int $partCount
     * @param int $partLength
     * @param bool $shouldFullFilenameBeKept
     */
    public function __construct($algorithm = self::ALGORITHM_MD5, $partCount = 2, $partLength = 2, $shouldFullFilenameBeKept = false)
    {
        $this->algorithm = (string)$algorithm;
        $this->partCount = (int)$partCount;
        $this->partLength = (int)$partLength;
        $this->shouldFullFilenameBeKept = (bool)$shouldFullFilenameBeKept;
    }

    /**
     * @param FileInfo $srcFileInfo
     * @param string $hash
     *
     * @return FileInfo
     */
    public function provideNameByHash(FileInfo $srcFileInfo, $hash)
    {
        $pathSuffixParts = array();
        for ($i = 0; $i < $this->partCount; $i++) {
            $pathSuffixParts[] = substr($hash, $i * $this->partLength, $this->partLength);
        }
        if ($this->shouldFullFilenameBeKept) {
            $name = $hash;
        } else {
            $name = substr($hash, $i * $this->partLength);
        }
        $pathSuffix = implode(FileInfo::SEPARATOR_DIRECTORY, $pathSuffixParts);
        $dstFileInfo = $srcFileInfo
            ->changeBasename($name)
            ->changePath($srcFileInfo->getPath().FileInfo::SEPARATOR_DIRECTORY.$pathSuffix)
        ;

        return $dstFileInfo;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @return int
     */
    public function getPartCount()
    {
        return $this->partCount;
    }

    /**
     * @return int
     */
    public function getPartLength()
    {
        return $this->partLength;
    }

    /**
     * @return boolean
     */
    public function shouldFullFilenameBeKept()
    {
        return $this->shouldFullFilenameBeKept;
    }
}
