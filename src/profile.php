<?php

    session_start();
    require("config.inc.php");

    $inhalt .= "<br>";
    $profileid = mysql_real_escape_string($_GET[id]);
    
    $abfragen=mysql_query("select * from alk_user WHERE `id` = '$profileid'");
    $userinprofile = mysql_fetch_array($abfragen);   
    
    if ($userinprofile[lang] == "de") {
        $languageset = $lang_german;
    } 
    else if ($userinprofile[lang] == "en") {
        $languageset = $lang_english;
    }
    else {
        $languageset = $lang_unknown;
    }
    $inhalt .= "<table><tr>";    
    $inhalt .= "<td>
                    <p><a href='messages.php?op=write&sendto=$profileid'>$lang_writemessage</a></p>
                    <img src='$userinprofile[avatar]' style='border: 1px solid; width: 200px; height: 200px;'>
                    ";
    if ($profileid != $_SESSION[id]) $inhalt .= "<p><a href='battle.php?op=startbattle&enemyid=$profileid'>$lang_attack</a></p>";
    $inhalt .= "
                </td>";
    $inhalt .= "<td valign='top' style='padding: 25px;'>";
    $inhalt .= "$lang_class: $userinprofile[classname]<br>";
    $inhalt .= "$lang_language: $languageset<br>
             ";
    $inhalt .= "</td>";
    $inhalt .= "</tr></table>";
    
    $inhalt .= "<br><b>$lang_profiltext</b><br><hr>".$userinprofile[profiltext];
    
    $isonline = "Offline";
    if(isOnline($userinprofile[id]) == 1) {
        $isonline = "Online";
    }
    
    $sitetitle = getnamebyid($profileid)." - ".$isonline;
    include_once 'template.php';
?>

