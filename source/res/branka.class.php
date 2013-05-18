<?php
/* TODO - kontrola prazdneho povolani
 */

class Branka {
	var $id, $skript, $akce, $user, $povolani, $silaCelkem, $cisloBrany, $komentar, $popisek, $privacy,$upkeepy_pred, $upkeepy_po,
		$boj, $template, $userID,
		$hospPred = '', $hospPo = '', $bojNerozlozeny = '', $stit = '', $vekID;
	
	
	function Branka (&$user, $skript, $akce, $id = 0) {
		$this->id = $id;
		$this->skript = $skript;
		$this->akce = $akce;
		$this->user = &$user;
		if ($id > 0) {
			if (!$this->nactiDatazDB ($id)) {
				echo '<div class="error">
					Br�na nebyla nalezena v datab�zi!
				</div> <!-- // -->';
			}
		}
	}
	
	function getTemplate () {
		return $this->user->getTemplate ();
	}
	
	function nactiDatazDB ($id) {
		if (!$dataSource = MySQL_Query ("SELECT * FROM `archiv_branek` WHERE `ID` = '$id'")) return 0;
		if (!$data = MySQL_Fetch_Array ($dataSource)) return 0;
		$this->povolani = $data['povolani'];
		$this->silaCelkem = $data['sila'];
		$this->hospPred = $data['pred'];
		$this->hospPo = $data['po'];
		$this->stit = $data['stit'];
		$this->komentar = $data['komentar'];
		$this->popisek = $data['popisek'];
		$this->vekID = $data['ID_veky'];
		$this->privacy = $data['privacy'];
		$this->cisloBrany = $data['cisloBrany'];
		$this->upkeepy_pred = $data['upkeepy_pred'];
		$this->upkeepy_po = $data['upkeepy_po'];
		$this->userID = $data['ID_archiv_uziv'];
		$this->boj = new Boj($id);
		
		return 1;
	}
	
