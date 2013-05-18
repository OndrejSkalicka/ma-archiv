-- phpMyAdmin SQL Dump
-- version 3.2.2-rc1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Sobota 18. května 2013, 10:24
-- Verze MySQL: 5.1.66
-- Verze PHP: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1250 */;

--
-- Databáze: `savannah_meliorannis`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `archiv_branek`
--

CREATE TABLE IF NOT EXISTS `archiv_branek` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_archiv_uziv` int(11) NOT NULL DEFAULT '0',
  `ID_veky` int(11) NOT NULL DEFAULT '0',
  `privacy` int(11) NOT NULL DEFAULT '0',
  `datum` int(11) NOT NULL DEFAULT '0',
  `cisloBrany` int(11) NOT NULL DEFAULT '0',
  `pred` text COLLATE utf8_czech_ci NOT NULL,
  `po` text COLLATE utf8_czech_ci NOT NULL,
  `boj` text COLLATE utf8_czech_ci NOT NULL,
  `kouzla` text COLLATE utf8_czech_ci NOT NULL,
  `stit` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `OT` int(11) NOT NULL DEFAULT '0',
  `povolani` set('Alchymista','Barbar','Druid','Hraničář','Iluzionista','Klerik','Mág','Nekromant','Theurg','Válečník','Amazonka','Vědma') COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `presvedceni` set('D','Z','N') COLLATE utf8_czech_ci NOT NULL DEFAULT 'D',
  `ztraty_utok` double NOT NULL DEFAULT '0',
  `ztraty_obrana` double NOT NULL DEFAULT '0',
  `sila` int(11) NOT NULL DEFAULT '0',
  `komentar` text COLLATE utf8_czech_ci NOT NULL,
  `popisek` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `upkeepy_pred` varchar(128) COLLATE utf8_czech_ci NOT NULL DEFAULT '0|0|0|0|0|0',
  `upkeepy_po` varchar(128) COLLATE utf8_czech_ci NOT NULL DEFAULT '0|0|0|0|0|0',
  PRIMARY KEY (`ID`),
  KEY `ID_archiv_uziv` (`ID_archiv_uziv`),
  KEY `ID_veky` (`ID_veky`),
  KEY `cisloBrany` (`cisloBrany`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6228 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `archiv_statusy`
--

CREATE TABLE IF NOT EXISTS `archiv_statusy` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `barva` varchar(7) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `cislo` int(11) NOT NULL DEFAULT '0',
  `text` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `archiv_uzivatele`
--

CREATE TABLE IF NOT EXISTS `archiv_uzivatele` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `login` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `heslo` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `nick` varchar(32) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=863 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
