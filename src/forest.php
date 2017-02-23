<?php

session_start();
require_once 'config.inc.php';

//$userid = $user[id];

$posx = getuserposx($user[id]);
$posy = getuserposy($user[id]);

$abfragen = mysql_query("select * from alk_map WHERE x = '$posx' AND y = '$posy'");
$map = mysql_fetch_array($abfragen);

if ($_GET[op] == "fight") {
//    echo getRandomMobUniquenameFromPosition($posx, $posy);
    header("location:battle.php?op=startbattle&enemyname=" . getRandomMobUniquenameFromPosition($posx, $posy));
    die;
}

$inhalt .= "<p style='text-align: center;'><a href='" . getCurrentFile() . "?op=hunt'>$lang_hunt</a> | <a href='" . getCurrentFile() . "?action=gather'>$lang_gather</a> | <a href='" . getCurrentFile() . "?op=fight'>$lang_fight</a></p>";
$inhalt .= "<div style='background-image: url(\"images/buildings/forest1.png\");height: 200px; width: 100%; border: 1px solid; text-align: center;'></div>";
$inhalt .= "<p>$map[description] ->> ". getPlantIdFromMap($posx, $posy) ."</p>";
//$inhalt .= "<p style='text-align: center;'>$_SESSION[debugmessage]</p>";
$inhalt .= "<p style='text-align: center;'>". showNotification($_SESSION[message])."</p>";
//$_SESSION[debugmessage] = "";

//Chat anzeigen
$inhalt .= showChat($user[id]);

$inhalt .= "<br><p style='text-align: right;'><a href='map.php'>$lang_back $lang_tomap</a></p>";
$sitetitle = getMapNameByPos($pos);
include_once 'template.php';
?>

