<?php
class Menu {
	var $aktivni, $skript, $polozky, $user,
		 $lastVolba /* posledni vykreslena class metodou vykresliVolbu */,
		 $lowStatusMsg = "Pouze pro registrovan� u�ivetele! (pop�. nem�te dostate�n� opr�vn�n�)";
	
	function Menu ($skript, &$user, $aktivni = null) {
		$this->skript = $skript;
		$this->aktivni = $aktivni;
		$this->nastavPolozky();
		$this->user = &$user;
	}
	
	function vykonejVolbu ($aktivni = null) {
		if (is_null($aktivni)) $aktivni = $this->aktivni;
		switch ($aktivni) {
			/*case $this->polozky["logout"]->akce:
				$this->user->logout(true);
			break;*/
			case $this->polozky['pridavani']->akce:
				if ($_REQUEST['zpetStep1']) $_REQUEST['step'] = 1;
				switch ($_REQUEST['step']) {
					case 3: $temp = 'Ulo�en� do datab�ze'; break;
					case 2: $temp = 'Potvrzen�'; break;
					default: $temp = 'Zad�v�n�'; break;
				}
				$this->vykresliVolbuStart('Krok '.(min(max(1,$_REQUEST['step']), 3)).'/3 - '.$temp, "archiv_pridavani");
				if ($this->polozky['pridavani']->minStatus <= $this->user->status()) {
				
					$branka = new Branka ($this->user, $this->skript, $aktivni);
					$branka->vykresliPridavaciTabulku($_REQUEST['step']);
					/*echo '</div> <!-- //archiv_pridavani -->';*/
				} else echo $this->lowStatusMsg;
				$this->vykresliVolbuStop();
			break;
			case $this->polozky['branky']->akce: 				
								
				if ($_REQUEST['simul'] > 0) $title = 'Simulace';
				elseif ($_REQUEST['show'] > 0) $title = 'Detail br�ny.'.
          '<br /><span style="font-weight: normal; font-size: 12px;">(<a href="index.php?show='.((int)$_REQUEST['show']).''.($_REQUEST['typ'] != 'simul' ? '&amp;typ=simul' : '').'">'.($_REQUEST['typ'] != 'simul' ? 'simul' : 'standard').' verze</a>)</span>';
				elseif ($_POST['hledej']) $title = 'V�sledky hled�n�';
				else $title = 'Vyhled�v�n� bran';
				
				$this->vykresliVolbuStart($title, 'archiv_hledani');
				
				if ($this->polozky['branky']->minStatus <= $this->user->status()) {
					if ($_REQUEST['simul'] > 0) {
						$br_id = $_REQUEST['simul'];
						$branka = new Branka($this->user, $this->skript, $aktivni, $br_id);
						$cisloBranky = $branka->cisloBrany;
						if ($branka->boj->presvedceni == 'Z') $cisloBranky += 10;
						if ($branka->boj->presvedceni == 'N') $cisloBranky += 20;
						
						$simul = new Simul ();
						$error = $simul->nastavParametry($cisloBranky, $br_id, $_REQUEST['vek']);
						
						if (!$error) {						
							/* $branka->boj->vykresliTabulkuJednotek($i) */
							echo '
							<div id="archiv_simul">							
								<div class="ramec">
									<div class="kolo">Simul (1. TU):<br /><br />'.
										$simul->odehrajKolo (1).'
									</div> <!-- //kolo -->
									<div class="kolo">Real (1. TU):<br /><br />'.
										$branka->boj->vykresliTabulkuJednotek(1).'
									</div> <!-- //kolo -->
									<div class="kolo">Simul (2. TU):<br /><br />'.
										$simul->odehrajKolo (2).'
									</div> <!-- //kolo -->
									<div class="kolo">Real (2. TU):<br /><br />'.
										$branka->boj->vykresliTabulkuJednotek(2).'
									</div> <!-- //kolo -->
									<div class="clear">
										&nbsp;
									</div> <!-- // -->
								</div> <!-- //ramec -->
								<br />
								<div class="ramec">
									<div class="kolo">Simul (3. TU):<br /><br />'.
										$simul->odehrajKolo (3).'
									</div> <!-- //kolo -->
									<div class="kolo">Real (3. TU):<br /><br />'.
										$branka->boj->vykresliTabulkuJednotek(3).'
									</div> <!-- //kolo -->
									<div class="kolo">Simul (4. TU):<br /><br />'.
										$simul->odehrajKolo (4).'
									</div> <!-- //kolo -->
									<div class="kolo">Real (4. TU):<br /><br />'.
										$branka->boj->vykresliTabulkuJednotek(4).'
									</div> <!-- //kolo -->
									<div class="clear">
										&nbsp;
									</div> <!-- //clear -->
								</div> <!-- //ramec -->
								<br />
								<div class="ramec">
									<table width="100%" >
									<tr>
										<td align="center">
											Simul: ';
							$sZtraty = $simul->vysledek();
							$branka->vykresliVysledek ($sZtraty['utok'] * 100, $sZtraty['obrana'] * 100);
							echo '
										</td>
										<td align="center">
											Real: ';
							$branka->vykresliVysledek ($branka->boj->ztraty['utok'], $branka->boj->ztraty['obrana']);
							echo '
										</td>
									</tr>
									</table>
									
								</div> <!-- //ramec -->
							</div> <!-- //archiv_simul -->
							';
						} 
						else 
							echo "$error";
					}
					elseif ($_REQUEST['show'] > 0) {
					  $branka = new Branka($this->user, $this->skript, $aktivni, $_REQUEST['show']);
					  if ($branka->privacy == 2 && $branka->userID != $this->user->userID && $this->user->userStatus < 200) {
              echo '<div class="error">
					Br�na nebyla nalezena v datab�zi!
				</div> <!-- // -->';
            } elseif (!$branka->error) {
              if ($_REQUEST['typ'] == 'simul') {
                $branka->vykresliBranku(1, 1, true);
              } else
              $branka->vykresliBranku(1, 1);
						}
					} else {
						$vyhledavac = new VyhledavaniBranek ($this->skript, $aktivni, $this->user);
						if ($_POST['hledej']) {
							$vyhledavac->vypisBrany($vyhledavac->vyhledejBrany());
						}
						else 
							$vyhledavac->vykresliHledaciTabulku();
					}
				} else echo $this->lowStatusMsg;
				$this->vykresliVolbuStop();
			break;
			case $this->polozky['vlastniBranky']->akce:
				/* HEAD */
				
				if ($_REQUEST['show'] > 0) $title = 'Vlastn� br�ny - detail';
				else $title = 'Vlastn� br�ny';
				$this->vykresliVolbuStart($title, 'archiv_vlastni');
					
				if ($this->polozky['vlastniBranky']->minStatus <= $this->user->status()) {
					
					/* BODY */
					if ($_REQUEST['show'] > 0) {
						$branka = new Branka($this->user, $this->skript, $aktivni, $_REQUEST['show']);
						if ($branka->userID == $this->user->userID || $this->user->userStatus >= 200) {
							$branka->vykresliUpdatovaciTabulku ();
							$branka->vykresliBranku(1);
						} else {
							echo '<div class="error">
								Nem�te pr�va na upravov�n� t�to br�ny
							</div> <!-- //error -->';
						}
					} else {
						if ($_POST['cil']) { //upravovani/mazani
							$branka = new Branka ($this->user, $this->skript, $aktivni, $_POST['cil']);
							if ($branka->user->userID == $this->user->userID) {
								if ($_POST['smazat'])
									if ($branka->smaz()) 
										echo '<div class="ok">
											Br�na byla smaz�na.
										</div> <!-- //ok -->';
									else echo '<div class="error">
										Chyba p�i maz�n� br�ny!
									</div> <!-- //error -->';
								if ($_POST['upravit']) {
									if ($branka->uprav())
										echo '<div class="ok">
											Br�na byla upravena.
										</div> <!-- //ok -->';
									else echo '<div class="error">
										Chyba p�i upravov�n� br�ny!
									</div> <!-- //error -->';
								}
							} else {
								echo '<div class="error">
									Na upravov�n� t�to branky nem�te pr�va!	
								</div> <!-- // -->';
							}
						}
						//zobrazeni vlastnich branek
						
						//sorting
						if ($_POST['upravSort']) {
							setcookie('sortVlastniOnly', $_POST['seradit_vlastni_only'], time() + 2592000);
							$_COOKIE['sortVlastniOnly'] = $_POST['seradit_vlastni_only']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
						
						
							setcookie('sortVlastni1', $_POST['seradit_podleCeho1'], time() + 2592000);
							$_COOKIE['sortVlastni1'] = $_POST['seradit_podleCeho1']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni1ascDesc', $_POST['seradit_ascDesc1'], time() + 2592000);
							$_COOKIE['sortVlastni1ascDesc'] = $_POST['seradit_ascDesc1']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							
							setcookie('sortVlastni2', $_POST['seradit_podleCeho2'], time() + 2592000);
							$_COOKIE['sortVlastni2'] = $_POST['seradit_podleCeho2']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni2ascDesc', $_POST['seradit_ascDesc2'], time() + 2592000);
							$_COOKIE['sortVlastni2ascDesc'] = $_POST['seradit_ascDesc2']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							
							setcookie('sortVlastni3', $_POST['seradit_podleCeho3'], time() + 2592000);
							$_COOKIE['sortVlastni3'] = $_POST['seradit_podleCeho3']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni3ascDesc', $_POST['seradit_ascDesc3'], time() + 2592000);
							$_COOKIE['sortVlastni3ascDesc'] = $_POST['seradit_ascDesc3']; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
						} 
						
						$sorty = array ('cisloBrany', 'sila', 'ztraty_utok', 'ztraty_obrana', 'povolani', 'OT', 'datum');
						$ascy = array ('ASC', 'DESC');
						if (!in_array ($_COOKIE['sortVlastni1'], $sorty) || !in_array ($_COOKIE['sortVlastni1ascDesc'], $ascy)) {
							setcookie('sortVlastni1', 'cisloBrany', time() + 2592000);
							$_COOKIE['sortVlastni1'] = 'cisloBrany'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni1ascDesc', 'ASC', time() + 2592000);
							$_COOKIE['sortVlastni1ascDesc'] = 'ASC'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
						}
						if (!in_array ($_COOKIE['sortVlastni2'], $sorty) || !in_array ($_COOKIE['sortVlastni2ascDesc'], $ascy)) {
							setcookie('sortVlastni2', 'sila', time() + 2592000);
							$_COOKIE['sortVlastni2'] = 'sila'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni2ascDesc', 'ASC', time() + 2592000);
							$_COOKIE['sortVlastni2ascDesc'] = 'ASC'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
						}
						if (!in_array ($_COOKIE['sortVlastni3'], $sorty) || !in_array ($_COOKIE['sortVlastni3ascDesc'], $ascy)) {
							setcookie('sortVlastni3', 'datum', time() + 2592000);
							$_COOKIE['sortVlastni3'] = 'datum'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
							setcookie('sortVlastni3ascDesc', 'ASC', time() + 2592000);
							$_COOKIE['sortVlastni3ascDesc'] = 'ASC'; //$_COOKIE se neprepisuje (kdyz zavolam setcookie, tak se mi do promenne $_COOKIE nic neprida)
						}
						
						echo '
						<form method="post" action="index.php">
						<input type="hidden" name="akce" value="'.$_REQUEST['akce'].'">
							<table>
							<tr>
								<td>Se�adit';
						$this->help('vlastSeradit');			
						echo
								'</td>
								<td class="right">
									1.
								</td>
								<td>
									<select class="select" name="seradit_podleCeho1">
								      	<option value="cisloBrany"'.($_COOKIE['sortVlastni1'] == 'cisloBrany' ? ' selected' : '').'>��sla br�ny</option>
								      	<option value="sila"'.($_COOKIE['sortVlastni1'] == 'sila' ? ' selected' : '').'>S�ly �to�n�ka</option>
								      	<option value="ztraty_utok"'.($_COOKIE['sortVlastni1'] == 'ztraty_utok' ? ' selected' : '').'>Ztr�t �to�n�ka</option>
											<option value="ztraty_obrana"'.($_COOKIE['sortVlastni1'] == 'ztraty_obrana' ? ' selected' : '').'>Ztr�t br�ny</option>
											<option value="povolani"'.($_COOKIE['sortVlastni1'] == 'povolani' ? ' selected' : '').'>Povol�n�</option>
											<option value="OT"'.($_COOKIE['sortVlastni1'] == 'OT' ? ' selected' : '').'>OT</option>
											<option value="datum"'.($_COOKIE['sortVlastni1'] == 'datum' ? ' selected' : '').'>Data</option>
							      </select>
									<select class="select" name="seradit_ascDesc1">
								      	<option value="ASC"'.($_COOKIE['sortVlastni1ascDesc'] == 'ASC' ? ' selected' : '').'>Vzestupn�</option>
								      	<option value="DESC"'.($_COOKIE['sortVlastni1ascDesc'] == 'DESC' ? ' selected' : '').'>Sestupn�</option>
							      </select><br />
								</td>
								<td class="right">
									2.
								</td>
								<td>
									<select class="select" name="seradit_podleCeho2">
											<option value="cisloBrany"'.($_COOKIE['sortVlastni2'] == 'cisloBrany' ? ' selected' : '').'>��sla br�ny</option>
								      	<option value="sila"'.($_COOKIE['sortVlastni2'] == 'sila' ? ' selected' : '').'>S�ly �to�n�ka</option>
								      	<option value="ztraty_utok"'.($_COOKIE['sortVlastni2'] == 'ztraty_utok' ? ' selected' : '').'>Ztr�t �to�n�ka</option>
											<option value="ztraty_obrana"'.($_COOKIE['sortVlastni2'] == 'ztraty_obrana' ? ' selected' : '').'>Ztr�t br�ny</option>
											<option value="povolani"'.($_COOKIE['sortVlastni2'] == 'povolani' ? ' selected' : '').'>Povol�n�</option>
											<option value="OT"'.($_COOKIE['sortVlastni2'] == 'OT' ? ' selected' : '').'>OT</option>
											<option value="datum"'.($_COOKIE['sortVlastni2'] == 'datum' ? ' selected' : '').'>Data</option>
							      </select>
									<select class="select" name="seradit_ascDesc2">
								      	<option value="ASC"'.($_COOKIE['sortVlastni2ascDesc'] == 'ASC' ? ' selected' : '').'>Vzestupn�</option>
								      	<option value="DESC"'.($_COOKIE['sortVlastni2ascDesc'] == 'DESC' ? ' selected' : '').'>Sestupn�</option>
							      </select><br />
								</td>
								<td class="right">
									3.
								</td>
								<td>
									<select class="select" name="seradit_podleCeho3">
								      	<option value="cisloBrany"'.($_COOKIE['sortVlastni3'] == 'cisloBrany' ? ' selected' : '').'>��sla br�ny</option>
								      	<option value="sila"'.($_COOKIE['sortVlastni3'] == 'sila' ? ' selected' : '').'>S�ly �to�n�ka</option>
								      	<option value="ztraty_utok"'.($_COOKIE['sortVlastni3'] == 'ztraty_utok' ? ' selected' : '').'>Ztr�t �to�n�ka</option>
											<option value="ztraty_obrana"'.($_COOKIE['sortVlastni3'] == 'ztraty_obrana' ? ' selected' : '').'>Ztr�t br�ny</option>
											<option value="povolani"'.($_COOKIE['sortVlastni3'] == 'povolani' ? ' selected' : '').'>Povol�n�</option>
											<option value="OT"'.($_COOKIE['sortVlastni3'] == 'OT' ? ' selected' : '').'>OT</option>
											<option value="datum"'.($_COOKIE['sortVlastni3'] == 'datum' ? ' selected' : '').'>Data</option>
							      </select>
									<select class="select" name="seradit_ascDesc3">
								      	<option value="ASC"'.($_COOKIE['sortVlastni3ascDesc'] == 'ASC' ? ' selected' : '').'>Vzestupn�</option>
								      	<option value="DESC"'.($_COOKIE['sortVlastni3ascDesc'] == 'DESC' ? ' selected' : '').'>Sestupn�</option>
							      </select><br />
								</td>
								'.($this->user->userStatus >= 200 ? '
								<td>
									<input type="checkbox" name="seradit_vlastni_only" id="seradit_vlastni_only"'.($_COOKIE['sortVlastniOnly'] ? ' checked' : '').'>
									<label for="seradit_vlastni_only">Pouze vlastn�</label>
								</td>
								' : '').'
								<td>
									<input type="submit" name="upravSort" value="Se�a�">
								</td>
							</tr>
							</table>
						</form>
						<hr>
						';
						$vyhledavac = new VyhledavaniBranek ($this->skript, $aktivni, $this->user);
						echo $this->user->Status;
						if (($this->user->userStatus >= 200) && (!$_COOKIE['sortVlastniOnly']))
							$vyhledavac->vypisBrany($vyhledavac->vyhledejVlastniBranyAdmin($this->user->userID, "`{$_COOKIE['sortVlastni1']}` {$_COOKIE['sortVlastni1ascDesc']}, `{$_COOKIE['sortVlastni2']}` {$_COOKIE['sortVlastni2ascDesc']}, `{$_COOKIE['sortVlastni3']}` {$_COOKIE['sortVlastni3ascDesc']}"), true);
						else $vyhledavac->vypisBrany($vyhledavac->vyhledejVlastniBrany($this->user->userID, "`{$_COOKIE['sortVlastni1']}` {$_COOKIE['sortVlastni1ascDesc']}, `{$_COOKIE['sortVlastni2']}` {$_COOKIE['sortVlastni2ascDesc']}, `{$_COOKIE['sortVlastni3']}` {$_COOKIE['sortVlastni3ascDesc']}"), true);
					}					
				} else echo $this->lowStatusMsg;

				/* TAIL */
				$this->vykresliVolbuStop();
			break;
			case $this->polozky['nastaveni']->akce:
				/* HEAD */
				$title = 'Nastaven�';
				$this->vykresliVolbuStart($title, 'archiv_nastaveni');
				
//				$this->user->setSablona ();
				
					
				if ($this->polozky['nastaveni']->minStatus <= $this->user->status()) {
					
					/* BODY */
					$nastaveni = new Nastaveni ($this->skript, $this->user);
					$nastaveni->vykresliTabulkuNastaveni();
					
					
				} else echo $this->lowStatusMsg;
				
				/* TAIL */
				$this->vykresliVolbuStop();
			break;
			case $this->polozky['stats']->akce:
				/* HEAD */
				$title = 'Statistiky';
				$this->vykresliVolbuStart($title, 'archiv_statistiky');
				
//				$this->user->setSablona ();
				
					
				if ($this->polozky['stats']->minStatus <= $this->user->status()) {
					
					/* BODY */
					$stats = new Statistiky ($this->skript);
					
					echo "<center><i>(statistiky jsou pouze z bran, kter� �to�n�k vyhr�l)<br /><br /></i></center>";
					
					if (!isset($_POST['vek_priorod'])) {
						$temp = MySQL_Fetch_Array (MySQL_Query ("SELECT MAX(`priorita`) FROM `veky`"));
						$_POST['vek_priorod'] = $temp[0];
					}
					
					if (!isset($_POST['vek_priordo'])) 
						$_POST['vek_priordo'] = 1;

					echo '
					<form action="'.$this->skript.'" method="post">
						<input type="hidden" name="akce" value="'.$_REQUEST['akce'].'">
						Statistiky k v�k�m od 
						<select name="vek_priorod">';
					$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");	
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['priorita'].'"'.($vek['priorita'] == $_POST['vek_priorod'] ? ' selected' : '').'>'.$vek['jmeno'].' ('.$vek['title'].')</option>';
					}
					echo'	</select>
					do
						<select name="vek_priordo">';
					$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");	
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['priorita'].'"'.($vek['priorita'] == $_POST['vek_priordo'] ? ' selected' : '').'>'.$vek['jmeno'].' ('.$vek['title'].')</option>';
					}
					echo'	</select> (v�etn�)
					(<input type="checkbox" name="detail"'.($_POST['detail'] ? ' checked' : '').' id="st_detail"> <label for="st_detail">Detailn�</label>)
					<input type="submit" value="Zobraz">
					</form>
					';
					echo '<table class="mainT">
					<tr>
						<td colspan="3">
							'.$stats->stats('povolaniSila3', $_POST['vek_priorod'], $_POST['vek_priordo'], $_POST['detail'], $URL).'
						</td>
					</tr>'
          .($_POST['detail'] ? '
          <tr>
            <td colspan="3">
            <img src="res/grafyGD.php?width=900&amp;start=0&amp;'.$stats->statsGraf('povolaniSila3', $_POST['vek_priorod'], $_POST['vek_priordo']).'">
            </td>
          </tr>' : '').
          '
					<tr>
						<td colspan="3">
							'.$stats->stats('povolaniOT3', $_POST['vek_priorod'], $_POST['vek_priordo'], $_POST['detail']).'
						</td>
					</tr>'
          .($_POST['detail'] ? '
          <tr>
            <td colspan="3">
            <img src="res/grafyGD.php?width=900&amp;border_l=40&amp;start=0&amp;'.$stats->statsGraf('povolaniOT3', $_POST['vek_priorod'], $_POST['vek_priordo']).'">
            </td>
          </tr>' : '').
          '
					</table>
					';
					
					
					
				} else echo $this->lowStatusMsg;
				
				/* TAIL */
				$this->vykresliVolbuStop();
			break;
			case $this->polozky['help']->akce:
				/* HEAD */
				$title = 'N�pov�da';
				$this->vykresliVolbuStart($title, 'archiv_help');
				
//				$this->user->setSablona ();
				
					
				if ($this->polozky['help']->minStatus <= $this->user->status()) {
					
					/* BODY */
					/*$nastaveni = new Nastaveni ($this->skript, $this->user);
					$nastaveni->vykresliTabulkuNastaveni();*/
					include 'help.html';
					
					
				} else echo $this->lowStatusMsg;
				
				/* TAIL */
				$this->vykresliVolbuStop();
			break;
			case 'simul':
				/* HEAD */
				$title = 'Simul�tor Bran';
				$this->vykresliVolbuStart($title, 'archiv_simul');
				
					
				if ($this->polozky['simul']->minStatus <= $this->user->status()) {					
					/* BODY */
					$br_id = 30;
					$branka = new Branka($this->user, $this->skript, $aktivni, $br_id);
					$cisloBranky = $branka->cisloBrany;
					if ($branka->boj->presvedceni == 'Z') $cisloBranky += 10;
					if ($branka->boj->presvedceni == 'N') $cisloBranky += 20;
					
					$simul = new Simul ();
					$simul->nastavParametry($cisloBranky, $br_id, $branka->vekID);
					
					
					
					/* $branka->boj->vykresliTabulkuJednotek($i) */
					echo '
					<div class="ramec">
						<div class="kolo">Simul:<br />'.
							$simul->odehrajKolo (1).'
						</div> <!-- //kolo -->
						<div class="kolo">Real:<br />'.
							$branka->boj->vykresliTabulkuJednotek(1).'
						</div> <!-- //kolo -->
						<div class="kolo">Simul:<br />'.
							$simul->odehrajKolo (2).'
						</div> <!-- //kolo -->
						<div class="kolo">Real:<br />'.
							$branka->boj->vykresliTabulkuJednotek(2).'
						</div> <!-- //kolo -->
						<div class="clear">
							&nbsp;
						</div> <!-- // -->
					</div> <!-- //ramec -->
					<br />
					<div class="ramec">
						<div class="kolo">Simul:<br />'.
							$simul->odehrajKolo (3).'
						</div> <!-- //kolo -->
						<div class="kolo">Real:<br />'.
							$branka->boj->vykresliTabulkuJednotek(3).'
						</div> <!-- //kolo -->
						<div class="kolo">Simul:<br />'.
							$simul->odehrajKolo (4).'
						</div> <!-- //kolo -->
						<div class="kolo">Real:<br />'.
							$branka->boj->vykresliTabulkuJednotek(4).'
						</div> <!-- //kolo -->
						<div class="clear">
							&nbsp;
						</div> <!-- // -->
					</div> <!-- //ramec -->
					';
					
				} else echo $this->lowStatusMsg;
				
				/* TAIL */
				$this->vykresliVolbuStop();			
			break;
			case 'registrace':
				/* HEAD */
				$title = 'Registrace';
				$this->vykresliVolbuStart($title, 'archiv_registrace');
				
				$registrace = new Registrace ($this->skript);
				if ($registrace->registruj()) {
					echo "Va�e registrace prob�hla �sp�n�. Nyn� se m��ete p�ihl�sit.";
				} else {
					$registrace->vykresliRegistracniTabulku();				
				}
				
				/* TAIL */
				$this->vykresliVolbuStop();				
			break;
			default:
				$this->vykresliVolbuStart('N/A yet', "null");				
				echo "comming soon";
				$this->vykresliVolbuStop();
			break;
		}		
	}
	
