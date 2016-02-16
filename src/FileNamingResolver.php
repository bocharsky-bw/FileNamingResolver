<?php

namespace FileNamingResolver;

use FileNamingResolver\NamingStrategy\NamingStrategyInterface;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class FileNamingResolver
{
    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @param NamingStrategyInterface $namingStrategy
     */
    public function __construct(NamingStrategyInterface $namingStrategy)
    {
        $this->setNamingStrategy($namingStrategy);
    }

    /**
     * @param FileInfo $srcFileInfo The source FileInfo
     *
     * @return FileInfo The destination FileInfo
     */
    public function resolveName(FileInfo $srcFileInfo)
    {
        $dstFileInfo = $this->namingStrategy->provideName($srcFileInfo);
        if (!$dstFileInfo instanceof FileInfo) {
            throw new \RuntimeException(
                sprintf('Selected naming strategy should return an instance of FileNamingResolver\FileInfo class')
            );
        }

        return $dstFileInfo;
    }

    /**
     * @param NamingStrategyInterface $namingStrategy
     *
     * @return $this
     */
    public function setNamingStrategy(NamingStrategyInterface $namingStrategy)
    {
        $this->namingStrategy = $namingStrategy;

        return $this;
    }

    /**
     * @return NamingStrategyInterface
     */
    public function getNamingStrategy()
    {
        return $this->namingStrategy;
    }
}
