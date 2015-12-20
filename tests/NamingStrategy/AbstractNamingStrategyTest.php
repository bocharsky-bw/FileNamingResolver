<?php

namespace Tests\FileNamingResolver\NamingStrategy;

use FileNamingResolver\NamingStrategy\AbstractNamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
class AbstractNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateUniqueString()
    {
        $uniqueString1 = AbstractNamingStrategy::generateUniqueString();
        $uniqueString2 = AbstractNamingStrategy::generateUniqueString();

        $this->assertNotEquals($uniqueString1, $uniqueString2);
    }
}
