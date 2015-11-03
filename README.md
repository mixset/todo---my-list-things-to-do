##ToDo list, my list things to do
-----------------

Script allows to store tasks, which should be done. Script includes such functions like:
- task status changing for *done* and *current*
- posibility of edition and deleting notes
- setting time of expiration

This app has been writing using server-side PHP language and AJAX technology. Appearance of website has been created using framework Twitter Boostrap(http://getbootstrap.com/2.3.2/)

Main core of the system is included in *todo.class.php* file. 

How to install?
-----------------

To install script, you have to open *config.ini* file, next you have to:

- As a **host** write down name of our host, defaulty it is *localhost*
- As a **user** write down user name. On localhost, it is *root*
- As a **password** fill up with your password to database. On localhost, password usually is empty
- **db_name** leave without changes. If you want to change name of database, you have to change name in database, too.
- **db_table** consists name of table, where notes will be stored. 

Changelog
--------
[03.05.2013]
- HTML&CSS code optymalization
- Added aria-label for buttons
- script.js file has been optymalized
- A new functions has been added: `checkdate()` and `setData()`
- Some less important changes in todo.class.php file.
- *"Rozpoczęto"* field has been added to table in website.

[30.06.2014]
- Config data nessesary to start script has been moved to config.ini file 
- `setData()` method gets config date from config.ini file. 
- `editNote()` method has been dubuged.
- Database creates automatically: a new rule in sql.sql file 
- *"Wróć"* button has been added in note edition page

[02.11.2015]
- Removed; `if(constant('SCRIPT') == false) die('Skrypt zablokowany!');`
- Comments have been added according to PHPDOC instructions
- Removed statement checking value of get_magic_quotes_gpc() function
- Removed statement comparing value of `$_SERVER['HTTP_HOST']` with variable `$this -> address_name`
- Two private properties have been added:  `$notifications` and `$config`
- Removed `setData()` method 
- Two methods have been added: `dbConnection()` and `isNoteExist()`
- HTML & JS code optymalization
- Added datepicker

[Demo Skryptu](http://skryptoteka.rynko.pl/moja-lista-todo-czyli-lista-rzeczy-do-zrobienia)
