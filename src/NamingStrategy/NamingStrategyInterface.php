<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
interface NamingStrategyInterface
{
    /**
     * @param FileInfo $srcFileInfo
     *
     * @return string
     */
    public function provideName(FileInfo $srcFileInfo);
}
