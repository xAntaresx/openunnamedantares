<?php

session_start();
require("config.inc.php");

// Benutzer gesamt

$query = mysql_Query("select count(`id`) as `gezaehlt` from `alk_user` WHERE su <> 1");
$rowzahl = @mysql_Fetch_Assoc($query);
$zahl = $rowzahl["gezaehlt"];


// Neuster Benutzer

if ($zahl != "0") {
    $abfragen = mysql_query("SELECT id FROM alk_user WHERE su <> 1 ORDER BY id DESC LIMIT 0,1");
    $anzahl = @mysql_result($abfragen, 0, 0);
} else {
    $anzahl = "0";
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $anzahl . "' AND su <> 1");
$user = @mysql_fetch_array($abfragen);
$user1 = $user["user"];

if (!isset($_GET[offset]) || $_GET[offset]=="") {
    $offset = 0;
}
else {
    $offset = mysql_real_escape_string($_GET[offset]);
}
if ($offset < 0) {
    $offset = 0;
}
$inhalt .= "<p style='text-align: right;'><a href='toplist.php?lang=$_GET[lang]&offset=".($offset-100)."'><<</a> ".($offset+1)." - ".($offset+100)." <a href='toplist.php?lang=$_GET[lang]&offset=".($offset+100)."'>>></a></p>";

//     ' . $lang_newest . ' Nekromant: ' . $user1 . '<br>
$inhalt .= '
    '.$lang_player.' ' . $lang_total . ': ' . $zahl . '<br>
    <br><b>' . $lang_toplist . '</b>


<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
                <td valign="top"><b></b></td>
                <td valign="top" style="width: 10px;"><b></b></td>
		<td valign="top" style="width: 100px;"><b>' . $lang_name . '</b></td>
                <td valign="top"><b>' . $lang_class . '</b></td>
	</tr>';


$platz = "1";
//$abfragen = mysql_query("select * from necro_user WHERE user <> 'Testeral' order by points DESC limit 10");
//while ($row = @mysql_fetch_array($abfragen)) {
//    mysql_query("UPDATE necro_user SET points = ".getpoints($row[id])." WHERE id = $row[id]");
//}

$abfragen = mysql_query("select * from alk_user WHERE su <> 1 limit 100 OFFSET $offset");
while ($user = @mysql_fetch_array($abfragen)) {

    $isonline = "images/offline.gif";
    if (isOnline($user[id]) == 1) {
        $isonline = "images/online.gif";
    }
    
    $inhalt .= '
	<tr>
                <td valign="top" style="text-align: right;"></td>
                <td valign="top" style="text-align: right;"><img src="' . $user[avatar] . '" style="border: 1px solid; width: 25px; height: 25px;"></td>
                <td><a href="profile.php?lang=' . $_GET["lang"] . '&id=' . $user["id"] . '"><img src="'.$isonline.'"> ' . $user["user"] . '</a></td>
                <td style="text-align: left;">'.$user[classname].'</td>
	</tr>';
    $platz++;
}
$inhalt .= "</table>";

$sitetitle = $lang_toplist;
include_once 'template.php';
?>