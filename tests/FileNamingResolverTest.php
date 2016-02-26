<?php

namespace Tests\FileNamingResolver;

use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\NamingStrategyInterface;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class FileNamingResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetNamingStrategy()
    {
        $mockedStrategy = $this->createMockedStrategy();
        $resolver = new FileNamingResolver($mockedStrategy);
        $this->assertSame($mockedStrategy, $resolver->getNamingStrategy());

        $anotherMockedStrategy = $this->createMockedStrategy();
        $resolver->setNamingStrategy($anotherMockedStrategy);
        $this->assertSame($anotherMockedStrategy, $resolver->getNamingStrategy());
    }

    public function testResolveName()
    {
        $mockedFileInfo = $this->createMockedFileInfo();
        $mockedStrategy = $this->createMockedStrategy();
        $mockedStrategy
            ->expects($this->once())
            ->method('provideName')
            ->with($mockedFileInfo)
            ->willReturn(new FileInfo(__FILE__))
        ;
        $resolver = new FileNamingResolver($mockedStrategy);
        $dstFileInfo = $resolver->resolveName($mockedFileInfo);

        $this->assertInstanceOf('FileNamingResolver\FileInfo', $dstFileInfo);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResolveNameException()
    {
        $mockedFileInfo = $this->createMockedFileInfo();
        $mockedStrategy = $this->createMockedStrategy();
        $mockedStrategy
            ->expects($this->once())
            ->method('provideName')
            ->with($mockedFileInfo)
            ->willReturn(__FILE__) // return string instead of FileInfo object to throw an RuntimeException in resolveName()
        ;
        $resolver = new FileNamingResolver($mockedStrategy);
        $resolver->resolveName($mockedFileInfo);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|NamingStrategyInterface
     */
    private function createMockedStrategy()
    {
        return $this
            ->getMockBuilder('FileNamingResolver\NamingStrategy\NamingStrategyInterface')
            ->getMock()
        ;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FileInfo
     */
    private function createMockedFileInfo()
    {
        return $this
            ->getMockBuilder('FileNamingResolver\FileInfo')
            ->setConstructorArgs(array(__FILE__))
            ->getMock()
        ;
    }
}
