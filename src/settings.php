<?php

session_start();
require("config.inc.php");

if (!isset($_SESSION["id"])) {
    header("location:login.php");
    die;
}

$abfragen = mysql_query("select * from alk_user WHERE `user` = '" . $_SESSION['user'] . "'");
$user = mysql_fetch_array($abfragen);

if ($user[su] == 1) {
    $admin = '<a href="admin.php">Administrations Bereich</a><br><br>';
}
if (!isset($_POST["submit"])) {
    $inhalt .= '<br><br>
        ID: ' . $user["id"] . '<br>   
        ' . $lang_username . ': <b>' . $user["user"] . '</b><br>';

    $inhalt .= '<br>
    <form action="settings.php" method="post">
    ' . $lang_language . ':
        <select id="input" name="language" size="1">';
    if ($user[lang] == "de") {
        $inhalt .= "
                <option value='de'>Deutsch</option>
                ";
    } else {
        $inhalt .= "
                <option value='en'>English</option>
                <option value='de'>Deutsch</option>
                ";
    }
    $inhalt .= '</select><br>';
    $inhalt .= "<p>$lang_classname <br><input style='width: 250px;' id='classname' name='classname' type='text' value='$user[classname]'></p>";
    $inhalt .= "<p>$lang_avatar URL<br><input style='width: 250px;' id='avatar' name='avatar' type='text' value='$user[avatar]'></p>";
    $inhalt .= "<p>$lang_profiltext<br> <textarea id='profiltext' name='profiltext' cols='50' rows='6'>" . $user[profiltext] . "</textarea><br>$lang_htmlallowed";
//    $inhalt .= "<p>App $lang_buttonsize<br><input style='width: 50px;' id='buttonsize' name='buttonsize' type='text' value='$row[buttonsize]'></p>";
    $inhalt .= '<input type="submit" name="submit" value="' . $lang_changesettings . '" class="button">
    </form>';

    include_once 'statimage.php';

    $inhalt .= '<center><br><br>' . $admin . '</center><br><br>';
} else {
    $language = mysql_real_escape_string($_POST[language]);
    $avatar = mysql_real_escape_string($_POST[avatar]);
    $classname = mysql_real_escape_string($_POST[classname]);
//    $buttonize = mysql_real_escape_string($_POST[buttonsize]);
    $profiltext = mysql_real_escape_string($_POST[profiltext]);

    $zeichen = array("<script>", "</script>", "<?php", "<?", "?>");
    $profiltext = str_replace($zeichen, "", $profiltext);

    $profiltext = mysql_real_escape_string($profiltext);

    mysql_query("UPDATE alk_user SET lang = '$language', avatar = '$avatar', classname = '$classname', profiltext = '$profiltext' WHERE `id` = '" . $_SESSION['id'] . "'");
    $inhalt .= '<br><br>' . $lang_settingschanged . '</center><br><br>';
}
$sitetitle = $lang_settings;
include_once 'template.php';
?>