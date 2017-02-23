<?php

$abfragen = mysql_query("select * from alk_user WHERE id = '" . $_SESSION['id'] . "'");
$user = mysql_fetch_array($abfragen);

$abfragen = mysql_query("select * from alk_skills WHERE userid = '$user[id]'");
$skill = mysql_fetch_array($abfragen);

$userid = $user[id];

$fireballmana = $user[intelligence];


$damage = 0;
$mana = 0;
switch ($spell) {
    case "fireball":
        $spellname = $lang_fireball;
        if ($skill[spellcasting] >= rand(1, 20)) {
            $damage = getDiceResult("1W$user[intelligence]");
            $mana = $fireballmana;
            $battletext = "$user1_name $lang_casts $lang_fireball $lang_anddoes2 $damage $lang_damage";
        } else {
            $battletext = "$user1_name $lang_failedhisattack";
        }
        break;

    default:
        break;
}

if ($spellbattlemenue == 1) {

    $spells = explode(",", $user[magicknowledge]);
    foreach ($spells as $spell) {
        if (checkifspellknown($userid, $spell)) {
            switch ($spell) {
                case "fireball":
                    $spellname = $lang_fireball;
                    $mana = $fireballmana;
                    break;
            }

            if (getManaFromBattle($user[id]) >= $mana) {
                $inhalt .= "<p><a style='background-color: #FF2222;' href='battle.php?attack=magic&spell=$spell'>$spellname</a></p>";
            } else {
                $inhalt .= "<p><a style='background-color: #222222;'>$spellname</a></p>";
            }
        }
    }
}    