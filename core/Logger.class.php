<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @license http://creativecommons.org/licenses/by-sa/3.0/pl/
*/

namespace System;

/**
 * Class Logger
 * @package Logger
*/
class Logger
{
    /**
     * Property with files where logs are being saved
     * @var array
     */
    private static $files = [
        'error' => 'errors.log',
        'info' => 'info.log',
        'warning' => 'warning.log',
        'debug' => 'debug.log'
    ];

    /**
     * Property with config data
     * @var array
     */
    public static $config = [
        'maxSize' => 100000,
        'maxLines' => 1000,
        'path' => 'logs/'
    ];

    /**
     * @param none
     * @return string $text
     */
    private static function getBasicData()
    {
        return "|| Date: ". date('j-m-Y, H:i:s')." || IP: ".strip_tags($_SERVER['REMOTE_ADDR'])." || userAgent: ".strip_tags($_SERVER['HTTP_USER_AGENT'])." <<<--->>>\n";
    }

    /**
     * @param string $file
     * @return boolean
     */
    private static function checkFile($file)
    {
        $path = self::$config['path'].self::$files[$file];

        if (!file_exists($path)) {
            return false;
        }

        $size = filesize($path);
        $lines = sizeof(explode('<<<--->>>', file_get_contents($path)));

        if ($size > 100000 || $lines > 1000) {
            return unlink($path);
        } else {
            return false;
        }
    }

    /**
     * @param string $file
     * @param int $line
     * @param string $info
     * @return boolean
     */
    public static function error($file, $line, $info)
    {
        Logger::checkFile('error');

        $data = "File: $file || Line: $line || Info: $info ".self::getBasicData();

        return !(file_put_contents(self::$config['path'].self::$files['error'], $data, FILE_APPEND) === false);
    }

    /**
     * @param string $file
     * @param int $line
     * @param string $info
     * @return boolean
     */
    public static function info($file, $line, $info)
    {
        Logger::checkFile('info');

        $data = "File: $file || Line: $line || Info: $info ".self::getBasicData();

        return !(file_put_contents(self::$config['path'].self::$files['info'], $data, FILE_APPEND) === false);
    }

    /**
     * @param string $file
     * @param int $line
     * @param string $info
     * @return boolean
     */
    public static function warning($file, $line, $info)
    {
        Logger::checkFile('warning');

        $data = "File: $file || Line: $line || Info: $info ".self::getBasicData();

        return !(file_put_contents(self::$config['path'].self::$files['warning'], $data, FILE_APPEND) === false);
    }

    /**
     * @param $info
     * @return bool
     */
    public static function debug($info)
    {
        Logger::checkFile('warning');

        $data = "Info: $info ".self::getBasicData();

        return !(file_put_contents(self::$config['path'].self::$files['debug'], $data, FILE_APPEND) === false);
    }

}
