<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["id"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION[id] . "'");
$user = @mysql_fetch_array($abfragen);

if (isset($_GET[move])) {
    $posx = mysql_real_escape_string($_GET[x]);
    $posy = mysql_real_escape_string($_GET[y]);
    $currentdate = getcurrentdatetimemin()+1;
    
    mysql_query("UPDATE alk_user SET endpos = '$posx:$posy', movedate='$currentdate' WHERE id = '$user[id]'");
//    $inhalt = "<meta http-equiv='refresh' content='3; URL=map.php'>$lang_startmoving.";
    header("location:map.php");
    die;
} else {
    header("location:map.php");
    die;
}

$sitetitle = $lang_map;
include_once 'template.php';
?>