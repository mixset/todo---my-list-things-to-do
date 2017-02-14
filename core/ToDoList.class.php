<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @Version: 1.3
 * @Contact: http://www.rynko.pl/kontakt
*/

namespace System;

use PDO;


/**
 * Class ToDoList
 * @package System
*/

class ToDoList extends Engine
{
    /**
     * @param $text
     * @param $timeout
     * @return array|bool
     */
    public function add($text, $timeout)
    {
        $time = self::$config['system']['time'];
        $result = [];

        if (!is_string($text) || !is_string($timeout)) {
            $result = [false, $this -> notifications[2]];
        }
        elseif (empty($text) || empty($timeout)) {
            $result = [false, $this -> notifications[3]];
        }
        elseif (!$this -> isValidDate($timeout)) {
            $result = [false, $this -> notifications[4]];
        } else {
            $timeout = str_replace('/', '-', $timeout);
            $timeout = strtotime($timeout);

            if($time > $timeout) {
                $result = [false, $this -> notifications[5]];
            } else {
                if (is_int($timeout)) {
                    $text = $this -> secure($text);
                    $timeout = $this -> secure($timeout);

                    $stmt = $this -> db -> prepare('INSERT INTO '.self::$config['db']['db_table'].'(`text`, `status`, `now`, `timeout`) VALUES(:text, 1, :time, :timeout)');
                     $stmt -> bindParam(':text', $text, PDO::PARAM_STR);
                     $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
                     $stmt -> bindParam(':timeout', $timeout, PDO::PARAM_INT);
                    $stmt -> execute();


                    if ($stmt -> rowCount() == 1) {
                        $result = [true, $this -> notifications[6]];
                    } else {
                        $result = [false, $this -> notifications[7]];
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
        $stmt = $this -> db -> prepare('SELECT * FROM '.self::$config['db']['db_table'].' ORDER BY timeout');
        $stmt -> execute();
        return $stmt -> fetchAll();
    }

    /**
     * @param $id
     * @return bool
     */
    public function isExist($id)
    {
        $id = $this -> secure($id);

        $stmt = $this -> db -> prepare('SELECT count(id) as amount FROM '.self::$config['db']['db_table'].' WHERE id = :id');
         $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        $result = $stmt -> fetchAll();

        return $result[0]['amount'] == 1;

    }

    /**
     * @param $id
     * @return array|null
     */
    public function done($id)
    {
        $id = $this -> secure($id);

        if ($this -> isExist($id)) {
            $stmt = $this -> db -> prepare('UPDATE '.self::$config['db']['db_table'].' SET `status` = "2" WHERE id = :id');
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if ($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[8]];
            } else {
                return [false, $this -> notifications[9]];
            }
        } else {
            Logger::info(__FILE__, __LINE__, "Given note is not found with id: " . $id);
            return null;
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    public function delete($id)
    {
        $id = $this -> secure($id);

        if ($this -> isExist($id)) {
            $stmt = $this -> db -> prepare('DELETE FROM  '.self::$config['db']['db_table'].' WHERE id = :id');
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if ($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[11]];
            } else {
                return [false, $this -> notifications[12]];
            }
        } else {
            Logger::info(__FILE__, __LINE__, "Given note is not found with id: " . $id);
            return null;
        }

    }

    /**
     * @param $id
     * @return array|null
     */
    public function current($id)
    {
        $id = $this -> secure($id);

        if ($this -> isExist($id)) {
            $stmt = $this -> db -> prepare('UPDATE '.self::$config['db']['db_table'].'  SET `status` = "1" WHERE id = :id');
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                return [true, $this -> notifications[13]];
            } else {
                return [false, $this -> notifications[14]];
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
        $id = $this -> secure($id);

        $stmt = $this -> db -> prepare('SELECT text, timeout FROM '.self::$config['db']['db_table'].' WHERE id = :id');
        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetch();
    }

    /**
     * @param $data
     * @return array
     */
    public function edit($data)
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
            $return = [false, $this -> notifications[5]];
        } else {
            $data = $this -> secure($data);

            $stmt = $this -> db -> prepare('UPDATE notes SET text = :content, timeout = :timeout WHERE id = :id');
             $stmt -> bindParam(':content', $data['text'], PDO::PARAM_STR);
             $stmt -> bindParam(':timeout', $timeout, PDO::PARAM_INT);
             $stmt -> bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt -> execute();

            if($stmt -> rowCount() == 1) {
                $return = [true, $this -> notifications[15]];
            } else {
                $return = [false, $this -> notifications[16]];
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
        return time() > $timeout;
    }
}
