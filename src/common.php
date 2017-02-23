<?php

// Chatfunktionen
function addToChat($userid, $message) {
    if ($message != "") {
        $sql = "INSERT INTO alk_chat SET user = '$userid', place='".getCurrentLocationByUserId($userid)."', message = '$message'";
        mysql_query($sql);

        $query = mysql_Query("select count(`id`) as `gezaehlt` from `alk_chat` WHERE place='".getCurrentLocationByUserId($userid)."'");
        $message = @mysql_Fetch_Assoc($query);
        $messages = $message["gezaehlt"];

        if ($messages > 25) {
            mysql_query("DELETE FROM alk_chat WHERE place='".getCurrentLocationByUserId($userid)."' ORDER BY id ASC LIMIT 1");
        }
    }
}
function showChat($userid) {
    $inhalt = "<table><tr><td>";
    $chatabfragen = mysql_query("select * from alk_chat WHERE place = '". getCurrentLocationByUserId($userid)."' ORDER BY id ASC");
    while ($chat = @mysql_fetch_array($chatabfragen)) {
        $isonline = "images/offline.gif";
        if (isOnline($chat[user]) == 1) {
            $isonline = "images/online.gif";
        }
        $inhalt .= "<img src='$isonline'> <a href='profile.php?id=$chat[user]'>" . getnamebyid($chat[user]) . "</a>: $chat[message]<br>";
    }    
    $inhalt .= "<form action='". getCurrentFile()."?chatop=addtext' method='post'>";
    $inhalt .= "<p>". getnamebyid($userid).": <input style='width: 250px;' id='message' name='message' type='text' value='' autofocus></p>";
    $inhalt .= "<input type='submit' name='submit' value='Chat' class='button'> <a href='". getCurrentFile() ."'><img alt='reload' style='width:25px;height:25px;' src''></a>";
    $inhalt .= "</form>";
    $inhalt .= "</td></tr></table>";
    return $inhalt;
}

function showNotification() {
    $inhalt .= "<p style='text-align: center;'>$_SESSION[message]</p>";
    $_SESSION[message] = "";
    return $inhalt;
}


function getidbyname($user) {
    $abfrage = mysql_query("select * from alk_user WHERE `user` = '" . $user . "' LIMIT 1");
    $row = mysql_fetch_array($abfrage);
    return $row[id];
}
function getnamebyid($id) {
    $abfrage = mysql_query("select * from alk_user WHERE `id` = '" . $id . "' LIMIT 1");
    $row = mysql_fetch_array($abfrage);
    return $row[user];
}
function getcurrentdatetime() {
    $sec = date("s");
    $min = date("i");
    $stund = date("H");
    $tagimmonat = date("d");
    $monate = date("m");
    $jahr = date("Y");
    $jahr = $jahr - 2000;

    $datumjetzt = $jahr . $monate . $tagimmonat . $stund . $min . $sec;

    return $datumjetzt;
}
function getcurrentdatetimeplus($plusstund, $plusmin, $plussec) {
    $sec = date("s");
    $min = date("i");
    $stund = date("H");
    $tagimmonat = date("d");
    $monate = date("m");
    $jahr = date("Y");
    $jahr = $jahr - 2000;

    $stundneu = $stund + $plusstund;
    if (strlen($stundneu) == "1") {
        $stundneu = "0" . $stundneu;
    }
    
    $minneu = $min + $plusmin;
    if (strlen($minneu) == "1") {
        $minneu = "0" . $minneu;
    }
    
    $secneu = $sec + $plussec;
    if (strlen($secneu) == "1") {
        $secneu = "0" . $secneu;
    }
    
    $datum = $jahr . $monate . $tagimmonat . $stundneu . $minneu . $secneu;

    return $datum;
}
function getcurrentdatetimemin() {
    $sec = date("s");
    $min = date("i");
    $stund = date("H");
    $tagimmonat = date("d");
    $monate = date("m");
    $jahr = date("Y");
    $jahr = $jahr - 2000;

    $datumjetzt = $jahr . $monate . $tagimmonat . $stund . $min;

    return $datumjetzt;
}
function sendmessage($sender, $receiver, $subject, $text) {
    $sender = mysql_real_escape_string($sender);
    $receiver = mysql_real_escape_string($receiver);
    $subject = mysql_real_escape_string($subject);
    $text = mysql_real_escape_string($text);
    mysql_query("INSERT INTO `alk_messages` (`sender`, `receiver`, `seen`, `subject`, `text`, `date`) VALUES ('$sender', '$receiver', '0', '$subject', '$text', '" . (getcurrentdate() + 1400) . "')");
}
function isOnline($userid) {
    $abfrage = mysql_query("select lastlogin from alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    if ((getcurrentdatetime()) < ($user[lastlogin] + 200)) {

        $retval = 1;
    }
    return $retval;
}
function getLevel($userid) {  
}


