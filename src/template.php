<?php

    $body = implode("",file("template/normal.php"));
    $body = str_replace("<?titel?>", $sitetitle, $body);
    $body = str_replace("<?inhalt?>",$inhalt,$body);
    $body = str_replace("<?ressourcen?>", $ressourcen, $body);

    $template = implode("",file("template/style.php"));
    $template = str_replace("<?inhalt?>", $body, $template);
    $template = str_replace("<?languagemenue?>", $languagemenue, $template);
    $template = str_replace("<?menue1?>", $menue1, $template);
    $template = str_replace("<?menue2?>", $menue2, $template);    
    $template = str_replace("<?menue3?>", $menue3, $template);
    $template = str_replace("<?online?>", $online, $template);
    $template = str_replace("<?footer?>", $footer, $template);
    $template = str_replace("<?charstat?>", $charstat, $template);
    $template = str_replace("<?adright?>", $adright, $template);
    $template = str_replace("<?adnoposition?>", $adnoposition, $template);
    $template = str_replace("<?adapp?>", $adapp, $template);
    echo ($template);

?>

