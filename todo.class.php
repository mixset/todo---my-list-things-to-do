<?php
/////////////////////////////////////////////////////////
// Author: Dominik Ryńko                               //
// Website: http://www.rynko.pl/                       //
// Version: 1.2                                       //
// Contact: http://www.rynko.pl/kontakt                //
// Info: If you want to modify this file, you have to  // 
//       leave information about author                //
// Description: Class, where are all methods           //
//                              All Rights Reserved    //
/////////////////////////////////////////////////////////

if(constant('SCRIPT') == false) die('Skrypt zablokowany!');

class todo {

 private $db;
 private $host = null;
 private $user = null; 
 private $password = null; 
 private $db_name = null;
 private $table_name = null; 
 private $address_name = null;
 private $file = 'config.ini';
 
 public function __construct() {
  
  echo $this -> setData();
 
  if($_SERVER['HTTP_HOST'] !== $this -> address_name)
   return false;
   
  if(phpversion() <= '5.0.0')
   return 'Twój server musi obsługiwać PHP w wersji 5.0.0 lub większej!';

  if(get_magic_quotes_gpc() == true) 
   ini_set('magic_quotes_gpc', 'off');
		
  error_reporting(E_ALL); // 0 -> hide errors, E_ALL ^ E_NOTICE -> show errors except notices 
  ini_set('display_error', '1'); // 1 -> show errors, 0 -> hide errors    
 
  $this -> db = new mysqli($this -> host, $this -> user, $this -> password, $this -> db_name);

  $this -> db -> query("SET NAMES utf8");
 
 }
 
 private function setData() {
 
   /*
	* @Access: private
    * @Description: method sets variables to connect with database and etc.
    * @Arguments: none
   */
  if(!file_exists($this -> file))
   return 'Brak pliku config.ini';
  if(filesize($this -> file) == 0)
   return 'Plik config.ini jest pusty';
   
  $config = parse_ini_file($this -> file);
  
  $this -> host = $config['host']; // Name of host to database
  $this -> user = $config['user']; // Name of username to database
  $this -> password = $config['password']; // Your password to database
  $this -> db_name = $config['db_name']; // Name of database 
  $this -> table_name = $config['db_table']; // Name of database 
  $this -> address_name = $config['address_name']; // Address of your website, for example: rynko.pl 

 }
 
  public function getTomorrowDate() {
  
   /*
	* @Access: public
    * @Description: method returns date plus 1 day.
    * @Arguments: none
    */
	
   return date('d/m/Y', strtotime('+1 days', time()));
  
  }
 
  private function isValidDate($timeout) {
   
	/*
	* @Access: private
    * @Description: method checks if the date is correct
    * @Arguments: (string) $timeout
    */
 
  $date = explode('/', $timeout);

  if(!preg_match('~^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$~', $timeout))
   return false;
  elseif(strlen($date[0]) !== 2 || strlen($date[1]) !== 2 || strlen($date[2]) !== 4)
   return false;
  elseif(!checkdate($date[1], $date[0], $date[2]))
   return false;
  else
   return true;
   
 }
 
 public function addNewNote($text, $timeout) {
 
   /*
	* @Access: public
    * @Description: method adds new note to database
    * @Arguments: (string) $text
	              (string) $timeout 
   */
 
 $time = time();
   
 if(!is_string($text) || !is_string($timeout))
  return array(
   0 => false,
   1 => 'Podane dane nie są typu string'
  );
 elseif(empty($text) || empty($timeout))
  return array(
   0 => false,
   1 => 'Uzupełnij wszystkie pola.'
  );
 elseif(!$this -> isValidDate($timeout))
  return array(
   0 => false,
   1 =>  'Wpisz poprawną datę.'
  );
  
   $timeout = str_replace('/', '-', $timeout);
   $timeout = strtotime($timeout);
   
   if($time > $timeout) // Podana data musi być większa od aktualnej
    return array(
     0 => false,
     1 => 'Musisz podać datę większą od aktualnej'
  );
  else
  {
   if(is_int($timeout))
   {
    $text    = trim(htmlspecialchars($this -> db -> real_escape_string($text)));
    $timeout = trim(htmlspecialchars($this -> db -> real_escape_string($timeout)));

    $stmt = $this -> db -> prepare('INSERT INTO notes VALUES("", ?, "1", ?, ?)');
     $stmt -> bind_param('sii', $text, $time, $timeout);
    $stmt -> execute();
   
    if($stmt -> affected_rows > 0)
    {
	 $stmt -> close();	 
     return array(
      0 => true,
      1 => 'Notatka dodana poprawnie.'
     );
    }
   }
   else
    return false;   
  }
 }
 
