-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 mai 2022 à 23:51
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeu`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id_player` int(11) NOT NULL,
  `login` varchar(40) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` text NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `joueur`
--

INSERT INTO `joueur` (`id_player`, `login`, `pseudo`, `mdp`, `score`) VALUES
(1, 'totolescroc', 'toto', '$2y$10$qlo9mDlF8.M5dotubkeMfuTLfelIorj1GBJqiRUNyGniEfG2c8Vzq', 0);

-- --------------------------------------------------------

--
-- Structure de la table `level`
--

CREATE TABLE `level` (
  `id_level` int(11) NOT NULL,
  `spawn_delay` int(11) NOT NULL,
  `enemy1` int(100) NOT NULL,
  `enemy2` int(100) NOT NULL,
  `enemy3` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `level`
--

INSERT INTO `level` (`id_level`, `spawn_delay`, `enemy1`, `enemy2`, `enemy3`) VALUES
(1, 600, 1000, 2000, 3000),
(2, 300, 2000, 4000, 6000),
(3, 100, 2000, 4000, 6000);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `joueur`
--
ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id_player`);

--
-- Index pour la table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `joueur`
--
ALTER TABLE `joueur`
  MODIFY `id_player` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
