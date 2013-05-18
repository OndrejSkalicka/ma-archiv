<?php
class Boj {
	var $text, $rozlozeno = 0, $presvedceni,
	    $ot = '<< Chybn� vstup >>',
		 $rozlozenyBoj = '', $rozlozenyBojChyby = null,
		 $kouzla,
		 $w = '����������������������������؊��abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ�����_.',
		 $ztraty = array ("utok" => 0, "obrana" => 0),
		 $indexy = array ("tu" => 0, "obrana" => 1, "pocetU" => 2, "jmenoU" => 3, "sipka" => 4, "pocetO" => 5, "jmenoO" => 6, "zabito" => 7, "zbyva" => 8, "celkem" => 9, "zraneno" => 10, "brneni" => 11);
	
	
	/* pokud je $boj_id, tak je to ID radku v databazi, pokud je to string, tak se z toho rozklada.. */
	function Boj ($boj_id) {
		if (is_numeric($boj_id)) $this->nactiDatazDB ($boj_id);
		else $this->text = $boj_id;
	}
	
	function nactiDatazDB ($id) {
		if (!$dataSource = MySQL_Query ("SELECT * FROM `archiv_branek` WHERE `ID` = '$id'")) return 0;
		if (!$data = MySQL_Fetch_Array ($dataSource)) return 0;
		$this->ot = $data['OT'];
		$this->presvedceni = $data['presvedceni'];
		$this->ztraty = array ("utok" => $data['ztraty_utok'], "obrana" => $data['ztraty_obrana']);
		$this->rozlozenyBoj = $data['boj'];
		$this->kouzla = $data['kouzla'];
		
		return 1;
	}
	
	function setText ($s) {
		$this->text = $s;
	}
	
	function vlastnost ($row ,$s) {
		$temp = explode ('|', $row);
		return $temp[$this->indexy[$s]];
	}
	
	function kouzlo ($tu, $utok_obrana) {
		$w = $this->w;
		if (preg_match('/'.$tu.'\|'.$utok_obrana.'\|(['.$w.'\s]+)\|(.*)/i', $this->kouzla, $matches)) {
			return array ('jmeno' => $matches[1], 'efekt' => $matches[2]);
		}
		return null;
	}
	
	function vykresliPribliznouTabulkuJednotek ($text = null, $help = null) {
		if ($text) echo $text.': <span class="povinne">*</span>';
		if ($help) $this->help($help);
		
		echo '<div class="nahled">
		<table>';
		
		if ($this->rozlozenyBoj) {
			$tu = 0;
			$temp = explode ("\n", trim($this->rozlozenyBoj));
			foreach ($temp as $jednotka) {
				if (!preg_match('/^(\d)\|(.*)$/', $jednotka, $match)) next;
				if ($match[1] > $tu) {
					$tu = $match[1];
					echo "____ TU: $tu ____<br /><br />";
					$kouUt = $this->kouzlo ($tu, 'u');
					if (!is_null($kouUt)) 
						echo "�to�n�k ses�l� {$kouUt['jmeno']}.<br /><br />";
					$kouOb = $this->kouzlo ($tu, 'o');
					if (!is_null($kouOb)) 
						echo "Obr�nce ses�l� {$kouOb['jmeno']}.<br /><br />";
				}
				echo max($this->vlastnost($jednotka, 'pocetU'),1)." x ".$this->vlastnost($jednotka, 'jmenoU')." ".$this->vlastnost($jednotka, 'sipka')."> ".max($this->vlastnost($jednotka, 'pocetO'),1)." x ".$this->vlastnost($jednotka, 'jmenoO').", zabito ".$this->vlastnost($jednotka, 'zabito')."<br />";
			}
		} else {
			echo "<< Chybn� vstup >>";
		}
		echo '
			</table>
		</div> <!-- // -->';
	}
	
