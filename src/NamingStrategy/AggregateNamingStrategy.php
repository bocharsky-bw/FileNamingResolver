<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * Apply each strategy to the given file in certain direction.
 *
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class AggregateNamingStrategy extends AbstractNamingStrategy
{
    const MODE_FORWARD = false;
    const MODE_REVERSE = true;

    /**
     * @var NamingStrategyInterface[]
     */
    protected $strategies = [];

    /**
     * @var bool
     */
    protected $mode;

    /**
     * @param array $strategies
     * @param bool $mode
     */
    public function __construct(array $strategies, $mode = self::MODE_FORWARD)
    {
        foreach ($strategies as $index => $strategy) {
            if (!$strategy instanceof NamingStrategyInterface) {
                throw new \InvalidArgumentException(sprintf(
                    'Strategy at index "%s" does not implement "%s" interface.',
                    $index,
                    '\FileNamingResolver\NamingStrategy\NamingStrategyInterface'
                ));
            }
            $this->strategies[] = $strategy;
        }
        $this->mode = (bool)$mode;
    }

    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $dstFileInfo = $srcFileInfo;

        $strategies = $this->isReverseMode()
            ? array_reverse($this->strategies, true)
            : $this->strategies
        ;
        foreach ($strategies as $strategy) {
            $filename = (string)$strategy->provideName($dstFileInfo);
            $dstFileInfo = new FileInfo($filename);
        }

        return $dstFileInfo->toString();
    }

    /**
     * @return NamingStrategyInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }

    /**
     * @return bool
     */
    public function isForwardMode()
    {
        return static::MODE_FORWARD === $this->mode;
    }

    /**
     * @return bool
     */
    public function isReverseMode()
    {
        return static::MODE_REVERSE === $this->mode;
    }
}
