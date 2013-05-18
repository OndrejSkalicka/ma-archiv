<?php
/* REQUIRES session_start() */
/* defines fce cislo */
function cislo ($cislo, $desmista = 0, $separator = ',')
{
	return number_format($cislo, $desmista, '.', $separator);
}

setlocale(LC_ALL, 'cs_CZ');

require_once "dblogin.php";
require_once "res/uzivatel.class.php";
require_once "res/menu.class.php";
require_once "res/polozkaMenu.class.php";
require_once "res/branka.class.php";
require_once "res/help.class.php";
require_once "res/jednotka.class.php";
require_once "res/boj.class.php";
require_once "res/vyhledavaniBranek.class.php";
require_once "res/nastaveni.class.php";
require_once "res/templates.class.php";
require_once "res/registrace.class.php";
require_once "res/statistiky.class.php";
require_once "res/simul.class.php";
require_once "res/simul_jednotka.class.php";

		
class Main {
	
	function Main ($skript) {
		/* login */
		
		
		$user = new Uzivatel ($skript, $_POST['u_name'], $_POST['u_pwd']);
		
		if ($_REQUEST['akce'] == "logout") {
			$_REQUEST['akce'] = '';
			$user->logout();
		}
		
		/*if (!$user->checkLogin()) {
			$user->vykresliLogovaciTabulku();
			return;
		}*/
		/* LOGIN */
		echo '
		<div id="archiv_top">
			';
		
		require_once 'res/smarty/Smarty.class.php';
		$smarty = new Smarty ();
		$top10 = mysql_query("SELECT `archiv_uzivatele`.`nick`, COUNT( * ) AS `pocet` 
                          FROM `archiv_uzivatele` 
                          INNER JOIN `archiv_branek` ON `archiv_uzivatele`.`ID` = `archiv_branek`.`ID_archiv_uziv` 
                          WHERE `archiv_uzivatele`.`ID` > '2'
                            AND `archiv_branek`.`privacy` = '0'
                          GROUP BY `archiv_uzivatele`.`ID` 
                          ORDER BY `pocet` DESC, `archiv_uzivatele`.`nick`
                          LIMIT 10");
    $users = array ('');
    while ($tmp = mysql_fetch_array($top10)) {
      $users[] = $tmp;
    }
		$smarty -> assign ('topUsers', $users);
		$smarty -> display ('head.tpl');
		//include "res/archivar.html";
		echo '
			<div class="login">	
			';
		if (!$user->checkLogin()) {
			$user->vykresliLogovaciTabulku();
		} else {
			$status = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `archiv_statusy` WHERE `cislo` = '".$user->status()."'"));
			echo "
			<table>
			<tr>
				<td>
					Nick:
				</td>
				<td>
					{$user->userNick}
				</td>
			</tr>
			<tr>
				<td>
					Status:
				</td>
				<td>
					<span style=\"color: {$status['barva']};\">{$status['text']}".($user->status() == 0 ? ' <a href="javascript:void(0);" onclick="return overlib(\'Jste registrovan�, ale neautorizovan� �len. Registraci dokon��te t�m, �e nap�ete &quot;zelenou&quot; n�komu z administr�tor�. V sou�asnosti jsou to Bruce, Magnus, Mlok a Savannah (ICQ 198-889-675). Kontakt bu� zelenou nebo ICQ nebo jak se v�m je poda�� naj�t. (btw, autorizace je dobr� t�eba na to, aby jste mohli p�id�vat br�ny :-)\', STICKY, CAPTION,\'Autorizace\');"><img src="img/help.png" height="11" width="11" alt="[?]"></a>' : '')."</span>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<a href=\"$skript?akce=logout\">Odhl�sit se</a>
				</td>
			</tr>
			</table>";
		}
		
		echo '
			</div> <!-- //login -->
			
		</div> <!-- //archiv_top -->
		<div class="clear">&nbsp;</div>
		';
		
		/* Help */
		$help = new Help ();
		//$help->javaScript();
		
		/* MENU */
		$menu = new Menu ($skript, $user, $_REQUEST['akce']);		
		$menu->vykresliMenu($_REQUEST['akce']);
		/* MAIN */
		echo '<div id="archiv_main">';
		$menu->vykonejVolbu();
		echo '</div> <!-- //archiv_main -->';
		echo '
		<div id="archiv_patka">
			Vytvo�eno Savannahem l�ta P�n� 2006. Optimalizov�no na prohl�ec� n�stroj Opera 9.02. 
		</div> <!-- // -->';
	}
}
?>
