<?php

    if ($_GET[chatop] == "addtext") {
        $message = mysql_real_escape_string($_POST[message]);
        addToChat($user[id], $message);
    }
    
    ?>

