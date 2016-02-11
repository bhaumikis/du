<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Dailyuse Webservice</title>
<link href="<?php echo $module_url;?>/../css/style.css" rel="stylesheet" type="text/css" media="screen"/>
<?php /* 
<link href="<?php echo $module_url;?>/../css/style_print.css" rel="stylesheet" type="text/css" media="print">
<link href="<?php echo $module_url;?>/../css/menu_style.css" rel="stylesheet" type="text/css" />
*/?>
<link href="<?php echo $module_url;?>/../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $module_url;?>/../css/jjsonviewer.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" language="javascript" src="<?php echo $module_url;?>/../js/jquery-1.10.2.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo $module_url;?>/../js/jquery.validate.js"></script>
<?php /* 
<script type="text/javascript" language="javascript" src="<?php echo $module_url;?>/../js/site-wide.js"></script>
*/?>
<script type="text/javascript" language="javascript" src="<?php echo $module_url;?>/../js/jjsonviewer.js"></script>
</head>
<body>
<div class="header">
  <?php include($module_path."/view/header/header.php");?>
</div>
<div id="content">
  <noscript>
  	<div align="center" class="actionMessage">
  		<span>Please enable javascript in your browser.</span>
	</div>
  </noscript>
  <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
  <?php include($middle);?>
</div>
<div class="footer">
  <?php include($module_path."/view/footer/footer.php");?>
</div>
</body>
</html>
