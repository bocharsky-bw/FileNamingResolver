<?php

namespace FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class ContentHashNamingStrategy extends AbstractHashNamingStrategy
{
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If source file does not exist
     */
    public function provideName(FileInfo $srcFileInfo)
    {
        if (!$srcFileInfo->isFile()) {
            throw new \InvalidArgumentException(sprintf(
                'The source file does not exist on %s specified path.',
                $srcFileInfo->toString()
            ));
        }
        $hash = hash_file($this->algorithm, $srcFileInfo->toString());

        $dstFileInfo = $this->provideNameByHash($srcFileInfo, $hash);

        return $dstFileInfo;
    }
}
