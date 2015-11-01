<?php
/**
 Author: Dominik Ryńko
 Website: http://www.rynko.pl/
 Version: 1.1
 Contact: http://www.rynko.pl/kontakt
*/

/**
 * Class todo
*/
class ToDo
{
    /**
     * @var
    */
    private $db;

    /**
    * @var array
    */
    private $notifications = [
        0 => 'Musisz mieć wersję PHP 5.4.0 lub nowszą.',
        1 => 'Błąd podczas próby połączenia z bazą danych: ',
        2 => 'Podane dane muszą być ciągiem znaków typu string.',
        3 => 'Uzupełnij wszystkie pola.',
        4 => 'Wpisz poprawną datę.',
        5 => 'Musisz podać datę większą od aktualnej',
        6 => 'Notatka dodana poprawnie.',
        7 => 'Zmiany zostały zapisane.',
        8 => 'Notatka została usunięta',
        9 => 'Notatka została uaktualniona.',
        10 => 'Wprowadź jakieś zmiany w notatce.',
        11 => 'Brak pliku config.ini',
        12 => 'Plik config.ini jest pusty'
    ];

    /**
     * @var array
    */
    private $config = [
        'errors' => [0 => 'E_ALL', 1 => 1], // 1: 0 -> hide errors, E_ALL ^ E_NOTICE -> show errors except notices, 2: 1 -> show errors, 0 -> hide errors
        'config' => ['file' => 'config.ini', 'db' => []],
    ];

