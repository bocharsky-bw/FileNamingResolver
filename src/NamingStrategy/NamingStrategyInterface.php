<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
interface NamingStrategyInterface
{
    /**
     * @param FileInfo $srcFileInfo The source FileInfo
     *
     * @return FileInfo The destination FileInfo
     */
    public function provideName(FileInfo $srcFileInfo);
}
