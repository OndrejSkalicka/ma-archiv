<?php
class Simul {
	var $branka, $vek, $jednotky,
		/* bojove konstanty */
		$ARMOR_ABSORB = 0.35, $DAM_MOD = 0.65;

	function Simul () {
		
	}
	
	function nastavParametry ($branka, $utokID, $vek) {
		$this->branka = $branka;
		$this->vek = $vek;
		
		$branky = MySQL_Query ("SELECT * FROM `Branky` WHERE `cislo` = '{$this->branka}' AND `ID_veky` = '{$this->vek}'");
		if (($branky_db = MySQL_Fetch_Array ($branky)) && ($branky_db['obranci'] != ""))
		{
			$obrana = $branky_db['obranci'];
		}
		else
		{
			return 'Nemùžu najít pøíslušnou bránu v databázi<br>';
		}
		
		$utok_jednotky = $this->brankaIDtoJednotky ($utokID);
		$obrana_jednotky = preg_split("/\n/", $obrana);
		
		foreach ($utok_jednotky as $jednotka)
		{
			if ($jednotka != "")
			{
				$j = explode("|", $jednotka);
		    	$this->jednotky[] = new Simul_Jednotka($j[0],$j[1],$j[2],1, $this->vek);
			}
		}
		foreach ($obrana_jednotky as $jednotka)
		{
			if ($jednotka != "")
			{
				$j = explode("|", $jednotka);
		    	$this->jednotky[] = new Simul_Jednotka($j[0],$j[1],$j[2],2, $this->vek, $this->branka);
			}
		}
	}
	
	function brankaIDtoJednotky ($id) {
		/* prevede format Archiv_branky do formatu simulu (jednotka|pocet|xp) */
		
		if (!$hospPred = MySQL_Fetch_Array (MySQL_Query ("SELECT `pred` FROM `archiv_branek` WHERE `ID` = '$id'")))
			return null;
		
		$jednotky = preg_split("/\n/", $hospPred[0]);
		
		$retVal = null;
		foreach ($jednotky as $jednotka) {
			$temp = explode ('|', $jednotka);
			$retVal [] = $temp[0]."|".$temp[6]."|".$temp[1];
		}
		
		return $retVal;
	}
	
	function SeradDleIni ($kolo)
	{
		$ini = null;
		foreach ($this->jednotky as $key => $value) 
		{
			if (
				($value->typ == "S") // strelec
				||
				(($value->typ == "B")&&($value->phb == 3)) //phb 3
				||
				(($value->typ == "B")&&($value->phb == 2)&&($kolo>=2)) //phb2 ve druhym kole
				||
				($kolo >= 3) //treti kolo utoci kazdej
				)
			{
				$ini[$key] = max (round($value->ini*($value->hrac==2?0.85:1)), 1)+($value->xp/201);
			}
		}
		if (!is_null($ini)) arsort ($ini);
		
		return $ini;
	}
	
	function SeradDlePwr ()
	{
		$power = null;
		foreach ($vojak as $key => $value) {
			$power[$key] = $value->pwr*$value->xp*$value->pocet_max;        
		}
		arsort ($power);
		
		return $power;
	}
	
	function NastavStartKola() 
	{
		foreach ($this->jednotky as $key => $value)
		{
			$this->jednotky[$key]->turn_dam_mod = 1;
			$this->jednotky[$key]->nastavCSka();
		}
	}
	
