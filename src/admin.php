<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION['id'] . "'");
$user = mysql_fetch_array($abfragen);

if ($user[su] == 1) {
    $inhalt .= "<p style='text-align: center;'><a href='admin.php?op=monster&subop=new'>Monster Editor</a></p>";

    if ($_GET[op] == "monster") {
        $inhalt .= "<p style='text-align: center;'><a href='admin.php?op=monster'>Neues Monster</a></p>";
        if ($_GET[subop] == "") {
            if ($_GET[id] == "") {
                $inhalt .= "<table>";
                $monstersql = mysql_query("SELECT * FROM alk_monster");
                while ($monster = @mysql_fetch_array($monstersql)) {
                    $monstername = $monster[name];

                    $inhalt .= "<tr>";
                    $inhalt .= "<td>$monstername</td>";
                    $inhalt .= "<td><a href='admin.php?op=monster&subop=edit&id=$monster[id]'>[Edit]</a></td>";
                    $inhalt .= "</tr>";
                }
                $inhalt .= "</table>";
            }
        } 
        else if ($_GET[subop] == "edit") {
            $monsterid = mysql_real_escape_string($_GET[id]);
            $monstersql = mysql_query("SELECT * FROM alk_monster WHERE id = '$monsterid'");
            $monster = mysql_fetch_array($monstersql);

            $inhalt .= "Uniquename: $monster[uniquename]";

            $inhalt .= "<p style='text-align: right;'><a href='admin.php?op=monster'>$lang_back</a></p>";
        }
        else if ($_GET[subop] == "new") {
            
        }
        else if ($_GET[subop] == "del") {
           
        }
    }
} else {
    $inhalt .= "$lang_accessdenied";
}

$sitetitle = $lang_admin;
include_once 'template.php';
?>

