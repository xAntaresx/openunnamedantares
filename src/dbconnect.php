<?php

// MySql Host
$dbHost = "localhost";

// MySql Username
$dbUser = "DBUSER";

// MySql Passwort
$dbPass = "DBPASSWORD";

// MySql Database Name
$dbName = "DBNAME";

// EMail
$emailadmin = "admin@alkhemeia.de";

// Own Domain
$domain = "http://unnamed.alkhemeia.de";

$connect = @mysql_connect($dbHost, $dbUser, $dbPass) or die("Konnte keine Verbindung zum Datenbankserver aufbauen!");
$selectDB = @mysql_select_db($dbName, $connect) or die("Konnte die Datenbank <b>$dbName</b> nicht ausw&auml;hlen!");
?>