    /**
     * @throws Exception
    */
    public  function __construct()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '=<')) {
            throw new Exception($this -> notifications[0]);
        } else {
            error_reporting($this -> config['errors'][0]);
            ini_set('display_error', $this -> config['errors'][1]);

            if(!file_exists($this -> config['config']['file'])) {
                throw new Exception($this->notifications[11]);
            }
            elseif(filesize($this -> config['config']['file']) == 0) {
                throw new Exception($this->notifications[12]);
            } else {
                $config = parse_ini_file($this -> config['config']['file']);

                $this -> config['db']['driver'] = $config['driver'];
                $this -> config['db']['host'] = $config['host'];
                $this -> config['db']['user'] = $config['user'];
                $this -> config['db']['password'] = $config['password'];
                $this -> config['db']['db_name'] = $config['db_name'];
                $this -> config['db']['table'] = $config['db_table'];

                $this -> dbConnection();
            }
        }
    }

    /**
     * @return bool
     * @throws Exception
    */
    private function dbConnection()
    {
        try {
            $this -> db = new PDO($this -> config['db']['driver'].'dbname='.$this -> config['db']['db_name'].';host='.$this -> config['db']['host'], $this -> config['db']['user'], $this -> config['db']['password']);
            $this -> db -> query('SET NAMES utf8');
            $this -> db -> query('SET CHARACTER_SET utf8_unicode_ci');
            return true;
        } catch(PDOException $e) {
            throw new Exception($this -> notifications[1].$e -> getMessage());
        }
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
    private function isValidDate($timeout)
    {
        $date = explode('/', $timeout);

        if(!preg_match('~^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$~', $timeout)) {
            return false;
        }
        elseif(strlen($date[0]) !== 2 || strlen($date[1]) !== 2 || strlen($date[2]) !== 4) {
            return false;
        }
        elseif(!checkdate($date[1], $date[0], $date[2])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $text
     * @param $timeout
     * @return array|bool
    */
    public function addNewNote($text, $timeout)
    {
        $time = time();
        $result = [];

        if(!is_string($text) || !is_string($timeout)) {
            $result = [false, $this -> notifications[2]];
        }
        elseif(empty($text) || empty($timeout)) {
            $result = [false, $this -> notifications[3]];
        }
        elseif(!$this -> isValidDate($timeout)) {
            $result = [false, $this -> notifications[4]];
        } else {
            $timeout = str_replace('/', '-', $timeout);
            $timeout = strtotime($timeout);

            if($time > $timeout) {
                $result = [false, $this -> notifications[5]];
            } else {
                if (is_int($timeout)) {
                    $text = trim(htmlspecialchars($text));
                    $timeout = trim(htmlspecialchars($timeout));

                    $stmt = $this -> db -> prepare('INSERT INTO '.$this -> config['db']['table'].' VALUES("", :text, "1", :time, :timeout)');
                     $stmt -> bindParam(':text', $text, PDO::PARAM_STR);
                     $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
                     $stmt -> bindParam(':timeout', $timeout, PDO::PARAM_INT);
                    $stmt -> execute();

                    if ($stmt -> rowCount() == 1) {
                        $result = [true, $this -> notifications[6]];
                    }
                }
            }
        }
       return $result;
    }

    /**
     * @return array
    */
    public function getNotesList()
    {
        $stmt = $this -> db -> prepare('SELECT * FROM '.$this -> config['db']['table'].' ORDER BY timeout');
        $stmt -> execute();
        $result = $stmt -> fetchAll();

        return $result;
    }

    /**
     * @param $id
     * @return bool
    */
    private function isNoteExist($id)
    {
        $id = trim(htmlspecialchars($id));

        $stmt = $this -> db -> prepare('SELECT * FROM '.$this -> config['db']['table'].' WHERE id = :id');
         $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();

        if ($stmt -> rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return array|null
    */
    public function doneNote($id)
    {
        $id = trim(htmlspecialchars($id));

        if ($this -> isNoteExist($id) == true) {
            $stmt = $this -> db -> prepare('UPDATE '.$this -> config['db']['table'].' SET `status` = "2" WHERE id = :id');
             $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[7]];
            }
        } else {
            return null;
        }
        return null;
    }

    /**
     * @param $id
     * @return array|null
    */
    public function deleteNote($id)
    {
        $id = trim(htmlspecialchars($id));

        if ($this -> isNoteExist($id) == true) {
            $stmt = $this -> db -> prepare('DELETE FROM  '.$this -> config['db']['table'].' WHERE id = :id');
             $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[8]];
            }
        } else {
            return null;
        }
        return null;
    }

    /**
     * @param $id
     * @return array|null
    */
    public function currentNote($id)
    {
        $id = trim(htmlspecialchars($id));

        if ($this -> isNoteExist($id) == true) {
            $stmt = $this -> db -> prepare('UPDATE '.$this -> config['db']['table'].'  SET `status` = "1" WHERE id = :id');
             $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[9]];
            } else {
                return null;
            }
         }
         return null;
    }

    /**
     * @param $id
     * @return bool
    */
    public function getOneRecord($id)
    {
        $id = trim(htmlspecialchars($id));

        $stmt = $this -> db -> prepare('SELECT text, timeout FROM '.$this -> config['db']['table'].' WHERE id = :id');
         $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        $result = $stmt -> fetch();

        return $result;
     }

    /**
     * @param $data
     * @return array
    */
    public function editNote($data)
    {
        $time = time();
        $timeout = str_replace('/', '-', trim($data['timeout']));
        $timeout = strtotime($timeout);

        if(empty($data['text']) || empty($data['timeout'])) {
            $return = [false, $this -> notifications[3]];
        }
        elseif(!$this -> isValidDate($data['timeout'])) {
            $return = [false, $this -> notifications[4]];
        }
        elseif($time > $timeout) {
            $return = [false, $this -> notifications[4]];
        } else {
            array_map('trim', $data);
            array_map('htmlspecialchars', $data);

            $stmt = $this -> db -> prepare('UPDATE  '.$this -> config['db']['table'].' SET text = :text, timeout = :timeout WHERE id = :id');
             $stmt -> bindParam(':text', $data['text'], PDO::PARAM_STR);
             $stmt -> bindParam(':timeout', $timeout, PDO::PARAM_STR);
             $stmt -> bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                $return = [true, $this -> notifications[9]];
            } else {
                $return = [true, $this -> notifications[10]];
            }
        }
        return $return;
    }

    /**
     * @param $timeout
     * @return bool
    */
    public function isNoteCurrent($timeout)
    {
        $time = time();

        if($time < $timeout) {
            return false;
        } else {
            return true;
        }
    }
}
?>