<?php

namespace GeneroWP\Common;

trait Singleton
{
    /**
     * @var self
     */
    protected static $instances = [];

    /**
     * @return self
     */
    final public static function getInstance()
    {
        if (!isset(self::$instances[__CLASS__])) {
            self::$instances[__CLASS__] = new static();
        }
        return self::$instances[__CLASS__];
    }

    /**
     * Prevent the instance from being cloned
     *
     * @return void
     */
    final private function __clone()
    {
    }

    /**
     * Prevent from being unserialized
     *
     * @return void
     */
    final private function __wakeup()
    {
    }
}
