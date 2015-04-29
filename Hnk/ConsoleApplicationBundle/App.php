<?php

namespace Hnk\ConsoleApplicationBundle;

class App
{
    /**
     * @var App
     */
    private static $instance = null;

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new App();
        }

        return self::$instance;
    }
}
