<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("SELECT * FROM alk_user WHERE id = '$_SESSION[id]'");
$user1 = mysql_fetch_array($abfragen);
$abfragen = mysql_query("SELECT * FROM alk_skills WHERE userid = '$_SESSION[id]'");
$user1_skill = mysql_fetch_array($abfragen);
$abfragen = mysql_query("SELECT * FROM alk_battle WHERE `battleid` = '$user1[battleid]' AND ownid = '$user1[id]'");
$user1_battle = mysql_fetch_array($abfragen);
$abfragen = mysql_query("SELECT * FROM alk_map WHERE x = '$posx' AND y = '$posy' LIMIT 1");
$map = mysql_fetch_array($abfragen);

$_SESSION[showcharnav] = 0;

if ($_GET[op] == "startbattle") {
    if (isset($_GET[enemyid])) {
        $enemyid = mysql_real_escape_string($_GET[enemyid]);
        $abfragen = mysql_query("SELECT * FROM alk_user WHERE id = '$enemyid'");
        $user2 = mysql_fetch_array($abfragen);
        $user2inbattle = $user2[battleid];
        $user2maxhits = $user2[hitpoints];
        $user2maxmana = $user2[mana];
        $user2pos = $user2[pos];
        $user2online = isOnline($user2[id]);
    } else if (isset($_GET[enemyname])) {
        $uniquename = mysql_real_escape_string($_GET[enemyname]);
        $user2online = 1;
        include 'mob.php';
//        $_SESSION[debugmessage] = $user2pos;
        $user2pos = explode(",", $user2pos);
        if (in_array($user1[pos], $user2pos)) {
            $user2pos = $user1[pos];
        } else {
            $user2pos = $user1[pos] . ":1";
        }
//        $_SESSION[debugmessage] .= "<br><br>$user2pos<br>$user1[pos]";

        $user2inbattle = "";
        $enemyid = mysql_real_escape_string($_GET[enemyname]);
    }
    $location = "currentposition.php?x=" . getuserposx($_SESSION[id]) . "&y=" . getuserposy($_SESSION[id]);
    if ($user1[ap] > 0) {
        if ($user1[hitpoints] > 0) {
            if ($user1[battleid] == "" && $user2inbattle == "") {
                // Create Battle
                if ($user1[pos] == $user2pos) {
                    if ($user2online == 1) {
                        createBattleEntry($user1[id], $enemyid, $user1[id] . $enemyid, 1, $user1[hitpoints], $user1[mana]);
                        createBattleEntry($enemyid, $user1[id], $user1[id] . $enemyid, 0, $user2maxhits, $user2maxmana);
                        decreaseAP($user1[id], 1);
                        $location = "battle.php";
                    } else {
                        // Gegner nicht Online
                        $_SESSION[message] = $lang_enemynotonline;
                    }
                } else {
                    // Gegner nicht auf selber Position
                    $_SESSION[message] = $lang_enemynotatsameposition;
                }
            } else {
                if ($user1[battleid] != "") {
                    // Du befindest dich im Kampf
                    $_SESSION[message] = $lang_youarecurrentlyfighting;
                } else if ($user2inbattle != "") {
                    // Dein Gegner befindet sich bereits in einem Kampf
                    $_SESSION[message] = $lang_yourenemyiscurrentlyfighting;
                }
            }
        } else {
            // Nicht genug Lebenspunkte
            $_SESSION[message] = $lang_notenoughthitpoints;
        }
    } else {
        // Nicht genug AP
        $_SESSION[message] = $lang_notenoughtap;
    }
    header("location:$location");
    die;
} else if ($_GET[op] == "battleend") {
    addnav("currentposition.php");
    if ($user1[battleid] != "") {
        if (!isset($_GET[flee])) {
            if ($user1_battle[hitpoints] <= 0) {
                // Kampf verloren
                if ($user1_battle[gotloot] == "0") {
                    if (ctype_digit($user1_battle[enemyid])) {
                        mysql_query("UPDATE alk_user SET gold = gold + '$user1[gold]' WHERE id = '$user2[id]'");
                        mysql_query("UPDATE alk_user SET gold = 0 WHERE id = '$user1[id]'");
                    }
                    mysql_query("UPDATE alk_battle SET gotloot = 1 WHERE battleid = '$user1[battleid]'");
                }
            } else {
                // Kampf gewonnen
                if ($user1_battle[gotloot] == "0") {
                    if (ctype_digit($user1_battle[enemyid])) {
                        mysql_query("UPDATE alk_user SET gold = gold + '$user2[gold]' WHERE id = '$user1[id]'");
                        mysql_query("UPDATE alk_user SET gold = 0 WHERE id = '$user2[id]'");
                        $loottext = "$user2gold $lang_currency $lang_recieved.";
                    } else {
                        $getloot = 1;
                        $uniquename = $user1_battle[enemyid];
                        include "mob.php";
                        mysql_query("UPDATE alk_user SET exp = exp + '$user2exp', gold = gold + '$user2gold' WHERE id = '$user1[id]'");
                        $loottext = "$user2gold $lang_currency $lang_recieved.<br>$user2exp $lang_experience $lang_recieved.";
                    }
                    mysql_query("UPDATE alk_battle SET gotloot = 1 WHERE battleid = '$user1[battleid]'");
                    $_SESSION[message] = "$loottext" . $_SESSION[message];
                }
            }
        }
        if (!ctype_digit($user1_battle[enemyid])) {
            mysql_query("DELETE FROM alk_battle WHERE battleid = '$user1[battleid]'");
        } else {
            mysql_query("DELETE FROM alk_battle WHERE battleid = '$user1[battleid]' AND ownid = '$user1[id]'");
        }
        mysql_query("UPDATE alk_user SET battleid = '', hitpoints = '$user1_battle[hitpoints]', mana = '$user1_battle[mana]' WHERE id = '$user1[id]'");

        header("location: currentposition.php?x=" . getuserposx($_SESSION[id]) . "&y=" . getuserposy($_SESSION[id]));
        die;
    } else {
        header("location: currentposition.php?x=" . getuserposx($_SESSION[id]) . "&y=" . getuserposy($_SESSION[id]));
        die;
    }
} else {
    if ($user1[battleid] != "") {
        $abfragen = mysql_query("SELECT * FROM alk_user WHERE id = '$user1_battle[enemyid]'");
        $user2 = mysql_fetch_array($abfragen);
        $abfragen = mysql_query("SELECT * FROM alk_battle WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[enemyid]'");
        $user2_battle = mysql_fetch_array($abfragen);

        $user1_name = $user1[user];
        $user1_weaponname = getEquipedWeaponName($user1[id]);

        if (ctype_digit($user2_battle[ownid])) {
            $user2_name = $user2[user];
            $user2_weaponname = getWeaponName($_user1[enemyid]);
            $user2avatar = $user2[avatar];
            $user2maxhits = getMaxHit($user2[id]);
            $user2maxmana = getMaxMana($user2[id]);
            $user2armor = getArmor($user2[id]);
            $user2dex = $user2[dexterity];
        } else {
            $uniquename = $user2_battle[ownid];
            include 'mob.php';
        }

        $battletext = "";
        if (isset($_GET[attack])) {
            if ($_GET[attack] == "weapon") {
                if (rand(1, 100) <= $user1_skill[getEquipedItemPriSkill($user1[id], getWeaponUniquename($user1[id]))]) {
                    $damage = getWeaponDamage($user1[id]);
                    $damage = $damage + ($damage / 100 * $user1_skill[tactics]) + ceil($user1[strenght] / 2) - $user2armor;
                    if ($damage < 0) {
                        $damage = 0;
                    }
                    $battletext = "$user1_name $lang_attacks $lang_with $user1_weaponname $lang_anddoes1 $lang_anddoes2 $damage $lang_damage";
                } else {
                    $damage = 0;
                    $battletext = "$user1_name $lang_failedhisattack";
                }

                mysql_query("UPDATE alk_battle SET hitpoints = (hitpoints-$damage) , active = 1 WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[enemyid]'");
                mysql_query("UPDATE alk_battle SET active = 0 WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[ownid]'");
            } else if ($_GET[attack] == "magic") {
                $spell = mysql_real_escape_string($_GET[spell]);
                if(checkifspellknown($user1[id], $spell)) {
                    $usespell = 1;
                }
                $spellbattlemenue = 0;
                include 'spells.php';
                
                mysql_query("UPDATE alk_battle SET hitpoints = (hitpoints-$damage) , active = 1 WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[enemyid]'");
                mysql_query("UPDATE alk_battle SET mana = mana-'$mana', active = 0 WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[ownid]'");                    
            } else if ($_GET[attack] == "flee") {
                $fleesuccess = 0;
                if (rand(1, $user2dex) <= $user1[dexterity]) {
                    $fleesuccess = 1;
                    $battletext = "$user1[user] $lang_hasfledfrombattle";
                    $newtext = $battletext . "<br>" . $user1_battle[battletext];
                    mysql_query("UPDATE alk_battle SET battletext = '$newtext' WHERE `battleid` = '$user1_battle[battleid]'");
                }
                if ($fleesuccess == 1) {
                    header("location: battle.php?op=battleend&flee");
                    die;
                } else {
                    $battletext = "$user1_name $lang_trytofleetext";
                    mysql_query("UPDATE alk_battle SET active = 1 WHERE `battleid` = '$user2_battle[battleid]' AND ownid = '$user1_battle[enemyid]'");
                    mysql_query("UPDATE alk_battle SET active = 0 WHERE `battleid` = '$user2_battle[battleid]' AND ownid = '$user1_battle[ownid]'");
                }
            }
            if (!ctype_digit($user2_battle[ownid])) {
//            $user2_name = $user2_battle[ownid];
//            include_once 'mob.php';
                $enemydamage = $enemydamage - getArmor($user1[id]);
                if ($enemydamage < 0) {
                    $enemydamage = 0;
                }
                $battletext = "$user2_name $lang_attacks $lang_with $user2_weaponname $lang_anddoes1 $lang_anddoes2 $enemydamage $lang_damage<br>" . $battletext;

                mysql_query("UPDATE alk_battle SET hitpoints = (hitpoints-$enemydamage) , active = 1 WHERE `battleid` = '$user2_battle[battleid]' AND ownid = '$user2_battle[enemyid]'");
                mysql_query("UPDATE alk_battle SET active = 0 WHERE `battleid` = '$user2_battle[battleid]' AND ownid = '$user2_battle[ownid]'");
            }
            if ($battletext != "") {
                $newtext = $battletext;

                mysql_query("UPDATE alk_battle SET battletext = '$newtext' WHERE `battleid` = '$user1_battle[battleid]'");
            }
        }

        $abfragen = mysql_query("select * from alk_user WHERE id = '$_SESSION[id]'");
        $user1 = mysql_fetch_array($abfragen);
        $abfragen = mysql_query("select * from alk_battle WHERE `battleid` = '$user1[battleid]' AND ownid = '$user1[id]'");
        $user1_battle = mysql_fetch_array($abfragen);

        $abfragen = mysql_query("select * from alk_user WHERE id = '$user1_battle[enemyid]'");
        $user2 = mysql_fetch_array($abfragen);
        $abfragen = mysql_query("select * from alk_battle WHERE `battleid` = '$user1_battle[battleid]' AND ownid = '$user1_battle[enemyid]'");
        $user2_battle = mysql_fetch_array($abfragen);

        $hitpointbarwidth = (150 / getMaxHit($user1[id]) * $user1_battle[hitpoints]) . "px";
        $hitpointcolor = "#00FF00";
        if ($user1_battle[hitpoints] <= (getMaxHit($user1[id]) / 100 * 50)) {
            $hitpointcolor = "#FFFF00";
            if ($user1_battle[hitpoints] <= (getMaxHit($user1[id]) / 100 * 25)) {
                $hitpointcolor = "#FF0000";
            }
        }
        if ($hitpointbarwidth <= 0) {
            $hitpointbarwidth = 1;
        }

        $manabarwidth = (150 / getMaxMana($user1[id]) * $user1_battle[mana]) . "px";
        $manacolor = "#0000FF";
        
        $user2hitpointbarwidth = (150 / $user2maxhits * $user2_battle[hitpoints]) . "px";
        $user2hitpointcolor = "#00FF00";
        if ($user2_battle[hitpoints] <= ($user2maxhits / 100 * 50)) {
            $user2hitpointcolor = "#FFFF00";
            if ($user2_battle[hitpoints] <= ($user2maxhits / 100 * 25)) {
                $user2hitpointcolor = "#FF0000";
            }
        }
        if ($user2hitpointbarwidth <= 0) {
            $user2hitpointbarwidth = 1;
        }

        $user2manabarwidth = (150 / $user2maxmana * $user2_battle[mana]) . "px";
        $user2manacolor = "#0000FF";        

        $inhalt .= "<table style='width: 100%;'>";
        $inhalt .= "<tr>";
        $inhalt .= "<td valign='top' style='text-align: left;'><p><img style='width: 200px; height: 200px;' src='$user1[avatar]'></p><p>$lang_hitpoints: $user1_battle[hitpoints]/" . getMaxHit($user1[id]) . "<div style='background-color: $hitpointcolor; width: $hitpointbarwidth; height: 5px;'></div><br>$lang_mana: $user1_battle[mana]/" . getMaxMana($user1[id]) . "<div style='background-color: $manacolor; width: $manabarwidth; height: 5px;'></div></p></td>";
        $inhalt .= "<td></td>";
        $inhalt .= "<td>$user1_battle[battletext]</td>";
        $inhalt .= "<td></td>";
        $inhalt .= "<td valign='top' style='text-align: right;'><p><img style='width: 200px; height: 200px;' src='$user2avatar'></p><p>$lang_hitpoints: $user2_battle[hitpoints]/$user2maxhits<div style='float: right; background-color: $user2hitpointcolor; width: $user2hitpointbarwidth; height: 5px;'></div><br>$lang_mana: $user2_battle[mana]/$user2maxmana <div style='display: block; float: right; background-color: $user2manacolor; width: $user2manabarwidth; height: 5px;'></div></p></td>";
        $inhalt .= "</tr>";
        $inhalt .= "<tr>";

        if ($user1_battle[hitpoints] <= 0 || $user2_battle[hitpoints] <= 0) {
            $inhalt .= "<td>";
            $inhalt .= "<p style='text-align: center;'><a href='battle.php?op=battleend'>$lang_endbattle</a></p>";
            $inhalt .= "</td>";
        } else {
            $inhalt .= "<td colspan='5'><hr><td>";
            $inhalt .= "</tr>";
            $inhalt .= "<tr>";
            if ($user1_battle[active] == "1") {
                $inhalt .= "<td>";
                $inhalt .= "<p><a style='background-color: #FF2222;' href='battle.php?attack=weapon'>" . getEquipedWeaponName($_SESSION[id]) . "</a></p>";
                $inhalt .= "<div>";
                include_once 'skills.php';
                $inhalt .= "</div>";
                $inhalt .= "<div>";
                $spellbattlemenue = 1;
                include 'spells.php';
                $inhalt .= "</div>";
                $inhalt .= "<p><a href='battle.php?attack=flee'>$lang_flee</a></p>";
                $inhalt .= "</td>";
            } else {
                $inhalt .= "<td colspan='5'><p style='text-align: center;'>$lang_waitingforopponentsturn<br><a href='battle.php'>$lang_reload</a></p></td>";
            }
        }


        $inhalt .= "</tr>";
        $inhalt .= "<table>";
    } else {
        header("location:map.php");
        die;
    }
}
$sitetitle = $lang_battle;
include_once 'template.php';

function showMagicAttack($userid) {
    
}

function getDamage() {
    
}

function getBattleText() {
    
}

function createBattleEntry($user1id, $user2id, $battleid, $active, $hitpoints, $mana) {
    $createbattle = "INSERT INTO `alk_battle` ("
            . "`battleid`, "
            . "`ownid`,"
            . "`enemyid`,"
            . "`active`,"
            . "`hitpoints`,"
            . "`mana`"
            . ") VALUES ("
            . "'$battleid', "
            . "'$user1id',"
            . "'$user2id',"
            . "'$active',"
            . "'$hitpoints',"
            . "'$mana'"
            . ")";
    mysql_query($createbattle);

    mysql_query("UPDATE alk_user SET battleid = '$battleid' WHERE id = '$user1id'");
}
?>

