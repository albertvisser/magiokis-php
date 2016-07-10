<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include('start.php') ?>
<title><?php echo $_section ?>_<?php echo $_subsect ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include('stylesheet.php');
include('denkbank_js.php');
// echo '<base href="' . $_section . '">'
?>
</head>
<body>
<div id="navtopbar">
<?php include('topbar.php') ?>
<span>
<a href="http://www.anybrowser.org/campaign/"><img src="http://www.pythoneer.nl/images/pany.gif" border="0" width="120" height="40" alt="Viewable With Any Browser" /></a>
<a href="http://validator.w3.org/check/referer"><img style="border: 0" src="http://www.pythoneer.nl/images/valid-xhtml10.gif" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
</span>
</div>
<br />
<div id="left">
<span class="maxhi"><?php include('toc.php') ?></span>
<span  style="text-align: center">
<br/>
</span>
</div>
<div id="right" class="maxhi">
<?php
include('data.php');
include('denkbank.php');
include('page_routines.php');
include('body.php');
?>
</div>
</body>
</html>
