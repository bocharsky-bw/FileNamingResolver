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
     * @param FileInfo $srcFileInfo
     * @return string
     */
    public function resolveName(FileInfo $srcFileInfo)
    {
        return (string)$this->namingStrategy->provideName($srcFileInfo);
    }

    /**
     * @param NamingStrategyInterface $namingStrategy
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
