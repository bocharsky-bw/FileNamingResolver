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
            ->willReturn(__FILE__)
        ;
        $resolver = new FileNamingResolver($mockedStrategy);
        $pathname = $resolver->resolveName($mockedFileInfo);

        $this->assertInternalType('string', $pathname);
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
