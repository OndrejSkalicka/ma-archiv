<?php
class VyhledavaniBranek {
	var $skript, $akce, $user, $branNaStranku;
	
	function VyhledavaniBranek ($skript, $akce, &$user) {
		$this->skript = $skript;
		$this->akce = $akce;
		$this->user = &$user;
		$this->branNaStranku = $user->getBranNaStranku();
	}
	
	function vyhledejVlastniBrany ($userID, $orderby = '`veky`.`priorita`, `cisloBrany`, `datum` DESC') {
		return /*MySQL_Query (*/"SELECT `archiv_branek`.*, `archiv_uzivatele`.`nick`, `veky`.`jmeno`, `veky`.`priorita` FROM `archiv_branek`
									LEFT JOIN `archiv_uzivatele` ON `archiv_uzivatele`.`ID` = `archiv_branek`.`ID_archiv_uziv`
									INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
									 WHERE `ID_archiv_uziv` = '{$userID}' ORDER BY {$orderby}"/*)*/;
	}
	
	function vyhledejVlastniBranyAdmin ($userID, $orderby = '`veky`.`priorita`, `cisloBrany`, `datum` DESC') {
		return /*MySQL_Query (*/"SELECT `archiv_branek`.*, `archiv_uzivatele`.`nick`, `veky`.`jmeno`, `veky`.`priorita` FROM `archiv_branek`
									LEFT JOIN `archiv_uzivatele` ON `archiv_uzivatele`.`ID` = `archiv_branek`.`ID_archiv_uziv`
									INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
									 WHERE 1 ORDER BY {$orderby}"/*)*/;
	}
	
	function vyhledejBrany () {
		$operatory = array ('eq' => '=', 'lt' => '<', 'let' => '<=', 'gt' => '>', 'get' => '>=', 'neq' => '<>');
		$select = "SELECT `archiv_branek`.*, `archiv_uzivatele`.`nick`, `veky`.`jmeno` FROM `archiv_branek`
						LEFT JOIN `archiv_uzivatele` ON `archiv_uzivatele`.`ID` = `archiv_branek`.`ID_archiv_uziv`
						INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
						 WHERE `privacy` <> '2' AND
						 ";
		/* presvedceni */
		$select .= "(";
		if ($_POST['brana_presvedceniD']) $select .= "`presvedceni` = 'D' OR ";
		if ($_POST['brana_presvedceniZ']) $select .= "`presvedceni` = 'Z' OR ";
		if ($_POST['brana_presvedceniN']) $select .= "`presvedceni` = 'N' OR ";
		$select .= " 0) AND \n";
		
		
		/* brana */
		$select .= "`cisloBrany` {$operatory[$_POST['brana_typ']]} '{$_POST['brana_cislo']}' AND \n";
		
		/* vek */
		$prioritaVeku = MySQL_Fetch_Array (MySQL_Query ("SELECT `priorita` FROM `veky` WHERE `ID` = '{$_POST['vek_id']}'"));
		$select .= "`priorita` {$operatory[$_POST['vek_typ']]} '{$prioritaVeku['priorita']}' AND \n";
		
		/* povolani */
		$povolani = array ("mag" => "Mág", "klerik" => "Klerik", "druid" => "Druid", 
      "theurg" => "Theurg", "iluzionista" => "Iluzionista", "alchymista" => "Alchymista", 
      "valecnik" => "Váleèník", "hranicar" => "Hranièáø", "nekromant" => "Nekromant", 
      "barbar" => "Barbar", 'vedma' => 'Vìdma', 'amazonka' => 'Amazonka');
		$selectTemp = '0';
		foreach ($povolani as $short => $long) {
			if ($_POST['povolani_typ'] == 'eq' && $_POST["povolani_{$short}"])
				$selectTemp .= " OR `povolani` = '$long'";
			if ($_POST['povolani_typ'] == 'neq' && !$_POST["povolani_{$short}"])
				$selectTemp .= " OR `povolani` = '$long'";
		}
		$select .= "($selectTemp) AND \n";
		
		/* ztraty U / O */
		$select .= "`ztraty_utok` {$operatory[$_POST['ztratyU_typ']]} '".str_replace(',', '.', $_POST['ztratyU_ztraty'])."' AND \n";
		$select .= "`ztraty_obrana` {$operatory[$_POST['ztratyO_typ']]} '".str_replace(',', '.', $_POST['ztratyO_ztraty'])."' AND \n";
		
		/* sila */
		$select .= "`sila` {$operatory[$_POST['sila_typ']]} '{$_POST['sila_value']}' AND \n";
		
		/* ot */
		$select .= "`ot` {$operatory[$_POST['ot_typ']]} '{$_POST['ot_value']}' AND \n";
		
		/* stity */
		if ($_POST['stit_value']) {
			$select .= '(';
			$stity = explode (',', $_POST['stit_value']);
			if (is_array ($stity)) {
				switch ($_POST['stit_typ']) {
					case 'eq_and':
						$operator = ' AND';
						$select .= '1';
					break;
					case 'eq_or':
						$operator = ' OR';
						$select .= '0';
					break;
					case 'neq':
						$operator = ' AND NOT';
						$select .= '1';
					break;
				} 
				foreach ($stity as $stit) {					
					$select .= "$operator (`stit` LIKE '%".trim($stit)."%')";
				}
			} else { /* $stity neni array */
				$select .= '1';
			}
			$select .= ") AND \n";
		}
		
		/* ordery: */
		$select .= "1 \n"; //konec podminek "AND"
		$select .= "ORDER BY `{$_POST['seradit_podleCeho1']}` {$_POST['seradit_ascDesc1']},
									`{$_POST['seradit_podleCeho2']}` {$_POST['seradit_ascDesc2']},
									`{$_POST['seradit_podleCeho3']}` {$_POST['seradit_ascDesc3']}
						";
		/*echo nl2br($select);*/
		return /*MySQL_Query ($select)*/$select;
	}
	
