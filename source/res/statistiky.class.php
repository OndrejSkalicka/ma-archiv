<?php
class Statistiky {
	var $skript;

	function Statistiky ($skript) {
		$this->skript = $skript;
		
		
	}
	
	function stats ($co, $priorOd, $priorDo, $detail) {
		
		if ($_GET['foo'] == 'bar') throw Exception ('Bruce');
		if ($priorDo < 1) 
			$priorDo = 999;
		$retVal = null;
		switch ($co) {
			case 'povolaniSila3':
				$retVal .= $this->vygenerujTabulku('sila', $priorOd, $priorDo, $detail, $URL);
			break;
			case 'povolaniOT3':
				$retVal .= $this->vygenerujTabulku('OT', $priorOd, $priorDo, $detail, $URL);
			break;
		}
		return $retVal;
	}
	
	function statsGraf ($co, $priorOd, $priorDo, $percent = false) {
		if ($priorDo < 1) 
			$priorDo = 999;
		$retVal = null;
		switch ($co) {
			case 'povolaniSila3':
				$retVal .= $this->vygenerujGrafTabulku('sila', $priorOd, $priorDo, $percent);
			break;
			case 'povolaniOT3':
				$retVal .= $this->vygenerujGrafTabulku('OT', $priorOd, $priorDo, $percent);
			break;
		}
		return $retVal;
	}
	
	function vygenerujGrafTabulku ($co, $priorOd, $priorDo, $percent) {
		$retVal = '';
		
		/* celkem pro vsechny presvedceni */
		$presvedceni = array ('D' => "`presvedceni` = 'D'", 'Z' => "`presvedceni` = 'Z'", 'N' => "`presvedceni` = 'N'");
		
    $rValues = array ();
    $max = array (1 => null, null, null, null, null, null, null, null, null);
    foreach ($presvedceni as $pKey => $pValue) {		  
			for ($i = 1; $i <= 9; $i ++ ) {
				$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT AVG(`$co`), COUNT(*) FROM `archiv_branek`  
																		 INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
																 	 	 WHERE `veky`.`priorita` <= '$priorOd' AND `veky`.`priorita` >= '$priorDo' AND `cisloBrany` = '$i' AND {$pValue} AND `ztraty_utok` < `ztraty_obrana`"));
				$rValues[$pKey][] = $pocet[0];
				
				if (is_null($max[$i]) || $pocet[0] > $max[$i]) $max[$i] = $pocet[0];
			}
		}
		
		$min_percent = null;
		
		$rValues2 = '(';
		foreach ($rValues as $key => $value) {
		  $rValues2 .= '(';
      foreach ($value as $key2 => $value2) {
        if ($percent) {
          $rValues2 .= round($value2 / $max[$key2 + 1] * 100).",";
          if (is_null($min_percent) || round($value2 / $max[$key2 + 1] * 100) < $min_percent) $min_percent = round($value2 / $max[$key2 + 1] * 100); 
        }
        else $rValues2 .= round($value2).",";
      }
      $rValues2 .= '),';
    }
		$rValues2 .= ')';
		
		$rValues2 = preg_replace ('/,\)/', ')', $rValues2);
		
		
		return "values={$rValues2}&amp;barvy=((255,0,255),(210,50,50),(0,250,0))&amp;start=".($percent * $min_percent)."&amp;padding=2&amp;caption=".($co == 'OT' ? 'Prùmìrné OT na branku' : 'Prùmìrná síla na branku').($percent ? ' [%]' : '')."&amp;texty=(Dka,Zka,Nka)&amp;carh=4&amp;popisky=(br1na,br2na,br3na,br4na,br5na,br6na,br7na,br8na,mesto)";
	}
	
