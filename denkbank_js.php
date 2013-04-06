<?php
if ($_section == "Denk" && $_subsect == "Select") {
    $flines = file('F:\\magiokis\\data\\content\\denk\\Functions.js');
    foreach($flines as $line) {
        $line = str_replace('%cgipad',$_cgipad,$line);
        $line = str_replace('%cgiprog',$_cgiprog,$line);
        echo $line;
    }
}
?>