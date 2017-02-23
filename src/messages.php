<?php

    session_start();
    require("config.inc.php");
    
    if(!isset($_SESSION["user"])) {
    header("location:login.php?");
    die;
    }    
  
    if ($_GET[op] == "write") {
        $sendto = getnamebyid(mysql_real_escape_string($_GET[sendto]));
        //Nachricht schreiben
        $inhalt .= "<br>";
        $inhalt .= "<form style='text-align: left;' action='messages.php?op=send' method='post' enctype='multipart/form-data'>";
        $inhalt .= "<table>";
        $inhalt .= "<tr>";
        $inhalt .= "<td style='text-align: right;'>$lang_receiver:</td><td><input style='width: 300px;' id='receiver' name='receiver' type='text' value='$sendto'></td>";
        $inhalt .= "</tr>";
        $inhalt .= "<tr>";
        $inhalt .= "<td style='text-align: right;'>$lang_subject:</td><td><input style='width: 300px;' id='subject' name='subject' type='text' value=''></td>";
        $inhalt .= "</tr>";
        $inhalt .= "<td></td><td><textarea id='text' name='text' cols='60' rows='8'></textarea></td>";
        $inhalt .= "</tr>";
        $inhalt .= "<tr>";
        $inhalt .= "<td></td><td style='text-align: right;'><input id='send' type='submit' value='Senden'></td>";
        $inhalt .= "</tr>";
        $inhalt .= "</form>";
        $inhalt .= "</table><br><a style='text-align: right;' href='messages.php'>$lang_back</a>";
    }
    else if ($_GET[op] == "send") {
        
        $sec = date("s");
        $min = date("i");
        $stund = date("H");
        $tagimmonat = date("d");
        $monate = date("m");
        $jahr = date("Y");
        $jahr = $jahr - 2000;
        
        $monateneu = $monate + "1";
        if (strlen($monateneu) == "1") {
            $monateneu = "0" . $monateneu;
        }
        
        //Nachricht senden
        $sender = mysql_real_escape_string($_SESSION[user]);
        $receiver = mysql_real_escape_string(getidbyname($_POST[receiver]));
        $subject = mysql_real_escape_string($_POST[subject]);
        $text = mysql_real_escape_string($_POST[text]);
        $date = $jahr . $monateneu . $tagimmonat . $stund; 
        
        mysql_query("INSERT INTO `alk_messages` (`sender`, `receiver`, `seen`, `subject`, `text`, `date`) VALUES ('$sender', '$receiver', '0', '$subject', '$text', '$date')");
        $inhalt .= "<br><br>";
        $inhalt .= "<div style='text-align: left;'>$lang_messagesent</div><a href='messages.php'><div style='text-align: right;'>$lang_back</div></a>";
    }
    else {
        
        mysql_query("update alk_messages SET seen = 1 WHERE `receiver` = '$_SESSION[id]'");
        
        $inhalt .= "<br><a href='messages.php?op=write'>$lang_writemessage</a><br><br>";
        
        //Eingegangene Nachrichten auflisten
        $abfragen = mysql_query("select * from alk_messages WHERE receiver = '$_SESSION[id]' ORDER BY id DESC");
        while ($message = @mysql_fetch_array($abfragen)) {
            if ($message[date] < getcurrentdate()) {
                mysql_query("DELETE FROM alk_messages WHERE $message[id]");
            }
            else {
                $inhalt .= '<table class="messagetable" width="100%" border="1" cellspacing="0" cellpadding="3" style="margin: 20px;">';
            $inhalt .= '
                <tr>
                        <td valign="top" style="width: 40%"><b>'.$lang_sender.':</b> <a href="profile.php?id='.  getidbyname(mysql_real_escape_string($message["sender"])).'">' . $message["sender"] . '</a></td>
                        <td valign="top" colspan="2" style="width: 60%"><b>' . $lang_subject . ':</b> ' . $message["subject"] . '</td>
                </tr>
                <tr>
                        <td valign="top" colspan="3">' . $message["text"] . '</td>
                </tr>
                <tr>
                        <td valign="top" colspan="3" style="text-align: right;"><small><a href="messages.php?op=write&sendto='.  getidbyname(mysql_real_escape_string($message["sender"])).'">'.$lang_reply.'</a></small></td>
                </tr>                
';
            $inhalt .= '</table>';
            }
            
//            echo "Datum jetzt: ".getcurrentdate()."<br>Datum Message: $message[date]";
            }
        
        
    }
    
    $sitetitle = $lang_letterpigeon;
    include_once 'template.php';
    
?>