	/* private */
	function vygenerujTabulku ($co, $priorOd, $priorDo, $detail) {
		$retVal = '';
		
		$retVal .= '
		<table class="bordered">
			<tr>
				<td colspan="99" class="head">
					'.($co == 'sila' ? 'Prùmìrná síla na brány podle povolání' : 'Prùmìrné OT na brány podle povolání').'
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td class="right">
					br1na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br2na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br3na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br4na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br5na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br6na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br7na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br8na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					mìsto
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>
				<td class="right">
					&sum; 1-9
				</td>' : '').'
			</tr>			
		';
		$povolani = array ('Alchymista', 'Barbar', 'Druid', 'Hranièáø', 'Iluzionista', 'Klerik', 'Mág', 'Nekromant', 'Theurg', 'Váleèník', 'Vìdma', 'Amazonka');
		/* vsechny presvedceni x povolani */
		foreach ($povolani as $povol) {
			$detail ? $presvedceni = array ('D' => "`presvedceni` = 'D'", 'Z' => "`presvedceni` = 'Z'", 'N' => "`presvedceni` = 'N'", 'celkem' => '1') 
			   		:
						$presvedceni = array ('' => '1');
			foreach ($presvedceni as $pKey => $pValue) {
				$retVal .= '
				<tr'.($pValue == '1' ? ' class="doubleBot"' : '').'>
				<td>
					'.$povol.' '.$pKey.'
				</td>';
				for ($i = 1; $i <= 9; $i ++ ) {
					$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT AVG(`$co`), COUNT(*) FROM `archiv_branek`  
																			 INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
																	 	 	 WHERE `veky`.`priorita` <= '$priorOd' AND `veky`.`priorita` >= '$priorDo' AND `cisloBrany` = '$i' AND `povolani` = '$povol' AND {$pValue} AND `ztraty_utok` < `ztraty_obrana`"));
					$retVal .= '
					<td class="right">
						'.cislo($pocet[0]).'
					</td>
					'.($detail ? '<td class="right">
						'.cislo($pocet[1]).'
					</td>' : '').'
					';
				}
				$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT COUNT(*) FROM `archiv_branek`  
																		 INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
																 	 	 WHERE `veky`.`priorita` <= '$priorOd' AND `veky`.`priorita` >= '$priorDo' AND `povolani` = '$povol' AND {$pValue} AND `ztraty_utok` < `ztraty_obrana`"));
				
				$retVal .= '
				'.($detail ? '<td class="right">
					'.cislo($pocet[0]).'
				</td>' : '').'
				</tr>						
				';
			}
		}
		/* celkem pro vsechny presvedceni */
		$presvedceni = array ('D' => "`presvedceni` = 'D'", 'Z' => "`presvedceni` = 'Z'", 'N' => "`presvedceni` = 'N'", '' => '1');
		foreach ($presvedceni as $pKey => $pValue) {
			$retVal .= '
			<tr class="bold'.($pValue == '1' ? ' doubleTop' : '').'">
			<td>
				Všechny '.$pKey.'
			</td>';
			for ($i = 1; $i <= 9; $i ++ ) {
				$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT AVG(`$co`), COUNT(*) FROM `archiv_branek`  
																		 INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
																 	 	 WHERE `veky`.`priorita` <= '$priorOd' AND `veky`.`priorita` >= '$priorDo' AND `cisloBrany` = '$i' AND {$pValue} AND `ztraty_utok` < `ztraty_obrana`"));
				$retVal .= '
				<td class="right">
					'.cislo($pocet[0]).'
				</td>						
				'.($detail ? '<td class="right">
					'.cislo($pocet[1]).'
				</td>' : '').'
				';
			}
			$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT COUNT(*) FROM `archiv_branek`  
																		 INNER JOIN `veky` ON `veky`.`ID` = `archiv_branek`.`ID_veky`
																 	 	 WHERE `veky`.`priorita` <= '$priorOd' AND `veky`.`priorita` >= '$priorDo' AND {$pValue} AND `ztraty_utok` < `ztraty_obrana`"));
				
				$retVal .= '
				'.($detail ? '<td class="right">
					'.cislo($pocet[0]).'
				</td>' : '').'
				</tr>						
				';
		}
		$retVal .= '
			<tr class="italic">
				<td>
					&nbsp;
				</td>
				<td class="right">
					br1na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br2na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br3na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br4na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br5na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br6na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br7na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					br8na
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>' : '').'
				<td class="right">
					mìsto
				</td>
				'.($detail ? '<td class="right">
					&sum;
				</td>
				<td class="right">
					&sum; 1-9
				</td>' : '').'
			</tr>
			</table>';
		return $retVal;
	}
}
?>
