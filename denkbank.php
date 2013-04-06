<?php
function trefwoordenlijst($xmlt) {
    $result = array();
    foreach ($xmlt->trefwoord as $node) {
        $result[] = $node["id"];
    }
    return $result;
}
function trefwoord($xmlt,$xmld,$x) {
    //~ echo 'trefwoord items opzoeken ',$xmlt->trefwoord[1]["id"]," ",$xmld->gedenk[1]->titel,"<br/>";
    $result = array();
    $refs = array();
    foreach ($xmlt->trefwoord as $node) {
        if ($node["id"] == $x) {
            foreach ($node->tekstref as $ref)
                $refs[] = (string)$ref;
            break;
        }
    }
    $x = $xmld->gedenk;
    foreach ($x as $node) {
        if (in_array($node["id"],$refs))
            $result[] = array("id"=>$node["id"], "titel"=>(string)$node->titel);
    }
    return $result;
}
function denksel($xmlt,$xmld,$i) {
    $found = false;
    $result = array();
    $regels = array();
    //~ $trefw = array();
    //~ $geentrefw = trefwoordenlijst($xmlt);
    $x = $xmld->gedenk;
    foreach ($x as $node) {
        if ($node["id"] == $i) {
            $titel = (string)$node->titel;
            foreach ($node->alinea as $child)
                $regels[]= (string)$child;
            //~ foreach ($node->trefwoord as $child) {
                //~ $h = (string)$child;
                //~ $trefw[] = $h;
                //~ if (in_array($h,$geentrefw))
                    //~ unset($geentrefw[$h]);
            //~ }
            $found = true;
            break;
        }
    }
    if ($found)
        //~ $result = array("titel"=>titel,"tekst"=>$regels,"trefw"=>$trefw,"geentrefw"=>$geentrefw);
        $result["titel"] = $titel;
        $result["tekst"] = $regels;
    return $result;
}
?>
