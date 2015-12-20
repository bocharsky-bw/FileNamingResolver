<?php

namespace Tests\FileNamingResolver;

use FileNamingResolver\FileInfo;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class FileInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testSeparatorDirectoryConstant()
    {
        $this->assertEquals(DIRECTORY_SEPARATOR, FileInfo::SEPARATOR_DIRECTORY);
    }

    public function testSeparatorExtensionConstant()
    {
        $this->assertEquals('.', FileInfo::SEPARATOR_EXTENSION);
    }

    public function testGetBasename()
    {
        $fileInfo = new FileInfo(__FILE__);
        $filename = $fileInfo->getFilename();
        $basename = trim($filename, '.'.$fileInfo->getExtension());

        $this->assertEquals($basename, $fileInfo->getBasename());
    }

    public function testIsExists()
    {
        $existentFileInfo = new FileInfo(__FILE__);
        $nonExistentFileInfo = new FileInfo(ltrim(__FILE__, '/'));

        $this->assertTrue($existentFileInfo->isExists());
        $this->assertFalse($nonExistentFileInfo->isExists());
    }

    public function testCreatePathname()
    {
        $path = ' /var/www/html/ ';
        $basename = ' /index/ ';
        $extension = ' .html ';
        $pathname = FileInfo::createPathname($path, $basename, $extension);

        $this->assertEquals('/var/www/html/index.html', $pathname);
    }

    public function testPurifyPath()
    {
        $path = ' /var/www/html/ ';

        $this->assertEquals('/var/www/html', FileInfo::purifyPath($path));
    }

    public function testPurifyBasename()
    {
        $basename = ' /index/ ';

        $this->assertEquals('index', FileInfo::purifyBasename($basename));
    }

    public function testPurifyExtension()
    {
        $extension = ' .html ';

        $this->assertEquals('html', FileInfo::purifyExtension($extension));
    }

    public function testChangePath()
    {
        $srcFileInfo = new FileInfo(__FILE__);

        $dstFileInfo = $srcFileInfo->changePath(__DIR__.'/uploads/');
        $this->assertEquals(__DIR__.'/uploads', $dstFileInfo->getPath());
        $this->assertNotSame($srcFileInfo, $dstFileInfo);
    }

    public function testChangeBasename()
    {
        $srcFileInfo = new FileInfo(__FILE__);

        $dstFileInfo = $srcFileInfo->changeBasename('/index/');
        $this->assertEquals('index', $dstFileInfo->getBasename());
        $this->assertNotSame($srcFileInfo, $dstFileInfo);
    }

    public function testChangeExtension()
    {
        $srcFileInfo = new FileInfo(__FILE__);

        $dstFileInfo = $srcFileInfo->changeExtension(' .html ');
        $this->assertEquals('html', $dstFileInfo->getExtension());
        $this->assertNotSame($srcFileInfo, $dstFileInfo);
    }

    public function testGetPathRelativeTo()
    {
        $fileInfo = new FileInfo('/var/www/web/uploads/products/image.jpg');

        $this->assertEquals('uploads/products', $fileInfo->getPathRelativeTo('/var/www/web'));
    }

    public function testGetPathnameRelativeTo()
    {
        $fileInfo = new FileInfo('/var/www/web/uploads/products/image.jpg');

        $this->assertEquals('uploads/products/image.jpg', $fileInfo->getPathnameRelativeTo('/var/www/web'));
    }

    public function testToString()
    {
        $fileInfo = new FileInfo(__FILE__);

        $this->assertEquals(__FILE__, $fileInfo->toString());
    }
}
