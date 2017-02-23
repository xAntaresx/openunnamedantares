<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["user"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION['id'] . "'");
$user = mysql_fetch_array($abfragen);

$skillabfragen = mysql_query("select * from alk_skills WHERE `userid` = '$user[id]'");
$skill = mysql_fetch_array($skillabfragen);

$username = $user['user'];

$inhalt .= "<p style='text-align: center;'><a href='charakter.php?op=skills'>Fähigkeiten</a> | <a href='charakter.php?op=equip'>Ausrüstung</a> | <a href='charakter.php?op=inventory'>Inventar</a></p>";

$inhalt .= "<table style='width: 100%;'>";
$inhalt .= "<tr>";
$inhalt .= "<td style='width: 200px;'>";
$inhalt .= "<a href='profile.php?id=$user[id]'><img src='$user[avatar]' style='border: 1px solid; width: 200px; height: 200px;'></a>";
$inhalt .= "</td>";
$inhalt .= "<td valign='top'>";

if ($_GET[op] == "equip") {
    if (isset($_POST["equip"])) {
        $head = mysql_real_escape_string($_POST[head]);
        mysql_query("UPDATE alk_inventory SET equiped = 0 WHERE userid = '$user[id]' AND type = 'helmet'");
        mysql_query("UPDATE alk_inventory SET equiped = 1 WHERE userid = '$user[id]' AND type = 'helmet' AND id = '$head'");

        $torso = mysql_real_escape_string($_POST[torso]);
        mysql_query("UPDATE alk_inventory SET equiped = 0 WHERE userid = '$user[id]' AND type = 'armor'");
        mysql_query("UPDATE alk_inventory SET equiped = 1 WHERE userid = '$user[id]' AND type = 'armor' AND id = '$torso'");

        $weapon = mysql_real_escape_string($_POST[weapon]);
        mysql_query("UPDATE alk_inventory SET equiped = 0 WHERE userid = '$user[id]' AND type = 'weapon'");
        mysql_query("UPDATE alk_inventory SET equiped = 1 WHERE userid = '$user[id]' AND type = 'weapon' AND id = '$weapon'");
    }

    $selectnamelang = "name_" . $user[lang];
    $tdstyle = "style='padding-right: 25px;'";

    $inhalt .= "<form action='charakter.php?op=equip' method='post'>";
    $inhalt .= "<table>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_head</td>";
    $inhalt .= "<td>";
    $inhalt .= "<select id='input' name='head' size='1'>";
    if (countEquipedItem($user[id], 'helmet') < 1) {
        $inhalt .= "<option value=''></option>";
    }
    $headsql = mysql_query("SELECT * FROM alk_inventory WHERE userid = '$user[id]' AND type='helmet' ORDER BY equiped DESC");
    while ($head = @mysql_fetch_array($headsql)) {
        $inhalt .= "<option value='$head[id]'>$head[$selectnamelang]</option>";
    }
    if (countEquipedItem($user[id], 'helmet') > 0) {
        $inhalt .= "<option value=''></option>";
    }
    $inhalt .= "</select>";
    $inhalt .= "</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_torso</td>";
    $inhalt .= "<td>";
    $inhalt .= "<select id='input' name='torso' size='1'>";
    if (countEquipedItem($user[id], 'armor') < 1) {
        $inhalt .= "<option value=''></option>";
    }
    $torsosql = mysql_query("SELECT * FROM alk_inventory WHERE userid = '$user[id]' AND type='armor' ORDER BY equiped DESC");
    while ($torso = @mysql_fetch_array($torsosql)) {
        $inhalt .= "<option value='$torso[id]'>$torso[$selectnamelang]</option>";
    }
    if (countEquipedItem($user[id], 'armor') > 0) {
        $inhalt .= "<option value=''></option>";
    }
    $inhalt .= "</select>";
    $inhalt .= "</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_weaponhand</td>";
    $inhalt .= "<td>";
    $inhalt .= "<select id='input' name='weapon' size='1'>";
    if (countEquipedItem($user[id], 'weapon') < 1) {
        $inhalt .= "<option value=''></option>";
    }
    $weaponsql = mysql_query("SELECT * FROM alk_inventory WHERE userid = '$user[id]' AND type='weapon' ORDER BY equiped DESC");
    while ($weapon = @mysql_fetch_array($weaponsql)) {
        $inhalt .= "<option value='$weapon[id]'>$weapon[$selectnamelang]</option>";
    }
    if (countEquipedItem($user[id], 'weapon') > 0) {
        $inhalt .= "<option value=''></option>";
    }
    $inhalt .= "</select>";
    $inhalt .= "</td>";
    $inhalt .= "</tr>";

    $inhalt .= "</table>";
    $inhalt .= "<p style='text-align: right;'><input type='submit' name='equip' value='$lang_changesettings' class='button'></p>";
    $inhalt .= "</form>";
}

