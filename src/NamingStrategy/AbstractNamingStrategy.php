<?php

namespace FileNamingResolver\NamingStrategy;

/**
 * @author Victor Bocharsky <bocharsky.bw@gmail.com>
 */
abstract class AbstractNamingStrategy implements NamingStrategyInterface
{
    /**
     * @return string
     */
    public static function generateUniqueString()
    {
        return str_replace('.', '', microtime(true)).uniqid();
    }
}
