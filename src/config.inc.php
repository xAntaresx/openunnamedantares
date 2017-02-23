<?php

require_once 'dbconnect.php';

// Ausgabe wenn nicht angemeldet
if (empty($_SESSION["id"])) {
    include_once 'lang/de.php';

    $online = "<a href='index.php'>$lang_home</a> | <a href='login.php'>$lang_login</a> | <a href='registration.php'>$lang_registration</a>";
    $ressourcen = "";
    $menue1 = "";
    $menue2 = "";
}
// Ausgabe wenn angemeldet
else {

    if (!isset($_SESSION["id"])) {
        header("location:login.php");
        die;
    }
//Spielanzeige leeren    
    $inhalt = "";

//Funktionen laden
    include_once 'common.php';

//Spielerdaten aus Datenbank laden
    $abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION['id'] . "'");
    $user = mysql_fetch_array($abfragen);

    $posx = getuserposx($user[id]);
    $posy = getuserposy($user[id]);
    $pos = getCurrentLocationByUserId($user[id]);


//Kartendaten der aktuellen Position aus Datenbank laden
    $abfragen = mysql_query("select * from alk_map WHERE x = '$posx' AND y = '$posy'");
    $map = mysql_fetch_array($abfragen);


//Eingestellte Sprache wählen
    if ($user[lang] == "de") {
        include_once 'lang/de.php';
    } else {
        include_once 'lang/de.php';
    }

    //Hintergrund für die Karte
    echo "<img id='hintergrund' src='images/fullbackground/$map[backimg].jpg'>";

    //Navigationsmenü
    $ressourcen = "<p style='float: right; margin-top: 0px;'>$bigm<a href='messages.php'>$lang_letterpigeon</a>$unbigm</p><p style='float: left; margin-top: 0px;'>";
    if ($user[battleid] != "") {
        $ressourcen .= "<a href='battle.php'>$lang_battle</a>";
    }
    $ressourcen .= "</p>";

    $menue1 = ""
            . "<a href='charakter.php'>$lang_charakter</a> | "
            . "";
    $online = "<a href='profile.php?id=$_SESSION[id]'>$lang_profile</a> | "
            . "<a href='settings.php'>$lang_settings</a> ";

    $menue2 = "";
    if ($user[pos] == $user[endpos]) {
        $menue2 .= "<b><a href='currentposition.php?x=$posx&y=$posy'>$lang_currentposition</a></b> | ";
    }
    $menue2 .= ""
            . "<a href='map.php?x=$posx&y=$posy'>$lang_map</a> | "
            . "<a href='toplist.php'>$lang_toplist</a>"
            . "";

    // Regeneration
//    echo getcurrentdatetime() . "<br>" . getcurrentdatetimeplus(0, 3, 0) . "<br>" . $user[regdate];
    $regsql = mysql_query("select id from alk_user WHERE regdate < " . getcurrentdatetime() . "");
    while ($reguser = @mysql_fetch_array($regsql)) {
        mysql_query("UPDATE alk_user SET hitpoints = hitpoints + '" . getHitReg($reguser[id]) . "' WHERE id='$reguser[id]' AND hitpoints < " . getMaxHit($reguser[id]) . "");
        mysql_query("UPDATE alk_user SET mana = mana + '" . getManaReg($reguser[id]) . "' WHERE id='$reguser[id]' AND mana < " . getMaxMana($reguser[id]) . "");
        mysql_query("UPDATE alk_user SET regdate='" . getcurrentdatetimeplus(0, 3, 0) . "' WHERE id='$reguser[id]'");
    }

// Regeneration Aktionspunkte
    $apsql = mysql_query("select id from alk_user WHERE apdate < " . getcurrentdatetime() . "");
    while ($apuser = @mysql_fetch_array($apsql)) {

        mysql_query("UPDATE alk_user SET ap = ap + 1 WHERE id='$apuser[id]' AND ap < " . getMaxAP($apuser[id]) . "");
        mysql_query("UPDATE alk_user SET apdate='" . getcurrentdatetimeplus(0, 5, 0) . "' WHERE id='$apuser[id]'");
    }
//mysql_query("UPDATE alk_user SET ap = ap+1, apdate = '" . getcurrentdatetimeplus(0,5,0) . "' WHERE apdate < '".getcurrentdatetime()."' AND ap < ROUND((constitution+dexterity/2)+8)");
    if ($user[hitpoints] > getMaxHit($user[id])) {
        mysql_query("UPDATE alk_user SET hitpoints = '" . getMaxHit($user[id]) . "'");
    }
    if ($user[hitpoints] < 0) {
        mysql_query("UPDATE alk_user SET hitpoints = '0'");
    }

    if ($user[mana] > getMaxMana($user[id])) {
        mysql_query("UPDATE alk_user SET mana = '" . getMaxMana($user[id]) . "'");
    }
    if ($user[mana] < 0) {
        mysql_query("UPDATE alk_user SET mana = '0'");
    }

    if ($user[ap] > getMaxAP($user[id])) {
        mysql_query("UPDATE alk_user SET ap = '" . getMaxAP($user[id]) . "'");
    }
    if ($user[ap] < 0) {
        mysql_query("UPDATE alk_user SET ap = '0'");
    }

    mysql_query("UPDATE alk_user SET lastlogin = '" . getcurrentdatetime() . "' WHERE id = '$_SESSION[id]'");
    move();

    if ($user[battleid] != "") {
        $abfragen = mysql_query("SELECT * FROM alk_battle WHERE `battleid` = '$user[battleid]' AND ownid = '$user[id]'");
        $battle = mysql_fetch_array($abfragen);

        $battleactive = $battle[active];
    } else {
        $battleactive = "0";
    }

    clearnav();
    if ($battleactive == "0") {
        addnav(getCurrentFile());
        addnav("map.php");
        addnav("chat.php");
        addnav("charakter.php");
        addnav("settings.php");
        addnav("profile.php");
        addnav("currentposition.php");
        addnav("move.php");
        addnav("messages.php");
        addnav("toplist.php");
        addnav("battle.php");
//        $mapabfragen = mysql_query("select * from alk_map WHERE x = '$posx' AND y = '$posy'");
//        $map = mysql_fetch_array($mapabfragen);
        if ($map[file] != "") {
            addnav($map[file]);
        }
    } else {
        addnav("battle.php");
    }
    if ($user[su] == 1) {
        addnav("admin.php");
    }

    //Lade Chatoperationen
    if (isset($_GET[chatop])) {
        include_once 'chat.php';
    }
    //Lade Aktionen
    if (isset($_GET[action])) {
        include_once 'action.php';
    }

    $allowednavs = explode(",", $user[allowednavs]);
    if (!in_array(getCurrentFile(), $allowednavs) && isset($_SESSION["id"])) {

        if ($battleactive == "0") {
            header("location:map.php");
        } else {
            header("location:battle.php");
        }
        die;
    }

    include_once 'charstat.php';

    $_SESSION[showcharnav] = 1;
}
$languagemenue = '';
$footer = "";
?>