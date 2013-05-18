<?php
class Templates {
	var $dir;

	function Templates ($dir) {
		$this->dir = $dir;
	}
	
	function templatesList () {
		$retVal = null;
		if (@$handle = opendir($this->dir)) {
			while (false !== ($file = readdir($handle))) 
				if ($file != '.' && $file != '..' && $this->isTemplateDir($file)) 
					$retVal[] = $file;
		}
		return $retVal;
	}

	function isTemplateDir ($subDir) {
		if (file_exists ($this->dir.$subDir."/template.css") && file_exists ($this->dir.$subDir."/template.php") && file_exists ($this->dir.$subDir."/template.jpg"))
			return true;
		return false;
	}
}
?>