if ($_GET[op] == "inventory") {
    $tdstyle = "style='padding-right: 25px; width: 35%;'";

    $inhalt .= "<table style='margin-left: 25px; margin-right: 25px; width: 100%; border: 1px solid;'>";
    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle><b>$lang_item</b></td>";
    $inhalt .= "<td><b>$lang_description</b></td>";
    $inhalt .= "<td style='text-align: center;'><b>$lang_weight</b></td>";
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
                $uniquemem = $lastunique;
                $lastunique = $item[uniquename];
                $inhalt .= "<tr>";
                $inhalt .= "<td $tdstyle>$anzahl x $lastname";
                if ($lastequiped == 1) {
                    $inhalt .= " <small>($lang_equiped)</small>";
                }
                $inhalt .= "</td>";
                $inhalt .= "<td>$lastdesc</td>";
                $inhalt .= "<td style='text-align: center;'>".($lastweight*$anzahl)."</td>";
                $inhalt .= "<td>";
                addnav("items.php");
                if ($lasttype == "nahrung") {
                    $inhalt .= "<a href='items.php?op=eat&what=$uniquemem'>$lang_eat</a>";
                }
                $inhalt .= "</td>";
                $inhalt .= "</tr>";
                $anzahl = 0;
            }
        }

        $lastunique = $item[uniquename];
        $lastname = $itemname;
        $lastdesc = $itemdescription;
        $lastequiped = $item[equiped];
        $lasttype = $item[type];
        $lastweight = $item[weight];
    }
    $anzahl++;
    $lastunique = $item[uniquename];
    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$anzahl x $lastname";
    echo $item[equiped];
    if ($lastequiped == 1) {
        $inhalt .= " <small>($lang_equiped)</small>";
    }
    $inhalt .= "</td>";
    $inhalt .= "<td>$lastdesc</td>";
    $inhalt .= "<td style='text-align: center;'>".($lastweight*$anzahl)."</td>";
    $inhalt .= "<td>";
    addnav("items.php");
    if ($lasttype == "nahrung") {
        $inhalt .= "<a href='items.php?op=use&what=$lastunique'>$lang_eat</a>";
    }
    $inhalt .= "<td>";
    $inhalt .= "</td>";
    $inhalt .= "</tr>";
    $anzahl = 1;
    $inhalt .= "</table>";
}

