<?php
class Uzivatel {
	var $userName, $userPwd, $userID, $userNick, $userStatus, 
		 $template, $branNaStranku, //settings 
		 $table_name = 'archiv_uzivatele', //tabulka uzivatelu
		 $skript; //ze ktereho skriptu se vola trida (kvuli formum napr.)
	
	
	function Uzivatel ($skript, $u_name = null, $u_pwd = null) {
		if (is_null($skript)) { // staticka trida, nebude se prihlasovat, menit sessions atp
			;
		} else {
			if (is_Null($u_name) && is_Null ($u_pwd)) { // vstup ze sessions
				$u_name = $_SESSION['u_name_archiv'];
				$u_pwd = $_SESSION['u_pwd_archiv'];
				} else { //vstup z formulare
				session_regenerate_id();
				$u_pwd = md5 ($u_pwd);
				$u_name = preg_quote($u_name);
				//$u_name = str_replace(array ('%','.', "'"), array ('\%', '\.', "\\'"), $u_name);
			}
			if ($this->userID = $this->checkLogin($u_name, $u_pwd)) 
				$this->nastavNamePwd ($u_name, $u_pwd);
			else $this->logout();		
			$this->skript = $skript;
		}	
		$this->setTemplate ($_COOKIE['template']);		
	}
	
	function status () {
		return (int)$this->userStatus;
	}
	
	function getBranNaStranku () {
		if ($this->branNaStranku == 10 || $this->branNaStranku == 20 || $this->branNaStranku == 50) return $this->branNaStranku;
		if ($_COOKIE['branNaStranku'] == 10 || $_COOKIE['branNaStranku'] == 20 || $_COOKIE['branNaStranku'] == 50) return $_COOKIE['branNaStranku'];
		return 20;
	}
	
	function checkLogin ($u_name = null, $u_pwd = null) {
		if (is_Null($u_name) && is_Null ($u_pwd)) {
			$u_name = $this->userName;
			$u_pwd = $this->userPwd;
		}
		
		@$q = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `{$this->table_name}` WHERE `login` LIKE '$u_name' AND `heslo` = '$u_pwd'"));
		
		if ($q) {
			$this->userNick = $q['nick'];
			$this->userStatus = $q['status'];
		}
		
		return $q['ID'];
	}
	
	function nastavNamePwd ($u_name, $u_pwd) {		
		$this->userName = $_SESSION['u_name_archiv'] = $u_name;
		$this->userPwd = $_SESSION['u_pwd_archiv'] = $u_pwd;
	}
	
	function logout ($show_msg = false) {
		unset($this->userName);
		unset($_SESSION['u_name_archiv']);
		unset($this->userPwd);
		unset($_SESSION['u_pwd_archiv']);
		if ($show_msg) 
			echo "Byl jste odhlášen ze systému. Pokraèujte <a href=\"{$this->skript}\">zde</a>";
	}
	
	function vykresliLogovaciTabulku () {
		echo '
		<div class="archiv_login">			
			<form action="'.$this->skript.'" method="post">
				<table class="archiv_login_table">
				
				<tr>
					<td>Login:</td>
					<td><input class="archiv_login_edit" name="u_name"></td>
				</tr>
				<tr>
					<td>Heslo:</td>
					<td><input class="archiv_login_edit" type="password" name="u_pwd"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="login" class="archiv_login_submit" value="Pøihlásit"></td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
						(<a href="'.$this->skript.'?akce=registrace">registrace</a>)
					</td>
				</tr>
				</table>			
			</form>		
		</div> <!-- //login -->
		';
	}
	
	function getTemplate () {
		return $this->template;
	}
	
	function setTemplate ($template) {
		$templateC = new Templates ("templates/");
		if (!$templateC->isTemplateDir ($template)) 
			$template = 'default';
			
		$this->template = $template;
		@setcookie('template', $template, time() + 2592000);
		$_COOKIE['template'] = $template; //$_COOKIE se neprepisuje
	}
	
	function setBranekNaStranku ($pocet) {
		if ($pocet != 10 && $pocet != 20 && $pocet != 50) $pocet = 20;
		$this->branNaStranku = $pocet;
		setcookie('branNaStranku', $pocet, time() + 2592000);
		$_COOKIE['branNaStranku'] = $pocet; //$_COOKIE se neprepisuje		
	}
}
?>
