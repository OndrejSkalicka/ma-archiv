<?php
/**
 * @author Savannah <savannah@seznam.cz>
 * @version 1.0
 * @package ExplorerDestroyer
 */
/**
 * Import detekce browseru
 */ 
require 'browser_detection_php_ar.php';
/**
 * Trida na vytvoreni spravneho kodu na vlozeni do HTML aby sel odstavit IE
 * 
 * Pouziti viz. metoda {@link go()}
 *  
 * @author Savannah <savannah@seznam.cz>
 * @version 1.0
 */ 
class ExplorerDestroyer {
  /**#@+
   * @access private
   */
  var $text = '<strong>Vidíme že používáte Internet Explorer.&nbsp;&nbsp;Tyto stránky pro nìj nejsou optimalizovány.&nbsp;&nbsp;Zkuste Firefox, bude se vám urèitì líbit.</strong>  
<br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&middot;</strong> Firefox blokuje pop-upy.
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&middot;</strong> Firefox zabraòuje pronikání virù a spywaru do vašeho poèítaèe.
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&middot;</strong> Firefox mnohem lépe zobrazuje stránky a ulehèuje práci jejich tvùrcùm.
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&middot;</strong> Firefox umožòuje záložkové prohlížení a mnoho dalšího
<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&middot;</strong> <i>"For each person you switch, Microsoft loses marketsharend an angel gets its wings."</i>
<br /><br />
Kliknìte na ikonku vpravo pro stažení.&nbsp;&nbsp;Firefox je zcela zdarma.<br>';
  var $buttonText = '<script type="text/javascript"><!--
google_ad_client = "pub-2238576042834948";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as_rimg";
google_cpa_choice = "CAAQ3d-SlwIaCDK4NvnNuF4WKJPal3Q";
google_ad_channel = "";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
  var $foreground = '#ffffff';
  var $background = '#999999';
  var $size = '15px';
  var $maxDelay = 0;
  var $postpone_key = 'ff_postpone_key';
  var $postpone_time = 'ff_postpone_time';
  var $level;
  /**#@-*/
  
  /**
     * Vytvori ED
     * @param integer $level uroven otravovani. viz {@link setLevel()}
     */
  function ExplorerDestroyer ($level = 1) {
    $this->level = $level;
  }
  
  /**
   * Kontrola jestli ma uzivatel pravo na preskoceni zobrazeni banneru
   * @return boolean ma uzivatel pravo preskocit banner
   */
  function checkDelay () {
  
    if ($_POST[$this->postpone_time] > time () // je to aktualni 
    && $this->encrypt($_POST[$this->postpone_time]) == $_POST[$this->postpone_key]) {// neni to fake 
      // nastavim cookies
      setcookie($this->postpone_time, $_POST[$this->postpone_time], $_POST[$this->postpone_time]);
      $_COOKIE[$this->postpone_time] = $_POST[$this->postpone_time];
      setcookie($this->postpone_key, $_POST[$this->postpone_key], $_POST[$this->postpone_time]);
      $_COOKIE[$this->postpone_key] = $_POST[$this->postpone_key];
    }
    
    // ma validni susenky
    if ($_COOKIE[$this->postpone_time] > time()
        && $this->encrypt($_COOKIE[$this->postpone_time]) == $_COOKIE[$this->postpone_key])
        return 1;
    
    setcookie($this->postpone_time, '', time() - 1);
    setcookie($this->postpone_key, '', time() - 1);
    return 0;
  }
  
  /**
   * @access private
   */
  function delayText () {
    $time = time () + $this->maxDelay * 60 * 60;
    $retVal = '<br /><form method="post">
                <input type="submit" value="Nezobrazovat na '.(int)$this->maxDelay.' hodin'.($this->maxDelay == 1 ? 'u' : ($this->maxDelay < 5 ? 'y' : '')).'">
                <input type="hidden" name="'.$this->postpone_key.'" value="'.$this->encrypt ($time).'">
                <input type="hidden" name="'.$this->postpone_time.'" value="'.$time.'">
               </form>';
    
    return $retVal;
  }
  
  /**
   * @access private
   */
  function encrypt ($x) {
    return md5(md5(md5($x.'verun')));
  }
  
  /**
   * Doba odlozeni zobrazeni banneru
   *      
   * Nastavi za jak dlouho se znova zobrazi banner po kliknuti na 'nezobrazovat pristich XX hodin(u/y)'      
   * @param integer $maxDelay pocet hodin na jak dlouho bude zobrazeni banneru oddaleno. Pokud <= 0 moznost se nezobrazi
   */
  function setMaxDelay ($maxDelay = 0) {
    $this->maxDelay = $maxDelay;
  }
  
  /**
   * Text banneru
   * @param string $text Text banneru ktery se zobrazi v poli
   */
  function setText ($text) {
    $this->text = $text;
  }
  
  /**
   * Text tlacitka
   * @param string $buttonText Text tlacitka, ktery dostanete od googlu
   */
  function setButtonText ($buttonText) {
    $this->buttonText = $buttonText;
  }
  
  /**
   * Barva textu banneru
   * @param string $foreground Barva v CSS formatu, bud 'red', 'green'.. nebo '#ffaacc'
   */
  function setForeground ($foreground) {
    $this->foreground = $foreground;
  }
  
  /**
   * Barva pozadi banneru
   * @param string $background Barva v CSS formatu, bud 'red', 'green'.. nebo '#ffaacc'
   */
  function setBackground ($background) {
    $this->background = $background;
  }
  
  /**
   * Velikost fontu textu banneru
   * @param string $size Velikost v CSS formatu, napr. '13px'
   */
  function setSize ($size) {
    $this->size = $size;
  }
  
  /**
   * Uroven otravovani banneru
   * 
   * <ol>
   * <li>nezobrazi se
   * <li>zobrazuje se pravidelne v horni casti stranky
   * <li>zobrazi se a uzivatel na nej musi kliknout nez bude smet pokracovat /not yet/
   * <li>uzivatel s IE nemuze pokracovat /not yet/
   * </ol>   
   * @param integer $level Uroven
   */
  function setLevel ($level) {
    $this->level = $level;
  }
  
  /**
   * Vykresleni
   * 
   * Pouzijte tak, ze nahradte tag <body> timto kodem:
   * <code>
   * <?php
   * $ED = new ExplorerDestroyer (1);
   * if ($ED->checkDelay()) $ED->setLevel (0);
   * $ED->setMaxDelay (12);
   * //pripadna dalsi nastaveni, barva textu atp
   * $ED->go();
   * ? >
   * </code>
   *    
   * popr. muzete radky 3-4 vynechat pokud nepouzivate skryvani banneru      
   */
  function go () {
    if ($this->level == 0 || browser_detection ( 'browser' ) != 'ie') {
      return;
    }
      
    echo '
      <div id="hasIE_level1" style="display: block; padding-bottom: 5px;">
      
        <div style="text-align: center; padding: 20px; background-color: '.$this->background.'; font-family: arial; font-size: '.$this->size.'; font-weight: normal; color: '.$this->foreground.'; line-height: 17px;">
        
          <div style="text-align: left; width: 650px; margin: 0 auto 0 auto;">
          
            <div style="padding-left: 8px; padding-top: 0px; float: right;">
            
            
            '.$this->buttonText.'
            
            
            </div>
          
          
          '.$this->text.($this->maxDelay > 0 ? $this->delayText() : '').'
          </div>
        
        </div>
      
      </div> 
      ';
  }
}
?>
