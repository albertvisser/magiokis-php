<?php
$_section = $_GET['section'];
$_subsect = $_GET['subsection'];
$_selitem = '';
$_seltrefw = '';
if (array_key_exists('item',$_GET))
    $_selitem = $_GET['item'];
if (array_key_exists('trefwoord',$_GET)) {
    $_selitem = $_GET['tekstnr'];
    $_seltrefw = $_GET['trefwoord'];
}
$c0 = 0;
$_topbar = array();
$_toc = array();
$_htmlpad = "/home/albert/www/magiokis/";
$_httproot = "http://original.magiokis.nl/";
$_dataroot = $_httproot;
$_stylepad = $_httproot . "style/";
$_imagepad = $_httproot . "images/";
// $_cgipad = $_httproot . "cgi-bin/"; // oud
$_cgipad = "http://php.magiokis.nl/"; // nieuw (voorlopig)
// $_cgiprog = 'mainscript.py'; // oud
$_cgiprog = 'magiokis.php'; // nieuw
$_datapad = '/home/albert/magiokis/data/';
$_tekstpad = $_datapad . 'zing/';
$_dichtpad = $_datapad . 'dicht/';
$_acteerpad = $_datapad . 'acteer/';
$_datapad = "http://data.magiokis.nl/";
$_xmlpad = $_datapad;
$_mp3pad = $_datapad . "mp3/";
$_artpad = $_datapad . "artwork/";
$_xmlutf = '<?xml version="1.0" encoding="UTF-8" ?>';
$_xml = simplexml_load_file($_xmlpad."tocs.xml");
//~ $_xml = simplexml_load_file("F:/magiokis/data/tocs.xml");
$_dbhandle = new PDO('sqlite:/home/albert/magiokis/data/songs/magiokis.sdb');
?>
