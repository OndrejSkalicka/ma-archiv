<?php
class PolozkaMenu {
	var $title, /* zobrazeny text */
		 $akce, /* ?akce=$akce */
		 $minStatus; /* minimalni status na pristup do teto sekce */
		 
	function PolozkaMenu ($title, $akce, $minStatus) {
		$this->title = $title;
		$this->akce = $akce;
		$this->minStatus = $minStatus;
	}
}
?>