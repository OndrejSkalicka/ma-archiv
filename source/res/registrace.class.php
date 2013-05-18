<?php
class Registrace {
	var $skript;

	function Registrace ($skript) {
		$this->skript = $skript;
	}
	
	function vykresliRegistracniTabulku () {
		echo '<form action="'.$this->skript.'" method="post">
				<input type="hidden" name="akce" value="registrace">
				<table>
				<tr>
					<td>
						Pøihlašovací jméno:';
		$this->help ('regLogin');
		echo '
					</td>
					<td>
						<input name="new_login" class="input" value="'.$_POST['new_login'].'">
					</td>
				</tr>
				<tr>
					<td>
						Pøezdívka:';
		$this->help ('regNick');
		echo '
					</td>
					<td>
						<input name="new_nick" class="input" value="'.$_POST['new_nick'].'">
					</td>
				</tr>
				<tr>
					<td>
						Heslo:';
		$this->help ('regPwd1');
		echo '
					</td>
					<td>
						<input name="new_pwd1" class="input" type="password">
					</td>
				</tr>
				<tr>
					<td>
						Heslo znovu:';
		$this->help ('regPwd2');
		echo '
					</td>
					<td>
						<input name="new_pwd2" class="input" type="password">
					</td>
				</tr>
				<tr>
					<td>
						Podmínky:';
		$this->help ('regPodminky');
		echo '
					</td>
					<td>
						<div class="podminky">
							';
		require "licence.html";			
		echo				'
						</div> <!-- //podminky -->
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
						<input type="checkbox" name="new_podminky" id="new_podminky"> <label for="new_podminky">Podmínky jsem èetl a souhlasím s nimi</label>
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
						<input class="submit" type="submit" name="new_registrace" value="Zaregistrovat">
					</td>
				</tr>
				</table>
			</form>';
	}
	
	function registruj () {
		if ($_POST['new_registrace']) {
			
			$errorMsg = null;
			/* login */
			if (!$_POST['new_login'])
				$errorMsg .= 'Nevyplnìný login<br />';
			elseif (MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `archiv_uzivatele` WHERE `login` LIKE '{$_POST['new_login']}'")) > 0)
				$errorMsg .= 'Takový login již existuje<br />';
			/* nick */
			if (!$_POST['new_nick'])
				$errorMsg .= 'Nevyplnìný nick<br />';
			elseif (MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `archiv_uzivatele` WHERE `nick` LIKE '{$_POST['new_login']}'")) > 0)
				$errorMsg .= 'Takový nick již existuje<br />';
			/* pwd */
			if (!$_POST['new_pwd1'] || !$_POST['new_pwd2'])
				$errorMsg .= 'Nevyplnìná obì hesla<br />';
			elseif ($_POST['new_pwd1'] != $_POST['new_pwd2'])
				$errorMsg .= 'Hesla se neshodují<br />';
			elseif (strlen($_POST['new_pwd1']) < 5)
				$errorMsg .= 'Heslo musí být alespoò 5 znakù dlouhé<br />';
			/* podminky */
			if ($_POST['new_podminky'] != 'on')
				$errorMsg .= "Nesouhlasíte s podmínkami registrace<br />";
			
			if ($errorMsg) 
				echo '<span class="error">'.$errorMsg.'</span><br /><br />';
			else {
				if (MySQL_Query ("INSERT INTO `archiv_uzivatele` ( `ID` , `status` , `login` , `heslo` , `nick` ) 
																		VALUES ('' , '100', '{$_POST['new_login']}', '".md5($_POST['new_pwd1'])."', '{$_POST['new_nick']}');"))
				return 1;
				else echo '<span class="error">Chyba pøi vkládání do databáze!</span><br /><br />';
			}
		}
		return 0;
	}
	
	function help ($key, $params = '') {
		require "res/help.text.php";
		$temp = new Help($key,$texty[$key][0],$texty[$key][1]);
		$temp->vytiskni($params);
	}
}
?>
