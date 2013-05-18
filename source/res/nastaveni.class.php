<?php
class Nastaveni {
	var $skript, $user;
	
	function Nastaveni ($skript, &$user) {
		$this->skript = $skript;
		$this->user = &$user;
	}

	function vykresliTabulkuNastaveni () {
		echo '<form action="'.$this->skript.'" method="post">
					<input type="hidden" name="akce" value="'.$_REQUEST['akce'].'">
					<table>
					<tr>
						<td>
							Šablona vzhledu';
		$this->help ('nastTemplate');
		echo '
						</td>
						<td>
							<table cellspacing="0">';
					
			
			$dir = "templates/";
			$template_C = new Templates ($dir);
			$templates = $template_C->templatesList();
			if (!is_array ($templates)) {
				echo "
				<tr>
					<td>
						Žádné šablony nebyly nalezeny.
					</td>
				</tr>";	
			}
			else foreach ($templates as $template) {
				echo '
				<tr>
					<td>
						<input type="radio" name="change_template" value="'.$template.'" id="template'.$template.'"'.($this->user->getTemplate() == $template ? ' checked' : '').'><label for="template'.$template.'">'.file_get_contents("{$dir}{$template}/template.name").'</label>
					</td>
					<td>
						<a href="'.$dir.$template."/template.jpg".'" target="_blank">screenshot</a>
					</td>
				</tr>';
			}
			
			echo'		</table>							
						</td>
					</tr>
					<tr>
						<td>
							Bran na stránku';
		$this->help ('nastPocetNaStranku');
		echo '
						</td>
						<td>
							<select name="change_bran_na_str">
								<option name="10" value="10"'.($this->user->getBranNaStranku() == 10 ? ' selected' : '').'>10</option>
								<option name="20" value="20"'.($this->user->getBranNaStranku() == 20 ? ' selected' : '').'>20</option>
								<option name="50" value="50"'.($this->user->getBranNaStranku() == 50 ? ' selected' : '').'>50</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<input type="submit" name="change_settings" value="Zmìò nastavení" class="submit">
						</td>
					</tr>
					</table>
					
				</form>';
	}
	
	function help ($key, $params = '') {
		require "res/help.text.php";
		$temp = new Help($key,$texty[$key][0],$texty[$key][1]);
		$temp->vytiskni($params);
	}
}
?>