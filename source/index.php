<?php
  error_reporting (E_NONE);
  
  if (file_exists('maintenance.html') && !$_COOKIE['debug']) {
    if ($_GET['cookie_debug_go']) {
      setcookie('debug', true, time() + 10000);
    } else {
      include 'maintenance.html';
      die();
    }
  }
    
	session_start();
	
	//nastaveni COOKIES
	
	if ($_POST['hledej']) {
	  // nastavim cookies na posledni vyhledavani
	  function setIt ($text) {
      setcookie('hledani_'.$text, ($_POST[$text] ? $_POST[$text] : 'false'), time() + 2592000);
    }
    setIt ('hledani_brana_typ');
    setIt ('hledani_brana_cislo');
    setIt ('brana_presvedceniD');
    setIt ('brana_presvedceniD');
    setIt ('brana_presvedceniD');
    setIt ('brana_presvedceniD');
    setIt ('brana_presvedceniD');
	  
	  
	}
	
	
	//error_reporting (E_ALL ^ E_NOTICE);
	
	require "main.php";

	$uziv = new Uzivatel (null);
	if ($_POST['change_settings']) {
		$uziv->setBranekNaStranku ($_POST['change_bran_na_str']);
		$uziv->setTemplate ($_POST['change_template']);
	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
	<title>Archiváø<?php
		$celkem = MySQL_Fetch_Array (MySQL_Query ("SELECT COUNT(*) FROM `archiv_branek` WHERE `privacy` < '2'"));
		echo " ({$celkem[0]} bran)";
	?></title>
	<link REL='STYLESHEET' TYPE='text/css' HREF='style.css'>
	<link REL='STYLESHEET' TYPE='text/css' HREF='archiv.css'>
	<link REL='STYLESHEET' TYPE='text/css' HREF='help.css'>
	<?php
		echo "<LINK REL='STYLESHEET' TYPE='text/css' HREF='templates/".$uziv->getTemplate()."/template.css'>\n";
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
	<meta http-equiv="cache-control" content="no-cache">
	<meta name="Description" content="Archiv branek pro Meliorannis - nejvìtší databáze Meliorannis bran na internetu!" />
  <meta name="Keywords" content="Meliorannis Archiváø Archiv Brána br1na br2na br3na br4na br5na br6na br7na br8na Mìsto" />
  <meta name="Title" content="Archiváø - archiv branek pro Meliorannis" />
  <link rel="shortcut icon" href="favicon.ico">
	<link rel="alternate" type="application/atom+xml" title="Archiváø" href="rss.php">
	<!-- <link rel="alternate" type="application/rss+xml" title="Archiváø" href="rss.php" /> -->
	<script type="text/javascript" src="overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
</head>
<body>
<?php
  /*require 'res/ExplorerDestroyer.class.php';
  $ED = new ExplorerDestroyer (12);
  
  if ($ED->checkDelay())
      $ED->setLevel (0);
      
  $ED->setMaxDelay (12);
  $ED->setBackground ('#ffffbb');
  $ED->setForeground ('black');  
  $ED->go();*/
?>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
	<div id="outer">
		<div id="inner">
		  <!-- <div style="width: 100%; text-align: center;">
		    <a href="http://www.inetpro.sk" target="_blank"><img src="img/iban.jpg" alt="InetPro.sk"></a>
		  </div> -->
			<?php
				/*require "main.php";*/
				new Main('index.php');
			?>
		</div> <!-- //inner -->
		<!--  -->
	</div> <!-- //outer -->
</body>
</html>
