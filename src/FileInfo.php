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
     * Returns base name of file without extension (or base name of directory).
     *
     * @return string
     */
    public function getBasename()
    {
        $suffix = static::SEPARATOR_EXTENSION.$this->getExtension();

        return parent::getBasename($suffix);
    }

    /**
     * Checks whether a file / directory exists.
     *
     * @return bool
     */
    public function isExists()
    {
        return file_exists($this->toString());
    }

    /**
     * Creates full pathname to the file / directory based on its path, basename and extension
     *
     * @param string $path Directory path to the file (or directory)
     * @param string $basename Base name of file without extension (or base name of directory)
     * @param string $extension [OPTIONAL] File extension (empty for directory)
     *
     * @return string
     */
    public static function createPathname($path, $basename, $extension = '')
    {
        $pathname = ''
            .static::purifyPath($path)
            .static::SEPARATOR_DIRECTORY
            .static::purifyBasename($basename)
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
     * Changes directory path to the file / directory.
     *
     * @param string $path
     *
     * @return static
     */
    public function changePath($path)
    {
        $pathname = $this->createPathname($path, $this->getBasename(), $this->getExtension());

        return new static($pathname);
    }

    /**
     * Changes base name of file without extension (or base name of directory).
     *
     * @param string $basename
     *
     * @return static
     */
    public function changeBasename($basename)
    {
        $pathname = $this->createPathname($this->getPath(), $basename, $this->getExtension());

        return new static($pathname);
    }

    /**
     * Changes file extension.
     *
     * @param string $extension
     *
     * @return static
     */
    public function changeExtension($extension)
    {
        $pathname = $this->createPathname($this->getPath(), $this->getBasename(), $extension);

        return new static($pathname);
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
