<?php
$texty = array (
	/* zad�v�n� */
	'zadVek' => array ('V�k','Vyberte v�k, ve kter�m jste branku d�vali.<br>V�choz� je nastaven zrovna prob�haj�c� v�k.'),
	'zadStit' => array ('�t�t na brance','<strong>Doporu�uji nechat na automatu!</strong><br>(tzn. nechat pole pr�zdn�)<br>Pokud se v�m ale zd�, �e automat to �patn� odhadl, tak ho m��ete vyplnit ru�n�, s t�m �e pak zad�vejte jm�na �t�t� odd�len� ��rkou a se spr�vnou velikost� p�smen.<br> Nap�.:<br>&nbsp;&nbsp;�elezn� golem, Striga, V�le�n� trpasl�k<br><br>Tato polo�ka se pak zobraz� v kolonce �t�t, aby m�l �lov�k p�ehled, s ��m jste na br�nu �li, obvykle je tam prost� jeden �t�t (nebo n�jak� zoo :-).'),
	'zadPopisek' => array ('Popisek', '<strong>ZAT�M NEFUNGUJE</strong><br />Popisek, kter� se zobraz� v seznamu branek. '),
	'zadHPred' => array ('Arm�da p�ed bojem','ozna�te hospoda�en� p�ed bojem a vlo�te ho sem.'),
	'zadHPo' => array ('Arm�da po boji','sem vlo�te hospoda�en� po boji<br>tato polo�ka nen� nutn�.'),
	'zadBoj' => array ('Pr�b�h boje','sem pat�� v�pis boje<br>vkl�dejte sem ten v�pis, kter� se vygeneruje hned po odklepnut� branky ne ten, kter� v�m p�ijde po�tou, proto�e u n�j nelze rozeznat ur�it� prvky!'),
	'zadKomentar' => array ('Autorsk� koment��','<strong>ZAT�M NEFUNGUJE</strong><br />Sem m��ete zadat n�jak� koment�� k brance.<br />Koment�� se zobraz� pouze v detailu br�ny.'),
	'zadPrivacy' => array ('�rove� soukrom�', 'Zde si m��ete vybrat, co v�echno se zobraz� o va�� br�n�. Pokud nastav�te `ve�ejn�`, br�na bude norm�ln� viditeln� a bude vid�t, �e jste ji p�idali vy. Polo�ka `anonymn�` zna�� �e br�na bude sice vid�t, ale neuk�e se u n�, kdo j� vlo�il. Posledn� mo�nost je nap�. pokud chcete zve�ejnit svoje br�ny a� na konci v�ku - br�na se v seznamech v�bec nezobraz�! (tzn. a� j� budete cht�t zve�ejnit, tak budete muset zm�nit typ privacy na `Ve�ejn�` nebo `Anonymn�`).'),
	/* potvrzen� zad�v�n� */
	'potVek' => array ('V�k','Zde si m��ete zkontrolovat, jestli jste spr�v� vyplnily v�k.'),
	'potOT' => array ('Po�et odehran� tah�','Zde si m��ete zkontrolovat, jestli automat spr�vn� zjistil po�et OT.'),
	'potPresvedceni' => array ('P�esv�d�en�', 'Zde si m��ete zkontroloval, jestli automat spr�vn� ur�il s jak�m p�esv�d�en�m jste d�vali br�nu.'),
	'potHPred' => array ('Arm�da p�ed bojem','Zde si m��ete p�ibli�n� zkontrolovat, jestli automat spr�vn� rozlo�il hospoda�en� p�ed branou.'),
	'potHPo' => array ('Arm�da po boji','Zde si m��ete p�ibli�n� zkontrolovat, jestli automat spr�vn� rozlo�il hospoda�en� po br�n�.'),
	'potBoj' => array ('Pr�b�h boje','Zde si m��ete p�ibli�n� zkontrolovat, jestli automat spr�vn� rozlo�il pr�b�h boje.'),
	'potStit' => array ('�t�t','Zde m��ete zm�nit, ��m jste na br�n� �t�tovali.'),
	'potKomentar' => array ('Autorsk� koment�r','<strong>ZAT�M NEFUNGUJE</strong><br />V� koment�� k brance.'),
	'potPrivacy' => array ('�rove� soukrom�', 'Zde si m��ete vybrat, co v�echno se zobraz� o va�� br�n�. Pokud nastav�te `ve�ejn�`, br�na bude norm�ln� viditeln� a bude vid�t, �e jste ji p�idali vy. Polo�ka `anonymn�` zna�� �e br�na bude sice vid�t, ale neuk�e se u n�, kdo j� vlo�il. Posledn� mo�nost je nap�. pokud chcete zve�ejnit svoje br�ny a� na konci v�ku - br�na se v seznamech v�bec nezobraz�! (tzn. a� j� budete cht�t zve�ejnit, tak budete muset zm�nit typ privacy na `Ve�ejn�` nebo `Anonymn�`).'),
	/* hled�n� */
	'hledBrana' => array ('Br�na','Kter� br�ny maj� b�t zahrnuty v hled�n�.'),
	'hledVek' => array ('V�k','Kter� v�ky maj� b�t zahrnuty v hled�n�.<br />Nejvy��� hodnota znamen� nejaktu�ln�j�� v�k, tzn. "<" znamen� v�echny v�ky star�� ne� vybran�.'),
	'hledPovolani' => array ('Povol�n�','Kter� povol�n� maj� b�t zahrnuty v hled�n�.'),
	'hledZtratyU' => array ('Ztr�ty �to�n�ka','Pro jak� ztr�ty �to�n�ka se m� prov�st hled�n�.'),
	'hledZtratyO' => array ('Ztr�ty br�ny','Pro jak� ztr�ty obr�nce se m� prov�st hled�n�'),
	'hledSila' => array ('S�la p�ed br�nou','Pro jakou s�lu se m� prov�st hled�n�.'),
	'hledOT' => array ('Odehran� TU','Branky s jak�m OT maj� b�t vyhled�ny.'),
	'hledStit' => array ('Pou�it� �t�t','Jak� se (ne)maj� na brance vyskytovat �t�ty. Pokud chcete zadat v�ce �t�t�, odd�lujte je ��rkou. Sta�� zadat ��st jm�na �t�tu (nap�. pro Archand�la napsat `archa`). M�te t�i mo�nosti jak hledat. <br /><br />`= AND` - hled� takov� br�ny, kter� obsahuj� v�echny zadan� �t�ty<br /><br />`= OR` - hled� takov� br�ny, kter� obsahuj� alespo� jeden zadan� �t�t<br /><br />`&ne;` - hled� takov� br�ny, kter� neobsahuj� zadan� �t�ty<br /><br />Feature: hled� se ��st �et�zce, tak�e pokud d�te �t�t `and�l`, tak se to vztahuje i na `archand�l` atp'),
	'hledSeradit' => array ('�azen� v�sledku','Podle �eho se m� �adit v�sledek. Nejprve se �ad� podle prvn�ho krit�ria, pak podle druh�ho (je-li prvn� pro n�kolik bran sejn�) a obdobn� podle t�et�ho.'),
	/* upravov�n� */
	'uprVek' => array ('V�k','Vyberte v�k, ve kter�m jste branku d�vali.'),
	'uprStit' => array ('�t�t na brance','<strong>Doporu�uji nechat na automatu!</strong><br>(tzn. nechat pole pr�zdn�)<br>Pokud se v�m ale zd�, �e automat to �patn� odhadl, tak ho m��ete vyplnit ru�n�, s t�m �e pak zad�vejte jm�na �t�t� odd�len� ��rkou a se spr�vnou velikost� p�smen.<br> Nap�.:<br>&nbsp;&nbsp;�elezn� golem, Striga, V�le�n� trpasl�k<br><br>Tato polo�ka se pak zobraz� v kolonce �t�t, aby m�l �lov�k p�ehled, s ��m jste na br�nu �li, obvykle je tam prost� jeden �t�t (nebo n�jak� zoo :-).'),
	'uprPopisek' => array ('Popisek', '<strong>ZAT�M NEFUNGUJE</strong><br />Popisek, kter� se zobraz� v seznamu branek. '),
	'uprPrivacy' => array ('�rove� soukrom�', 'Zde si m��ete vybrat, co v�echno se zobraz� o va�� br�n�. Pokud nastav�te `ve�ejn�`, br�na bude norm�ln� viditeln� a bude vid�t, �e jste ji p�idali vy. Polo�ka `anonymn�` zna�� �e br�na bude sice vid�t, ale neuk�e se u n�, kdo j� vlo�il. Posledn� mo�nost je nap�. pokud chcete zve�ejnit svoje br�ny a� na konci v�ku - br�na se v seznamech v�bec nezobraz�! (tzn. a� j� budete cht�t zve�ejnit, tak budete muset zm�nit typ privacy na `Ve�ejn�` nebo `Anonymn�`).'),
	/* listing (hled�n� nebo v�pis vlastn�ch) */
	'listVek' => array ('V�k','V�k ve kter�m byla br�na d�v�na.'),
	'listDatum' => array ('Datum a �as posledn� �pravy','Datum a �as kdy byla br�na vlo�ena nebo naposledy upravena.'),
	'listStit' => array ('�t�t na brance','��m bylo na br�n� �t�tov�no.'),
	'listCislo' => array ('Po�adov� ��slo br�ny', 'Kter� br�na to je :-)'),
	'listDZN' => array ('P�esv�d�en�','Jak�ho p�esv�d�en� je br�na.'),
	'listSila' => array ('S�la arm�dy p�ed bojem','Z jak� s�ly byla br�na skolena.'),
	'listPovolani' => array ('Povol�n� �to�n�ka','Povol�n� �to�n�ka, co dodat? :-)'),
	'listOT' => array ('Po�et odehran�ch TU','S kolika odehran�mi tahy br�na padla.'),
	'listZtraty' => array ('V�sledek na br�n�','V�sledek boje.'),
	'listBrana' => array ('Odkaz na br�nu','Po kliknut� se uk�e dan� br�na, jej� pr�b�h, hospoda�en� atp.'),
	'listPridal' => array ('Autor br�ny','Kdo br�nu p�idal (pozor, kdo j� p�idal neznamen� �e i on j� d�val. Nap�. Magnus p�id�v� i n�jak� br�ny se sv�ho archivu). Pokud je polo�ka `Anonymn�`, tak si autor nastavil �rove� privacy na skrytou. Pokud je polo�ka `U�ivatel neexistuje`, tak ji� u�ivatel, kter� br�nu vlo�il, neexistuje (smazal se atp.)'),
	'listPrivacy' => array ('�rove� soukrom�', 'Zde je vid�t, co v�echno se zobraz� o va�� br�n�. Pokud nastav�te `ve�ejn�`, br�na bude norm�ln� viditeln� a bude vid�t, �e jste ji p�idali vy. Polo�ka `anonymn�` zna�� �e br�na bude sice vid�t, ale neuk�e se u n�, kdo j� vlo�il. Posledn� mo�nost je nap�. pokud chcete zve�ejnit svoje br�ny a� na konci v�ku - br�na se v seznamech v�bec nezobraz�! (tzn. a� j� budete cht�t zve�ejnit, tak budete muset zm�nit typ privacy na `Ve�ejn�` nebo `Anonymn�`)'),
	/* nastaven� */
	'nastTemplate' => array ('�ablona na zobrazen� bran', 'Tady si m��ete nastavit, jak chcete, aby vypadal detail br�ny. Pro vzhled dan�ch �ablon klikn�te na `screenshot`. Toto nastaven� se ukl�d� do cookies (aby ho mohli vyu��vat i nezaregistrovan� u�ivatel�) a tud� se v�e na po��ta� a ne na ��et! (tzn. pokud si na jenom po��ta�i nastav�te �ablonu jinou ne� v�choz� a p�ihl�s�te se na sv�j ��et na jin�m po��ta�i, tak na druh�m PC budete m�t st�le v�choz� �ablonu)'),
	'nastPocetNaStranku' => array ('Po�et bran na str�nku', 'Zde si m��ete nastavit, kolik se m� p�i vyhled�v�n� zobrazit bran na jedn� str�nce. Toto nastaven� se ukl�d� do cookies (aby ho mohli vyu��vat i nezaregistrovan� u�ivatel�) a tud� se v�e na po��ta� a ne na ��et! (tzn. pokud si na jenom po��ta�i nastav�te �ablonu jinou ne� v�choz� a p�ihl�s�te se na sv�j ��et na jin�m po��ta�i, tak na druh�m PC budete m�t st�le v�choz� �ablonu)'),
	/* registrace */
	'regLogin' => array ('P�ihla�ovac� jm�no', 'Pod t�mto jm�nem se budete p�ihla�ovat do syst�mu. Nikde se nezobrazuje.'),
	'regNick' => array ('P�ezd�vka', 'Toto je va�e p�ezd�vka. M�la by, pokud hrajete pravideln� MA pod jedn�m nickem, b�t pr�v� ona p�ezd�vka kterou na MA pou��v�te. Ta se pak zobraz� pod autorem br�ny. '),
	'regPwd1' => array ('Heslo', 'P�ihla�ovac� heslo do syst�mu, pod n�m a je�t� va�im loginem se budete p�ihla�ovat pokud budete cht�t p�id�vat br�ny. Heslo mus� b�t alespo� 5 znak� dlouh�.'),
	'regPwd2' => array ('Heslo znova', 'Zopakov�n� p�ihla�ovac�ho hesla, pro kontrolu.'),
	'regPodminky' => array ('Podm�nky registrace', 'Podm�nky si pozorn� p�e�t�te a pouze pokud s nimi souhlas�te, pokra�ujte v registraci.'),
	/* detail branky */
	'detail_simul' => array ('Simulace', 'Odsimuluje br�nu v jej�m v�ku. M��ete zvolit i jin� v�k, ve kter�m se m� simulace prov�st (proto�e nap�. na star�� br�ny nejsou vypln�n�, tak m��ete zkusit simulaci alespo� na nov�j��ch)')
);

?>