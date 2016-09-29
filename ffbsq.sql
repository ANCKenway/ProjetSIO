-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 29 Septembre 2016 à 09:47
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ffbsq`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `libelle` varchar(15) NOT NULL,
  `ageMin` int(11) NOT NULL,
  `ageMax` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `centredebowling`
--

CREATE TABLE `centredebowling` (
  `id` int(11) NOT NULL,
  `denomination` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

CREATE TABLE `club` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `rue` varchar(20) NOT NULL,
  `codePostal` varchar(5) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `codeLigueRegionale` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `competition`
--

CREATE TABLE `competition` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `tarif` int(11) NOT NULL,
  `idClub` int(11) NOT NULL,
  `idCentreDeBowling` int(11) NOT NULL,
  `idCategorie` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `doublette`
--

CREATE TABLE `doublette` (
  `idCompetition` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `rue` varchar(20) NOT NULL,
  `codePostal` varchar(5) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

CREATE TABLE `equipe` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `idCompetition` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `individuel`
--

CREATE TABLE `individuel` (
  `idCompetition` int(11) NOT NULL,
  `idNiveauJeu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

CREATE TABLE `licence` (
  `numeros` int(11) NOT NULL,
  `annee` year(4) NOT NULL,
  `idPratiquant` int(11) NOT NULL,
  `idNiveauJeu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `licenceequipe`
--

CREATE TABLE `licenceequipe` (
  `numerosLicence` int(11) NOT NULL,
  `idEquipe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `licenceindividuel`
--

CREATE TABLE `licenceindividuel` (
  `numerosLicence` int(11) NOT NULL,
  `idIndividuel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `licencejeune`
--

CREATE TABLE `licencejeune` (
  `nomResp` varchar(20) NOT NULL,
  `prenomResp` varchar(20) NOT NULL,
  `telResp` varchar(15) NOT NULL,
  `numerosLicence` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `licencemixte`
--

CREATE TABLE `licencemixte` (
  `numerosLicence` int(11) NOT NULL,
  `idEntreprise` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ligueregionale`
--

CREATE TABLE `ligueregionale` (
  `code` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `rue` varchar(20) NOT NULL,
  `codePostal` varchar(5) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `niveaujeu`
--

CREATE TABLE `niveaujeu` (
  `id` int(11) NOT NULL,
  `libelle` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pratiquant`
--

CREATE TABLE `pratiquant` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(15) NOT NULL,
  `rue` varchar(25) NOT NULL,
  `codePostal` varchar(5) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `genre` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `centredebowling`
--
ALTER TABLE `centredebowling`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codeLigueRegionale` (`codeLigueRegionale`);

--
-- Index pour la table `competition`
--
ALTER TABLE `competition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idClub` (`idClub`),
  ADD KEY `idCentreDeBowling` (`idCentreDeBowling`),
  ADD KEY `idCategorie` (`idCategorie`);

--
-- Index pour la table `doublette`
--
ALTER TABLE `doublette`
  ADD PRIMARY KEY (`idCompetition`),
  ADD KEY `idCompetition` (`idCompetition`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCompetition` (`idCompetition`);

--
-- Index pour la table `individuel`
--
ALTER TABLE `individuel`
  ADD PRIMARY KEY (`idCompetition`,`idNiveauJeu`),
  ADD KEY `idCompetition` (`idCompetition`),
  ADD KEY `idNiveauJeu` (`idNiveauJeu`);

--
-- Index pour la table `licence`
--
ALTER TABLE `licence`
  ADD PRIMARY KEY (`numeros`),
  ADD KEY `idPratiquant` (`idPratiquant`),
  ADD KEY `idNiveauJeu` (`idNiveauJeu`);

--
-- Index pour la table `licenceequipe`
--
ALTER TABLE `licenceequipe`
  ADD PRIMARY KEY (`numerosLicence`,`idEquipe`),
  ADD KEY `numerosLicence` (`numerosLicence`),
  ADD KEY `idEquipe` (`idEquipe`);

--
-- Index pour la table `licenceindividuel`
--
ALTER TABLE `licenceindividuel`
  ADD PRIMARY KEY (`numerosLicence`,`idIndividuel`),
  ADD KEY `numerosLicence` (`numerosLicence`),
  ADD KEY `idIndividuel` (`idIndividuel`);

--
-- Index pour la table `licencejeune`
--
ALTER TABLE `licencejeune`
  ADD PRIMARY KEY (`numerosLicence`),
  ADD KEY `numerosLicence` (`numerosLicence`);

--
-- Index pour la table `licencemixte`
--
ALTER TABLE `licencemixte`
  ADD PRIMARY KEY (`numerosLicence`),
  ADD KEY `idEntreprise` (`idEntreprise`),
  ADD KEY `numerosLicence` (`numerosLicence`);

--
-- Index pour la table `ligueregionale`
--
ALTER TABLE `ligueregionale`
  ADD PRIMARY KEY (`code`);

--
-- Index pour la table `niveaujeu`
--
ALTER TABLE `niveaujeu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pratiquant`
--
ALTER TABLE `pratiquant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `centredebowling`
--
ALTER TABLE `centredebowling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `club`
--
ALTER TABLE `club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `competition`
--
ALTER TABLE `competition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `equipe`
--
ALTER TABLE `equipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `licence`
--
ALTER TABLE `licence`
  MODIFY `numeros` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `licencemixte`
--
ALTER TABLE `licencemixte`
  MODIFY `numerosLicence` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ligueregionale`
--
ALTER TABLE `ligueregionale`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `niveaujeu`
--
ALTER TABLE `niveaujeu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `pratiquant`
--
ALTER TABLE `pratiquant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
