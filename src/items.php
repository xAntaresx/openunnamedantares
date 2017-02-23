<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["id"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION[id] . "'");
$user = @mysql_fetch_array($abfragen);

if ($_GET[op] != "") {
    if ($_GET[op] == "eat") {
        $uniquename = mysql_real_escape_string($_GET[what]);
        removeItemByUnique($userid, $uniquename);
    }
    header("location:charakter.php?op=inventory");
    die;            
} else {
    header("location:charakter.php?op=inventory");
    die;
}

function removeItemByUnique($userid, $uniquename) {
    $uniquename = mysql_real_escape_string($uniquename);
    $result = mysql_query("SELECT * FROM alk_inventory WHERE uniquename = '$uniquename' LIMIT 1");
    $item = mysql_fetch_array($result);
    
    mysql_query("DELETE FROM alk_inventory WHERE id = '$item[id]'");
    
}

include_once 'template.php';
?>