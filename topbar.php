<?php
foreach($_xml->children() as $child) {
    foreach($child->attributes() as $a => $b) {
        //~ echo "$a: $b\n";
        if ($a == 'id') {$id = strval($b);}
        elseif ($a == 'start') {$start = strval($b);}
        elseif ($a == 'width') {$width = strval($b);}
        };
    $_topbar[ $id ] = array($start,$width);
}
//~ foreach($_topbar as $x => $y) {
    //~ echo "$x:";
    //~ foreach($y as $z) {
        //~ echo " $z";
    //~ }
    //~ echo "\n";
//~ }
$sections = array(
    array('OW','Home',71),
    array('SpeelMee','Contents',66),
    array('Speel','Bestof',64),
    array('Zing','Contents',68),
    array('Vertel','Start',75),
    array('Dicht','Start',83),
    array('Act','Contents',86),
    array('Art','Start',74),
    array('Denk','Start',76),
    array('Bio','Start',76)
    );
echo '<img src="http://local.magiokis.nl/images/TopBar_' .  $_section . '.gif" border="0" width="750" height="40" usemap="#GetAround"  alt="Topbar" />';
echo '<map name="GetAround" id="GetAround">';
$c0 = 0;
foreach ($sections as $thissect)
{
    $regel = '';
    if ($thissect[0] == $_section)
        $regel = '<!-- ';
    $c1 = $c0 + 3 ;
    $c2 = $c1 + $thissect[2];
    $c0 = $c2;
    $regel = $regel . '<area shape="rect" coords="'.$c1.',1,'.$c2.',39" href="http://php.magiokis.nl/magiokis.php?section='.$thissect[0].'&subsection='.$thissect[1].'" alt="" />';
    if ($thissect[0] == $_section)
        $regel = $regel . ' -->';
    echo $regel;
};
echo '    </map>';
?>
