<?php

namespace app;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
//    const DB_HOST = '65.49.216.128';
    const DB_HOST = '127.0.0.1';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'cooking_world';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '1226237liyang';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    static $ROOT_ABSOLUTE_PATH;
    public static function get_root_path()
    {
        if (!isset($ROOT_ABSOLUTE_PATH))
        {
            $ROOT_ABSOLUTE_PATH  = dirname(__DIR__);
        }
        return $ROOT_ABSOLUTE_PATH;
    }
}