if ($_GET[op] == "" || $_GET[op] == "skills") {

    if (isset($_GET[up]) || isset($_GET[down])) {
        $sql = "UPDATE ";
        $checkok = 0;
        $exp = 0;

        if ($_GET[up] == "strenght" || $_GET[up] == "constitution" || $_GET[up] == "dexterity" || $_GET[up] == "intelligence" || $_GET[up] == "willpower" || $_GET[up] == "speed" || $_GET[down] == "strenght" || $_GET[down] == "constitution" || $_GET[down] == "dexterity" || $_GET[down] == "intelligence" || $_GET[down] == "willpower" || $_GET[down] == "speed") {
            $sql .= "alk_user SET ";
            $where = "id";
            if (isset($_GET[up])) {
                if ($user[exp] >= getExpTableAttribut($user[$_GET[up]])) {
                    $checkok = 1;
                    $exp = getExpTableAttribut($user[$_GET[up]]);
                }
                if ($user[$_GET[up]] >= 100) {
                    $checkok = 2;
                }
                if (getRemainingAttributPoints($user[id]) <= 0) {
                    $checkok = 3;
                }
            }
            if (isset($_GET[down])) {
//                if ($row[exp] >= getExpTableAttribut($row[$_GET[down]])) {
                $checkok = 1;
//                }
                if ($user[$_GET[down]] <= 1) {
                    $checkok = 2;
                }
            }
        } else {
            $sql .= "alk_skills SET ";
            $where = "userid";
            if (isset($_GET[up])) {
                if ($user[exp] >= getExpTableSkill($skill[$_GET[up]])) {
                    $checkok = 1;
                    $exp = getExpTableSkill($skill[$_GET[up]]);
                }
                if ($skill[$_GET[up]] >= 100) {
                    $checkok = 2;
                }
                if (getRemainingSkillPoints($user[id]) <= 0) {
                    $checkok = 3;
                }
            }
            if (isset($_GET[down])) {
//                if ($row[exp] >= getExpTableSkill($skill[$_GET[down]])) {
                $checkok = 1;
//                }
                if ($skill[$_GET[down]] <= 0) {
                    $checkok = 2;
                }
            }
        }

        if (isset($_GET[up])) {
            $skill = mysql_real_escape_string($_GET[up]);
            $sql .= "$skill = $skill + 1 ";
        }
        if (isset($_GET[down])) {
            $skill = mysql_real_escape_string($_GET[down]);
            $sql .= "$skill = $skill - 1 ";
        }

        $sql .= "WHERE $where = $user[id]";
        if ($checkok == 1) {
            mysql_query($sql);
            mysql_query("UPDATE alk_user SET exp = exp - '$exp' WHERE id = '$user[id]'");
        } else if ($checkok == 2) {
            $inhalt .= "<p style='text-align: center;'>$lang_skillend.</p>";
        } else if ($checkok == 3) {
            $inhalt .= "<p style='text-align: center;'>$lang_nopointsover.</p>";
        } else {
            $inhalt .= "<p style='text-align: center;'>$lang_notenoughtexp.</p>";
        }
    }

    $abfragen = mysql_query("select * from alk_user WHERE `id` = '" . $_SESSION['id'] . "'");
    $user = mysql_fetch_array($abfragen);

    $skillabfragen = mysql_query("select * from alk_skills WHERE `userid` = '$user[id]'");
    $skill = mysql_fetch_array($skillabfragen);

    $tdstyle = "style='padding-right: 25px;'";
    $inhalt .= "<div style='text-align: right;'>$lang_experience: $user[exp]</div>";
    $inhalt .= "<table>";

    $inhalt .= "<tr>";
    $inhalt .= "<td><b>$lang_attributes</b> (" . getRemainingAttributPoints($user[id]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td style='width: 25px;'></td>";
    $inhalt .= "<td><b>$lang_skills</b> (" . getRemainingSkillPoints($user[id]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_strenght</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=strenght'>[-]</a> $user[strenght] <a href='charakter.php?op=skills&up=strenght'>[+]</a> (" . getExpTableAttribut($user[strenght]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_swordfighting</td>";
    $inhalt .= "<td> <a href='charakter.php?op=skills&down=sword'>[-]</a> $skill[sword] <a href='charakter.php?op=skills&up=sword'>[+]</a> (" . getExpTableSkill($skill[sword]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_constitution</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=constitution'>[-]</a> $user[constitution] <a href='charakter.php?op=skills&up=constitution'>[+]</a> (" . getExpTableAttribut($user[constitution]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_axefighting</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=axe'>[-]</a> $skill[axe] <a href='charakter.php?op=skills&up=axe'>[+]</a> (" . getExpTableSkill($skill[axe]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_dexterity</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=dexterity'>[-]</a> $user[dexterity] <a href='charakter.php?op=skills&up=dexterity'>[+]</a> (" . getExpTableAttribut($user[dexterity]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_stafffighting</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=staff'>[-]</a> $skill[staff] <a href='charakter.php?op=skills&up=staff'>[+]</a> (" . getExpTableSkill($skill[staff]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_intelligence</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=intelligence'>[-]</a> $user[intelligence] <a href='charakter.php?op=skills&up=intelligence'>[+]</a> (" . getExpTableAttribut($user[intelligence]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_tactics</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=tactics'>[-]</a> $skill[tactics] <a href='charakter.php?op=skills&up=tactics'>[+]</a> (" . getExpTableSkill($skill[tactics]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_willpower</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=willpower'>[-]</a> $user[willpower] <a href='charakter.php?op=skills&up=willpower'>[+]</a> (" . getExpTableAttribut($user[willpower]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_spellcasting</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=spellcasting'>[-]</a> $skill[spellcasting] <a href='charakter.php?op=skills&up=spellcasting'>[+]</a> (" . getExpTableSkill($skill[spellcasting]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle>$lang_speed</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=speed'>[-]</a> $user[speed] <a href='charakter.php?op=skills&up=speed'>[+]</a> (" . getExpTableAttribut($user[speed]) . ")</td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_wilderness</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=wilderness'>[-]</a> $skill[wilderness] <a href='charakter.php?op=skills&up=wilderness'>[+]</a> (" . getExpTableSkill($skill[wilderness]) . ")</td>";
    $inhalt .= "</tr>";

    $inhalt .= "<tr>";
    $inhalt .= "<td $tdstyle></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td></td>";
    $inhalt .= "<td $tdstyle>$lang_botany</td>";
    $inhalt .= "<td><a href='charakter.php?op=skills&down=botany'>[-]</a> $skill[botany] <a href='charakter.php?op=skills&up=botany'>[+]</a> (" . getExpTableSkill($skill[botany]) . ")</td>";
    $inhalt .= "</tr>";    
    
    $inhalt .= "</table>";
}

$inhalt .= "</td>";
$inhalt .= "</tr>";
$inhalt .= "</table>";

$sitetitle = $username . " - " . $lang_level . ": " . getLevel($user[id]);
include_once 'template.php';
?>