	/* vykresli DIVy pro volbu -> tzn. celou spodni cast stranky */
	function vykresliVolbuStart ($title, $class) {
		$this->lastVolba = $class;
		echo '<div id="'.$this->lastVolba.'">
			<div class="archiv_nadpis">
				'.$title.'
			</div> <!-- //archiv_nadpis -->';
	}
	
	function vykresliVolbuStop () {
		echo '</div> <!-- //'.$this->lastVolba.' -->';
	}
	
	function nastavPolozky () {
		$this->polozky = array ("branky" => new PolozkaMenu("Branky", '', 0),
										"help" => new PolozkaMenu("Help", 'help', 0 ),
				 						"pridavani" => new PolozkaMenu("P�id�v�n�", 'pridavani', 100),
										"vlastniBranky" => new PolozkaMenu("Vlastn� branky", 'vlastni', 100),
										"stats" => new PolozkaMenu("Statistiky", 'stats', 0),
										"nastaveni" => new PolozkaMenu("Nastaven�", 'nastaveni', 0)/*,
										"simul" => new PolozkaMenu("Simul�tor branek", 'simul', 100)*/);
	}
	
	function vykresliPolozku ($polozka, $selected = false) {
		echo '
		<div class="archiv_polozka">
			<a href="'.$this->skript.($polozka->akce ? '?akce='.$polozka->akce : '').'"><span'.($selected ? ' class="selected"' : '').'>'.$polozka->title.'</span></a>
		</div> <!-- //archiv_polozka -->';
	}
	
	function vykresliMenu () {
		echo '<div id="archiv_menu">';		
		foreach ($this->polozky as $polozka) {
			$this->vykresliPolozku ($polozka, $this->aktivni == $polozka->akce);
		}
		echo '</div> <!-- //archiv_menu -->
		<div class="clear">&nbsp;</div>';
	}
	
	function help ($key, $params = '') {
		require "res/help.text.php";
		$temp = new Help($key,$texty[$key][0],$texty[$key][1]);
		$temp->vytiskni($params);
	}
}
?>
