<?php
function Songtekst($id) {
    global $_tekstpad;
    $y = Song($id);
    $regels = array();
    foreach ($y as $dh) {
        $titel = $dh[3]; // "Songtitel"
        if ($dh[6] != 'n/a') {
            $f = $dh[6];
            if ($f == '') $f = $titel.'.xml';
            $xml = simplexml_load_file($_tekstpad.$f);
            $titel = strval($xml->titel);
            foreach ($xml->children() as $child) {
                if ($child->getname() == 'br')
                    $regels[] = '';
                elseif ($child->getname() == 'couplet') {
                    $regels[] = '';
                    foreach ($child->children() as $kid) {
                        if ($kid->getname() == 'regel')
                            $regels[] = strvalue($kid);
                        elseif ($kid->getname() == 'br')
                            $regels[] = '';
                    }
                }
            }
        }
    }
}
function Opname($id) {
    global $_dbhandle;
    $cmd = 'select * from opnames where id == "'.$id.'"';
    $result = $_dbhandle->query($cmd);
    $h = array();
    foreach ($result as $x)
        $h[] = $x;
    if (sizeof($h) == 0) {
        // tweede poging: zoeken via url(deel)
        $cmd = 'select * from opnames where url == "'.$id.'"';
        $result = $_dbhandle->query($cmd);
        $h = array();
        foreach ($result as $x)
            $h[] = $x;
    }
    return $h;
}
function Song($id) {
    global $_dbhandle;
    $cmd = 'select * from songs where id == "'.$id.'"';
    $result = $_dbhandle->query($cmd);
    //~ print_r($result);
    $h = array();
    foreach ($result as $x)
        $h[] = $x;
    //~ print_r($h);
    return $h;
// $url = $x[6];
// if ($url == "" && $x[3] == "") $url = $x[3].".xml";
// $y[] = array('Songtitel': $x[3], 'TekstID': $x[2], 'MuziekID': $x[1], 'datering': $x[4], 'datumtekst': $x[5], 'url': $url, self.commentaar: $x[7]);
}
?>