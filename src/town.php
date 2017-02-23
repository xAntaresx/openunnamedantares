<?php

session_start();
require 'config.inc.php';
addnav("town.php");

//Benachrichtigungen anzeigen
$inhalt .= showNotification();

//Stadtinhalt Start
$inhalt .= "<table><tr>";

$buildings = explode(",", $map[buildings]);
foreach ($buildings as $buildingid) {
    $abfragen = mysql_query("select * from alk_buildings WHERE `id` = '$buildingid'");
    $building = mysql_fetch_array($abfragen);
    addnav("$building[file]");
    $inhalt .= "<td style='text-align: center;'><a href='$building[file]?back=" . getCurrentFileNameOnly() . "'>$building[name]<br><img alt='$building[name]' src='images/buildings/$building[img]'></a></td>";
}
$inhalt .= "</tr></table>";

//Chat Anzeigen
$inhalt .= showChat($user[id]);

$inhalt .= "<br><p style='text-align: right;'><a href='map.php'>$lang_back $lang_tomap</a></p>";
$sitetitle = getMapNameByPos($pos);
include_once 'template.php';
?>

