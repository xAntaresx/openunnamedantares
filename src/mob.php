<?php

$mobsql = "SELECT * FROM alk_monster WHERE uniquename='$uniquename'";
$result = mysql_query($mobsql);
$monster = mysql_fetch_array($result);

$user2maxhits = $monster[hitpoints];
$user2maxmana = $monster[mana];
    $user2_name = $monster[name];
    $user2_weaponname = $monster[weaponname];


$enemydamage = getDiceResult($monster[damage]);
$user2armor = $monster[armor];
$user2dex = $monster[dexterity];
$user2avatar = "images/npc/$monster[uniquename].jpg";
if (!file_exists($user2avatar)) {
    $user2avatar = "images/npc/default.jpg";
}
$user2pos = getPositionFromUniquename($monster[uniquename]);
$user2exp = $monster[exp];
$user2gold = $monster[gold];
$loot = $monster[loot];

if ($getloot == 1) {
    $lootlist = explode(",", $loot);
    foreach ($lootlist as $item) {
        $lootvalues = explode(":", $item);
        $amount = $lootvalues[0];
        $uniquename = $lootvalues[1];
        for ($i = 0; $i < $amount; $i++) {
            $chance = rand($lootvalues[2], $lootvalues[3]);

            if ($chance = 1) {
                $lootitemsql = mysql_query("SELECT * FROM alk_items WHERE uniquename='$uniquename'");
                $lootitem = mysql_fetch_array($lootitemsql);

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
                        . "'$lootitem[uniquename]',"
                        . "'$lootitem[name]',"
                        . "'$lootitem[description]',"
                        . "'$lootitem[type]',"
                        . "'$lootitem[value1]',"
                        . "'$lootitem[value2]',"
                        . "'$lootitem[pri_skill]',"
                        . "'$lootitem[sec_skill]',"
                        . "'$lootitem[gold]',"
                        . "'$lootitem[weight]',"
                        . "'$lootitem[image]',"
                        . "'0'"
                        . ")");
                
                $selectnamelang = "name_".$user[lang];
                $itemname = $lootitem[$selectnamelang];
                $_SESSION[message] .= "<br>$itemname $lang_recieved.";
            }
        }
    }
}
$getloot = 0;
?>