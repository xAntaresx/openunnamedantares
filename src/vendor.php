<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    die;
}

addnav(getCurrentFile());

//Kaufen
if (isset($_GET[buy])) {
    $itemid = mysql_real_escape_string($_GET[buy]);
    
            
    $item = getItemById($itemid);

    if ($user[gold] >= $item[gold]) {
        mysql_query("INSERT INTO `alk_inventory` ("
                . "userid,"
                . "uniquename,"
                . "name,"
                . "description,"
                . "type,"
                . "value1,"
                . "value2,"
                . "pri_skill,"
                . "sec_skill,"
                . "gold,"
                . "weight,"
                . "image,"
                . "equiped"
                . ") VALUES ("
                . "'$user[id]',"
                . "'$item[uniquename]',"
                . "'$item[name]',"
                . "'$item[description]',"
                . "'$item[type]',"
                . "'$item[value1]',"
                . "'$item[value2]',"
                . "'$item[pri_skill]',"
                . "'$item[sec_skill]',"
                . "'$item[gold]',"
                . "'$item[weight]',"
                . "'$item[image]',"
                . "'0'"
                . ")");
        mysql_query("UPDATE alk_user SET gold=gold-'$item[gold]' WHERE id = '$user[id]'");
        $messagetext = "$item[name] $lang_bought.";
    } else {
        $messagetext = "$lang_notenoughtgold.";
    }
}

// Ausgabe für Verkaufen
if ($_GET[sell]) {
    $itemid = mysql_real_escape_string($_GET[sell]);
    $itemabfragen = mysql_query("select * from alk_inventory WHERE `id` = '$itemid' AND userid = '$user[id]' LIMIT 1");
    $item = mysql_fetch_array($itemabfragen);

    $messagetext = "$item[name] $lang_sold.<br>";
    $messagetext .= "$item[gold] $lang_currency $lang_recieved.";
    
    mysql_query("UPDATE alk_user SET gold=gold+'$item[gold]' WHERE id = '$user[id]'");
    mysql_query("DELETE FROM alk_inventory WHERE id = '$itemid'");
}

//Kaufen/Verkaufen Navigation
$inhalt .= "<p style='text-align: center;'><a href='vendor.php?menue=buy&back=$_GET[back]'>$lang_buy</a> | <a href='vendor.php?menue=sell&back=$_GET[back]'>$lang_sell</a></p>";

// Liste der Items die verkauft werden können
if ($_GET[menue] == "sell") {
    $tdstyle = "style='padding-right: 25px; width: 35%;'";
    $inhalt .= "<p style='text-align: center;'>$messagetext</p>";
    $messagetext = "";
    $inhalt .= "<table style='margin-left: 25px; margin-right: 25px; width: 100%; border: 1px solid;'>";
    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle><b>$lang_item</b></td>";
    $inhalt .= "<td><b>$lang_description</b></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "</tr>";
    $lastunique = "";
    $inventorysql = mysql_query("SELECT * FROM alk_inventory WHERE userid = '$user[id]' ORDER BY uniquename");
    while ($item = @mysql_fetch_array($inventorysql)) {
            $itemname = $item[name];
            $itemdescription = $item[description];

        if ($lastunique != "") {
            if ($lastunique == $item[uniquename]) {
                $anzahl++;
            } else {
                $anzahl++;
                $lastunique = $item[uniquename];
                if ($lastequiped == 0) {
                $inhalt .= "<tr>";
                $inhalt .= "<td $tdstyle>$anzahl x $lastname"; 
                $inhalt .= "</td>";
                $inhalt .= "<td>$lastdesc</td>";
                $inhalt .= "<td><a href='vendor.php?menue=sell&sell=$lastid&back=$_GET[back]'>1x $lang_sell: $lastgold $lang_currency</a></td>";
                $inhalt .= "</tr>";
                }
                $anzahl = 0;
            }
        }

        $lastunique = $item[uniquename];
        $lastname = $itemname;
        $lastdesc = $itemdescription;
        $lastequiped = $item[equiped];
        $lastid = $item[id];
        $lastgold = $item[gold];
    }
    $lastunique = $item[uniquename];
    $anzahl++;
    if ($lastequiped == 0) {
    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$anzahl x $lastname"; 
    $inhalt .= "</td>";
    $inhalt .= "<td>$lastdesc</td>";
    $inhalt .= "<td><a href='vendor.php?menue=sell&sell=$lastid&back=$_GET[back]'>1x $lang_sell: $lastgold $lang_currency</a></td>";
    $inhalt .= "</tr>";
    }
    $anzahl = 1;
    $inhalt .= "</table>";
} 
// Liste der Items die gekauft werden können
else {
    $inhalt .= "<p style='text-align: center;'>$messagetext</p>";
    $inhalt .= "<table style='width: 100%;'>";
    $inhalt .= "<tr>";
    $inhalt .= "<td>";
    $inhalt .= "<table style='width: 100%;'>";
    $inhalt .= "<tr>";
    $inhalt .= "<td><b>$lang_item</b></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td><b>$lang_price</b></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "</tr>";
    $vendorsql = mysql_query("SELECT * FROM alk_items WHERE type='weapon'");
    while ($item = @mysql_fetch_array($vendorsql)) {
        $shoppos = explode(",", $item[shoppos]);
        if (in_array("$user[pos]", $shoppos)) {
            $inhalt .= "<tr>";
            $inhalt .= "<td>$item[name]</td>";
            $inhalt .= "<td> </td>";
            $inhalt .= "<td>$item[gold] $lang_currency</td>";
            $inhalt .= "<td> </td>";
            $inhalt .= "<td><a href='vendor.php?back=$_GET[back]&buy=$item[id]'>$lang_buy</a></td>";
            $inhalt .= "</tr>";
        }
    }
    $inhalt .= "</table>";
    $inhalt .= "</td>";
    $inhalt .= "<td valign='top'><img src='images/npc/smith2.gif'></td>";
    $inhalt .= "</tr>";
    $inhalt .= "</table>";
}
$inhalt .= "<br>";

$inhalt .= "<br><p style='text-align: right;'><a href='$_GET[back].php'>$lang_back</a></p>";
$sitetitle = $lang_smith;
include_once 'template.php';
?>