	function vykresliTabulkuJednotek ($kolo) {
		/* nezamenovat $kolo (ktere kolo se ma vykreslit) a $tu (ktere kolo prave probiram) */
		$retString = '';
		
		if ($this->rozlozenyBoj) {
			$tu = 0;
			$temp = explode ("\n", trim($this->rozlozenyBoj));
			foreach ($temp as $jednotka) {
				if (!preg_match('/^(\d)\|(.*)$/', $jednotka, $match)) next;
				if ($match[1] > $tu) {
					$tu = $match[1];
					
					if ($tu == $kolo) { /* zacatek aktivniho kola */
					/* kouzla */
					$kouUt = $this->kouzlo ($tu, 'u');
					if (!is_null($kouUt)) 
						$retString .=  "�to�n�k ses�l� {$kouUt['jmeno']}.<br />{$kouUt['efekt']}<br /><br />";
					$kouOb = $this->kouzlo ($tu, 'o');
					if (!is_null($kouOb)) 
						$retString .=  "Obr�nce ses�l� {$kouOb['jmeno']}.<br />{$kouOb['efekt']}<br /><br />";
					}
				}
				if ($tu == $kolo) { /* jsem ve spravnem kole */
					$string = '';
					if ($this->vlastnost($jednotka, 'obrana')) $string .= '<span class="cs">Obrana:<br />';
					if ($this->vlastnost($jednotka, 'pocetU')) $string .= htmlspecialchars($this->vlastnost($jednotka, 'pocetU')." x ");
																			 $string .= htmlspecialchars($this->vlastnost($jednotka, 'jmenoU')." ".$this->vlastnost($jednotka, 'sipka')."> ");
					if ($this->vlastnost($jednotka, 'pocetO')) $string .= htmlspecialchars($this->vlastnost($jednotka, 'pocetO')." x ");
					if ($this->vlastnost($jednotka, 'jmenoO')) { /* ma cil */
						$string .= htmlspecialchars($this->vlastnost($jednotka, 'jmenoO'))."<br />";
						$string .= htmlspecialchars('zabito: '.$this->vlastnost($jednotka, 'zabito').', zb�v�: '.$this->vlastnost($jednotka, 'zbyva').' / '.$this->vlastnost($jednotka, 'celkem'));
						if ($this->vlastnost($jednotka, 'zraneno') !== '') $string .= htmlspecialchars(' zran�no: '.$this->vlastnost($jednotka, 'zraneno')).' %<br />'; 
							else $string .= '<br />';
						if ($this->vlastnost($jednotka, 'brneni')) $string .= htmlspecialchars('brn�n� zb�v�: '.$this->vlastnost($jednotka, 'brneni')).'%<br />';
					} else { /* nema cil */
						$string .= 'Jednotka nem� ��dn� c�l';
					}
					if ($this->vlastnost($jednotka, 'obrana')) $string .= '</span>';
					$retString .= '<div class="'.($this->vlastnost($jednotka, 'sipka') != '-' ? 'utok' : 'obrana').'">
						'.$string.'</div>
						';
				}
			}
		} else {
			/*echo "<< Chybn� vstup >>";*/
			return null;
		}
		return $retString;
	}
	
