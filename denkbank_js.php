<?php
if ($_section == "Denk" && $_subsect == "Select") {
    $flines = file('/home/albert/magiokis/data/content/Denk/functions.js');
    foreach($flines as $line) {
        $line = str_replace('%cgipad',$_cgipad,$line);
        $line = str_replace('%cgiprog',$_cgiprog,$line);
        echo $line;
    }
}
?>
