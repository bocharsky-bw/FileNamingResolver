<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class CallbackNamingStrategy extends AbstractNamingStrategy
{
    /**
     * @var callable
     */
    protected $func;

    /**
     * @param callable $func
     */
    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $func = $this->func;

        return (string)$func($srcFileInfo);
    }

    /**
     * @return callable
     */
    public function getFunc()
    {
        return $this->func;
    }
}