	function zapisDataDoDB ($nechat_id = false) {
		$error = '';
		/* TODO - kontrola jestli nevklada fake */
		
		/* kontrola jestli uz v DB jsem nebo jestli je to novy zaznam */
		if (MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `archiv_branek` WHERE `ID` = '{$this->id}'")) == 0) { //nejsem jeste v DB
			if (!MySQL_Query ("INSERT INTO `archiv_branek` (`ID`) VALUES ('0')"))
				$error .= "Chyba p�i zapisov�n� do datab�ze - vytv��en� z�znamu<br />";
				
			$temp = MySQL_Fetch_Array (MySQL_Query ("SELECT `ID` FROM `archiv_branek` ORDER BY `ID` DESC"));
			$this->id = $temp['ID'];
		}
		if (!$this->povolani)
			$error = 'Chybn� rozlo�en� povol�n�<br />';
		if (!MySQL_Query ("UPDATE `archiv_branek` SET ".($nechat_id ? '' : "`ID_archiv_uziv` = '{$this->user->userID}',")."
																	 `ID_veky` = '{$this->vekID}',
																	 ".($nechat_id ? '' : "`datum` = '".time()."',")."
																	 `OT` = '{$this->boj->ot}',
																	 `povolani` = '{$this->povolani}',
																	 `sila` = '{$this->silaCelkem}',
																	 `ztraty_utok` = '{$this->boj->ztraty['utok']}',
																	 `ztraty_obrana` = '{$this->boj->ztraty['obrana']}',
																	 `cisloBrany` = '{$this->cisloBrany}',
																	 `komentar` = '{$this->komentar}',
																	 `stit` = '{$this->stit}',
																	 `popisek` = '{$this->popisek}',
																	 `privacy` = '{$this->privacy}',
																	 `upkeepy_pred` = '{$this->upkeepy_pred}',
																	 `upkeepy_po` = '{$this->upkeepy_po}'
								 WHERE `ID` = '{$this->id}'")) 
		 	$error .= 'Chyba p�i zapisov�n� do datab�ze - z�kladn� �daje<br />';
		if (!$this->boj->zapisDataDoDB($this->id))
			$error .= 'Chyba p�i zapisov�n� do datab�ze - boj<br />';
		if (!($this->hospPred && MySQL_Query ("UPDATE `archiv_branek` SET `pred` = '{$this->hospPred}' WHERE `ID` = '{$this->id}'")))
			$error .= 'Chyba p�i zapisov�n� do datab�ze - hospoda�en� p�ed bojem<br />';
		if (!MySQL_Query ("UPDATE `archiv_branek` SET `po` = '{$this->hospPo}' WHERE `ID` = '{$this->id}'"))
			$error .= 'Chyba p�i zapisov�n� do datab�ze - hospoda�en� po boji<br />';
		
		if ($error) {
			echo "$error";
			MySQL_Query ("DELETE FROM `archiv_branek` WHERE `ID` = '{$this->id}'");
			return 0;
		}
		return 1;
	}
	
	function vykresliUpdatovaciTabulku () {
		echo '
		<center>url: http://archiv.ma.savannahsoft.eu/index.php?show=' .$this->id . '</center><br /><br />
		<form action="'.$this->skript.'" method="post">
		<input type="hidden" name="cil" value="'.$this->id.'">
		<input type="hidden" name="akce" value="'.$this->akce.'">
		<input type="hidden" name="cisloBrany" value="'.$this->cisloBrany.'">
		  ��slo: 
		  <select class="malyVstup" name="cislo">';
		$branky = array (1 => 'br1na', 'br2na', 'br3na','br4na', 'br5na', 'br6na','br7na', 'br8na', 'm�sto');
		foreach ($branky as $cislo => $text) {
      echo '<option value="' . $cislo . '"' . ($cislo == $this -> cisloBrany ? ' selected' : '') . '>'.$text.'</option>';
    }
		echo '</select>
    V�k: ';			
		$this->help('uprVek');
		echo '
		<select class="malyVstup" name="vek">';
		$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
		while ($vek = MySQL_Fetch_Array ($veky)) {
			echo '<option value="'.$vek['ID'].'"'.($vek['ID'] == $this->vekID ? ' selected' : '').'>'.$vek['jmeno'].' ('.$vek['title'].')</option>';
		}
		echo'		</select>
		�t�t: ';
		$this->help('uprStit');				
		echo '
			<input class="malyVstup" name="stit" value="'.htmlspecialchars($this->stit).'">
		Popisek: ';
		$this->help('uprPopisek');				
		echo '
			<input class="malyVstup" name="popisek" value="'.htmlspecialchars($this->popisek).'">
		Privacy: ';
		$this->help('uprPrivacy');				
		echo '
			<select class="malyVstup" name="privacy">
				<option value="0"'.($this->privacy == 0 ? ' selected' : '').'>Ve�ejn�</option>
				<option value="1"'.($this->privacy == 1 ? ' selected' : '').'>Anonymn�</option>
				<option value="2"'.($this->privacy == 2 ? ' selected' : '').'>Skryt�</option>
			</select>
		Koment��: ';
		$this->help('uprKomentar');
		echo '
			<textarea class="vstup" name="komentar">'.htmlspecialchars($this->komentar).'</textarea>
		<table>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="next" value="Upravit" name="upravit"> <input type="submit" onClick=\'return window.confirm("Opravdu chcete smazat branku?")\' class="back" value="Smazat" name="smazat"></td>
			</tr>
			</table>
			
		</form>
		';
				
	}
	
	/* vola se z mainu kdyz upravuju tabulku v 'Vlastni branky' */
	function uprav ($data = null) {
		if (is_null($data)) {
			$this->vekID = $_POST['vek'];
			$this->stit = $_POST['stit'];
			$this->popisek = $_POST['popisek'];
			$this->komentar = $_POST['komentar'];
			$this->privacy = $_POST['privacy'];
			$this -> cisloBrany = $_POST['cislo'];
			return $this->zapisDataDoDB (1 /* nechat id_uzivatele */);
		} else {
			//TODO - update podle zadanych dat a ne z postu
		}
	}
		
	/**
	 * @param bool $simul jestli se ma vykreslit pouze text pro simul
	 */   	
	function vykresliBranku ($info = 0, $all = 0, $simul = false) {
    if ($simul) {
      echo '<div style="width: 300px; background-color: #8ea7dd; margin: 0px 330px;">';
      $temp = explode ("\n", trim($this -> hospPred));
      if (is_array ($temp))
        foreach ($temp as $row) {
          $chunks = explode ("|", trim ($row));
          echo $chunks[0] . "|" . $chunks[6] . "|" . $chunks [1] . "<br />";
        }
      else
        echo "Nelze";
      echo '</div>';
      return ;
    }
		/* pokud je $info == 1, tak se vykresli i detaily jako OT atp (treba na 
		*	upravovanji branky se to kresli jinam */
		$templateFile = $this->getTemplate ();
		if (!file_exists("templates/{$templateFile}/template.php")) {
			echo '<span class="error">�ablona neexistuje.</span>';
			return 0;
		}
		$template = file_get_contents ("templates/{$templateFile}/template.php");
		
		if ($info) $temp = $this->vykresliObecneInfo($all);
			else $temp = '';
		
		$template = preg_replace ('/{OBECNE_INFO}/', $temp, $template);
		
		for ($i = 1; $i <= 4; $i ++) 
			$template = preg_replace ('/{KOLO_'.$i.'}/', $this->boj->vykresliTabulkuJednotek($i), $template);	
		
		
		$template = preg_replace ('/{HOSP_PRED}/', $this->vykresliHospodareni ($this->hospPred), $template);
		$template = preg_replace ('/{HOSP_PO}/', $this->vykresliHospodareni ($this->hospPo), $template);
	
		echo $template;
	}
	
	function vykresliObecneInfo ($detail = 0) {
		$retString = '';
		$lossU = $this->boj->ztraty['utok'];
		$lossO = $this->boj->ztraty['obrana'];
		$vek = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `veky` WHERE `ID` = '{$this->vekID}'"));
		$vek = $vek['jmeno'].' ('.$vek['title'].')';
		if ($lossU < $lossO) $extra = 'vyhral';
		elseif ($lossU == $lossO) $extra = 'remiza';
		else $extra = 'prohral';
		$retString .= '
			<table>
			<tr>
				<td>V�k</td>
				<td>
					'.$vek.'
				</td>
			</tr>
			<tr>
				<td>
					Autor
				</td>
				<td>
					'.$this->autor().'
				</td>
			</tr>
			<tr>
				<td>
					OT
				</td>
				<td>
					'.cislo($this->boj->ot).'
				</td>
			</tr>
			<tr>
				<td>
					S�la p�ed �tokem
				</td>
				<td>
					'.cislo($this->silaCelkem).'
				</td>
			</tr>
			<tr>
				<td>
					P�esv�d�en�
				</td>
				<td>';
			if ($this->boj->presvedceni == 'N') $retString .= 'Neutr�ln�';
				elseif ($this->boj->presvedceni == 'D') $retString .= 'Dobr�';
				else $retString .= "Zl�";
				
			$retString .= '
				</td>
			</tr>
			<tr>
				<td>
					Povol�n�
				</td>
				<td>
					'.$this->povolani.'
				</td>
			</tr>
			<tr>
				<td>
					Br�na
				</td>
				<td>
					Br'.$this->cisloBrany.'na
				</td>
			</tr>
			<tr>
				<td>
					V�sledek
				</td>
				<td class="'.$extra.'">
					Va�e ztr�ty jsou '.cislo ($lossU, 2).' %, obr�nce '.cislo($lossO, 2).' %.
				</td>
			</tr>';
			if ($detail) {			
//   			simulace branky
// 				$retString .= '<tr>
// 					<td>					
// 						Simulace ';
// 						
// 				require "res/help.text.php";
// 				$temp = new Help("detail_simul",$texty["detail_simul"][0],$texty["detail_simul"][1]);
// 				$retString .= $temp->posliVysledek();
// 				$retString .= '
// 						
// 					</td>
// 					<td>'.'
// 						<form action="'.$this->skript.'" method="post">
// 							<input type="hidden" name="akce" value="'.$_REQUEST['akce'].'">
// 							<input type="hidden" name="simul" value="'.$this->id.'">
// 							<input class="submit" type="submit" value="Simuluj">
// 							(v�k: <select class="submit" name="vek">';
// 							
// 					$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
// 					while ($vek = MySQL_Fetch_Array ($veky)) {
// 						$retString .= '<option value="'.$vek['ID'].'"'.($this->vekID == $vek['ID'] ? ' selected' : '').'>'.$vek['jmeno'].' ('.$vek['title'].')</option>';
// 					}
// 					$retString .= '		</select>)
// 						</form>
// 					</td>
// 				</tr>';
			}
			$retString .= '
			</table>
		';
		
		return $retString;
	}
	
	function autor ($id = null) {
		$brana = MySQL_Query ("SELECT `archiv_branek`.*, `archiv_uzivatele`.`nick` FROM `archiv_branek`
									LEFT JOIN `archiv_uzivatele` ON `archiv_uzivatele`.`ID` = `archiv_branek`.`ID_archiv_uziv`
									WHERE `archiv_branek`.`ID` = '".(is_null($id) ? $this->id : $id)."'");					
		
		
		if (!$brana = MySQL_Fetch_Array ($brana)) return null;
		
		return ($brana['privacy'] == 1 ? 'Anonymn�' : 
						   ($brana['nick'] ? $brana['nick'] : 'Neexistuj�c� u�ivatel'));
	}
	
	function vykresliVysledek ($lossU, $lossO) {
		if ($lossU < $lossO) $extra = 'vyhral';
		elseif ($lossU == $lossO) $extra = 'remiza';
		else $extra = 'prohral';
		echo '
		<div class="vysledek">
			<span class="'.$extra.'">
				Va�e ztr�ty jsou '.cislo ($lossU, 2).' %, obr�nce '.cislo($lossO, 2).' %.
			</span>
		</div> <!-- //vysledek -->
		';
	}
	
	function vykresliHospodareni ($hosp) {
		$retString = '';
		/* pokud je $space == 1 tak je trida divu zaroven jeste hospodareni_space (kvuli mezere) */
		
		if (trim($hosp)) {
			$retString .= '				
				<table>
				<tr>
					<td>
						<strong>Jednotka</strong>
					</td>
					<td>
						<strong>XP</strong>
					</td>
					<td>
						<strong>Typ</strong>
					</td>
					<td>
						<strong>S�la</strong>
					</td>
					<td>
						<strong>Po�et</strong>
					</td>
					<td>
						<strong>zl/TU</strong>
					</td>
					<td>
						<strong>mn/TU</strong>
					</td>
					<td>
						<strong>pp/TU</strong>
					</td>
				</tr>			
				';
						
			$temp = explode ("\n", trim($hosp));
			
			$zlato_tot = 0;
			$mana_tot = 0;
			$lidi_tot = 0;
			$pwr_tot = 0;
			
			foreach ($temp as $jednotka) {
				if (!preg_match('/([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)/', $jednotka, $match)) 
					next;
				
				$zlato_tot += $match[8];
				$mana_tot += $match[9];
				$lidi_tot += $match[10];
				$pwr_tot += $match[6];
				
				$retString .= "
				<tr>
					<td>
						{$match[1]}
					</td>
					<td class=\"right\">
						{$match[2]}
					</td>
					<td>
						{$match[4][0]}{$match[5][0]}".($match[5][0] == 'B' ? $match[3] : '')."
					</td>
					<td class=\"right\">
						".cislo($match[6])."
					</td>
					<td class=\"right\">
						".cislo($match[7])."
					</td>
					<td class=\"right\">
						".cislo($match[8])."
					</td>
					<td class=\"right\">
						".cislo($match[9])."
					</td>
					<td class=\"right\">
						".cislo($match[10])."
					</td>
				</tr>
				
				";
			}	//- foreach
			
			if ($space) $upkeep = explode("|", $this->upkeepy_po);
				else $upkeep = explode("|", $this->upkeepy_pred);
			
			$retString .= '
				<tr>
					<td colspan="8">
						<hr>
					</td>
				</tr>
				<tr>					
					<td colspan="5">
						Stavby a budovy
					</td>				
					<td class="right">
						'.cislo($upkeep[0]).'
					</td>
					<td class="right">
						'.cislo($upkeep[1]).'
					</td>
					<td class="right">
						'.cislo($upkeep[2]).'
					</td>
				</tr>
				<tr>					
					<td colspan="5">
						Podann�
					</td>				
					<td class="right">
						'.cislo($upkeep[3]).'
					</td>
					<td class="right">
						'.cislo($upkeep[4]).'
					</td>
					<td class="right">
						'.cislo($upkeep[5]).'
					</td>
				</tr>
				<tr>					
					<td colspan="5">
						Arm�da
					</td>				
					<td class="right">
						'.cislo($zlato_tot).'
					</td>
					<td class="right">
						'.cislo($mana_tot).'
					</td>
					<td class="right">
						'.cislo($lidi_tot).'
					</td>
				</tr>
				<tr>					
					<td colspan="8">
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						Celkem
					</td>
					<td>
						'.cislo($pwr_tot).'
					</td>
					<td>
						&nbsp;
					</td>
					<td class="right">
						'.cislo($zlato_tot + $upkeep[0] + $upkeep[3]).'
					</td>
					<td class="right">
						'.cislo($mana_tot + $upkeep[1] + $upkeep[4]).'
					</td>
					<td class="right">
						'.cislo($lidi_tot + $upkeep[2] + $upkeep[5]).'
					</td>
				</tr>
			</table>
			';
		} else {
			return "&lt;&lt; Nevypln�no &gt;&gt;";
		}
		
		return $retString;
	}
	
	
	function vykresliPridavaciTabulku ($step = 0) {
		if (get_magic_quotes_gpc ()) {
			$_POST['hosp_pred'] = stripslashes ($_POST['hosp_pred']);
			$_POST['hosp_po'] = stripslashes ($_POST['hosp_po']);
			$_POST['branka'] = stripslashes ($_POST['branka']);
		}
		switch ($step) {
			case 3:
				$this->vekID = $_POST['vek'];
				$this->komentar = $_POST['komentar'];
				$this->stit = $_POST['stit'];
				$this->popisek = $_POST['popisek'];
				$this->privacy = $_POST['privacy'];
				$chyba = '';
				/* zadany vstup, provedu rozklad a pak se uvidi */
				if (!$this->rozklad ($_POST['hosp_pred'], $_POST['hosp_po'], $_POST['branka']) || $_POST['presvedceni'] == '-' || !$this->boj->rozklad()) 
					$chyba .= "Byl zad�n chybn� vstup nebo do�lo k chyb� v rozkladu. Zkontrolujte zda jste spr�vn� vyplnili v�echna pole a pokud probl�m p�etrv�, kontaktujte spr�ce.<br>";
				if ($this->cisloBrany < 1 || $this->cisloBrany > 9)
					$chyba .= "Nebylo spr�vn� zji�t�no ��slo br�ny<br />";
				if (!$this->povolani)
					$chyba .= "Nebylo spr�vn� zji�t�no povol�n�<br />";
				
				/* zadam data do DB */
				if (!$chyba) {
					if ($this->zapisDataDoDB()) {
						echo "Data byla ulo�ena do datab�ze. Va�i branku si m��ete prohl�dnout/upravit v menu `Vlastn� branky`";
					}
					else 
						echo '<div class="error">
							Chyba p�i vkl�d�n� br�ny do datab�ze. Kontaktujte spr�vce.
						</div> <!-- // -->';
				}
				echo '<div class="error">
					'.$chyba.'
				</div> <!-- // -->';
				
			break;
			case 2:
				$this->vekID = $_POST['vek'];
				$chyba = '';
				/* zadany vstup, provedu rozklad a pak se uvidi */
				if (!$this->rozklad ($_POST['hosp_pred'], $_POST['hosp_po'], $_POST['branka'])) 
					$chyba .= "Byl zad�n chybn� vstup nebo do�lo k chyb� v rozkladu. Zkontrolujte zda jste spr�vn� vyplnili v�echna pole a pokud probl�m p�etrv�, kontaktujte spr�ce.<br>";
				if ($this->cisloBrany < 1 || $this->cisloBrany > 9)
					$chyba .= "Nebylo spr�vn� zji�t�no ��slo br�ny<br />";
				if (!$this->povolani)
					$chyba .= "Nebylo spr�vn� zji�t�no povol�n�<br />";
				
				if (!$this->hospPred)
					$this->hospPred = '<< Chybn� vstup >>';
				if (!$this->hospPo)
					$this->hospPo = '<< Nevypln�no >>';
			
				
				echo '<form action="'.$this->skript.'" method="post">
				V�k: <span class="povinne">*</span>';
				$this->help ("potVek");
				echo '<div class="malyNahled">';
					if ($vek = $this->getVek()) echo $vek;
					else {
						$chyba .= "Byl �patn� vypln�n v�k.<br>";
						echo "Chybn� vypln�n� v�k!";
					}
				echo '</div>
				�t�t: ';
				$this->help ("potStit");
				echo ' (upravovateln�)<input class="malyNahled" name="stit" value="'.htmlspecialchars($this->stit).'">
				OT: <span class="povinne">*</span>';
				$this->help ("potOT");
				echo '<div class="malyNahled">
					'.(int)$this->boj->ot.'
				</div> <!-- //malyNahled -->
				��slo br�ny: <span class="povinne">*</span>';
				$this->help ("potCislo");
				echo '<div class="malyNahled">
					'.($this->cisloBrany == 9 ? 'm�sto' : ($this->cisloBrany >= 1 ? "br{$this->cisloBrany}na" : htmlspecialchars('<< Chybn� vstup >>'))).'
				</div> <!-- //malyNahled -->
				P�esv�d�en�: <span class="povinne">*</span>';				
				$this->help ("potPresvedceni");
				echo ' (upravovateln�) <select name="presvedceni" class="malyNahled"> 
					<option value="-"'.($this->boj->presvedceni == '' ? ' selected' : '').'>&lt;&lt; Nezji�t�no &gt;&gt;</option>
					<option value="D"'.($this->boj->presvedceni == 'D' ? ' selected' : '').'>Dobr�</option>
					<option value="Z"'.($this->boj->presvedceni == 'Z' ? ' selected' : '').'>Zl�</option>
					<option value="N"'.($this->boj->presvedceni == 'N' ? ' selected' : '').'>Neutr�ln�</option>
					'.$this->boj->presvedceni.'&nbsp;
				</select>
				Privacy: <span class="povinne">*</span>';
				$this->help ("potPrivacy");
				echo ' (upravovateln�)<select class="malyVstup" name="privacy">
					<option value="0"'.($_POST['privacy'] == 0 ? ' selected' : '').'>Ve�ejn�</option>
					<option value="1"'.($_POST['privacy'] == 1 ? ' selected' : '').'>Anonymn�</option>
					<option value="2"'.($_POST['privacy'] == 2 ? ' selected' : '').'>Skryt�</option>
				</select>';
				
				
				
				
				$this->vykresliPribliznouTabulkuJednotek($this->hospPred, 'Jednotky p�ed bojem', 'potHPred', true);
				$this->boj->vykresliPribliznouTabulkuJednotek('Boj na br�n�', 'potBoj');
				$this->vykresliPribliznouTabulkuJednotek($this->hospPo, 'Jednotky po boji', 'potHPo');
				
				$this->komentar = $_POST['komentar'];
				$this->stit = $_POST['stit'];
				
				echo 'Koment��: ';
				$this->help ("potKomentar");
				echo '
				<div class="nahled">'.nl2br(htmlspecialchars($_POST['komentar'])).'
				</div>
				<input type="hidden" name="step" value="3">
				<input type="hidden" name="akce" value="'.$this->akce.'">
				<input type="hidden" name="hosp_pred" value="'.htmlspecialchars($_POST['hosp_pred']).'">
				<input type="hidden" name="hosp_po" value="'.htmlspecialchars($_POST['hosp_po']).'">
				<input type="hidden" name="branka" value="'.htmlspecialchars($_POST['branka']).'">
				<input type="hidden" name="komentar" value="'.htmlspecialchars($_POST['komentar']).'">
				<input type="hidden" name="popisek" value="'.htmlspecialchars($_POST['popisek']).'">
				<input type="hidden" name="vek" value="'.htmlspecialchars($_POST['vek']).'">
				<table>'.($this->boj->rozlozenyBojChyby == 'Chybne rozlozeno' ? '
				<tr>
					<td colspan="3">
						<div class="error">
							!! Pozor - br�na nebyla pravd�podob� cel� rozlo�ena. Podrobn� si, pros�m, zkontrolujte, jestli odpov�d� automatem vytvo�en� rozklad skute�n� br�n�. Pokud ne, kontaktujte, pros�m, administr�tory.	 !!
						</div> <!-- //erro -->
					</td>
				</tr>
				': '').'
					<tr>
						<td colspan="2">&nbsp;</td>
						<td><input type="submit" class="back" value="Zp�t" name="zpetStep1"> <input type="submit" class="next" value="Pokra�uj"'.($chyba ? ' disabled' : '').'></td>
					</tr>
				</table>
				</form>';
				if ($chyba)
					echo '<div class="error">
						'.$chyba.'
					</div> <!-- //error -->';
				echo '<br />
				<span class="povinne">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*) Povinn� parametry</span>';
			break;
			default:
				
				echo '
				<form action="'.$this->skript.'" method="post">
				<input type="hidden" name="step" value="2">
				<input type="hidden" name="akce" value="'.$this->akce.'">
					V�k: <span class="povinne">*</span>';
					
				$this->help('zadVek');
				echo '
							<select class="malyVstup" name="vek">';
				$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
				while ($vek = MySQL_Fetch_Array ($veky)) {
					echo '<option value="'.$vek['ID'].'"'.($_POST['vek'] == $vek['ID'] ? ' selected' : '').'>'.$vek['jmeno'].' ('.$vek['title'].')</option>';
				}
				echo'		</select>
				<!-- �t�t: ';
				$this->help('zadStit');				
				echo '
					<input class="malyVstup" name="stit" value="'.$_POST['stit'].'"> -->
				Popisek: ';
				$this->help('zadPopisek');				
				echo '
					<input class="malyVstup" name="popisek" value="'.$_POST['popisek'].'">
				Privacy: <span class="povinne">*</span>';
				$this->help ("zadPrivacy");
				echo ' <select class="malyVstup" name="privacy">
					<option value="0"'.($_POST['privacy'] == 0 ? ' selected' : '').'>Ve�ejn�</option>
					<option value="1"'.($_POST['privacy'] == 1 ? ' selected' : '').'>Anonymn�</option>
					<option value="2"'.($_POST['privacy'] == 2 ? ' selected' : '').'>Skryt�</option>
				</select>
				Hospoda�en� p�ed branou: <span class="povinne">*</span>';
				$this->help('zadHPred');
				echo'
					<textarea class="vstup" name="hosp_pred">'.$_POST['hosp_pred'].'</textarea>
				Boj na br�n�: <span class="povinne">*</span>';
				$this->help('zadBoj');
				echo'
					<textarea class="vstup" name="branka">'.$_POST['branka'].'</textarea>
						
				Hospoda�en� po br�n�: ';
				$this->help('zadHPo');
				echo'
					<textarea class="vstup" name="hosp_po">'.$_POST['hosp_po'].'</textarea>
				Koment��: ';
				$this->help('zadKomentar');
				echo '
					<textarea class="vstup" name="komentar">'.$_POST['komentar'].'</textarea>
				
					<table>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" class="back" value="Zp�t" name="zpetStep0" disabled> <input type="submit" class="next" value="Pokra�uj"></td>
					</tr>
					</table>
					
				</form>
				<br />
				<span class="povinne">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*) Povinn� parametry</span>
				';
			break;		
		}
	}
	
	/* vykresli orientacni tabulku jednotek do DIVu (jako druhy krok vkladani koberce, pro kontrolu) */
	function vykresliPribliznouTabulkuJednotek ($rozklad, $text = null, $help = null, $povinne = false) {
		if ($text) echo $text.': '.($povinne ? '<span class="povinne">*</span>' : '');
		if ($help) $this->help($help);
		$radky = explode ("\n", $rozklad);
		
		echo '<div class="nahled">
		<table>';
		foreach ($radky as $radek) if ($radek) {
			$temp = new Jednotka ($radek);
			$temp->vykresliMinitabulku();
		}
		echo '
			</table>
		</div> <!-- // -->';
	}
	
	
	function rozklad ($hospPred, $hospPo, $boj) {
		$error = '';
		/* user info */
			/* povolani */
		if (preg_match ('/(V�le�n�k|Klerik|M�g|Alchymista|Druid|Hrani���|Barbar|Iluzionista|Nekromant|Theurg|V�dma|Amazonka), ID (\d+)/', $hospPred, $match)) 
			$this->povolani = $match[1];
		else $error = 1;
			/* regent_ID */
		if (preg_match ('/(V�le�n�k|Klerik|M�g|Alchymista|Druid|Hrani���|Barbar|Iluzionista|Nekromant|Theurg|V�dma|Amazonka)/', $hospPred, $match))
			$this->povolani = $match[1];
		else $error = 1;
		if (preg_match ('/Br�ny \((\d)\.\)/', $hospPred, $match))
			$this->cisloBrany = $match[1];
		elseif (preg_match ('/(Kr�l Godefroy|D�monka Fiona|M�sto duch�)/', $hospPred))
			$this->cisloBrany = 9;
		else $error = 1;
		
		
		/* hospodareni pred */
		{
			$hospPredRozklad = null;
			$this->silaCelkem = 0;
			$i = 0;
			$stit = '';
			
			/* poddani & budovy */
			preg_match ('/Stavby a Budovy \*\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+/', $hospPred, $stavby);
			preg_match ('/Poddan�\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+/', $hospPred, $poddani);
			
			$this->upkeepy_pred = "{$stavby[1]}|{$stavby[3]}|{$stavby[5]}|{$poddani[1]}|{$poddani[3]}|{$poddani[5]}";
			
			/* 						  1.jmeno 2-3. XP             4. phb 5.druh          6. typ          7-8. sila             9-10. pocet           11-12.zl_tu             13-14. mn_tu           15-16. pp_tu  */
			if (preg_match_all ('/(.*?)\s+([-+]?\d+(\.\d+)?)\s+(\d)\s+(Poz\.|Let\.)\s+(Str\.|Boj\.)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)/', $hospPred, $matches)) { /* army */
				foreach ($matches[9] as $key => $jednotka) {
					$this->silaCelkem += $matches[7][$key];
					$hospPredRozklad .= "{$matches[1][$key]}|{$matches[2][$key]}|{$matches[4][$key]}|{$matches[5][$key]}|{$matches[6][$key]}|{$matches[7][$key]}|{$matches[9][$key]}|{$matches[11][$key]}|{$matches[13][$key]}|{$matches[15][$key]}\n";
				}
			}
			
			
			/* stit */
			if ($_POST['stit']) $this->stit = $stity = $_POST['stit'];
			else {
				$stity = '';
				for ($i = 0; ($i < 4 && $matches[7][$i]); $i ++) {
					if ($i == 3) {
						$stity = '';
						break;
					}
					$stity .= ($stity ? ', ' : '').$matches[1][$i];
					if (!$matches[7][$i+1] || $matches[7][0] * 0.6 > $matches[7][$i+1])
						break;
					
				}
				if (!$stity) $stity = "Sm�s";
			}
		}
		
		/* Hospodareni po */
		{
			$hospPoRozklad = null;
			
			/* poddani & budovy */
			preg_match ('/Stavby a Budovy \*\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+/', $hospPo, $stavby);
			preg_match ('/Poddan�\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+/', $hospPo, $poddani);
			
			$this->upkeepy_po = "{$stavby[1]}|{$stavby[3]}|{$stavby[5]}|{$poddani[1]}|{$poddani[3]}|{$poddani[5]}";
			
			/* 						  1.jmeno 2-3. XP             4. phb 5.druh          6. typ          7-8. sila             9-10. pocet           11-12.zl_tu             13-14. mn_tu           15-16. pp_tu  */
			if (preg_match_all ('/(.*?)\s+([-+]?\d+(\.\d+)?)\s+(\d)\s+(Poz\.|Let\.)\s+(Str\.|Boj\.)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)/', $hospPo, $matches)) { /* army */
				foreach ($matches[9] as $key => $jednotka) {
					//$hospPoRozklad .= "jmeno:{$matches[1][$key]}|xp:{$matches[2][$key]}|phb:{$matches[4][$key]}|druh:{$matches[5][$key]}|typ:{$matches[6][$key]}|sila:{$matches[7][$key]}|pocet:{$matches[9][$key]}|zl_tu:{$matches[11][$key]}|mn_tu:{$matches[13][$key]}|pp_tu:{$matches[15][$key]}\n";
					$hospPoRozklad .= "{$matches[1][$key]}|{$matches[2][$key]}|{$matches[4][$key]}|{$matches[5][$key]}|{$matches[6][$key]}|{$matches[7][$key]}|{$matches[9][$key]}|{$matches[11][$key]}|{$matches[13][$key]}|{$matches[15][$key]}\n";
				}
			}
		}
		
		/* boj */
		$this->boj = new Boj($boj);
		$this->boj->rozklad();
		if ($hospPredRozklad && /*$bojRozklad*/$this->boj->rozlozeno) {
			$this->hospPred = $hospPredRozklad;
			$this->hospPo = $hospPoRozklad;	
			$this->stit = $stity;
			return 1;
		} else {
			if ($hospPredRozklad) {
				$this->hospPred = $hospPredRozklad;
				$this->stit = $stity;
			}
			if ($hospPoRozklad) $this->hospPo = $hospPoRozklad;
			return 0;
		}
	}
	
	function getVek () {
		if ($vek = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `veky` WHERE `ID` = '{$this->vekID}'"))) 
			return $vek['jmeno'].' ('.$vek['title'].')';
		else return null;
	}
	
	function help ($key, $params = '') {
		require "res/help.text.php";
		$temp = new Help($key,$texty[$key][0],$texty[$key][1]);
		$temp->vytiskni($params);
	}
	
	function smaz () {
		/* test jestli existuje */
		if (!MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `archiv_branek` WHERE `ID` = '{$this->id}'")))
			return 0;
		return MySQL_Query ("DELETE FROM `archiv_branek` WHERE `ID` = '{$this->id}'");
	}
}
?>
