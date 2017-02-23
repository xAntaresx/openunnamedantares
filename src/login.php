<?php
session_start();
require("config.inc.php");


if(!isset($_POST["submit"])) {

$inhalt .= '
<form action="login.php" method="post">
<table cellpadding="3">
	<tr>
		<td height="20"></td>
		<td></td>
	</tr>
	<tr>
		<td width="55%" align="right">'.$lang_username.'</td>
		<td><input type="text" name="user" class="input"></td>
	</tr>

	<tr>
		<td width="55%" align="right">'.$lang_password.'</td>
		<td><input type="password" name="passwort" class="input"></td>
	</tr>

	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="'.$lang_login.'" class="button"></td>
	</tr>
	<tr>
		<td height="20"></td>
		<td></td>
	</tr>
</table>
</form>';


}else{

	$passwortv = md5($_POST["passwort"]);
	$abfragen = mysql_query("select * from alk_user WHERE `user` = '".mysql_real_escape_string($_POST["user"])."' AND `passwort` = '".$passwortv."'");
	$user = mysql_fetch_array($abfragen);

	if(empty($user["id"])) {

	$use_tpl = "none";
	$inhalt = '<br>'.$lang_loginnotsuccess.'.<br>'.$lang_usernameorpasswordwrong.'.<br><br><br>';

	}else{
        
        $_SESSION["id"] = $user["id"];
	$_SESSION["user"] = $user["user"];
	$use_tpl = "none";
	$inhalt = '<meta http-equiv="refresh" content="3; URL=charakter.php"><br>'.$lang_loginsuccessful.'.<br><br><a class="user" href="charakter.php">'.$lang_clickhere.'</a>, '.$lang_togotomenue.'<br><br><br>';
   
	}
}


if(isset($_GET["logout"])) {


	if(empty($_SESSION["user"])) {

	header("location:login.php");

	}else{

	session_destroy();
	$use_tpl = "none";
	$inhalt = '<meta http-equiv="refresh" content="3; URL=index.php"><br>'.$lang_logoutsuccessful.'.<br><br><a class="user" href="index.php">'.$lang_clickhere.'</a>, '.$lang_togotohome.'.<br><br><br>';

	}


}

$body=implode("",file("template/normal.tpl"));
$body=str_replace("<?inhalt?>",$inhalt,$body);
$body = str_replace("<?titel?>", $lang_login, $body);

if($use_tpl == "none")
{
$body = $inhalt;
}

$sitetitle = $lang_login;
include_once 'template.php';
?>