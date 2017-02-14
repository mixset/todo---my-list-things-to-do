<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @license http://creativecommons.org/licenses/by-sa/3.0/pl/
*/

namespace System;

use PDO;
use PDOException;

/**
 * Class Engine
 * @package Engine
 */
class Engine
{
    /**
     * Consists all data from config file
     * @var array
     */
    public static $config;

    /**
     * Path where config.php is placed
     * @var string
     */
    public $path = 'config/';

    /**
     * @var
    */
    protected $db;

    /**
     * @var array
     */
    protected $notifications = [
        0 => 'Brak pliku config.php lub plik jest pusty.',
        1 => 'Błąd podczas próby połączenia z bazą danych: ',
        2 => 'Podane dane muszą być ciągiem znaków.',
        3 => 'Uzupełnij wszystkie pola.',
        4 => 'Wpisz poprawną datę.',
        5 => 'Musisz podać datę większą od aktualnej',
        6 => 'Notatka dodana poprawnie.',
        7 => 'Wystąpił błąd podczas dodawania notatki.',
        8 => 'Zmiany zostały zapisane.',
        9 => 'Wystąpił błąd podczas zamiany statusu notatki',
        11 => 'Notatka została usunięta',
        12 => 'Wystąpił błąd podczas usuwania notatki',
        13 => 'Notatka została uaktualniona.',
        14 => 'Wystąpił błąd podczas oznaczania notatki jak aktywna.',
        15 => 'Zaktualizowano notatkę',
        16 => 'Wystąpił błąd podczas aktualizowania notatki'
    ];

    /**
     * @param none
     */
    public function __construct()
    {
        $configFile = $this -> path.'config.php';

        if (checkFile($configFile)) {
            $config = [];
            require $configFile;
            self::$config = $config;
        } else {
            Logger::error(__FILE__, __LINE__, $this -> notifications[0]);
            exit;
        }

        // Errors displaying
        error_reporting(self::$config['system']['error'][0]);
        ini_set('display_error', self::$config['system']['error'][1]);

        // Set default charset and document type
        header('Content-Type: text/html; charset='.self::$config['system']['charset']);

        // Set default timezone
        date_default_timezone_set(self::$config['system']['default_timezone']);

        // set default locale
        setlocale(LC_ALL, self::$config['system']['locale']);

        $this -> dbConnection();
    }

    /**
     * @param null $page
     */
    public function router($page = null)
    {
        $page = $this -> secure($page);

        if (is_null($page) || empty($page)) {
            $path = self::$config['path']['subpages'].'front.php';
        } else {
            $path =  self::$config['path']['subpages'].$page.'.php';
        }

        if (checkFile($path)) {
            include $path;
        } else {
            Logger::warning(__FILE__, __LINE__, 'File: ' . $path . ' does not exist or is empty.');
        }
    }

    /**
     * @return PDO
     */
    private function dbConnection()
    {
        try {
            $this -> db = new PDO(self::$config['db']['driver'].'dbname='.self::$config['db']['db_name'].';host='.self::$config['db']['host'], self::$config['db']['user'], self::$config['db']['password']);
            $this -> db -> query('SET NAMES utf8');
            $this -> db -> query('SET CHARACTER_SET utf8_unicode_ci');
        } catch(PDOException $e) {
            Logger::error(__FILE__, __LINE__, $this -> notifications[1].$e -> getMessage());
        }

        return null;
    }

    /**
     * @return bool|string
     */
    public function getTomorrowDate()
    {
        return date('d/m/Y', strtotime('+1 days', time()));
    }

    /**
     * @param $timeout
     * @return bool
     */
    protected function isValidDate($timeout)
    {
        $date = explode('/', $timeout);

        if(!preg_match('~^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$~', $timeout)) {
            return false;
        }
        elseif(strlen($date[0]) !== 2 || strlen($date[1]) !== 2 || strlen($date[2]) !== 4) {
            return false;
        } else {
            return checkdate($date[1], $date[0], $date[2]);
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function secure($data)
    {
        if (!is_array($data)) {
            return htmlspecialchars(trim($data));
        } else {
            $array = [];
            foreach ($data as $key => $value) {
                $array[$key] = $this -> secure($value);
            }

            return $array;
        }
    }

    /**
     * @param bool|false $ip2long
     * @return int
     */
    public function getIP($ip2long = false)
    {
        $ip = $this -> secure($_SERVER['REMOTE_ADDR']);

        if ($ip2long == true) {
            $ip = ip2long($ip);
        }

        return $ip;
    }
}

