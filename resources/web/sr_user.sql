-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Set 12, 2017 alle 03:46
-- Versione del server: 5.1.73
-- Versione PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `admindb_itzunami`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `sr_user`
--

CREATE TABLE `sr_user` (
  `id` int(11) NOT NULL,
  `creato` text NOT NULL,
  `stato` text NOT NULL,
  `aggiorna_data_last` text NOT NULL,
  `tag` text NOT NULL,
  `username` text NOT NULL,
  `livello` text NOT NULL,
  `clan` text NOT NULL,
  `clantag` text NOT NULL,
  `chest` text NOT NULL,
  `s1` text NOT NULL,
  `s2` text NOT NULL,
  `s3` text NOT NULL,
  `s4` text NOT NULL,
  `s5` text NOT NULL,
  `s6` text NOT NULL,
  `s7` text NOT NULL,
  `s8` text NOT NULL,
  `s9` text NOT NULL,
  `s10` text NOT NULL,
  `s11` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `sr_user`
--
ALTER TABLE `sr_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `sr_user`
--
ALTER TABLE `sr_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
