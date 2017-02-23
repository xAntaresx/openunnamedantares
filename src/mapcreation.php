<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    die;
}

addnav("mapcreation.php");

$abfragen = mysql_query("select * from alk_user WHERE `user` = '" . $_SESSION['user'] . "'");
$user = mysql_fetch_array($abfragen);

$posx = mysql_real_escape_string($_GET[x]);
$posy = mysql_real_escape_string($_GET[y]);

if ($user[su] == 1) {
    if (isset($_GET[style])) {
        $style = mysql_real_escape_string($_GET[style]);
        $query = mysql_Query("select count(`id`) as `gezaehlt` from `alk_map` WHERE x='$posx' AND y='$posy'");
        $rowzahl = @mysql_Fetch_Assoc($query);
        $zahl = $rowzahl["gezaehlt"];

        if ($style == "water" || "hill") {
            $block = 1;
        } else {
            $block = 0;
        }

        if ($zahl > 0) {
            mysql_query("UPDATE alk_map SET backimg = '$style', block='$block' WHERE x='$posx' AND y='$posy'");
        } else {
            mysql_query("INSERT INTO alk_map SET backimg = '$style', x='$posx', y='$posy', block='$block'");
        }
        header("location:map.php?x=$posx&y=$posy");
        die;
    }

//$inhalt .= "
//                    <select id='input' name='style' size='1'>
//                        <option value='grass'>Gras</option>
//                        <option value='water'>Wasser</option>
//                    </select>     
//";

    $inhalt .= "<a href='mapcreation.php?style=grass&x=$posx&y=$posy'>Gras</a><br>";
    $inhalt .= "<a href='mapcreation.php?style=forest&x=$posx&y=$posy'>Wald</a><br>";
    $inhalt .= "<a href='mapcreation.php?style=water&x=$posx&y=$posy'>Wasser</a><br>";
    $inhalt .= "<a href='mapcreation.php?style=hill&x=$posx&y=$posy'>Berge</a><br>";
    $inhalt .= "<a href='mapcreation.php?style=desert&x=$posx&y=$posy'>Wüste</a>";
} else {
    header("location:map.php");
    die;
}
include_once 'template.php';
?>