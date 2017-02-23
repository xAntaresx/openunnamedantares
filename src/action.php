<?php

// Sammeln
if ($_GET[action] == "gather") {
    if ($user[ap] >= 1) {
        //Sammeln starten
        $gathered = gather($user[id],$posx,$posy);

        if ($gathered != "") {
            $gathered = explode(",", $gathered);
            foreach ($gathered as $itemid) {
                if ($itemid != "") {
                    //Informationen über Item holen
                    $item = getItemById($itemid);

                    // Ausgabe was gefunden wurde
                    $_SESSION[message] .= "$item[name] $lang_found<br>";
                }
            }
        } else {
            //Ausgabe: Nichts gefunden
            $_SESSION[message] = "$lang_nothingfound";
        }
    } else {
        //Ausgabe: Nicht genug Aktionspunkte
        $_SESSION[message] = "$lang_notenoughtap";
    }
}

