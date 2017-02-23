<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["id"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION[id] . "'");
$user = @mysql_fetch_array($abfragen);

$posx = getuserposx($user[id]);
$posy = getuserposy($user[id]);

    $mapabfragen = mysql_query("SELECT * FROM alk_map WHERE x = '$posx' AND y = '$posy'");
    $map = @mysql_fetch_array($mapabfragen);

    if ($map[file] != "") {
        header("location:$map[file]");
        die;
    } else {
        header("location:map.php");
        die;
    }



$sitetitle = $lang_map;
include_once 'template.php';

