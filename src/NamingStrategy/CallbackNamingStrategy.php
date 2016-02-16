<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class CallbackNamingStrategy extends AbstractNamingStrategy
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $func = $this->callback;

        $dstFileInfo = $func($srcFileInfo);
        if (!$dstFileInfo instanceof FileInfo) {
            throw new \RuntimeException(
                sprintf('Callback naming strategy should return an instance of FileNamingResolver\FileInfo class')
            );
        }

        return $dstFileInfo;
    }

    /**
     * @return \Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