 public function getNotesList() {
  
   /*
	* @Access: public
    * @Description: method fetchs all records from database 
    * @Arguments: none
   */  
   
   $data = array();

    $stmt = $this -> db -> query('SELECT * FROM notes ORDER BY timeout');
   
   while($row = $stmt -> fetch_row())
   {
     $data[] = $row;
   }
    
   $stmt -> close();

   return $data;
 }
 
 public function doneNote($id) {
 
   /*
	* @Access: public
    * @Description: method updates record in database where id = (int)
    * @Arguments: (int) $id
   */ 
   
  $id = $this -> db -> real_escape_string(strip_tags(trim($id)));
  
  $stmt = $this -> db -> prepare('UPDATE notes SET `status` = "2" WHERE id = ?');
   $stmt -> bind_param('i', $id);
  $stmt -> execute();
  
  if($stmt -> affected_rows > 0)
  {
   $stmt -> close();
   return array(
    0 => true,
    1 => 'Zmiany zostały zapisane.'
   );
  }
   
  
 }
 
  public function deleteNote($id) {
 
   /*
	* @Access: public
    * @Description: method removes record from database where id = (int)
    * @Arguments: (int) $id
   */ 
   
  $id = $this -> db -> real_escape_string(strip_tags(trim($id)));
  
  $stmt = $this -> db -> prepare('DELETE FROM notes WHERE id = ?');
   $stmt -> bind_param('i', $id);
  $stmt -> execute();
  
  if($stmt -> affected_rows > 0)
  {
   $stmt -> close();
   return array(
    0 => true,
    1 => 'Notatka została usunięta.'
   );
  }
   
 }
 
  public function currentNote($id) {
 
    /*
	* @Access: public
    * @Description: method sets notes as current( status = 1)
    * @Arguments: (int) $id
    */ 
 
  $id = $this -> db -> real_escape_string(strip_tags(trim($id)));
  
  $stmt = $this -> db -> prepare('UPDATE notes SET `status` = "1" WHERE id = ?');
   $stmt -> bind_param('i', $id);
  $stmt -> execute();
  
  if($stmt -> affected_rows > 0)
  {
   $stmt -> close();
   return array(
    0 => true,
    1 => 'Notatka została uaktualniona.'
   );
  }
  
 }
 
 public function getOneRecord($id) {
 
   /*
	* @Access: public
    * @Description: method returns just one records from database where id = (int)
    * @Arguments: (int) $id
   */ 
   
   $id = $this -> db -> real_escape_string(strip_tags(trim($id)));

   $data = array();

    $stmt = $this -> db -> query("SELECT text, timeout FROM notes WHERE id = '".$id."'");
   
   while($row = $stmt -> fetch_row())
   {
     $data[] = $row;
   }
    
   $stmt -> close();

   return $data;

 }
 
 
  public function editNote($text, $timeout, $id) {

   /*
	* @Access: public
    * @Description: metods updates note with datas $text and $timeout where $id
    * @Arguments: (int) $id,
	              (int) $timeout,
                  (string) $text
   */  
  if(empty($text) || empty($timeout))
   return array(
    0 => false,
    1 => 'Wypełnij wszystkie pola.'
   );
  elseif(!$this -> isValidDate($timeout))
   return array(
    0 => false,
    1 => 'Wpisz poprawną datę.'
   );
   elseif(time() < strtotime($timeout)) // Podana data musi być większa od aktualnej
    return array(
   0 => false,
   1 => 'Musisz podać datę większą od aktualnej'
  );
   else
   {  
    $id      = trim(htmlspecialchars($this -> db -> real_escape_string($id)));
    $text    = trim(htmlspecialchars($this -> db -> real_escape_string($text)));
	
    $timeout = str_replace('/', '-', trim($timeout));
    $timeout = strtotime($timeout);
  
    $stmt = $this -> db -> prepare("UPDATE notes SET text = ?, timeout = ? WHERE id = ?");
     $stmt -> bind_param('sii', $text, $timeout, $id);
    $stmt -> execute();

   if($stmt -> affected_rows > 0)
   {
    $stmt -> close();
    return array(
     0 => true,
     1 => 'Zmiany zapisano poprawnie.'
    );
   }
   else
   {
    $stmt -> close();
    return array(
     0 => true,
     1 => 'Wprowadź jakieś zmiany w notatce.'
    );
   }

  }
 }
 
 public function isNoteCurrent($timeout) {
    
 /* 
  * @Access: public
  * @Description: method checks is note current
  * @Arguments: (int) $timeout
 */ 
   
 $time = time();

 if($time < $timeout)
  return false;
 else
  return true;

 }

}



?>