Lista ToDo, czyli moja lista rzeczy do zrobienia
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
- W pole host wpisać nazwę naszego hosta, na naszym lokalnym serverze jest to *localhost*
- W pole user wpisać nazwę użytkownika, na naszym lokalnym serverze jest to *root*
- W pole password wpisać nasze hasło do bazy danych, na naszym lokalnym serverze zazwyczaj hasła nie ma
- Pole db_name najlepiej pozostawić bez zmian, chybe, że majsterkuje sie przy nazwie bazy danych
- Pole db_table jw. 
- Uwaga! W pole address_name wpisać adres strony. Aby poprawnie uzupełnić to pole przekopiuj zawartość kodu: `echo $_SERVER['HTTP_HOST']` w miejsce do tego przeznaczne w pliku config.ini

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
- 

[Demo Skryptu](http://skryptoteka.rynko.pl/moja-lista-todo-czyli-lista-rzeczy-do-zrobienia)
