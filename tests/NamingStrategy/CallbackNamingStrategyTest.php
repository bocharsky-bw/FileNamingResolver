<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\FileInfo;
use FileNamingResolver\NamingStrategy\CallbackNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class CallbackNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFunc()
    {
        $func = function(FileInfo $fileInfo) {
            return $fileInfo->toString();
        };
        $strategy = new CallbackNamingStrategy($func);

        $this->assertSame($func, $strategy->getCallback());
    }
}