//Location Funktionen
// Gibt die aktuelle X-Position des Spielers mit der ID $id zurück
function getuserposx($id) {
    $abfrage = mysql_query("select pos from alk_user WHERE `id` = '$id' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    $pos = explode(":", $user[pos]);
    return $pos[0];
}
// Gibt die aktuelle Y-Position des Spielers mit der ID $id zurück
function getuserposy($id) {
    $abfrage = mysql_query("select pos from alk_user WHERE `id` = '$id' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    $pos = explode(":", $user[pos]);
    return $pos[1];
}
// Gibt die aktuelle Position (XXX:XXX) des Spielers mit der ID $id zurück
function getCurrentLocationByUserId($id) {
    $posx = getuserposx($id);
    $posy = getuserposy($id);
    
    return $posx.":".$posy;
}
function checkMapBlock($x, $y) {
    $abfrage = mysql_query("select block from alk_map WHERE x = '$x' AND y = '$y'");
    $map = mysql_fetch_array($abfrage);

    return $map[block];
}
function move() {
    $currentdate = getcurrentdatetimemin();
    $movesql = mysql_query("SELECT * FROM alk_user WHERE movedate <= $currentdate AND pos <> endpos");
    while ($moveuser = @mysql_fetch_array($movesql)) {
        $startpos = explode(":", $moveuser[pos]);
        $startposx = $startpos[0];
        $startposy = $startpos[1];

        $endpos = explode(":", $moveuser[endpos]);
        $endposx = $endpos[0];
        $endposy = $endpos[1];

        $newposx = $startposx;
        $newposy = $startposy;

        for ($i = $moveuser[movedate]; $i <= $currentdate; $i++) {
//            if ($newposx < $endposx) {
//                $newposx = $newposx + 1;
//            } else if ($newposx > $endposx) {
//                $newposx = $newposx - 1;
//            } else {
//                if ($newposy < $endposy) {
//                    $newposy = $newposy + 1;
//                } else if ($newposy > $endposy) {
//                    $newposy = $newposy - 1;
//                } else {
//                    
//                }
//            }

            $newposset = 0;
            if ($newposx < $endposx && $newposset == 0) {
                if (!checkMapBlock($newposx + 1, $newposy)) {
                    $newposx = $newposx + 1;
                    $newposset = 1;
                }
            }
            if ($newposx > $endposx && $newposset == 0) {
                if (!checkMapBlock($newposx - 1, $newposy)) {
                    $newposx = $newposx - 1;
                    $newposset = 1;
                }
            }
            if ($newposy < $endposy && $newposset == 0) {
                if (!checkMapBlock($newposx, $newposy + 1)) {
                    $newposy = $newposy + 1;
                    $newposset = 1;
                }
            }
            if ($newposy > $endposy && $newposset == 0) {
                if (!checkMapBlock($newposx, $newposy - 1)) {
                    $newposy = $newposy - 1;
                    $newposset = 1;
                }
            }

            if ($moveuser[pos] != $moveuser[endpos]) {
                mysql_query("UPDATE alk_user SET pos = '$newposx:$newposy', movedate = movedate+1, exp = exp + 1 WHERE id='$moveuser[id]'");
            }
        }
    }
}
function getMapNameByPos($pos) {
    $position = explode(":", $pos);
    $posx = $position[0];
    $posy = $position[1];
    
    $mapsql = "SELECT name FROM alk_map WHERE x='$posx' AND y='$posy'";
    $result = mysql_query($mapsql);
    $map = mysql_fetch_array($result);
    
    return $map[name];
}
function getMapDescriptionByPos($pos) {
    $position = explode(":", $pos);
    $posx = $position[0];
    $posy = $position[1];
    
    $mapsql = "SELECT description FROM alk_map WHERE x='$posx' AND y='$posy'";
    $result = mysql_query($mapsql);
    $map = mysql_fetch_array($result);
    
    return $map[description];
}
function addnav($nav) {
    $abfrage = mysql_query("SELECT allowednavs FROM alk_user WHERE `id` = '$_SESSION[id]' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    $allowednavs = $user[allowednavs];
    $newnav = $allowednavs . "," . $nav;
    mysql_query("UPDATE alk_user SET allowednavs = '$newnav' WHERE id='$_SESSION[id]'");
}
function clearnav() {
    mysql_query("UPDATE alk_user SET allowednavs = '' WHERE id='$_SESSION[id]'");
}
function countEquipedItem($userid, $position) {
    $query = mysql_Query("SELECT COUNT(`id`) AS `anzahl` FROM `alk_inventory` WHERE userid='$userid' AND type = '$position' AND equiped > 0");
    $equiped = @mysql_Fetch_Assoc($query);
    $equiped = $equiped["anzahl"];

    return $equiped;
}
function getRemainingAttributPoints($userid) {
    $abfrage = mysql_query("SELECT strenght, constitution, dexterity, intelligence, willpower, speed FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    $attributepoints = 106 - ($user[strenght] + $user[constitution] + $user[dexterity] + $user[intelligence] + $user[willpower] + $user[speed]);
    return $attributepoints;
}
function getRemainingSkillPoints($userid) {
    $abfrage = mysql_query("SELECT sword, axe, staff, tactics FROM alk_skills WHERE `userid` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    $attributepoints = 800 - ($user[sword] + $user[axe] + $user[staff] + $user[tactics]);
    return $attributepoints;
}
function getExpTableAttribut($skill) {
    $firstexp = 0;
    $secondexp = 2;
    for ($i = 0; $i <= $skill; $i++) {
        $exp = $firstexp + $secondexp;
        $firstexp = $secondexp;
        $secondexp = $exp;
    }
    return $exp;
}
function getExpTableSkill($skill) {
//    $firstexp = 0;
//    $secondexp = 1;
//    for ($i = 0; $i <= $skill; $i++) {
//        $exp = $firstexp + $secondexp;
//        $firstexp = $secondexp;
//        $secondexp = $exp;
//    }
    $exp = $skill*$skill;
    return $exp;
}
function getMaxHit($userid) {
    $abfrage = mysql_query("SELECT constitution FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);

    return $user[constitution] * 10;
}
function getMaxMana($userid) {
    $abfrage = mysql_query("SELECT intelligence FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);

    return $user[intelligence] * 10;
}
function getMaxAP($userid) {
    $abfrage = mysql_query("SELECT constitution, dexterity FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);

    return ceil($user[constitution] + $user[dexterity] / 2) + 8;
}
function increaseAP($userid, $amount) {
    mysql_query("UPDATE alk_user SET ap = ap+'$amount' WHERE id = '$userid'");
}
function decreaseAP($userid, $amount) {
    mysql_query("UPDATE alk_user SET ap = ap-'$amount' WHERE id = '$userid'");
}
function getArmor($userid) {
    $defense = 0;
    $armorsql = mysql_query("SELECT * FROM alk_inventory WHERE userid = '$userid' AND equiped > 0 AND (type='armor' OR type='helmet')");
    while ($armor = @mysql_fetch_array($armorsql)) {
        $defense += $armor[value1];
    }
    return $defense;
}

function getWeaponUniquename($userid) {
    $abfragen = mysql_query("select * from alk_inventory WHERE userid = '$userid' AND type='weapon' AND equiped = '1'");
    $weapon = mysql_fetch_array($abfragen);

    return $weapon[uniquename];
}
function getEquipedWeaponName($userid) {
    $abfragen = mysql_query("select * from alk_inventory WHERE userid = '$userid' AND type='weapon' AND equiped = '1'");
    $weapon = mysql_fetch_array($abfragen);
    
    $selectnamelang = "name_".getLang($userid);
    $weaponname = $weapon[$selectnamelang];

    return $weaponname;
}
function getDiceResult($dice) {
    
    $dice = explode("W", $dice);
    $result = 0;
    for ($i = 0; $i < $dice[0]; $i++) {
        $result += rand(1, $dice[1]);
    }

    return $result;
}
function getWeaponDamage($userid) {
    $abfragen = mysql_query("select * from alk_inventory WHERE userid = '$userid' AND type='weapon' AND equiped = '1'");
    $weapon = mysql_fetch_array($abfragen);
    
    $damage = getDiceResult($weapon[value1]);
    $damage += $weapon[value2];

    return $damage;
}
function getEquipedItemPriSkill($userid, $uniqueitemname) {
    $abfragen = mysql_query("select pri_skill from alk_inventory WHERE userid = '$userid' AND uniquename='$uniqueitemname' AND equiped = '1'");
    $weapon = mysql_fetch_array($abfragen);

    return $weapon[pri_skill];
}
function getEquipedItemSecSkill($userid, $uniqueitemname) {
    $abfragen = mysql_query("select sec_skill from alk_inventory WHERE userid = '$userid' AND uniquename='$uniqueitemname' AND equiped = '1'");
    $weapon = mysql_fetch_array($abfragen);

    return $weapon[sec_skill];
}

//Iteminformationen aus Itemdatenbank laden
function getItemById($itemid) {
    $itemabfragen = mysql_query("select * from alk_items WHERE `id` = '$itemid'");
    $item = mysql_fetch_array($itemabfragen);
    return $item;
}
function getItemByUniquename($uniquename) {
    $itemabfragen = mysql_query("select * from alk_items WHERE WHERE uniquename = '$uniquename'");
    $item = mysql_fetch_array($itemabfragen);
    return $item;
}

function getItemPriSkill($userid, $uniqueitemname) {
    $abfragen = mysql_query("select pri_skill from alk_inventory WHERE userid = '$userid' AND uniquename='$uniqueitemname'");
    $weapon = mysql_fetch_array($abfragen);

    return $weapon[pri_skill];
}
function getItemSecSkill($userid, $uniqueitemname) {
    $abfragen = mysql_query("select sec_skill from alk_inventory WHERE userid = '$userid' AND uniquename='$uniqueitemname'");
    $weapon = mysql_fetch_array($abfragen);

    return $weapon[sec_skill];
}
function createItem($userid, $uniqueitemname, $itemname, $description, $type, $value1, $value2, $pri_skill, $sec_skill, $gold, $weight) {
    $itemsql = "INSERT INTO alk_inventory ("
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
            . "weight"
            . ") VALUE ("
            . "'$userid',"
            . "'$uniqueitemname',"
            . "'$itemname',"            
            . "'$description',"            
            . "'$type',"
            . "'$value1',"
            . "'$value2',"
            . "'$pri_skill',"
            . "'$sec_skill',"
            . "'$gold',"
            . "'$weight'"
            . ")";
    mysql_query($itemsql);
}
function createItemById($userid, $itemid) {
    $itemid = mysql_real_escape_string($itemid);
    
    $abfragen = mysql_query("select * from alk_items WHERE id='$itemid'");
    $item = mysql_fetch_array($abfragen);
    
            $itemname = $item[name];
            $description = $item[description];            
            $type = $item[type];
            $value1 = $item[value1];
            $value2 = $item[value2];
            $pri_skill = $item[pri_skill];
            $sec_skill = $item[secs_kill];
            $gold = $item[gold];
            $weight = $item[weight];
            $exp = $item[exp];
    
    $itemsql = "INSERT INTO alk_inventory ("
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
            . "weight"
            . ") VALUE ("
            . "'$userid',"
            . "'$uniqueitemname',"
            . "'$itemname',"                  
            . "'$description',"
            . "'$type',"
            . "'$value1',"
            . "'$value2',"
            . "'$pri_skill',"
            . "'$sec_skill',"
            . "'$gold',"
            . "'$weight'"
            . "'$exp'"
            . ")";
    mysql_query($itemsql);
}
function createItemByUnique($userid, $uniqueitemname) {
    $uniqueitemname = mysql_real_escape_string($uniqueitemname);
    
    $abfragen = mysql_query("select * from alk_items WHERE uniquename='$uniqueitemname'");
    $item = mysql_fetch_array($abfragen);
    
            $itemname = $item[name];
            $description = $item[description];            
            $type = $item[type];
            $value1 = $item[value1];
            $value2 = $item[value2];
            $pri_skill = $item[pri_skill];
            $sec_skill = $item[secs_kill];
            $gold = $item[gold];
            $weight = $item[weight];
            $exp = $item[exp];
    
    $itemsql = "INSERT INTO alk_inventory ("
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
            . "weight"
            . ") VALUE ("
            . "'$userid',"
            . "'$uniqueitemname',"
            . "'$itemname',"                  
            . "'$description',"
            . "'$type',"
            . "'$value1',"
            . "'$value2',"
            . "'$pri_skill',"
            . "'$sec_skill',"
            . "'$gold',"
            . "'$weight'"
            . "'$exp'"            
            . ")";
    mysql_query($itemsql);
}
function getHitReg($userid) {
    $abfrage = mysql_query("SELECT strenght, constitution FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    $reg = ceil((getMaxHit($userid)/100)*($user[strenght]+($user[constitution]*2)));
    return $reg;
}
function getManaReg($userid) {
    $abfrage = mysql_query("SELECT willpower, intelligence FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    $reg = ceil((getMaxHit($userid)/100)*($user[intelligence]+($user[willpower]*2)));
    return $reg;    
}
function getLang($userid) {
    $abfrage = mysql_query("SELECT lang FROM alk_user WHERE `id` = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    $lang = $user[lang];
    
    if ($user[lang] == "") {
        $lang = "en";
    }
    
    return $lang;
}
function getRandomMobUniquenameFromPosition($posx,$posy) {
    $posx = (mysql_real_escape_string($posx));
    $posy = (mysql_real_escape_string($posx));
    $abfrage = mysql_query("SELECT monster FROM alk_map WHERE x = '$posx' AND y = '$posy' LIMIT 1");
    $map = mysql_fetch_array($abfrage);
    
    $monster = explode(",", $map[monster]);
    $randommonster = array_rand($monster);
    
    return mysql_real_escape_string($monster[$randommonster]);
}
function getPositionFromUniquename($uniquename) {
    $abfragen = mysql_query("SELECT x,y,monster FROM alk_map WHERE NOT monster = ''");
    
    $pos = "";
    
    while ($map = @mysql_fetch_array($abfragen)) {
        $monsterarray = explode(",",$map[monster]);
        if (in_array($uniquename, $monsterarray)) {
            if (!$pos == "") {
                $pos .= ",";
            }
            $pos .= "$map[x]:$map[y]";
        }
    }
    return $pos;
}
function checkifspellknown($userid, $usedspell) {
    $userid = mysql_real_escape_string($userid);
    $usedspell = mysql_real_escape_string($usedspell);
    $abfrage = mysql_query("SELECT magicknowledge FROM alk_user WHERE id = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    $spell = explode(",", $user[magicknowledge]);
    
    if (in_array($usedspell, $spell)) {
        return true;
    }
    else {
        return false;
    }
}
function getMana($userid) {
    $userid = mysql_real_escape_string($userid);
    $abfrage = mysql_query("SELECT mana FROM alk_user WHERE id = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    return $user[mana];
}
function getManaFromBattle($userid) {
    $userid = mysql_real_escape_string($userid);
    $abfrage = mysql_query("SELECT mana FROM alk_battle WHERE ownid = '$userid' LIMIT 1");
    $user = mysql_fetch_array($abfrage);
    
    return $user[mana];
}
function getCurrentFile() {
    $currentfile = explode("/",$_SERVER['PHP_SELF']);
    return $currentfile[count($currentfile)-1];
}
function getCurrentFileNameOnly() {
    //Gibt den aktuellen Dateinamen ohne Endung zurück
    $currentfile = explode("/",$_SERVER['PHP_SELF']);
    $currentfilename = explode(".",$currentfile[count($currentfile)-1]);
    return $currentfilename[0];
}

function getPlantIdFromMap($posx,$posy) {
    $abfragen = mysql_query("select plants from alk_map WHERE x = '$posx' AND y = '$posy' LIMIT 1");
    $row = mysql_fetch_array($abfragen);
    return $row;
}
//Pflanzen sammeln (Nochmal um bzw neu schreiben, hier stimmt irgendwas nicht ganz, es werden keine Items gefunden)
function gather($userid,$posx,$posy) {
    $abfragen = mysql_query("select * from alk_skills WHERE `userid` = '$userid'");
    $skill = mysql_fetch_array($abfragen);

    $exp = 1;

    $plantarray = explode(",", getPlantIdFromMap($posx,$posy));
    foreach ($plantarray as $plantid) {
        $plant = getItemById($plantid);
        if ($skill[$plant[pri_skill]] >= rand(1, $plant[value1])) {
            $exp += 1;
            if ($skill[$plant[sec_skill]] >= rand(1, $plant[value2])) {
                $gathered .= ",$plant[name]";
                createItemById($userid, $plantid);
                $exp += $plant[exp];
            }
        }
    }

    mysql_query("UPDATE alk_user SET ap = ap - 1, exp = exp+'$exp' WHERE id = '$userid'");

    return $gathered;
}
?>