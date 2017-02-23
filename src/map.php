<?php

session_start();
require_once 'config.inc.php';

if ($user[su] >= 1) {
    addnav("mapcreation.php");
}
if ($user[pos] != $user[endpos]) {
    $inhalt = "<meta http-equiv='refresh' content='65; URL=map.php'>";
}

$xstart = mysql_real_escape_string($_POST[x] - 3);
if ($_POST[x] == "") {
    $xstart = mysql_real_escape_string($_GET[x] - 3);
    if ($_GET[x] == "") {
        $xstart = $posx - 3;
        if ($posx == "") {
            $xstart = 497;
        }
    }
}

$ystart = mysql_real_escape_string($_POST[y] - 3);
if ($_POST[y] == "") {
    $ystart = mysql_real_escape_string($_GET[y] - 3);
    if ($_GET[y] == "") {
        $ystart = $posy - 3;
        if ($posy == "") {
            $ystart = 497;
        }
    }
}

if ($xstart < 0) {
    $xstart = 0;
}
if ($xstart > 994) {
    $xstart = 994;
}

if ($ystart < 0) {
    $ystart = 0;
}
if ($ystart > 994) {
    $ystart = 994;
}

$inhalt .= "";
$inhalt .= "<p align='right'>
                <form style='text-align: right;' action='map.php' method='post' enctype='multipart/form-data'>
";

$inhalt .= "
                    <input style='width: 50px; text-align: center;' id='x' name='x' type='text' value='" . ($xstart + 3) . "'>
                    <input style='width: 50px; text-align: center;' id='y' name='y' type='text' value='" . ($ystart + 3) . "'>
                    <input id='search' type='submit' value='$lang_show'>
                </form>
            </p>";
$inhalt .= "<table width='100%' height='100%' border='0' cellspacing='0' cellpadding='3'>";
$inhalt .= "<tr>";
$inhalt .= "<td></td><td style='width: 15px;'><center><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart + 2) . "'><img src='images/go_up.gif'></a><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart - 4) . "'><img src='images/jump_up.gif'></a><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart + 2) . "'><img src='images/go_up.gif'></a></center></td><td></td>";
$inhalt .= "</tr>";
$inhalt .= "<tr>";
$inhalt .= "<td style='text-align: right;'><a href='map.php?x=" . ($xstart + 2) . "&y=" . ($ystart + 3) . "'><img src='images/go_left.gif'></a><br><a href='map.php?x=" . ($xstart - 4) . "&y=" . ($ystart + 3) . "'><img src='images/jump_left.gif'></a><br><a href='map.php?x=" . ($xstart + 2) . "&y=" . ($ystart + 3) . "'><img src='images/go_left.gif'></a></td>";
$inhalt .= "<td width='100%' height='100%'>";

//inhalt

$inhalt .= "<table width='100%' height='100%' align='center' border='0' cellspacing='0' cellpadding='3'>";

for ($y = $ystart; $y <= $ystart + 6; $y++) {
    $inhalt .= "<tr>";
    for ($x = $xstart; $x <= $xstart + 6; $x++) {
        $userava = "<br>";
        $sqluserpos = mysql_query("select * from alk_user WHERE pos = '$x:$y'");
        while ($userpos = @mysql_fetch_array($sqluserpos)) {
            if (isOnline($userpos[id])) {
            $userava .= "<a href='profile.php?&id=$userpos[id]'><img src='$userpos[avatar]' style='border: 1px solid; border-color: 555555; width: 16px; height: 16px;'></a>";
            }
            else {
                $userava .= "";
            }
        }
        $userava .= "<br>";
        $mapabfragen = mysql_query("select * from alk_map WHERE x=$x AND y=$y");
        $map = @mysql_fetch_array($mapabfragen);

        $inhalt .= "<td style='background-image: url(\"images/background/$map[backimg].png\");'><center><small>";
        if ($user[su] >= 1) {
            $inhalt .= "<a href='mapcreation.php?x=$x&y=$y'>Style</a><br>";
        }
        if ($map[name] != "") {
            $inhalt .= "<img style='border: 1px solid; width: 32px; height: 32px;' src='images/map/$map[img].png'><br><a href='move.php?move&x=$map[x]&y=$map[y]'>$map[name]</a><br>";
        }
        $inhalt .= "$userava</small></center></td>";
    }
    $inhalt .= "</tr>";
}

$inhalt .= "</table>";

//inhalt ende

$inhalt .= "</td>";
$inhalt .= "<td align='left' style=''><a href='map.php?x=" . ($xstart + 4) . "&y=" . ($ystart + 3) . "'><img src='images/go_right.gif'></a><br><a href='map.php?x=" . ($xstart + 10) . "&y=" . ($ystart + 3) . "'><img src='images/jump_right.gif'></a><br><a href='map.php?x=" . ($xstart + 4) . "&y=" . ($ystart + 3) . "'><img src='images/go_right.gif'></a></td>";
$inhalt .= "</tr>";
$inhalt .= "<tr>";
$inhalt .= "<td></td><td style='width: 15px;'><center><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart + 4) . "'><img src='images/go_down.gif'></a><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart + 10) . "'><img src='images/jump_down.gif'></a><a href='map.php?x=" . ($xstart + 3) . "&y=" . ($ystart + 4) . "'><img src='images/go_down.gif'></a></center></td><td></td>";
$inhalt .= "</tr>";
$inhalt .= "</table>";

$sitetitle = $lang_map;
include_once 'template.php';
?>