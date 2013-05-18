<?php
class Jednotka {
	var $vlastnosti,
	 	 $indexy = array ("jmeno" => 0, "xp" => 1, "phb" => 2, "druh" => 3, "typ" => 4, "sila" => 5, "pocet" => 6, "zl_tu" => 7, "mn_tu" => 8, "pp_tu" => 9);
		 
	function Jednotka ($retezec) {
		$this->vlastnosti = explode ("|",$retezec);
		if (count($this->vlastnosti) == 1)
			$this->vlastnosti = $this->vlastnosti[0];
	}	
	
	function vlastnost ($s) {
		return $this->vlastnosti[$this->indexy[$s]];
	}
	
	function vykresliMinitabulku () {
		if (is_array($this->vlastnosti)) 
			echo '
			<tr>
				<td class="right">'.$this->vlastnost('pocet').'x </td>
				<td>'.$this->vlastnost('jmeno').'</td>
				<td class="right">'.$this->vlastnost('sila').' pwr</td>
			</tr>
			';
		else echo '
			<tr>
				<td colspan="3">'.$this->vlastnosti.'</td>
			</tr>';
	}
}
?>