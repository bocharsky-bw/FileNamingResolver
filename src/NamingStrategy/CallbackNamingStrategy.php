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
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $func = $this->callback;

        return (string)$func($srcFileInfo);
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
