<?php

namespace App;

/**
 * Custom modification to Slim App Class
 * @package App
 */
class App extends \Slim\App
{
    /**
     * @var Utils Application support utilities
     */
    private static $utils;

    /**
     * Returns App Utils instance
     * @return Utils
     */
    public static function utils()
    {
        return self::$utils;
    }

    /**
     * App constructor.
     * @param array $container
     * @param Utils $utils
     */
    public function __construct($container = [], Utils $utils)
    {
        parent::__construct($container);

        self::$utils = $utils;
    }
}
