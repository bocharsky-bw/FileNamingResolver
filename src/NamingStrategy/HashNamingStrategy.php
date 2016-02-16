<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class HashNamingStrategy extends AbstractHashNamingStrategy
{
    /**
     * {@inheritdoc}
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        $string = $this->generateUniqueString();
        $hash = hash($this->algorithm, $string);

        $dstFileInfo = $this->provideNameByHash($srcFileInfo, $hash);

        return $dstFileInfo;
    }
}
