<?php

session_start();
require("config.inc.php");

if (isset($_GET["image"])) {
    unset($_SESSION["captcha_spam"]);

    $zahl = "7";
    $str = "";

// l und 1 sind nicht dabei, da sie bei dieser Font gleich aussehen
    $buchstaben = 'abcdefghijkmnopqrstuvwxyz234567890';

    $buchstabenzahl = strlen($buchstaben) - "1";

    for ($i = "1"; $zahl >= $i; $i++) {
        srand(microtime() * 1000000);
        $zufall = rand(0, $buchstabenzahl);
        $str .= $buchstaben{$zufall};
    }

    $_SESSION["captcha_spam"] = $str;


    $font = "font/captcha.ttf";
    $pic = ImageCreateFromGIF("images/captcha.gif");
    $im = ImageCreate(150, 30);
    $color = ImageColorAllocate($pic, 200, 47, 9);
    ImageTTFText($pic, 22, 1, 18, 30, $color, $font, $str);
    imageline($pic, 20, 25, 90, 32, $color);

    header("Content-type: image/gif");
    ImageGIF($pic);
    ImageDestroy($pic);
} else {

    if (!isset($_POST["submit"])) {

        $inhalt .= '
<form action="registration.php" method="post">

<table cellpadding="5">
	<tr>
		<td width="150" height="20"></td>
		<td></td>
	</tr>
	<tr>
		<td width="150" align="right">' . $lang_username . ':</td>
		<td><input type="text" name="user" class="input" size="25"></td>
	</tr>
	<tr>
		<td width="150" align="right">' . $lang_email . ':</td>
		<td><input type="text" name="email" size="25" class="input"></td>
	</tr>
	<tr>
		<td width="150" align="right">' . $lang_password . ':</td>
		<td><input type="password" name="passwort" size="25" class="input"></td>
	</tr>
	<tr>
		<td width="150" align="right">' . $lang_repeatpassword . ':</td>
		<td><input type="password" name="passwort2" size="25" class="input"></td>
	</tr>     
	<tr>
		<td width="150" align="right">Sprache:</td>
		<td>
                    <select id="input" name="language" size="1">
                        <option value="de">Deutsch</option>
                    </select>                
                </td>
	</tr>';        
//	<tr>
//		<td width="150" align="right"></td>
//		<td><img src="registration.php?image" border="0" alt="' . $lang_securitycode . '"></td>
//	</tr>
//	<tr>
//		<td width="150" align="right">' . $lang_securitycode . ':</td>
//		<td><input type="text" name="captcha_spam" size="25" class="input"></td>
//	</tr>
        $inhalt .= '
	<tr>
		<td></td>
		<td><br><input type="submit" name="submit" value="' . $lang_createuser . '" class="button"></td>
	</tr>
	<tr>
		<td width="150" height="20"></td>
		<td></td>
	</tr>
</table>
</form>';
    } elseif (($_POST["user"] == "") OR ( $_POST["email"] == "") OR ( $_POST["passwort"] == "") OR ( $_POST["passwort2"] == "")) {

        $use_tpl = "none";
        $inhalt = "'$lang_youmustfillallfilds'";

        $use_tpl = "none";
    } elseif ($_POST["passwort"] != $_POST["passwort2"]) {

        $use_tpl = "none";
        $inhalt = $lang_passworddontmatch;
    } 
//    elseif ($_POST["captcha_spam"] != $_SESSION["captcha_spam"]) {
//
//        $use_tpl = "none";
//        $inhalt = "<br>$lang_securitycodeiswrong";
//    } 
    else {
        $checkusername = mysql_real_escape_string($_POST["user"]);
        $abfragen = mysql_query("select * from alk_user WHERE `user` = '" . $checkusername . "'");
        $user = mysql_fetch_array($abfragen);

        if (isset($user["id"])) {

            $use_tpl = "none";
            $inhalt = "<br>$lang_usernamealreadyexists";
        } else {

            $username = mysql_real_escape_string($_POST["user"]);
            $email = mysql_real_escape_string($_POST["email"]);
            $language = mysql_real_escape_string($_POST["language"]);

            if ($insert = @mysql_query("INSERT INTO alk_user SET user = '" . $username . "', passwort = '" . md5($_POST["passwort"]) . "', email = '" . $email . "', lang = '" . $language . "', pos='500:500', endpos='500:500'")) {
                $abfragen = mysql_query("select * from alk_user WHERE `user` = '$username'");
                $user = mysql_fetch_array($abfragen);

                mysql_query("INSERT INTO alk_skills SET userid = $user[id]");
                
                $header = $emailadmin;
                $betreff = "$lang_signup";
                $kommentar = "$lang_welcometo Necromancer! \n \n$lang_username: " . $_POST["user"] . " \n$lang_password: " . $_POST["passwort"] . "\n\n\n$lang_wewishyoumuchfun.\n\n\n$lang_thismailwassendautomatically.\nmailto: " . $emailadmin . "\n\n";
                mail($mail, $betreff, $kommentar, $header);
                $use_tpl = "none";
                $inhalt = '<meta http-equiv="refresh" content="3; URL=login.php"><br>' . $lang_usercreationsuccessful . '.<br><br><a class="user" href="login.php">' . $lang_clickhere . '</a>, ' . $lang_togotologin;
            } else {
                $use_tpl = "none";
                $inhalt = "<br>$lang_usercouldnotbecreated";
            }
        }
    }

    $body = implode("", file("template/normal.tpl"));
    $body = str_replace("<?inhalt?>", $inhalt, $body);
    $body = str_replace("<?titel?>", $lang_registration, $body);

    if ($use_tpl == "none") {
        $body = $inhalt;
    }

    $sitetitle = $lang_registration;
    include_once 'template.php';
}
?>