<link rel="stylesheet" href="<?php
?>Magiokis.css" type="text/css" /><?php
if (in_array($_section,array('OW','SpeelMee',"Speel","Zing")))
    $css = 'Songtekst_html.css';
elseif ($_section == 'Dicht')
    $css = 'Dicht_html.css';
elseif ($_section == 'Vertel')
    $css = 'Vertel_html.css';
elseif ($_section == 'Act')
    $css = 'toneelstuk_html.css';
else
    $css = 'dummy.css';
?><link rel="stylesheet" href="<?php
echo $_stylepad.$css
?>" type="text/css" />