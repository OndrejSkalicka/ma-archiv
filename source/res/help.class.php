<?php
/* 
*	HELP
*	parametr kontruktoru - musi byt unikatni!!
 */
class Help {
	var $text, $head, $id;
	function Help ($id = null, $head = null, $text = null) {
		$this->id = $id;
		$this->setText ($text);
		$this->setHead ($head);
	}
	
	/**
	* nastavi hlavicku helpu
	*/
	function setHead ($head) {
		$this->head = preg_replace ('/([\'"])/', '\\$1', $head);
	}
	
	/**
	* nastavi telo helpu
	*/
	function setText ($text) {
		$this->text =htmlspecialchars($text);
		//str_replace (array ('\'', '"'), array ('\\\'', '\"'), $text);
		//preg_replace ('/([\'"])/', '\\\1', $text);
	}

	/*function vytiskni ($link = '<img src="img/help.png" height="1!1" width="11" alt="[?]">') {
		echo '
		<div id="helpokno_'.$this->id.'" class="help">
			<img class="helpclosebutton" title="Zavøít" onclick="skryj(\'helpokno_'.$this->id.'\');" src="img/se.gif" alt=" X " align="middle" height="14" width="16">
			
			<h2 class="helpnadpis">'.$this->head.'</h2>
			'.$this->text.'
			</div>		
		<span title="Help" class="helpclick" onclick="zobrazokno(event,\'helpokno_'.$this->id.'\')">'.$link.'</span>';
	}*/
	
	function vytiskni ($params = '', $link = '<img src="img/help.png" height="11" width="11" alt="[?]">') {
		if ($params) {
			if (!preg_match ('/^\s*,/', $params)) {
				$params = ', '.trim ($params);
			}
		}
		echo '
			<a href="javascript:void(0);" onclick="return overlib(\''.$this->text.'\', STICKY, CAPTION,\''.$this->head.'\''.$params.');">'.$link.'</a>';
	}
	
	function posliVysledek ($params = '', $link = '<img src="img/help.png" height="11" width="11" alt="[?]">') {
		if ($params) {
			if (!preg_match ('/^\s*,/', $params)) {
				$params = ', '.trim ($params);
			}
		}
		return '
			<a href="javascript:void(0);" onclick="return overlib(\''.$this->text.'\', STICKY, CAPTION,\''.$this->head.'\''.$params.');">'.$link.'</a>';
	}
	
	
	/**
	* Vytiskne JS kod, ktery je treba pro beh helpu	
	*/
	function javaScript () {
		echo
		'<script type="text/javascript">
		function skryj(co){
		            document.getElementById(co).style.display=\'none\';
		}
		 
		function zobrazokno(event,cil)
		{
		    xshift = 10;
			 yshift = 10;
		    
		    if(document.getElementById) 
		      {
		       
		       prvek=document.getElementById(cil)
		       if (document.all && !window.opera) {
		          x = event.clientX + document.body.scrollLeft + xshift;
		          y = event.clientY + document.body.scrollTop + yshift;  
		        }  
		        else {
		          x = event.pageX + xshift;
		          y = event.pageY + yshift;   
		         }
		        if (document.all && !window.opera) {
		          prvek.style.pixelLeft = x;
		          prvek.style.pixelTop = y;
		         }
		         else {
		          prvek.style.left = x + "px";
		          prvek.style.top = y + "px";
		         }
		        
		
		       prvek.style.display = "block"
		      }
		}
		
		</script>';
	}
}
?>