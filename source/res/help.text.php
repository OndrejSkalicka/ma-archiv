<?php
$texty = array (
	/* zadávání */
	'zadVek' => array ('Vìk','Vyberte vìk, ve kterém jste branku dávali.<br>Výchozí je nastaven zrovna probíhající vìk.'),
	'zadStit' => array ('Štít na brance','<strong>Doporuèuji nechat na automatu!</strong><br>(tzn. nechat pole prázdné)<br>Pokud se vám ale zdá, že automat to špatnì odhadl, tak ho mùžete vyplnit ruènì, s tím že pak zadávejte jména štítù oddìlená èárkou a se správnou velikostí písmen.<br> Napø.:<br>&nbsp;&nbsp;Železný golem, Striga, Váleèný trpaslík<br><br>Tato položka se pak zobrazí v kolonce štít, aby mìl èlovìk pøehled, s èím jste na bránu šli, obvykle je tam prostì jeden štít (nebo nìjaká zoo :-).'),
	'zadPopisek' => array ('Popisek', '<strong>ZATÍM NEFUNGUJE</strong><br />Popisek, který se zobrazí v seznamu branek. '),
	'zadHPred' => array ('Armáda pøed bojem','oznaète hospodaøení pøed bojem a vložte ho sem.'),
	'zadHPo' => array ('Armáda po boji','sem vložte hospodaøení po boji<br>tato položka není nutná.'),
	'zadBoj' => array ('Prùbìh boje','sem patøí výpis boje<br>vkládejte sem ten výpis, který se vygeneruje hned po odklepnutí branky ne ten, který vám pøijde poštou, protože u nìj nelze rozeznat urèité prvky!'),
	'zadKomentar' => array ('Autorský komentáø','<strong>ZATÍM NEFUNGUJE</strong><br />Sem mùžete zadat nìjaký komentáø k brance.<br />Komentáø se zobrazí pouze v detailu brány.'),
	'zadPrivacy' => array ('Úroveò soukromí', 'Zde si mùžete vybrat, co všechno se zobrazí o vaší bránì. Pokud nastavíte `veøejná`, brána bude normálnì viditelná a bude vidìt, že jste ji pøidali vy. Položka `anonymní` znaèí že brána bude sice vidìt, ale neukáže se u ní, kdo jí vložil. Poslední možnost je napø. pokud chcete zveøejnit svoje brány až na konci vìku - brána se v seznamech vùbec nezobrazí! (tzn. až jí budete chtít zveøejnit, tak budete muset zmìnit typ privacy na `Veøejná` nebo `Anonymní`).'),
	/* potvrzení zadávání */
	'potVek' => array ('Vìk','Zde si mùžete zkontrolovat, jestli jste správì vyplnily vìk.'),
	'potOT' => array ('Poèet odehraný tahù','Zde si mùžete zkontrolovat, jestli automat správnì zjistil poèet OT.'),
	'potPresvedceni' => array ('Pøesvìdèení', 'Zde si mùžete zkontroloval, jestli automat správnì urèil s jakým pøesvìdèením jste dávali bránu.'),
	'potHPred' => array ('Armáda pøed bojem','Zde si mùžete pøibližnì zkontrolovat, jestli automat správnì rozložil hospodaøení pøed branou.'),
	'potHPo' => array ('Armáda po boji','Zde si mùžete pøibližnì zkontrolovat, jestli automat správnì rozložil hospodaøení po bránì.'),
	'potBoj' => array ('Prùbìh boje','Zde si mùžete pøibližnì zkontrolovat, jestli automat správnì rozložil prùbìh boje.'),
	'potStit' => array ('Štít','Zde mùžete zmìnit, èím jste na bránì štítovali.'),
	'potKomentar' => array ('Autorský komentár','<strong>ZATÍM NEFUNGUJE</strong><br />Váš komentáø k brance.'),
	'potPrivacy' => array ('Úroveò soukromí', 'Zde si mùžete vybrat, co všechno se zobrazí o vaší bránì. Pokud nastavíte `veøejná`, brána bude normálnì viditelná a bude vidìt, že jste ji pøidali vy. Položka `anonymní` znaèí že brána bude sice vidìt, ale neukáže se u ní, kdo jí vložil. Poslední možnost je napø. pokud chcete zveøejnit svoje brány až na konci vìku - brána se v seznamech vùbec nezobrazí! (tzn. až jí budete chtít zveøejnit, tak budete muset zmìnit typ privacy na `Veøejná` nebo `Anonymní`).'),
	/* hledání */
	'hledBrana' => array ('Brána','Které brány mají být zahrnuty v hledání.'),
	'hledVek' => array ('Vìk','Které vìky mají být zahrnuty v hledání.<br />Nejvyšší hodnota znamená nejaktuálnìjší vìk, tzn. "<" znamená všechny vìky starší než vybraný.'),
	'hledPovolani' => array ('Povolání','Která povolání mají být zahrnuty v hledání.'),
	'hledZtratyU' => array ('Ztráty útoèníka','Pro jaké ztráty útoèníka se má provést hledání.'),
	'hledZtratyO' => array ('Ztráty brány','Pro jaké ztráty obránce se má provést hledání'),
	'hledSila' => array ('Síla pøed bránou','Pro jakou sílu se má provést hledání.'),
	'hledOT' => array ('Odehrané TU','Branky s jakým OT mají být vyhledány.'),
	'hledStit' => array ('Použitý štít','Jaké se (ne)mají na brance vyskytovat štíty. Pokud chcete zadat více štítù, oddìlujte je èárkou. Staèí zadat èást jména štítu (napø. pro Archandìla napsat `archa`). Máte tøi možnosti jak hledat. <br /><br />`= AND` - hledá takové brány, které obsahují všechny zadané štíty<br /><br />`= OR` - hledá takové brány, které obsahují alespoò jeden zadaný štít<br /><br />`&ne;` - hledá takové brány, které neobsahují zadané štíty<br /><br />Feature: hledá se èást øetìzce, takže pokud dáte štít `andìl`, tak se to vztahuje i na `archandìl` atp'),
	'hledSeradit' => array ('Øazení výsledku','Podle èeho se má øadit výsledek. Nejprve se øadí podle prvního kritéria, pak podle druhého (je-li první pro nìkolik bran sejné) a obdobnì podle tøetího.'),
	/* upravování */
	'uprVek' => array ('Vìk','Vyberte vìk, ve kterém jste branku dávali.'),
	'uprStit' => array ('Štít na brance','<strong>Doporuèuji nechat na automatu!</strong><br>(tzn. nechat pole prázdné)<br>Pokud se vám ale zdá, že automat to špatnì odhadl, tak ho mùžete vyplnit ruènì, s tím že pak zadávejte jména štítù oddìlená èárkou a se správnou velikostí písmen.<br> Napø.:<br>&nbsp;&nbsp;Železný golem, Striga, Váleèný trpaslík<br><br>Tato položka se pak zobrazí v kolonce štít, aby mìl èlovìk pøehled, s èím jste na bránu šli, obvykle je tam prostì jeden štít (nebo nìjaká zoo :-).'),
	'uprPopisek' => array ('Popisek', '<strong>ZATÍM NEFUNGUJE</strong><br />Popisek, který se zobrazí v seznamu branek. '),
	'uprPrivacy' => array ('Úroveò soukromí', 'Zde si mùžete vybrat, co všechno se zobrazí o vaší bránì. Pokud nastavíte `veøejná`, brána bude normálnì viditelná a bude vidìt, že jste ji pøidali vy. Položka `anonymní` znaèí že brána bude sice vidìt, ale neukáže se u ní, kdo jí vložil. Poslední možnost je napø. pokud chcete zveøejnit svoje brány až na konci vìku - brána se v seznamech vùbec nezobrazí! (tzn. až jí budete chtít zveøejnit, tak budete muset zmìnit typ privacy na `Veøejná` nebo `Anonymní`).'),
	/* listing (hledání nebo výpis vlastních) */
	'listVek' => array ('Vìk','Vìk ve kterém byla brána dávána.'),
	'listDatum' => array ('Datum a èas poslední úpravy','Datum a èas kdy byla brána vložena nebo naposledy upravena.'),
	'listStit' => array ('Štít na brance','Èím bylo na bránì štítováno.'),
	'listCislo' => array ('Poøadové èíslo brány', 'Která brána to je :-)'),
	'listDZN' => array ('Pøesvìdèení','Jakého pøesvìdèení je brána.'),
	'listSila' => array ('Síla armády pøed bojem','Z jaké síly byla brána skolena.'),
	'listPovolani' => array ('Povolání útoèníka','Povolání útoèníka, co dodat? :-)'),
	'listOT' => array ('Poèet odehraných TU','S kolika odehranými tahy brána padla.'),
	'listZtraty' => array ('Výsledek na bránì','Výsledek boje.'),
	'listBrana' => array ('Odkaz na bránu','Po kliknutí se ukáže daná brána, její prùbìh, hospodaøení atp.'),
	'listPridal' => array ('Autor brány','Kdo bránu pøidal (pozor, kdo jí pøidal neznamená že i on jí dával. Napø. Magnus pøidává i nìjaké brány se svého archivu). Pokud je položka `Anonymní`, tak si autor nastavil úroveò privacy na skrytou. Pokud je položka `Uživatel neexistuje`, tak již uživatel, který bránu vložil, neexistuje (smazal se atp.)'),
	'listPrivacy' => array ('Úroveò soukromí', 'Zde je vidìt, co všechno se zobrazí o vaší bránì. Pokud nastavíte `veøejná`, brána bude normálnì viditelná a bude vidìt, že jste ji pøidali vy. Položka `anonymní` znaèí že brána bude sice vidìt, ale neukáže se u ní, kdo jí vložil. Poslední možnost je napø. pokud chcete zveøejnit svoje brány až na konci vìku - brána se v seznamech vùbec nezobrazí! (tzn. až jí budete chtít zveøejnit, tak budete muset zmìnit typ privacy na `Veøejná` nebo `Anonymní`)'),
	/* nastavení */
	'nastTemplate' => array ('Šablona na zobrazení bran', 'Tady si mùžete nastavit, jak chcete, aby vypadal detail brány. Pro vzhled daných šablon kliknìte na `screenshot`. Toto nastavení se ukládá do cookies (aby ho mohli využívat i nezaregistrovaní uživatelé) a tudíž se váže na poèítaè a ne na úèet! (tzn. pokud si na jenom poèítaèi nastavíte šablonu jinou než výchozí a pøihlásíte se na svùj úèet na jiném poèítaèi, tak na druhém PC budete mít stále výchozí šablonu)'),
	'nastPocetNaStranku' => array ('Poèet bran na stránku', 'Zde si mùžete nastavit, kolik se má pøi vyhledávání zobrazit bran na jedné stránce. Toto nastavení se ukládá do cookies (aby ho mohli využívat i nezaregistrovaní uživatelé) a tudíž se váže na poèítaè a ne na úèet! (tzn. pokud si na jenom poèítaèi nastavíte šablonu jinou než výchozí a pøihlásíte se na svùj úèet na jiném poèítaèi, tak na druhém PC budete mít stále výchozí šablonu)'),
	/* registrace */
	'regLogin' => array ('Pøihlašovací jméno', 'Pod tímto jménem se budete pøihlašovat do systému. Nikde se nezobrazuje.'),
	'regNick' => array ('Pøezdívka', 'Toto je vaše pøezdívka. Mìla by, pokud hrajete pravidelnì MA pod jedním nickem, být právì ona pøezdívka kterou na MA používáte. Ta se pak zobrazí pod autorem brány. '),
	'regPwd1' => array ('Heslo', 'Pøihlašovací heslo do systému, pod ním a ještì vašim loginem se budete pøihlašovat pokud budete chtít pøidávat brány. Heslo musí být alespoò 5 znakù dlouhé.'),
	'regPwd2' => array ('Heslo znova', 'Zopakování pøihlašovacího hesla, pro kontrolu.'),
	'regPodminky' => array ('Podmínky registrace', 'Podmínky si pozornì pøeètìte a pouze pokud s nimi souhlasíte, pokraèujte v registraci.'),
	/* detail branky */
	'detail_simul' => array ('Simulace', 'Odsimuluje bránu v jejím vìku. Mùžete zvolit i jiný vìk, ve kterém se má simulace provést (protože napø. na starší brány nejsou vyplnìné, tak mùžete zkusit simulaci alespoò na novìjších)')
);

?>