	/* parametrem je SQL SELECT */
	function vypisBrany ($dotaz, $privacy = false) {
    global $user; 
		$queryFull = MySQL_Query ($dotaz);
		
		/* start */
		$start = $_POST['posunStart'];
		if ($_POST['posun'] == '>') $start += $this->branNaStranku;
		if ($_POST['posun'] == '>>') $start = MySQL_Num_Rows($queryFull) - $this->branNaStranku;
		if ($_POST['posun'] == '<') $start -= $this->branNaStranku;
		if ($_POST['posun'] == '<<') $start = 0;
		$start = max (0, min(MySQL_Num_Rows($queryFull) - $this->branNaStranku, $start));
		
		$query = MySQL_Query ($dotaz." LIMIT $start, {$this->branNaStranku}");
		/*foreach ($_POST as $key => $value)
			echo htmlspecialchars('<input type="hidden" name="'.$key.'" value="\'.$_POST[\''.$key.'\'].\'">')."<br />";*/
		echo '
		<form method="post" action="index.php">
		<table width="100%">
		<tr>
			<td>
				Celkem: '.cislo(MySQL_Num_Rows($queryFull) ? MySQL_Num_Rows($queryFull) : 0).' záznam(ù),
				zobrazuji: '.cislo($start + 1).' - '.cislo(MySQL_Num_Rows($query) ? $start + MySQL_Num_Rows($query) : $start + 0).'
			</td>
			<td style="text-align: right;">
				<input type="hidden" name="posunStart" value="'.$start.'">
				<input type="submit" class="submit" value="&lt;&lt;" name="posun">
				<input type="submit" class="submit" value="&lt;" name="posun">
				<input type="submit" class="submit" value="&gt;" name="posun">
				<input type="submit" class="submit" value="&gt;&gt;" name="posun">
				
				<input type="hidden" name="akce" value="'.$_REQUEST['akce'].'">
				<input type="hidden" name="brana_typ" value="'.$_POST['brana_typ'].'">
				<input type="hidden" name="brana_cislo" value="'.$_POST['brana_cislo'].'">
				<input type="hidden" name="brana_presvedceniD" value="'.$_POST['brana_presvedceniD'].'">
				<input type="hidden" name="brana_presvedceniZ" value="'.$_POST['brana_presvedceniZ'].'">
				<input type="hidden" name="brana_presvedceniN" value="'.$_POST['brana_presvedceniN'].'">
				<input type="hidden" name="vek_typ" value="'.$_POST['vek_typ'].'">
				<input type="hidden" name="vek_id" value="'.$_POST['vek_id'].'">
				<input type="hidden" name="povolani_typ" value="'.$_POST['povolani_typ'].'">
				<input type="hidden" name="povolani_amazonka" value="'.$_POST['povolani_amazonka'].'">
				<input type="hidden" name="povolani_vedma" value="'.$_POST['povolani_vedma'].'">
				<input type="hidden" name="povolani_mag" value="'.$_POST['povolani_mag'].'">
				<input type="hidden" name="povolani_klerik" value="'.$_POST['povolani_klerik'].'">
				<input type="hidden" name="povolani_druid" value="'.$_POST['povolani_druid'].'">
				<input type="hidden" name="povolani_theurg" value="'.$_POST['povolani_theurg'].'">
				<input type="hidden" name="povolani_iluzionista" value="'.$_POST['povolani_iluzionista'].'">
				<input type="hidden" name="povolani_alchymista" value="'.$_POST['povolani_alchymista'].'">
				<input type="hidden" name="povolani_valecnik" value="'.$_POST['povolani_valecnik'].'">
				<input type="hidden" name="povolani_hranicar" value="'.$_POST['povolani_hranicar'].'">
				<input type="hidden" name="povolani_nekromant" value="'.$_POST['povolani_nekromant'].'">
				<input type="hidden" name="povolani_barbar" value="'.$_POST['povolani_barbar'].'">
				<input type="hidden" name="ztratyU_typ" value="'.$_POST['ztratyU_typ'].'">
				<input type="hidden" name="ztratyU_ztraty" value="'.$_POST['ztratyU_ztraty'].'">
				<input type="hidden" name="ztratyO_typ" value="'.$_POST['ztratyO_typ'].'">
				<input type="hidden" name="ztratyO_ztraty" value="'.$_POST['ztratyO_ztraty'].'">
				<input type="hidden" name="sila_typ" value="'.$_POST['sila_typ'].'">
				<input type="hidden" name="sila_value" value="'.$_POST['sila_value'].'">
				<input type="hidden" name="ot_typ" value="'.$_POST['ot_typ'].'">
				<input type="hidden" name="ot_value" value="'.$_POST['ot_value'].'">
				<input type="hidden" name="stit_typ" value="'.$_POST['stit_typ'].'">
				<input type="hidden" name="stit_value" value="'.$_POST['stit_value'].'">
				<input type="hidden" name="seradit_podleCeho1" value="'.$_POST['seradit_podleCeho1'].'">
				<input type="hidden" name="seradit_ascDesc1" value="'.$_POST['seradit_ascDesc1'].'">
				<input type="hidden" name="seradit_podleCeho2" value="'.$_POST['seradit_podleCeho2'].'">
				<input type="hidden" name="seradit_ascDesc2" value="'.$_POST['seradit_ascDesc2'].'">
				<input type="hidden" name="seradit_podleCeho3" value="'.$_POST['seradit_podleCeho3'].'">
				<input type="hidden" name="seradit_ascDesc3" value="'.$_POST['seradit_ascDesc3'].'">
				<input type="hidden" name="hledej" value="'.$_POST['hledej'].'">
			</td>
		</tr>
		</table>
		';
		
		echo '
		<table class="searchResult" cellspacing="1" width="100%">
		<tr>
			<td>No.</td>
			<td>
				Vìk<br />';
		$this->help ('listVek');
		echo '
			</td>
			<td>
				Datum';
		$this->help ('listDatum');
		echo '
			</td>
			';
		if ($privacy) {
			echo '
			<td>
				Privacy';
			$this->help ('listPrivacy');
			echo '
			</td>';
		}
		echo '
			<td class="right">
				Èíslo';
		$this->help ('listCislo');
		echo '
			</td>
			<td class="right">
				D/Z/N';
		$this->help ('listDZN');
		echo '
			</td>
			<td class="right">
				Síla';
		$this->help ('listSila');
		echo '
			</td>
			<td class="right">
				Štít';
		$this->help ('listStit');
		echo '
			</td>
			<td class="right">
				Povolání';
		$this->help ('listPovolani');
		echo '
			</td>
			<td class="right">
				OT';
		$this->help ('listOT');
		echo '
			</td>
			<td class="right">
				Ztráty';
		$this->help ('listZtraty');
		echo '
			</td>
			<td>
				Brána';
		$this->help ('listBrana');
		echo '
			</td>
			<td class="right">
				Pøidal';
		$this->help ('listPridal');
		echo '
			</td>'.($this->user->status() >= 2 ? 
      '<td class="right">U</td>'
      : '').'
		</tr>';
		if (!MySQL_Num_Rows ($query))
			echo '
			<tr>
				<td colspan="13" align="center">
					Žádné odpovídající záznamy
				</td>
			</tr>';
		else {
			$i = $start;
			while ($brana = MySQL_Fetch_Array ($query)) {
			 	$i ++;
				echo '
				<tr>
					<td>
						'.$i.'.
					</td>
					<td>
						'.$brana['jmeno'].'
					</td>
					<td>
						'.date (/*"d.m.y (H:i)"*/"d.m.y", $brana['datum']).'
					</td>
					'.($privacy ? '
					<td>
						'.($brana['privacy'] == 0 ? 'Veøejná' : ($brana['privacy'] == 1 ? 'Anonymní' : 'Skrytá')).'
					</td>' : '').'	
					<td class="right">
						'.($brana['cisloBrany'] < 9 ? "br{$brana['cisloBrany']}na" : "Mìsto").'
					</td>
					<td class="right">
						'.($brana['presvedceni'] == 'D' ? "Dobré" : ($brana['presvedceni'] == 'Z' ? "Zlé" : "Neutrální")).'
					</td>
					<td class="right">
						'.cislo($brana['sila']).'
					</td>
					<td class="right">
						'.($brana['stit'] ? $brana['stit'] : 'N/A').'
					</td>
					<td class="right">
						'.$brana['povolani'].'
					</td>
					<td class="right">
						'.cislo($brana['OT']).'
					</td>
					<td class="right">
						'.cislo($brana['ztraty_utok'], 2).' % : '.cislo($brana['ztraty_obrana'], 2).' %
					</td>
					<td width="1">
						<a href="'.$this->skript.($this->akce ? "?akce=$this->akce&amp;show={$brana['ID']}" : "?show={$brana['ID']}").'"><img src="img/brana.gif" height="23" width="37" alt="BRANA"></a>
					</td>
					<td class="right">
						';
				$brana_static = new Branka ($this->user, null, null, null);
				echo $brana_static->autor($brana['ID']);
				echo '
					</td>'.($this->user->status() >= 2 ? 
          '<td class="right"><a href="'.$this->skript.($this->akce ? "?akce=$this->akce&amp;show={$brana['ID']}" : "?akce=vlastni&amp;show={$brana['ID']}").'">U</a></td>'
          : '').'
				</tr>
				';
			}
		}
		echo' 
		</table>
		</form>';
	}

	function vykresliHledaciTabulku () {
		echo '
		Zde si mùžete vyhledat brány pro Meliorannis podle Vámi zadaných kritérií:
		<form action="'.$this->skript.'" method="post" name="search">
			
			<table>
				<tr>
					<td><input type="hidden" name="akce" value="'.$this->akce.'">
            Brána ';
		$this->help('hledBrana');
		echo
					'</td>
					<td>
						<select class="typ" name="brana_typ">
						   	<option value="eq"'.($_COOKIE['hledani_brana_typ'] == 'eq' ? ' selected' : '').'>=</option>
						   	<option value="lt"'.($_COOKIE['hledani_brana_typ'] == 'lt' ? ' selected' : '').'>&lt;</option>
								<option value="let"'.($_COOKIE['hledani_brana_typ'] == 'let' ? ' selected' : '').'>&lt;=</option>
						   	<option value="gt"'.($_COOKIE['hledani_brana_typ'] == 'gt' ? ' selected' : '').'>&gt;</option>
								<option value="get"'.(!$_COOKIE['hledani_brana_typ'] || $_COOKIE['hledani_brana_typ']=='get' ? ' selected' : '').'>&gt;=</option>
					   </select>				   
					</td>
					<td>
						<select class="select" name="brana_cislo">
				      	<option value="1"'.($_COOKIE['hledani_brana_cislo'] == '1' ? ' selected' : '').'>br1na</option>
				      	<option value="2"'.($_COOKIE['hledani_brana_cislo'] == '2' ? ' selected' : '').'>br2na</option>
								<option value="3"'.($_COOKIE['hledani_brana_cislo'] == '3' ? ' selected' : '').'>br3na</option>
								<option value="4"'.($_COOKIE['hledani_brana_cislo'] == '4' ? ' selected' : '').'>br4na</option>
								<option value="5"'.($_COOKIE['hledani_brana_cislo'] == '5' ? ' selected' : '').'>br5na</option>
								<option value="6"'.($_COOKIE['hledani_brana_cislo'] == '6' ? ' selected' : '').'>br6na</option>
								<option value="7"'.($_COOKIE['hledani_brana_cislo'] == '7' ? ' selected' : '').'>br7na</option>
								<option value="8"'.($_COOKIE['hledani_brana_cislo'] == '8' ? ' selected' : '').'>br8na</option>
					      <option value="9"'.($_COOKIE['hledani_brana_cislo'] == '9' ? ' selected' : '').'>mìsto</option>
				      </select>
            <input class="checkbox" type="checkbox" name="brana_presvedceniD" id="presvedceniD" checked><label for="presvedceniD">D</label>
            <input class="checkbox" type="checkbox" name="brana_presvedceniZ" id="presvedceniZ" checked><label for="presvedceniZ">Z</label>
            <input class="checkbox" type="checkbox" name="brana_presvedceniN" id="presvedceniN" checked><label for="presvedceniN">N</label>
					</td>
				</tr>
				<tr>
					<td>Vìk';
		$this->help('hledVek');			
		echo
					'</td>
					<td>
						<select class="typ" name="vek_typ">
						   	<option value="eq">=</option>
						   	<option value="gt">&lt;</option>
								<option value="get" selected>&lt;=</option>
						   	<option value="lt">&gt;</option>
								<option value="let">&gt;=</option>
								<!-- POZOR - OBRACENE POPISKY, KVULI PRIORITE, KTERA JE U NOVYCH VEKU 1 A STARYCH 999 -->
					   	</select>
			   
					</td>
					<td>
						<select class="select" name="vek_id">
					      	';
			$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
			while ($vek = MySQL_Fetch_Array ($veky))
				echo '<option value="'.$vek['ID'].'">'.$vek['jmeno'].' ('.$vek['title'].')</option>';
								echo '
				      	</select>
					</td>
				</tr>
				<tr>
					<td>Povolání';
		$this->help('hledPovolani');			
		echo
					'</td>
					<td>
						<select class="typ" name="povolani_typ">
						   	<option value="eq">=</option>
						   	<option value="neq">&ne;</option>
					   </select>  
					</td>
					<td>
						<table border=0>
							<tr>
								<td><input class="checkbox" type="checkbox" name="povolani_mag" id="povolani_mag" checked><label for="povolani_mag">Mág</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_klerik" id="povolani_klerik" checked><label for="povolani_klerik">Klerik</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_druid" id="povolani_druid" checked><label for="povolani_druid">Druid</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_theurg" id="povolani_theurg" checked><label for="povolani_theurg">Theurg</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_iluzionista" id="povolani_iluzionista" checked><label for="povolani_iluzionista">Iluzionista</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_vedma" id="povolani_vedma" checked><label for="povolani_vedma">Vìdma</label></td>
								<!-- <td><input class="checkbox" type="checkbox" id="povolani_vse" checked><label for="povolani_vse">V‘e</label></td> -->
							</tr>
							<tr>
								<td><input class="checkbox" type="checkbox" name="povolani_alchymista" id="povolani_alchymista" checked><label for="povolani_alchymista">Alchymista</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_valecnik" id="povolani_valecnik" checked><label for="povolani_valecnik">Váleèník</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_hranicar" id="povolani_hranicar" checked><label for="povolani_hranicar">Hranièáø</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_nekromant" id="povolani_nekromant" checked><label for="povolani_nekromant">Nekromant</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_barbar" id="povolani_barbar" checked><label for="povolani_barbar">Barbar</label></td>
								<td><input class="checkbox" type="checkbox" name="povolani_amazonka" id="povolani_amazonka" checked><label for="povolani_amazonka">Amazonka</label></td>
								<!-- <td><input class="checkbox" type="checkbox" id="povolani_nic"><label for="povolani_nic">Nic</label></td> -->
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>Ztraty útoèníka';
		$this->help('hledZtratyU');			
		echo
					'</td>
					<td>
						<select class="typ" name="ztratyU_typ">
						   	<option value="eq">=</option>
						   	<option value="lt">&lt;</option>
								<option value="let">&lt;=</option>
						   	<option value="gt">&gt;</option>
								<option value="get" selected>&gt;=</option>
					   	</select>   
					</td>
					<td>
						<input type="text" class="edit right" name="ztratyU_ztraty" value="0">  %
					</td>
				</tr>
				<tr>
					<td>Ztraty obránce';
		$this->help('hledZtratyO');			
		echo
					'</td>
					<td>
						<select class="typ" name="ztratyO_typ">
						   	<option value="eq">=</option>
						   	<option value="lt">&lt;</option>
								<option value="let">&lt;=</option>
						   	<option value="gt">&gt;</option>
								<option value="get" selected>&gt;=</option>
					   	</select>   
					</td>
					<td>
						<input type="text" class="edit right" name="ztratyO_ztraty" value="0">  %
					</td>
				</tr>
				<tr>
					<td>Síla';
		$this->help('hledSila');			
		echo
					'</td>
					<td>
						<select class="typ" name="sila_typ">
						   	<option value="eq">=</option>
						   	<option value="lt">&lt;</option>
								<option value="let">&lt;=</option>
						   	<option value="gt">&gt;</option>
								<option value="get" selected>&gt;=</option>
					   </select>   
					</td>
					<td>
						<input type="text" class="edit right" name="sila_value" value="0">  
					</td>
				</tr>
				<tr>
					<td>OT';
		$this->help('hledOT');			
		echo
					'</td>
					<td>
						<select class="typ" name="ot_typ">
						   	<option value="eq">=</option>
						   	<option value="lt">&lt;</option>
								<option value="let">&lt;=</option>
						   	<option value="gt">&gt;</option>
								<option value="get" selected>&gt;=</option>
					   </select>   
					</td>
					<td>
						<input type="text" class="edit right" name="ot_value" value="0">  
					</td>
				</tr>
				<tr>
					<td>Štít';
		$this->help('hledStit', 'WIDTH, 300');			
		echo
					'</td>
					<td>
						<select class="typ" name="stit_typ">
						   	<option value="eq_and">= AND</option>
								<option value="eq_or">= OR</option>
						   	<option value="neq">&ne;</option>
					   </select>  
					</td>
					<td>
						<input type="text" class="edit" name="stit_value" value="">
					</td>
				</tr>
				<tr>
					<td>Seøadit';
		$this->help('hledSeradit');			
		echo
					'</td>
					<td class="right">
						1.
					</td>
					<td>
						<select class="select" name="seradit_podleCeho1">
					      	<option value="cisloBrany">Èísla brány</option>
					      	<option value="sila">Síly útoèníka</option>
					      	<option value="ztraty_utok">Ztrát útoèníka</option>
								<option value="ztraty_obrana">Ztrát brány</option>
								<option value="povolani">Povolání</option>
								<option value="OT">OT</option>
								<option value="datum">Data</option>		
				      </select>
						<select class="select" name="seradit_ascDesc1">
					      	<option value="ASC">Vzestupnì</option>
					      	<option value="DESC">Sestupnì</option>
				      </select><br />
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td class="right">
						2.
					</td>
					<td>
						<select class="select" name="seradit_podleCeho2">
								<option value="cisloBrany">Èísla brány</option>
					      	<option value="sila" selected>Síly útoèníka</option>
					      	<option value="ztraty_utok">Ztrát útoèníka</option>
								<option value="ztraty_obrana">Ztrát brány</option>
								<option value="povolani">Povolání</option>
								<option value="OT">OT</option>
								<option value="datum">Data</option>								
				      </select>
						<select class="select" name="seradit_ascDesc2">
					      	<option value="ASC">Vzestupnì</option>
					      	<option value="DESC">Sestupnì</option>
				      </select><br />
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td class="right">
						3.
					</td>
					<td>
						<select class="select" name="seradit_podleCeho3">
					      	<option value="cisloBrany">Èísla brány</option>
					      	<option value="sila">Síly útoèníka</option>
					      	<option value="ztraty_utok">Ztrát útoèníka</option>
								<option value="ztraty_obrana">Ztrát brány</option>
								<option value="povolani">Povolání</option>
								<option value="OT">OT</option>
								<option value="datum" selected>Data</option>		
				      </select>
						<select class="select" name="seradit_ascDesc3">
					      	<option value="ASC">Vzestupnì</option>
					      	<option value="DESC">Sestupnì</option>
				      </select><br />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<input class="submit" type="button" value="Reset" onClick="
							document.search.brana_cislo.selectedIndex = 0;
							
							document.search.vek_id.selectedIndex = 0;
						
							document.search.brana_presvedceniD.checked = true;
							document.search.brana_presvedceniZ.checked = true;
							document.search.brana_presvedceniN.checked = true;
							
							document.search.povolani_mag.checked = true;
							document.search.povolani_klerik.checked = true;
							document.search.povolani_druid.checked = true;
							document.search.povolani_theurg.checked = true;
							document.search.povolani_iluzionista.checked = true;
							document.search.povolani_alchymista.checked = true;
							document.search.povolani_valecnik.checked = true;
							document.search.povolani_hranicar.checked = true;
							document.search.povolani_nekromant.checked = true;
							document.search.povolani_barbar.checked = true;
							document.search.povolani_amazonka.checked = true;
							document.search.povolani_vedma.checked = true;
							
							document.search.ztratyU_ztraty.value = 0;
							document.search.ztratyO_ztraty.value = 0;
							document.search.sila_value.value = 0;
							document.search.ot_value.value = 0;
							document.search.stit_value.value = \'\';		
							
							document.search.seradit_podleCeho1.selectedIndex = 0;
							document.search.seradit_ascDesc1.selectedIndex = 0;
							document.search.seradit_podleCeho2.selectedIndex = 1;
							document.search.seradit_ascDesc2.selectedIndex = 0;
							document.search.seradit_podleCeho3.selectedIndex = 6;
							document.search.seradit_ascDesc3.selectedIndex = 0;
							
							
							document.search.brana_typ.selectedIndex = 4;
							document.search.vek_typ.selectedIndex = 2;
							document.search.povolani_typ.selectedIndex = 0;
							document.search.ztratyU_typ.selectedIndex = 4;
							document.search.ztratyO_typ.selectedIndex = 4;
							document.search.sila_typ.selectedIndex = 4;
							document.search.ot_typ.selectedIndex = 4;
							document.search.stit_typ.selectedIndex = 0;
							">
						<input class="submit" name="hledej" type="submit" value="Hledej">
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
