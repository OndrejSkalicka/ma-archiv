<?php

//error_reporting (E_ALL ^ E_NOTICE);

switch ($host = preg_replace('/^(www\.)*/', '', $_SERVER['HTTP_HOST'])) {
  case 'localhost':
    $host = "localhost";
		$user = "root";
		$password = "???";
		$database = "ma_archiv";
  break;
  case 'archiv.ma.savannahsoft.eu':
  case 'archiv.savannahsoft.eu':
    $host = "localhost";
		$user = "savannah";
		$password = "???";
		$database = "savannah_meliorannis";
  break;
  default:
    die ('Unknown host: ' . htmlspecialchars($host));
  break;
}

$typ = 4;

//error_reporting (E_ALL ^ E_NOTICE);

$spojeni = MySQL_Connect($host, $user, $password);
MySQL_Select_DB($database,$spojeni);
?>
