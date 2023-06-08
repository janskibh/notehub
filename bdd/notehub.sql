-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 21, 2023 at 09:05 PM
-- Server version: 10.5.19-MariaDB-0+deb11u2
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notehub`
--

CREATE Database IF NOT EXISTS notehub;
USE notehub;
-- --------------------------------------------------------

--
-- Table structure for table `annees`
--

CREATE TABLE `annees` (
  `ID` int(11) NOT NULL,
  `annees` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annees`
--

INSERT INTO `annees` (`ID`, `annees`) VALUES
(1, '2022-2023');

-- --------------------------------------------------------

--
-- Table structure for table `annonces`
--

CREATE TABLE `annonces` (
  `ID` int(11) NOT NULL,
  `emetteur` int(11) NOT NULL,
  `couleur` varchar(255) DEFAULT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `visible` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annonces`
--

INSERT INTO `annonces` (`ID`, `emetteur`, `couleur`, `titre`, `message`, `date`, `visible`) VALUES
(1, 32, '#F79817', 'Annulation tournoi football', 'Malheureusement, le tournoi de football prévu ne pourra pas avoir lieu. La décision de ne pas justifier les absences pendant la durée du tournoi a été prise par la direction de l\'IUT, ce qui a contraint le BDE à annuler l\'événement.', '2023-05-21 18:54:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `devoirs`
--

CREATE TABLE `devoirs` (
  `ID` int(11) NOT NULL,
  `prof` int(11) NOT NULL,
  `contenu` varchar(255) DEFAULT NULL,
  `ressource` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devoirs`
--

INSERT INTO `devoirs` (`ID`, `prof`, `contenu`, `ressource`, `date`) VALUES
(1, 1, 'Faire la triple intégrale des identités remarquebles de la dérifation de la fonction tierce.', 14, '2023-05-21 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `groupes`
--

CREATE TABLE `groupes` (
  `ID` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `alternance` tinyint(1) NOT NULL COMMENT 'FI ou FA'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table des groupes de TP';

--
-- Dumping data for table `groupes`
--

INSERT INTO `groupes` (`ID`, `nom`, `annee`, `alternance`) VALUES
(1, 'RT1-FI-A1', 1, 0),
(2, 'RT1-FI-A2', 1, 0),
(3, 'RT1-FI-B1', 1, 0),
(4, 'RT1-FA', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `profs`
--

CREATE TABLE `profs` (
  `ID` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profs`
--

INSERT INTO `profs` (`ID`, `nom`) VALUES
(1, 'Sébastien Le Moel'),
(2, 'Samuel Marty'),
(3, 'Amar Ramdane-Cherif'),
(4, 'Willy Guillemin'),
(5, 'Marie-Bernard Bat'),
(6, 'Jenny Fancett'),
(7, 'Dana Marinca'),
(8, 'Etienne Huot'),
(9, 'Abdelaziz Benallegue'),
(10, 'Luc Bondant');

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `ID` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ip_pub` int(11) NOT NULL COMMENT 'ID publication dans sa table',
  `groupe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`ID`, `type`, `ip_pub`, `groupe`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ressources`
--

CREATE TABLE `ressources` (
  `ID` int(11) NOT NULL,
  `semestre` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `code` int(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ressources`
--

INSERT INTO `ressources` (`ID`, `semestre`, `nom`, `code`) VALUES
(1, 1, 'Initiation réseaux', 101),
(2, 1, 'Archi Réseaux', 102),
(3, 1, 'Réseaux locaux', 103),
(4, 1, 'Réseaux locaux', 104),
(6, 1, 'Systèmes numériques', 106),
(7, 1, 'Programmation', 107),
(8, 1, 'Système d\exploitation', 108),
(9, 1, 'Technologies WEB', 109),
(10, 1, 'Anglais S1', 110),
(11, 1, 'ECCP S1', 111),
(12, 1, 'PPP S1', 112),
(13, 1, 'Maths signal', 113),
(14, 1, 'Maths Trans', 114),
(15, 1, 'Gestion de projet', 115);

-- --------------------------------------------------------

--
-- Table structure for table `semestres`
--

CREATE TABLE `semestres` (
  `ID` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `annee` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semestres`
--

INSERT INTO `semestres` (`ID`, `numero`, `annee`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usercas` varchar(255) DEFAULT NULL,
  `passcas` varchar(255) DEFAULT NULL,
  `pp_url` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `groupe` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table utilisateurs';

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`ID`, `username`, `password`, `usercas`, `passcas`, `pp_url`, `verified`, `admin`, `groupe`) VALUES
(22, 'user1', '5f4dcc3b5aa765d61d8327deb882cf99', 'ac7aff186748dac1c272dafd2bf0d1bd', '48cccca3bab2ad18832233ee8dff1b0b', NULL, 0, 0, 1),
(23, 'user2', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 1),
(24, 'user3', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 1),
(25, 'user4', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 1),
(26, 'user5', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 1),
(27, 'user6', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 2),
(28, 'user7', '5f4dcc3b5aa765d61d8327deb882cf99', '2bb809e352d05dff8dcc914e57ee0cb6', '74c20544feea664a434aa5959357854b', NULL, 0, 0, 2),
(29, 'user8', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 2),
(30, 'user9', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 2),
(31, 'user10', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 0, 2),
(32, 'admin1', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 1, 1),
(33, 'admin2', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, NULL, NULL, 0, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annees`
--
ALTER TABLE `annees`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `devoirs`
--
ALTER TABLE `devoirs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `profs`
--
ALTER TABLE `profs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ressources`
--
ALTER TABLE `ressources`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `semestres`
--
ALTER TABLE `semestres`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annees`
--
ALTER TABLE `annees`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `devoirs`
--
ALTER TABLE `devoirs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `profs`
--
ALTER TABLE `profs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ressources`
--
ALTER TABLE `ressources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `semestres`
--
ALTER TABLE `semestres`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
