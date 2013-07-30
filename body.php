<?php
function readtext($me,$level) {
    global $_section, $_subsect, $_selitem, $_cgipad, $_cgiprog,$_xml,$_dbhandle,$_imagepad,$_datapad,$_dichtpad,$_mp3pad,$_artpad,$_htmlpad,$_xmlpad,$_acteerpad;
    $found = false;
    //~ echo$_subsect;
    foreach($me->children() as $kid) {
        $n = strval($kid->getName());
        //~ echo "\n";echo $n.'  '.strval($kid)."\n";print_r($kid);echo("\n");
        if ($n == 'page') {
            $subsect = strval($kid['id']); // pagina waar we zitten
            //~ echo ("$subsect\n");
            if ($subsect == $_subsect)
                $found = true;
            else
                continue;
            $adres = strval($kid['url']); // niet nodig, dit is een link - is deze gekozen dan zijn we van de site af
            $source = strval($kid['src']); // document om te includen
            $action = strval($kid['action']); // wat te doen
            $serie  = strval($kid['serie']); // bij OW: de songserie om op te halen, wordt geregeld in het stuk verderop
            $tekst = strval($kid); // niet nodig, dit was de tekst van de link
            //~ echo "$action\n";
            if ($_section == 'Denk' && $subsect == 'Select') {
                denkbank("init",'-1');
            }
            elseif ($source != '') {
                //~ readfile('http://data.magiokis.nl/'.$source);
                $lines = file('/home/albert/magiokis/data/'. $source);
                foreach ($lines as $line) {
                    $line = str_replace('%cgipad',$_cgipad,$line);
                    $line = str_replace('%cgiprog',$_cgiprog,$line);
                    $line = str_replace('%imagepad',$_imagepad,$line);
                    $line = str_replace('%datapad',$_datapad,$line);
                    $line = str_replace('%dichtpad',$_dichtpad,$line);
                    $line = str_replace('%mp3pad',$_mp3pad,$line);
                    $line = str_replace('%artpad',$_artpad,$line);
                    if ($action == 'fixlinks') {
                        $h = strstr($line,'<a href');
                        if ($h) {
                            $y = explode('"',$line);
                            $z = $_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$subsect.'&amp;item='.$y[1];
                            $line = $y[0].$z.$y[2];
                        }
                    }
                    elseif ($action == 'addplaylists') {
                        $h = strstr($line,'xspf');
                        if ($h) {
                            make_xspf_object($line);
                            $line = '';
                        }
                    }
                    //~ elseif ($_section == 'Art') {
                        //~ $h = strstr($line,'<img');
        /*
                    # als ART dan de pagina uit de content directory lezen
                    #                       sowieso moet artpad gesubstitueerd worden
                    #                       plaatje kopieren naar standaard naam
                    #                       img veranderen naar standaard naam en standaard grootte + link eromheen naar ware grootte
        */
                        //~ echo $
                        //~ }
                    //~ }
                    echo $line;
                }
            }
            elseif ($serie != '') { // action = "getserie"
                //~ echo "ok nu gaan we gegevens zoeken\n";
                $zoek = str_replace("9_"," 9",$serie);
                $cmd = 'select titel,opgenomen from opnameseries where id = "'.$zoek.'" and opname = ""';
                $result = $_dbhandle->query($cmd);
                //~ print_r($result);
                foreach ($result as $entry) {
                    $titel = $entry["titel"];
                    $tekst = $entry["opgenomen"];
                }
                $cmd = 'select opnameseries.opname,songs.titel,songs.id,opnames.url';
                $cmd = $cmd.' from opnameseries,opnames left outer join songs on opnames.song = songs.id';
                $cmd = $cmd.' where opnameseries.id = "'.$zoek.'" and opnameseries.opname = opnames.id';
                //~ echo "$cmd\n";
                $result = $_dbhandle->query($cmd);
                //~ print_r($result);
                $lijst = array();
                $songs = array();
                $titels = array();
                $urls = array();
                foreach ($result as $entry) {
                    $lijst[] = $entry[0];
                    $songs[] = $entry[2];
                    $titels[] = $entry[1];
                    $urls[] = $entry[3];
                }
                echo '<p align="left">'.$titel.'<br />Recorded: '.$tekst.'</p>';
                echo '<table align="center" cellspacing="0" border="0" cellpadding="4" width="90%">';
                $h = count($lijst) / 2;
                $h = (int) $h;
                if ($h * 2 < count($lijst))
                    $h++;
                for ($i = 0; $i < $h; $i++) { // niet $lijst[] elementen gebruiken maar $urls[]? nee, want ik heb juist de opname-ids nodig
                // maar omdat ik de opnames al heb gevonden is meesturen ervan helemaal niet zo'n gek idee
                    echo '<tr><td width="50%"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&subsection='.$_subsect.'&item='.$lijst[$i].'">'.$titels[$i].'</a></td>' ;
                    $j = $i + $h;
                    if ($j < count($lijst))
                        echo '<td width="50%"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&subsection='.$_subsect.'&item='.$lijst[$j].'">'.$titels[$j].'</a></td></tr>';
                    else
                        echo '</tr>';
                }
                echo '</table>';
            }
        }
        elseif ($n == 'level' || $n == 'list') {
            //$level++;
            $found = readtext($kid,$level+1);
            //$level--;
        }
        elseif ($n == 'link') {
            $source = strval($kid["show"]);
            $tekst = strval($kid);
            if ($tekst == $_subsect && $source != '') { //   $_section == "Act"
                make_tekst_page($_acteerpad.$source);
                $found = true;
            }
        }
        if ($found)
            break;
    }
    return $found;
}
if ($_selitem!= '' && $_selitem !== '1') {
    if ($_section == "OW")
        make_xspf_opn_page($_selitem);  // opname-id
    elseif ($_section == "SpeelMee")
        make_xspf_opn_page($_selitem);  // filenaam zonder .mp3
    elseif ($_section == "Speel")
        if ($_subsect != "Modules")
            make_xspf_opn_page($_selitem);  // filenaam zonder .mp3
        else {
            } // filenaam met extensie en bovenliggende dir
    elseif ($_section == "Zing")
        make_xspf_pl_page($_selitem); // songid
    elseif ($_section == "Vertel" || $_section == 'Act')
        //~ echo '  [<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&subsection='.$_subsect.'">terug naar lijst</a>]';
        make_tekst_page($_selitem); // complete pad + filenaam
    elseif ($_section == "Dicht")
        make_tekst_page($_selitem); // komt niet voor
    elseif ($_section == "Denk")
        denkbank($_seltrefw,$_selitem); // trefwoord, tekstnummer
}
else {
    foreach($_xml->children() as $child) {
        if ($child['id'] == $_section)
            $found = readtext($child,0);
    }
    if (! $found) {
        if ($_section == "Vertel") {
            $found = false;
            $xml = simplexml_load_file("/home/albert/magiokis/data/vertel/vertellers.xml");
            if ($xml->user['naam'] == 'papa') {
                foreach($xml->user->categorie as $cat) {
                    $n = strval($cat['naam']);
                    $i = strval($cat["id"]);
                    if ($n == $_subsect) {
                        $found = true;
                        break;
                    }
                }
            }
            if ($found) {
                if ($_subsect == "Langere")
                    echo "<h3>hee, typisch, deze zijn geen van alle afgemaakt...</h3>";
                else
                     echo "<br />";
                echo '<div style="padding-left: 20%">';
                $xml = simplexml_load_file("/home/albert/magiokis/data/vertel/verteller_papa.xml");
                $pad = strval($xml-> path);
                foreach($xml->verhaal as $item) {
                    if ($item["categorie"] == $i) {
                        $url = $pad.strval($item->url);
                        echo '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$_subsect.'&amp;item='.$url.'">'.$item["titel"].'</a><br />';
                    }
                }
            echo '</div>';
            }
            else
                echo "<div>subsection ".$_subsect." niet bekend bij section 'Vertel'</div>";
        }
        elseif ($_section == "Zing") {
            $result = $_dbhandle->query('SELECT distinct id FROM jaren');
            $found = false;
            foreach ($result as $entry) {
                if ($entry["id"] == $_subsect) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $result = $_dbhandle->query('select tekst from jaren where id = "'.$_subsect.'" and song = ""');
                foreach ($result as $entry) {
                    $tekst = $entry[0];
                }
                echo "<div>".$tekst."</div>";
                $result = $_dbhandle->query('select song from jaren where id = "'.$_subsect.'"');
                foreach ($result as $entry) {
                    $do = Song($entry[0]);
                    foreach($do as $ds) {
                        echo '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$_subsect.'&amp;item='.$entry[0].'">'.$ds[3].'</a> ('.$ds[4].') '.$ds[7].'<br />';
                    }
                }
            }
            else
                echo "<div>subsection ".$_subsect." niet bekend bij section 'Zing'</div>";
        }
        elseif ($_section == "Dicht") {
            //~ if (!in_array($_subsect,array("Start","Cover","Inhoud"))) {
                $found = false;
                $xml = simplexml_load_file("/home/albert/magiokis/data/dicht/Dicht_Trefwoorden.xml");
                foreach ($xml->jaren->jaar as $jaar) {
                    if ($jaar["id"] == $_subsect) {
                        $found = true;
                        break;
                    }
                }
                if ($found)
                    make_tekst_page("/home/albert/magiokis/data/dicht/Dicht_".$_subsect.".xml");
                else
                    echo "<div>subsection '.$_subsect.' niet bekend bij section 'Dicht'</div>";
            //~ }
        }
    }
}
?>
