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
$_htmlpad = "F:/www/magiokis/";
$_httproot = "http://www.magiokis.nl/";
$_dataroot = $_httproot;
$_stylepad = $_httproot . "style/";
$_imagepad = $_httproot . "images/";
// $_cgipad = $_httproot . "cgi-bin/"; // oud
$_cgipad = "http://php.magiokis.nl/"; // nieuw (voorlopig)
// $_cgiprog = 'mainscript.py'; // oud
$_cgiprog = 'magiokis.php'; // nieuw
$_datapad = 'F:/magiokis/data/';
$_tekstpad = $_datapad . 'zing/';
$_dichtpad = $_datapad . 'dicht/';
$_acteerpad = $_datapad . 'acteer/';
$_datapad = "http://data.magiokis.nl/";
$_xmlpad = $_datapad;
$_mp3pad = $_datapad . "mp3/";
$_artpad = $_datapad . "Artwork/";
$_xmlutf = '<?xml version="1.0" encoding="UTF-8" ?>';
$_xml = simplexml_load_file($_xmlpad."tocs.xml");
//~ $_xml = simplexml_load_file("F:/magiokis/data/tocs.xml");
$_dbhandle = new PDO('sqlite:F:\magiokis\data\songs\magiokis.sdb');
/*
        if data.has_key("sectie"):
            self.sectie = data["sectie"]
        if data.has_key("subsectie"):
            self.subsectie = data["subsectie"]
        if data.has_key("selitem"):
            self.selitem = data["selitem"]
        self.page = Page(self.sectie,self.subsectie,self.selitem)
class Page(PageHandler):
    def __init__(self,s,u,it="",id=""):

        self.tekstnr = -1
        if data.has_key("selid"):
            self.selid = data["selid"]
        if data.has_key("trefwoord"):
            self.trefwoord = data["trefwoord"]
        if data.has_key("tekstid"):
            self.tekstid = data["tekstid"]
            try:
                self.tekstnr = int(self.tekstid)

*/
?>