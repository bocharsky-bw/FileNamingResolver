<?php

namespace Tests\FileNamingResolver;

use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\DatetimeNamingStrategy;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class FileNamingResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetNamingStrategy()
    {
        $hashStrategy = new HashNamingStrategy();
        $datetimeStrategy = new DatetimeNamingStrategy();

        $resolver = new FileNamingResolver($hashStrategy);
        $this->assertSame($hashStrategy, $resolver->getNamingStrategy());

        $resolver->setNamingStrategy($datetimeStrategy);
        $this->assertSame($datetimeStrategy, $resolver->getNamingStrategy());
    }

    public function testResolveName()
    {
        $fileInfo = new FileInfo(__FILE__);
        $hashStrategy = new HashNamingStrategy();
        $resolver = new FileNamingResolver($hashStrategy);
        $filename = $resolver->resolveName($fileInfo);

        $this->assertInternalType('string', $filename);
        $this->assertStringStartsWith($fileInfo->getPath(), $filename);
        $this->assertStringEndsWith($fileInfo->getExtension(), $filename);
    }
}
