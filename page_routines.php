<?php
function denkbank($trefwoord,$tekstnr) {
    global $_htmlpad,$_xmlpad,$_datapad;
    // via http werkt het ineens niet hier, daarom een beetje anders
    //~ $xmlpad = $_xmlpad.'denk/';
    $xmlpad = '/home/albert/magiokis/data/denk/';
    $fn = $xmlpad.'trefwoorden.xml';
    $xmlt = simplexml_load_file($fn);
    $fn = $xmlpad.'denkerij.xml';
    $xmld = simplexml_load_file($fn);
    $rlines = file('/home/albert/magiokis/data/content/Denk/Select.html');
    $tel = 1;
    foreach ($rlines as $line) {
        if (trim($line) == '<trefwoordenlijst>') { //  selector voor trefwoorden opbouwen
            $h1 = trefwoordenlijst($xmlt);
            foreach ($h1 as $x) {                    // trefwoord selecteren in trefwoorden-selector
                $s = '';
                if ($x == $trefwoord) $s = ' selected';
                echo '<option'.$s.' value="'.$x.'">'.$x.'</option>';
            }
        }
        elseif (trim($line) == '<titellijst>') {       //   selector voor titels opbouwen (niet bij eerste keer)
            if ($trefwoord != 'init') {
                echo 'trefwoord is niet init ',$xmlt->trefwoord[0]["id"]," ",$xmld->gedenk[0]->titel,"<br/>";
                $th = trefwoord($xmlt,$xmld,$trefwoord);
                print_r($th);
                foreach ($th as $x) { // zoek in denkerij.xml de <gedenk> elementen met deze waarden als id
                    if ($x["id"]  == $tekstnr) $s = ' selected';
                    echo '<option'.$s.' value="'.$x["id"].'">'.$x["titel"].'</option>';
                }
            }
        }
        elseif (trim($line) == '<gekozentekst>') { //   gevraagde tekst toevoegen in textarea
            if ($trefwoord != 'init' && $tekstnr != '-1') {
                $dh = denksel($xmlt,$xmld,$tekstnr);
                //~ $titel = $dh["titel"];
                foreach ($dh["tekst"] as $x)
                    echo $x;
            }
        }
        else
            echo trim($line);
    }
}
function zetom($bron,$doel) {
    //~ echo strval($bron->getName()),"\n";
    foreach ($bron->children() as $child) {
        $s = strval($child->getName());
        if (strval($child) != '')
            $h = $doel->addChild("span",strval($child));
        else
            $h = $doel->addChild('span');
        $h->addAttribute("class",$s);
        zetom($child,$h);
    }
}
function make_tekst_page($it) { // xml elementen veranderen in spans
// aansturen met volledige filenaam
    $t = array();
    $on = "tempfile.html";
    $xml = simplexml_load_file($it);
    $titel = strval($xml->titel);
    $class = strval($xml->getName());
    $nxml = new SimpleXMLElement("<span/>");
    $nxml->addAttribute("class",$class);
    zetom($xml,$nxml);
    echo $nxml->asXML(); // zo komen  ?xml elementen als declaratie en stylesheet ook mee
}
function make_xspf_object($line) { // wat is het verschil met de vorige?
    global $_httproot;
    $s = preg_split("/[\s]+/", $line);
//        foreach ($s as $t) {
//            print t,"  ";
//        }
    if ($s[0] == '<!--' && $s[1] == 'xspf' && $s[2] == 'playlist') {
        echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/'.$s[3].'.xspf&amp;autoload=false" width="400">';
        echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/'.$s[3].'.xspf&amp;autoload=false">' ;
        echo 'Alternate content';
        echo '</object>';
    }
    elseif ($s[0] == '<!--' && $s[1] == 'xspf' && $s[2] == 'song') {
        echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$do_wwwurl.'&amp;song_title='.$titel.'" width="400" height="15">';
        echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$do_wwwurl.'&amp;song_title='.$titel.'">';
        echo 'Alternate content';
        echo '</object>';
    }
}
function make_xspf_objects($fl) { // wat is het verschil met de volgende?
    global $_httproot;
    foreach (file($fl) as $line) {
        $s = preg_split("/[\s]+/", $line);
//        foreach ($s as $t) {
//            print t,"  ";
//        }
        if ($s[0] == '<!--' && $s[1] == 'xspf' && $s[2] == 'playlist') {
            echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/'.$s[3].'.xspf&amp;autoload=false" width="400">';
            echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/'.$s[3].'.xspf&amp;autoload=false">' ;
            echo 'Alternate content';
            echo '</object>';
        }
        elseif ($s[0] == '<!--' && $s[1] == 'xspf' && $s[2] == 'song') {
            echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$do_wwwurl.'&amp;song_title='.$titel.'" width="400" height="15">';
            echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$do_wwwurl.'&amp;song_title='.$titel.'">';
            echo 'Alternate content';
            echo '</object>';
        }
        else
            echo $line;
    }
}
function make_xspf_opn_page($it) { // xspf player speelt de song af terwijl de tekst getoond wordt
// argument kan opname-id zijn maar ook filenaam - afhandelen binnen Opname functie
    global $_httproot,$_datapad,$_tekstpad,$_cgipad,$_cgiprog,$_section,$_subsect;
    $x = Opname($it);
    foreach ($x as $do) {
        $titel = '(untitled)';
        $y = Song($do['song']); // "SongID" (3)
        foreach ($y as $dh) {
            $titel = $dh['titel']; // "Songtitel" (3)
            $f = '';
            if ($dh['url'] != 'n/a') {
                $f = $dh['url'];
                if ($f == '') $f = $titel.'.xml';
            }
        }
        $url = $_datapad.'mp3/'.$do['url'].'.mp3'; // "wwwurl"
        echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$url.'&amp;song_title='.$titel.'" width="400" height="15">';
        echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$url.'&amp;song_title='.$titel.'">';
        echo 'Alternate content';
        echo '</object>';
        //~ echo '  [<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&subsection='.$_subsect.'">terug naar lijst</a>]';
        if ($f == '')
            echo '<span class="songtekst"><span class="br"/><span class="titel">'.$titel.'</span><span class="couplet"><span class="regel">Geen tekst aanwezig</span></span></span>';
        else
            make_tekst_page($_tekstpad.$f);
    }
}
function make_xspf_pl_page($it) { // pagina moet song gegevens tonen + een playlist met alle opnames ervan?
// Aansturen met song-id
    global $_httproot,$_dbhandle,$_datapad,$_htmlpad,$_tekstpad;
    $y = Song($it); // "SongID"
    $titel = '(untitled)';
    $f = '';
    foreach ($y as $dh) {
        $titel = $dh['titel']; // "Songtitel"
        if ($dh['url'] != 'n/a') {
            $f = $dh['url'];
            if ($f == '')
                $f = $titel.'.xml';
        }
    }
    $cmd = 'select opnames.id, url, plaatsen.naam, datums.naam from opnames, plaatsen, datums where song == "'.$it.'" and plaatsen.id == plaats and datums.id == datum';
    $result = $_dbhandle->query($cmd);
    $entry = array();
    foreach ($result as $x) {
        echo "\n";
        if ($x["id"] == '')
            continue;
        $url = $_datapad.'mp3/'.$x["url"].'.mp3'; // "wwwurl"
        $entry[] = array($url,$x[2].", ".$x[3]);
    }
    $ap = 'true';
    if (sizeof($entry) > 1) $ap = false;
    //~ foreach ($entry as $x) {
    if (count($entry) == 1) {
        $x = $entry[0];
        echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$x[0].'&amp;song_title='.$x[1].'&amp;autoplay='.$ap.'" width="500" height="15">';
        echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player_slim.swf?song_url='.$x[0].'&amp;song_title='.$x[1].'&amp;autoplay='.$ap.'">';
        echo 'Alternate content';
        echo '</object>';
    }
    else {
        $xml = new SimpleXMLElement('<playlist version="0" xmlns="http://xspf.org/ns/0/"></playlist>');
        $xml->addChild('title',$titel);
        $tl = $xml->addChild('trackList');
        foreach ($entry as $x) {
            $tr = $tl->addChild('track');
            $tr->addChild('location',$x[0]);
            $tr->addChild('title',$x[1]);
        }
        $fl = fopen($_htmlpad.'pl/temp.xspf','w');
        fwrite($fl,$xml->asXML());
        fclose($fl);
        echo '<object type="application/x-shockwave-flash" data="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/temp.xspf&amp;player_title='.$titel.'&amp;autoload=true" width="600" height="152">';
        echo '<param name="movie" value="'.$_httproot.'xspf_player/xspf_player.swf?playlist_url='.$_httproot.'pl/temp.xspf&amp;player_title='.$titel.'&amp;autoload=true">';
        echo 'Alternate content';
        echo '</object>';
    }
    if ($f == '')
        echo '<span class="songtekst"><span class="br"/><span class="titel">'.$titel.'</span><span class="couplet"><span class="regel">Geen tekst aanwezig</span></span></span>';
    else
        make_tekst_page($_tekstpad.$f);
}
?>
