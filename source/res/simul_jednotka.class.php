<?php
class Simul_Jednotka {	
	var $jmeno, $dmg, $zvt, $brn, $brn_zbyva, $ini, $typ, $druh, $phb, $pwr, $pocet, $pocet_max, 
	$cs, $xp, $hrac, $error, $ID, $overflow,
	$utok_na = "dmg";   //hrac - 1 utoci, 2 brani
	
	function Simul_Jednotka ($jmeno, $pocet, $xp, $hrac, $vek, $branka = 0)	//nacte info o jednotce z DB
	{
		$this->error = 0;
		
		$jmeno = trim($jmeno);
		$xp = trim ($xp);
		$pocet = trim ($pocet);
		
		$this->xp = max (min ($xp,100),1);
		$this->hrac = $hrac;
		$this->pocet = $this->pocet_max = $pocet;
		$this->overflow = 0;
		
		$unit = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '$jmeno' ".($branka != 0 /* vstup z brany */ ? "AND `brankar` = '1' AND `ID_veky` = '$vek'" : "AND `brankar` = '0'"));
		
		if (MySQL_Num_Rows ($unit) == 0) return 0;
		
		$unit_db = MySQL_Fetch_Array ($unit);
		
		$this->jmeno = $unit_db['jmeno'];
		$this->dmg = $unit_db['dmg'];
		$this->zvt = $unit_db['zvt'];
		$this->brn = $unit_db['brn'];
		$this->ini = $unit_db['ini'];
		$this->typ = $unit_db['typ'];
		$this->druh = $unit_db['druh'];
		$this->phb = $unit_db['phb'];
		$this->pwr = $unit_db['pwr'];
		$this->ID = $unit_db['ID'];

		$this->brn_zbyva = $this->brn;
		
		return 1;
	}
	
	function StitovaniNaDmg ()
	{
		return $this->pocet*$this->dmg;
	}
	
	function ZjistiVhodnyCil ($jednotky) //strana je strana utocnika, muze_na_letce - 1 ano, 0 ne
	{
		$max = 0;
		$jednotka = -1;
		foreach ($jednotky as $key => $value)
		{
			if (($value->hrac != $this->hrac)&&((($this->typ == 'S') || ($this->druh == 'L'))||($value->druh!="L")))
			{
				if ($value->StitovaniNaDmg()>$max)
				{
					$max = $value->StitovaniNaDmg();
					$jednotka = $key;
				}
			}
		}
		return $jednotka;
	}
	
	function BonusZaKolo ($kolo)
	{
		switch ($kolo)
		{
			case 1:
				return 0.6;
			break;
			case 2:
				return 0.8;
			break;
			case 3:
				return 1.0;
			break;
			case 4:
				return 0.7;
			break;
			default: 
				return 1;
			break;
		}
	}
	
	function nastavCSka () {
		if ($this->xp < 70) $this->cs = 1;
		elseif ($this->xp < 95) $this->cs = 2;
		else $this->cs = 3;
	}
}
?>