	function odehrajKolo ($kolo) {
		$retVal = null;
		
		$this->NastavStartKola();
		$ini = $this->SeradDleIni($kolo);
		
		if (!is_null($ini))
		{		
			foreach ($ini as $idUtok => $value)
			{
				$utok = &$this->jednotky[$idUtok];
				
				if (($utok->pocet != 0)&&($utok->jmeno != ""))
				{
					$retVal .= '<div class="'.($utok->hrac == 1 ? "s_utok":"s_obrana").'">
									'.($utok->pocet > 1 ? $utok->pocet." x " : '')."{$utok->jmeno} ".($utok->hrac == 1 ? "=":"-")."> ";
					$idCil = $utok->ZjistiVhodnyCil($this->jednotky);
					if ($idCil > -1) {
						$cil = &$this->jednotky[$idCil];
					
						$retVal .= $cil->pocet." x {$cil->jmeno} <br>\n";
					
						$dmg = $utok->dmg*$utok->xp*$utok->pocet/100;
						$dmg *= $this->DAM_MOD; 
						$dmg *= $utok->BonusZaKolo($kolo);
						$dobrneni = min($dmg*$this->ARMOR_ABSORB,$cil->brn_zbyva*$cil->pocet*$cil->xp/100); 
						$dmg -= $dobrneni;
						$cil->brn_zbyva = max (0, $cil->brn_zbyva - ($dobrneni/$cil->pocet/$cil->xp*100));
						
						$killed = min (floor ($dmg / ($cil->zvt*$cil->xp/100)), $cil->pocet);
						$retVal .= "Zabito: $killed, zbývá: ".($cil->pocet - $killed)."/".$cil->pocet_max."<br>\n";
						if (round($cil->brn_zbyva) > 0)
							$retVal .= "Brnìní zbývá: ".cislo($cil->brn_zbyva/$cil->brn*100)."%<br>\n";
						
						//------- CSko ---------
						if (($utok->typ == "B")&&($cil->cs > 0))
						{
							$cil->cs --;
							$retVal .= "<div class=\"s_cs\"><br>Obrana:<br>\n".($cil->pocet > 1 ? $cil->pocet." x " : '')."{$cil->jmeno} ".($utok->hrac == 1?"-":"=")."> ";
							$retVal .= $utok->pocet."/".$utok->pocet_max." x {$utok->jmeno} <br>\n";
							
							$dmg = $cil->dmg * $cil->xp * $cil->pocet / 100 * $this->DAM_MOD;
							$dmg *= $cil->BonusZaKolo($kolo);
							
							if ($cil->typ == "B")
							{
								if ($utok->druh == "P") $dmg *= 1/3;
								if ($utok->druh == "L") $dmg *= 1/4;
							}
							if ($cil->typ == "S")
								$dmg *= 1/2;

							$dobrneni = min($dmg * $this->ARMOR_ABSORB,$utok->brn_zbyva*$utok->pocet*$utok->xp/100); 
							$dmg -= $dobrneni;
							$utok->brn_zbyva = max (0, $utok->brn_zbyva - $dobrneni/$utok->pocet/$utok->xp*100);
							
							$killed_cs = min(floor ($dmg / ($utok->zvt*$utok->xp/100)), $utok->pocet);

							$retVal .= "Zabito: $killed_cs, zbývá: ".($utok->pocet-$killed_cs)."/".$utok->pocet_max."<br>\n";
							if (round($utok->brn_zbyva) > 0)
								@$retVal .= "Brnìní zbývá: ".cislo($utok->brn_zbyva/$utok->brn*100)."%<br>\n";
							$utok->pocet -= $killed_cs;
							$retVal .= "</div> <!-- //utok/obrana-csko -->";
						} /* CSko */
						$cil->pocet -= $killed;
					} /* $idCil > -1 */
					else $retVal .= "Nemá cíl<br>\n";					
					
					$retVal .= '</div> <!-- //utok/obrana-prvo -->';
				} /* if jednotka->pocet > 0 */
			}	/* foreach jednotky */
		} /* if (!is_null(ini)).. */
		else
			$retVal .= "Žádné bojové jednotky pro toto kolo!<br>";

		
		return $retVal;
	}
	
	function vysledek () {
		$pwr[1] = 0;
		$pwr[2] = 0;
		$pwrrest[1] = 0;
		$pwrrest[2] = 0;
		foreach ($this->jednotky as $key => $value)
		{
			$pwr[$value->hrac] += $value->pwr*$value->pocet_max*$value->xp/100;
			$pwrrest[$value->hrac] += $value->pwr*$value->pocet*$value->xp/100;
		}
		
		return array ('utok' => 1 - $pwrrest[1]/$pwr[1], 'obrana' => 1 - $pwrrest[2]/$pwr[2]);
	}
}
?>