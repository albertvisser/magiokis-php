<?php
function readelem($me,$level) {
    global $_section, $_subsect, $_selitem,$_cgipad, $_cgiprog,$_xml,$_dbhandle,$_datapad,$_acteerpad;
    foreach($me->children() as $kid) {
        $n = strval($kid->getName());
        if ($n == 'page') {
            $subsect = strval($kid['id']);
            $adres = strval($kid['url']);
            $source = strval($kid['src']);
            $tekst = strval($kid);
            if ($tekst == '')
                $tekst = '&nbsp;';
            if ($subsect != '' && $tekst != '&nbsp;') {
                if ($subsect == $_subsect) {
                    if ($_selitem == '')
                        $t = '<span class="backnb">'.$tekst.'</span>';
                    else
                        $t = '<span class="back2nb"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$subsect.'">'.$tekst.'</a></span>';
                }
                else
                    $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$subsect.'">'.$tekst.'</a>';
            }
            elseif ($adres != '')
                $t = '<a href="'.$adres.'">'.$tekst.'</a>';
            else
                $t = '';
            if ($_section != 'Art' || $tekst != '&nbsp;') {
                if ($level > 0)
                    echo '<li class="nobul">',$t,'</li>';
                else
                    echo $t,'<br/>';
            }
        }
        elseif ($n == 'regel') {
            $class = strval($kid['class']);
            $tekst = strval($kid);
            if ($tekst == '')
                $tekst = '&nbsp;';
            elseif ($tekst{0} == '[')
                $tekst = str_replace(array('[',']'),array('<','>'),$tekst);
            if ($class != '')
                $t ='<span class="'.$class.'">'.$tekst.'</span>';
            else
                $t ='<span>'.$tekst.'</span>';
            if ($level > 0)
                echo '<li class="nobul">',$t,'</li>';
            else
                echo $t,'<br/>';
        }
        elseif ($n == 'level') {
            $level++;
            echo '<ul>';
            readelem($kid,$level);
            echo'</ul>';
            $level--;
        }
        elseif ($n == 'list') {
            if ($_section == "Zing") {
                $result = $_dbhandle->query('SELECT distinct id FROM jaren');
                foreach ($result as $entry) {
                    $h = $entry["id"];
                    if ($h == $_subsect)
                        //~ $t = '<span class="backnb">'.$entry["id"].'</span>';
                        if ($_selitem == '')
                            $t = '<span class="backnb">'.$h.'</span>';
                        else
                            $t = '<span class="back2nb"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$h.'">'.$h.'</a></span>';
                    else
                        $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$h.'">'.$h.'</a>';
                    if ($level > 0)
                        echo '<li class="nobul">',$t,'</li>';
                    else
                        echo $t,'<br/>';
                }
            }
            elseif ($_section == "Vertel") {
                $xml = simplexml_load_file("F:\magiokis\data\vertel\vertellers.xml");
                if ($xml->user['naam'] == 'papa') {
                    foreach($xml->user->categorie as $cat) {
                        $t = strval($cat);
                        $n = strval($cat['naam']);
                        if ($t == '')
                            $t = $n;
                        if ($n == $_subsect)
                            if ($_selitem == '')
                                $t = '<span class="backnb">'.$t.'</span>';
                            else
                                $t = '<span class="back2nb"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$n.'">'.$t.'</a></span>';
                            //~ $t = '<span class="backnb">'.$t.'</span>';
                        else
                            $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$n.'">'.$t.'</a>';
                        if ($level > 0)
                            echo '<li class="nobul">',$t,'</li>';
                        else
                            echo $t,'<br/>';
                    }
                }
            }
            elseif ($_section == "Dicht") {
                $xml = simplexml_load_file("F:\magiokis\data\dicht\Dicht_Trefwoorden.xml");
                foreach ($xml->jaren->jaar as $jaar) {
                    if ($jaar["id"] == $_subsect)
                        $t = '<span class="backnb">'.$jaar["id"].'</span>';
                    else
                        $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$jaar['id'].'">'.$jaar['id'].'</a>';
                    if ($level > 0)
                        echo '<li class="nobul">',$t,'</li>';
                    else
                        echo $t,'<br/>';
                }
            }
            elseif ($_section == "OW") {
                foreach ($kid->children() as $x) { // <page id="OW1" serie="B9_A">The Old Whores</page>
                    $subsect = strval($x['id']);
                    $tekst = strval($x);
                    if ($subsect == $_subsect)
                        //~ $t = '<span class="backnb">'.$tekst.'</span>';
                        if ($_selitem == '')
                            $t = '<span class="backnb">'.$tekst.'</span>';
                        else
                            $t = '<span class="back2nb"><a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$subsect.'">'.$tekst.'</a></span>';
                    else
                        $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$subsect.'">'.$tekst.'</a>';
                    if ($level > 0)
                        echo '<li class="nobul">',$t,'</li>';
                    else
                        echo $t,'<br/>';
                }
            }
        }
        elseif ($n == 'link') {
            $s = strval($kid["show"]);
            $tekst = strval($kid);
            if ($s != '') {
                if ($tekst == $_subsect)
                    $t = '<span class="backnb">'.$tekst.'</span>';
                else
                    $t = '<a href="'.$_cgipad.$_cgiprog.'?section='.$_section.'&amp;subsection='.$tekst.'&amp;item='.$_acteerpad.$s.'">'.$tekst.'</a>';
                }
            else
                $t = $tekst;
            if ($level > 0)
                echo '<li class="nobul">',$t,'</li>';
            else
                echo $t,'<br/>';
        }
    }
}
foreach($_xml->children() as $child) {
    if ($child['id'] == $_section) {
        readelem($child,0);
    }
}
?>