	function rozklad () {
		$w = $this->w;
		$retVal = 1;
		/* TUcka */
		if (preg_match_all ('#Tah �.: (\d+) / zb�v� (\d+)#', $this->text, $match)) {
			$this->ot =  $match[1][count($match[1]) - 1];
		} else return null;
		/* presvedceni */
		require "res/presvedceni.text.php";
		$this->presvedceni = '';
		if (preg_match ('/(?:M�j pane|M� pan�|Moje pan�), za�to�ila? jste na (['.$w.']+) br�nu, jej�m� obr�ncem je (['.$w.']+) se svou dru�inou/', $this->text, $matches)) {
			if ($DPresvedceni[$matches[1]] == $matches[2]) $this->presvedceni = 'D';
			elseif ($ZPresvedceni[$matches[1]] == $matches[2]) $this->presvedceni = 'Z';
			elseif ($NPresvedceni[$matches[1]] == $matches[2]) $this->presvedceni = 'N';
		} elseif(preg_match ('/�tok na (Pandemonium|Genesis|M�sto duch�):/', $this->text, $matches)) {
			if ($matches[1] == 'Pandemonium') $this->presvedceni = 'D';
			elseif ($matches[1] == 'Genesis') $this->presvedceni = 'Z';
			elseif ($matches[1] == 'M�sto duch�') $this->presvedceni = 'N';
		} else return null;
		/* orez o zbytecnosti */
    
		$brana = '#(.*)(?:Moje pan�|M�j pane|M� pan�)(.*)dru�inou(.*)(Vyhr�la? jste|To je Va�e prohra|V�sledek bitvy je nerozhodn�)(.*)#s';
		$mesto = '#(.*)Tah �.: \d+ / zb�v� \d+\s*\n(Ses�l�n� ['.$w.'\s]+? bylo dokon�eno\s*\n.*\n)?zl: [+-]?\d+, mn: [+-]?\d+, pp: [+-]?\d+\s*\n((?:Byl(?:[aoy]) postaven(?:[aoy])|Stavitel� postavili) \d+ budov(?:[uy])?\.? / celkem \d+ x ['.$w.'\s]+\s*\n)?(Do arm�dy jste narekrutovala? \d+ x ['.$w.'\s]+, celkem \d+\s*\nCena za jednotky: \d+(\.\d+)?, \d+(\.\d+)?, \d+(\.\d+)?\s*\n)?(.*['.$w.'\s]+ (?:p�i�el|p�i�la) o -?\d+(\.\d+)?% s�ly arm�dy, ['.$w.'\s]+ o -?\d+(\.\d+)?% s�ly arm�dy\.).*#s';
		if (preg_match ($brana, $this->text)) {
			$boj = trim (preg_replace ($brana, '$3', $this->text));
		} elseif (preg_match ($mesto, $this->text, $x)) {
		  $boj = trim (preg_replace ($mesto, '$8', $this->text));
		} else return null;
		
		/* ted projedu trojradek po trojradku a kouknu, jestli je na nem neco z boje */
		$tu = 1;
		$result = null;
		//				 1-2.obrana         3-4. pocetU 5. jmenoU   6. =>|-> 8-9.pocetO 10. jmenoO              11. zabito    12. zbyva 13.zbyva Z     14-15. zraneno  16-17. brneni
		//$pattern = '#^((obrana):\s*\r\n)?((\d+) x )?([\w][\w ]+) ([-=])> ((\d+) x )?([\w ]+?)\s*\r\nzabito: (\d+), zb�v�: (\d+) / (\d+)( zran�no: (-?\d+)%)?\s*\r\n(brn�n� zb�v�: (-?\d+)%\s*)?\r\n#';
		/*$w = '\w';
		$w = '��������������������������؊��abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ�����';*/
		
		$pattern = '#^((obrana):\s*\n)?((\d+) x )?(['.$w.']['.$w.' ]+) ([-=])> (Jednotka nem� ��dn� c�l\s*|((\d+) x )?(['.$w.' ]+?)\s*\nzabito: (\d+), zb�v�: (\d+) / (\d+)( zran�no: (-?\d+)%)?\s*\n)(brn�n� zb�v�: (-?\d+)%\s*)?\r\n#';
		//������������������������؊��
		
		/* jmeno utocnika/obrance pro kouzla */
		$mestoZtratyPattern = '/(['.$w.'\s]+) (?:p�i�el|p�i�la) o -?\d+(\.\d+)?% s�ly arm�dy, (['.$w.'\s]+) o -?\d+(\.\d+)?% s�ly arm�dy\./';
		if (preg_match ($mestoZtratyPattern, $boj, $utok_obrana)) {
			$utok_jmeno = trim($utok_obrana[1]);
			$obrana_jmeno = trim($utok_obrana[3]);
		}
		
		
		$count = 0;
		for ($tu = 1; $tu <= 4; $tu ++) {
			/* kouzla */
			$kouzloU = '/^'.$utok_jmeno.' ses�l� "(['.$w.'\s]+)"\s*\n((Magove jsou viditelne nervozni - neco ocividne neni v poradku\.\s*\n)?Sesl�n� nebylo dokon�eno �sp�n�, protivn�k ses�lan� kouzlo magicky zastavil\.\s*\n|((?:(Kouzlo bylo magickou ochranou t�m�� odvr�ceno\.|Kouzlo bylo oslabeno magickou ochranou\.)\s*\n)?(.*\n.*\n)))?/';
			$kouzloO = '/^'.$obrana_jmeno.' ses�l� "(['.$w.'\s]+)"\s*\n((Magove jsou viditelne nervozni - neco ocividne neni v poradku\.\s*\n)?Sesl�n� nebylo dokon�eno �sp�n�, protivn�k ses�lan� kouzlo magicky zastavil\.\s*\n|((?:(Kouzlo bylo magickou ochranou t�m�� odvr�ceno\.|Kouzlo bylo oslabeno magickou ochranou\.)\s*\n)?(.*\n.*\n)))?/';
			if (preg_match ($kouzloU, $boj, $matches)) {
				$boj = trim(preg_replace ($kouzloU, '', $boj));
				$this->kouzla .= "$tu|U|{$matches[1]}|".preg_replace("(\r?\n\r?)", '<br />',  trim($matches[2]))."\n";
			}
			if (preg_match ($kouzloO, $boj, $matches)) {
				$boj = trim(preg_replace ($kouzloO, '', $boj));
				$this->kouzla .= "$tu|O|{$matches[1]}|".preg_replace("(\r?\n\r?)", '<br />',  trim($matches[2]))."\n";
			}
			
			/* utok jednotky */
			while (preg_match ($pattern, $boj, $match)) { //jsem v utoku nejake jednotky
				$result[$tu][] = "{$match[2]}|{$match[4]}|{$match[5]}|{$match[6]}|{$match[9]}|{$match[10]}|{$match[11]}|{$match[12]}|{$match[13]}|{$match[15]}|{$match[17]}"; //ulozim si boj
				$boj = preg_replace ($pattern, '', $boj);		
			}
			$boj = trim( preg_replace ('/^\s+/', '', $boj));
		}
		
		/* orezani ztrat */
		/*$boj = trim(preg_replace ('/Va�e ztr�ty jsou -?\d+(\.\d+)?% s�ly arm�dy, obr�nce -?\d+(\.\d+)?% s�ly arm�dy/', '', $boj));
		$boj = trim(preg_replace ($mestoZtratyPattern, '', $boj));
		if ($boj != '')
			$this->rozlozenyBojChyby = 'Chybne rozlozeno';*/
		
		// nemazat, pouziva se na rozklad az budu delat mesta
		$debug = 0;
		if ($debug) {
			echo "<br />preciste start --&gt;|".nl2br($boj)."|&lt;-- precise end<br />";
			foreach ($result as $kolo => $tu_jednotky) {
				echo "TU: $kolo<br />";
				foreach ($tu_jednotky as $jednotka) echo "&nbsp;&nbsp;&nbsp;&nbsp;$jednotka<br />";
			}
			echo "<br />kouzla:";
			print_r ($this->kouzla);
		}
		
	  /* PREVOD BOJE Z POLE POLI NA FORMAT KEREJ SE POUZIVA V DB */
		$this->rozlozenyBoj = '';
		if (is_array($result)) foreach ($result as $kolo => $tu_jednotky) {
			foreach ($tu_jednotky as $jednotka) if (trim($jednotka)) {
				$this->rozlozenyBoj.= "$kolo|$jednotka\n";
			}
		}
		
		$ztratyBrana = '/Va�e ztr�ty jsou (\d+(\.\d+)?)% s�ly arm�dy, obr�nce (\d+(\.\d+)?)% s�ly arm�dy/';
		$ztratyMesto = '/'.$utok_jmeno.' (?:p�i�el|p�i�la) o (\d+(\.\d+)?)% s�ly arm�dy, '.$obrana_jmeno.' o (\d+(\.\d+)?)% s�ly arm�dy\./';
		
    if (preg_match ($ztratyBrana, $boj, $ztraty) || preg_match ($ztratyMesto, $boj, $ztraty)) {
			$this->ztraty['utok'] = $ztraty[1];
			$this->ztraty['obrana'] = $ztraty[3];
			$boj = trim(preg_replace ($ztratyBrana, '', $boj));
			$boj = trim(preg_replace ($ztratyMesto, '', $boj));
			if ($boj != '')
				$this->rozlozenyBojChyby = 'Chybne rozlozeno';
		} else return null;
		
		$this->rozlozeno = $retVal;
		
		return $retVal;
	}
	
	function zapisDataDoDB ($id) {
		if (MySQL_Query ("UPDATE `archiv_branek` SET `boj` = '{$this->rozlozenyBoj}',
																	`presvedceni` = '{$this->presvedceni}',
																	`kouzla` = '{$this->kouzla}'
							   WHERE `ID` = '$id'"))
			return 1;
		
		return 0;
	}
	
	function help ($key) {
		require "res/help.text.php";
		$temp = new Help($key,$texty[$key][0],$texty[$key][1]);
		$temp->vytiskni();
	}
}
?>