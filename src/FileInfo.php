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
     * @param string $suffix
     *
     * @return string
     */
    public function getBasename($suffix = null)
    {
        $suffix = (string)$suffix;
        if ('' === $suffix) {
            // trim current extension from the end of this file name
            $suffix = static::SEPARATOR_EXTENSION.$this->getExtension();
        }

        return parent::getBasename($suffix);
    }

    /**
     * Checks whether a file or directory exists.
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
     * @param string $basename
     * @param string $extension
     *
     * @return string
     */
    public static function createPathname($path, $basename, $extension)
    {
        return ''
            .static::purifyPath($path)
            .static::SEPARATOR_DIRECTORY
            .static::purifyBasename($basename)
            .static::SEPARATOR_EXTENSION
            .static::purifyExtension($extension)
        ;
    }

    public static function purifyPath($path)
    {
        $path = (string)$path;
        $path = trim($path);
        $path = rtrim($path, '\\/');

        return $path;
    }

    public static function purifyBasename($basename)
    {
        $basename = (string)$basename;
        $basename = trim($basename);
        $basename = trim($basename, '\\/');

        return $basename;
    }

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
        $filename = $this->createPathname($path, $this->getBasename(), $this->getExtension());

        return new static($filename);
    }

    /**
     * Changes file name
     *
     * @param string $basename
     *
     * @return static
     */
    public function changeBasename($basename)
    {
        $filename = $this->createPathname($this->getPath(), $basename, $this->getExtension());

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
        $filename = $this->createPathname($this->getPath(), $this->getBasename(), $extension);

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
