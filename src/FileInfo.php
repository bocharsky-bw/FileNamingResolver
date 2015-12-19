<?php

namespace FileNamingResolver;

/**
 * An IMMUTABLE object which represents abstract or real file or directory (folder)
 *
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class FileInfo extends \SplFileInfo
{
    const SEPARATOR_DIRECTORY = DIRECTORY_SEPARATOR;
    const SEPARATOR_EXTENSION = '.';

    /**
     * Returns file name without extension.
     *
     * @return string
     */
    public function getBasenameWithoutExtension()
    {
        $suffix = static::SEPARATOR_EXTENSION.$this->getExtension();

        return $this->getBasename($suffix);
    }

    /**
     * Checks whether a file or directory exists.
     *
     * @return bool
     */
    public function isExists()
    {
        return file_exists($this->toString());
    }

    /**
     * Creates full pathname to the file based on its path, basename and extension
     *
     * @param string $path
     * @param string $basenameWithoutExtension
     * @param string $extension
     *
     * @return string
     */
    public static function createPathname($path, $basenameWithoutExtension, $extension = '')
    {
        $pathname = ''
            .static::purifyPath($path)
            .static::SEPARATOR_DIRECTORY
            .static::purifyBasename($basenameWithoutExtension)
        ;
        if ($extension) {
            $pathname .= ''
                .static::SEPARATOR_EXTENSION
                .static::purifyExtension($extension)
            ;
        }

        return $pathname;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function purifyPath($path)
    {
        $path = (string)$path;
        $path = trim($path);
        $path = rtrim($path, '\\/');

        return $path;
    }

    /**
     * @param string $basename
     *
     * @return string
     */
    public static function purifyBasename($basename)
    {
        $basename = (string)$basename;
        $basename = trim($basename);
        $basename = trim($basename, '\\/');

        return $basename;
    }

    /**
     * @param string $extension
     *
     * @return string
     */
    public static function purifyExtension($extension)
    {
        $extension = (string)$extension;
        $extension = trim($extension);
        $extension = ltrim($extension, '.');

        return $extension;
    }

    /**
     * Changes directory path to the file
     *
     * @param string $path
     *
     * @return static
     */
    public function changePath($path)
    {
        $filename = $this->createPathname($path, $this->getBasenameWithoutExtension(), $this->getExtension());

        return new static($filename);
    }

    /**
     * Changes base name without extension
     *
     * @param string $basenameWithoutExtension
     *
     * @return static
     */
    public function changeBasenameWithoutExtension($basenameWithoutExtension)
    {
        $filename = $this->createPathname($this->getPath(), $basenameWithoutExtension, $this->getExtension());

        return new static($filename);
    }

    /**
     * Changes file extension
     *
     * @param string $extension
     *
     * @return static
     */
    public function changeExtension($extension)
    {
        $filename = $this->createPathname($this->getPath(), $this->getBasenameWithoutExtension(), $extension);

        return new static($filename);
    }

    /**
     * Converts object to a string representation.
     *
     * @return string
     */
    public function toString()
    {
        return (string)$this;
    }
}
