<?php

if (isset($_SESSION["id"])) {
    if(!$_SESSION[showcharnav] == "0") {
//    $abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION['id'] . "'");
//    $user = mysql_fetch_array($abfragen);

    $charstat .= "<div style='background-color: #000000; width: 300px; padding-top: 50px; padding-bottom: 50px; border: 1px solid;'>";
    $charstat .= "<div>";
    $charstat .= "<img style='margin-left: 50px; border: 1px solid; width: 200; height: 200;' src='$user[avatar]'>";
    $charstat .= "<p style='margin-left: 50px;'>";
    $charstat .= "$lang_hitpoints: $user[hitpoints]/" . getMaxHit($user[id]) . "";
    $charstat .= "</p>";
    $hitpointbarwidth = (150 / getMaxHit($user[id]) * $user[hitpoints]) . "px";
    $hitpointcolor = "#00FF00";
    if ($user[hitpoints] <= (getMaxHit($user[id]) / 100 * 50)) {
        $hitpointcolor = "#FFFF00";
        if ($user[hitpoints] <= (getMaxHit($user[id]) / 100 * 25)) {
            $hitpointcolor = "#FF0000";
        }
    }
    if ($hitpointbarwidth <= 0) {
        $hitpointbarwidth = 1;
    }
    $charstat .= "<div style='background-color: $hitpointcolor; margin-left: 50px; margin-top: -10px; width: $hitpointbarwidth; height: 5px;'></div>";
    $charstat .= "<p style='margin-left: 50px;'>";
    $charstat .= "$lang_mana: $user[mana]/" . getMaxMana($user[id]) . "";
    $charstat .= "</p>";
    $manabarwidth = (150 / getMaxMana($user[id]) * $user[mana]) . "px";
    $manacolor = "#0000FF";
    $charstat .= "<div style='background-color: $manacolor; margin-left: 50px; margin-top: -10px; width: $manabarwidth; height: 5px;'></div>";

    
    $charstat .= "<p style='margin-left: 50px;'>";
    $charstat .= "$lang_actionpoints: $user[ap]/".  getMaxAP($user[id])."";
    $charstat .= "</p>";
    $apbarwidth = (150 / getMaxAP($user[id]) * $user[ap]) . "px";
    $apcolor = "#FFFF00";
    $charstat .= "<div style='background-color: $apcolor; margin-left: 50px; margin-top: -10px; width: $apbarwidth; height: 5px;'></div>";
    
    $charstat .= "<p style='margin-left: 50px;'>";
    $charstat .= "$lang_purse: $user[gold] $lang_currency";
    $charstat .= "</p>";
    
    $charstat .= "<p style='margin-left: 50px;'>";
    $weaponabfragen = mysql_query("select * from alk_inventory WHERE userid = '$user[id]' AND type = 'weapon' AND equiped = '1'");
    $weapon = mysql_fetch_array($weaponabfragen);    
    $charstat .= "$lang_weaponhand: ".getEquipedWeaponName($user[id])."<br>";
    $charstat .= "$lang_damage: $weapon[value1] +$weapon[value2]";
    $charstat .= "</p>";

    $charstat .= "<p style='margin-left: 50px;'>";
    $defense = getArmor($user[id]);
    $charstat .= "$lang_armor: $defense";
    $charstat .= "</p>";
    $charstat .= "</div>";
    
    $charstat .= "<p style='margin-left: 50px;'>";
    if (getMapNameByPos($user[pos]) != "") {
        $mapname .= getMapNameByPos($user[pos])." ($user[pos])";
    }
    else {
        $mapname = "$user[pos]";
    }
    $charstat .= "$lang_currentposition: $mapname<br>";
    if (getMapNameByPos($user[endpos]) != "") {
        $endmapname .= getMapNameByPos($user[endpos])." ($user[endpos])";
    }
    else {
        $endmapname = "$user[endpos]";
    }
    if ($user[pos] != $user[endpos]) {
        $charstat .= "$lang_onthewayto: $endmapname<br>";
    }
    $charstat .= "</p>";
    }
    $charstat .= "</div>";
}
?>