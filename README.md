<<<<<<< HEAD
﻿##Lista ToDo, czyli moja lista rzeczy do zrobienia
=======
##Lista ToDo, czyli moja lista rzeczy do zrobienia
>>>>>>> 1011c9d44c6635919a39edbf14474adcfca6665e
=========================

Skrypt pozwala zapisywaać rzeczy do zrobienia. Skrypt zawiera takie funkcje jak:
- zmiana statusu zadania na *zrobione* i *aktualne*
- edycja notatek
- usuwanie notatek
- ustawianie czasu wygaśnięcia

Aplikacja została napisana za pomocą języka server-side PHP oraz technologii AJAX. Szkielet strony został stworzony dzięki frameworkowi [Twitter Boostrap](http://getbootstrap.com/2.3.2/)

Główny rdzeń skryptu został zawarty w pliku todo.class.php

Sposób instalacji
-----------------

Aby zainstalować skrypt należy otworzyć plik config.ini a następnie:
<<<<<<< HEAD
- W pole **host** wpisać nazwę naszego hosta, na naszym lokalnym serverze jest to *localhost*
- W pole **user** wpisać nazwę użytkownika, na naszym lokalnym serverze jest to *root*
- W pole **password** wpisać nasze hasło do bazy danych, na naszym lokalnym serverze zazwyczaj hasła nie ma
- Pole **db_name** najlepiej pozostawić bez zmian. W przeciwnym przypadku należy zadbać, aby nazwa bazy danych pokrywała sie z nazwą w bazie danych
- Pole **db_table** zawiera nazwe tabeli, w której przechowywane beda notatki. 
=======
- W pole host wpisać nazwę naszego hosta, na naszym lokalnym serverze jest to *localhost*
- W pole user wpisać nazwę użytkownika, na naszym lokalnym serverze jest to *root*
- W pole password wpisać nasze hasło do bazy danych, na naszym lokalnym serverze zazwyczaj hasła nie ma
- Pole db_name najlepiej pozostawić bez zmian. W przeciwnym przypadku należy zadbać, aby nazwa bazy danych pokrywalo sie z tym w bazie danych
- Pole db_table zawiera nazwe tabeli, w której przechowywane beda notatki. 
>>>>>>> 1011c9d44c6635919a39edbf14474adcfca6665e

Changelog
--------
[03.05.2013]
- Optymalizacja kodu CSS
- Dodanie aria-label dla buttonów
- Optymalizacja kodu HTML
- Optymalizacja pliku script.js
- Dodano funkcję checkdatę
- Dodano metodę setData()
- Kilka przeróbek w klasie todo
- Dodano pole "Rozpoczęto" w tabeli 

[30.06.2014]
- Dane potrzebne do uruchomienia skryptu znajdują się w pliku config.ini
- Metoda setData w klasie todo pobiera dane z pliku config.ini
- Poprawa działania metody *editNote*
- Baza danych tworzy się automatycznie: nowa regułka w pliku *sql.sql*
- Dodano button *wróć* na podstronie edycji notatki

[02.11.2015]
- usunięto: `if(constant('SCRIPT') == false) die('Skrypt zablokowany!');`
- dodano komentarze, które zostały napisanie zgodnie ze wskazaniami PHPdoc.
- usunięto warunek sprawdzający wartośc funkcji get_magic_quotes_gpc()
- usunięto warunek porównujący wartość *$_SERVER['HTTP_HOST']* ze zmienną *$this -> address_name*
- dodano dwie prywatne właściwości: *$notifications* z powiadomieniami oraz *$config* z ustawieniami
- usunięto metodę setData
- dodano metodę dbConnection(), isNoteExist()
- optymalizacja html i js
- dodano datepicker

[Demo Skryptu](http://skryptoteka.rynko.pl/moja-lista-todo-czyli-lista-rzeczy-do-zrobienia)
