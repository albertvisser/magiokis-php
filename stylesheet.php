<link rel="stylesheet" href="<?php
?>magiokis.css" type="text/css" /><?php
if (in_array($_section,array('OW','SpeelMee',"Speel","Zing")))
    $css = 'songtekst_html.css';
elseif ($_section == 'Dicht')
    $css = 'dicht_html.css';
elseif ($_section == 'Vertel')
    $css = 'vertel_html.css';
elseif ($_section == 'Act')
    $css = 'toneelstuk_html.css';
else
    $css = 'dummy.css';
?><link rel="stylesheet" href="<?php
echo $_stylepad.$css
?>" type="text/css" />
