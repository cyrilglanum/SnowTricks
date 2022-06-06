-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 17 fév. 2022 à 13:22
-- Version du serveur : 10.5.12-MariaDB-cll-lve
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u566041279_snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `trick_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `author`, `message`, `created_at`, `trick_id`, `user_id`) VALUES
(1, 'Cyrilg@gmail.com', 'hello toi', '2020-12-10 00:00:00', 24, NULL),
(2, 'Cyrilg@gmail.com', 'hello toi', '2021-12-15 10:28:36', 25, NULL),
(3, 'trick 1', '6249471418292562023', '2021-12-15 10:28:36', 28, NULL),
(4, 'Cyrilg@gmail.com', 'Bonjour', '2021-12-15 10:28:36', 25, NULL),
(5, 'trick 3', '518581661156553934', '2021-12-15 10:28:36', 24, NULL),
(6, 'trick 4', '51734184620395642', '2021-12-15 10:28:36', 27, NULL),
(7, 'trick 5', '4949573846613424037', '2021-12-15 10:28:36', 26, NULL),
(8, 'trick 6', '211035422188135442', '2021-12-15 10:28:36', 24, NULL),
(9, 'trick 7', '3626484462825501550', '2021-12-15 10:28:36', 26, NULL),
(10, 'trick 8', '554342327604652621', '2021-12-15 10:28:36', 24, NULL),
(11, 'trick 9', '72896014284714148', '2021-12-15 10:28:36', 27, NULL),
(12, 'trick 10', '5826131464023056', '2021-12-15 10:28:36', 25, NULL),
(13, 'trick 11', '104228154216141603', '2021-12-15 10:28:36', 24, NULL),
(14, 'trick 12', '18615552146254611819', '2021-12-15 10:28:36', 28, NULL),
(15, 'trick 13', '184345615837554268', '2021-12-15 10:28:36', 25, NULL),
(16, 'trick 14', '4744102812127323539', '2021-12-15 10:28:36', 27, NULL),
(17, 'trick 15', '149433601615102254', '2021-12-15 10:28:36', 28, NULL),
(18, 'trick 16', '33542228132831048', '2021-12-15 10:28:36', 27, NULL),
(19, 'trick 17', '171534308115658236', '2021-12-15 10:28:36', 25, NULL),
(20, 'trick 18', '3325125224232452926', '2021-12-15 10:28:36', 25, NULL),
(21, 'trick 19', '14552443423461265734', '2021-12-15 10:28:36', 27, NULL),
(22, 'trick 0', 'QsDzslEQcA', '2021-12-15 10:28:36', 28, NULL),
(23, 'trick 1', 'vKbkuMlze6', '2021-12-15 10:28:36', 28, NULL),
(24, 'trick 2', 'R1IX4SF4ps', '2021-12-15 10:28:36', 26, NULL),
(25, 'trick 3', 'f378AJ9ayv', '2021-12-15 10:28:36', 25, NULL),
(26, 'trick 4', '8EOGctoF01', '2021-12-15 10:28:36', 26, NULL),
(27, 'trick 5', 'QYOfxM2CIu', '2021-12-15 10:28:36', 25, NULL),
(28, 'trick 6', 'tKCEOk7C3h', '2021-12-15 10:28:36', 26, NULL),
(29, 'trick 7', 'TGN3Jf8WuZ', '2021-12-15 10:28:36', 26, NULL),
(30, 'trick 8', '9IZdWbYNOS', '2021-12-15 10:28:36', 26, NULL),
(31, 'trick 9', 'Fgbt5DOd2Y', '2021-12-15 10:28:36', 27, NULL),
(32, 'trick 10', 'muXARHrCJT', '2021-12-15 10:28:36', 28, NULL),
(33, 'trick 11', 'IbGBzJcQbT', '2021-12-15 10:28:36', 28, NULL),
(34, 'trick 12', 'bruG5lVEhg', '2021-12-15 10:28:36', 26, NULL),
(35, 'trick 13', '4jSJ2MDd3d', '2021-12-15 10:28:36', 25, NULL),
(36, 'trick 14', 'K12bUUwg1b', '2021-12-15 10:28:36', 24, NULL),
(37, 'trick 15', 'KJgVBr5tw9', '2021-12-15 10:28:36', 24, NULL),
(38, 'trick 16', 'RkztPTgYOb', '2021-12-15 10:28:36', 27, NULL),
(39, 'trick 17', '6k4LoTTNls', '2021-12-15 10:28:36', 26, NULL),
(40, 'trick 18', 'jNoks7o18x', '2021-12-15 10:28:36', 24, NULL),
(41, 'trick 19', 'TxfASnftLF', '2021-12-15 10:28:36', 27, NULL),
(42, 'cyril@glanum.com', 'test commentaire', '2021-12-17 10:08:19', 25, 14),
(43, 'cyril@glanum.com', 'eeee', '2021-12-17 15:56:26', 26, 14),
(44, 'thomas@glanum.com', 'WAOUH TROP BIEN', '2021-12-17 15:56:38', 33, 15),
(45, 'thomas@glanum.com', 'BOGOSS', '2021-12-17 15:56:45', 30, 15),
(46, 'cyril@glanum.com', 'hello', '2021-12-17 16:07:18', 32, 14),
(47, 'cyril@glanum.com', 'Valoche va écrire un truc', '2021-12-19 21:11:40', 24, 14),
(48, 'valerie.jouve@gmail.comcom', 'Coucou, la guitare, vous êtes trop fort', '2021-12-19 21:11:46', 25, 19),
(49, 'valerie.jouve@gmail.comcom', 'Coucou, la guitte, vous êtes trop fort', '2021-12-19 21:13:21', 25, 19),
(50, 'leo@glanum.com', 'pas mal !', '2022-01-19 13:26:01', 41, 25),
(51, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:33:46', 42, 25),
(52, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(53, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(54, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(55, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(56, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(57, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(58, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(59, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:37', 42, 25),
(60, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:38', 42, 25),
(61, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:38', 42, 25),
(62, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:38', 42, 25),
(63, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:38', 42, 25),
(64, 'leo@glanum.com', 'pas mal ! 😉', '2022-01-19 13:34:38', 42, 25),
(65, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(66, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(67, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(68, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(69, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(70, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(71, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(72, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:02', 42, 7),
(73, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:03', 42, 7),
(74, 'cyril@glanum.comezz', 'test commentaire', '2022-01-19 13:37:03', 42, 7),
(75, 'cyril@glanum.com', 'Hello, bienvenue sur le forum', '2022-02-01 11:43:17', 9999999, 14),
(76, 'cyril@glanum.com', 'Ceci est un forum pour les utilisateurs', '2022-02-01 13:19:34', 9999999, 14),
(77, 'cyrilg868686@gmail.com', 'Hello tout le monde', '2022-02-02 14:50:21', 9999999, 27),
(78, 'cyrilg868686@gmail.com', 'Hello caro', '2022-02-02 14:53:17', 26, 27),
(79, 'cyrilg868686@gmail.com', 'test commentaire', '2022-02-03 15:22:26', 37, 27);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211006123128', '2021-10-06 12:33:15', 33),
('DoctrineMigrations\\Version20211006124817', '2021-10-06 12:49:08', 51),
('DoctrineMigrations\\Version20211025083704', '2021-10-25 08:46:08', 80),
('DoctrineMigrations\\Version20211026131950', '2021-10-26 13:35:18', 37);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `log` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trick_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `url`, `type`, `trick_id`, `created_at`, `description`, `url_video`) VALUES
(5, 'telechargement-snow-61bc62070ef36.jpg', 'IMG', 25, '2021-12-17 10:10:15', 'test image', NULL),
(6, 'wpid-craziest-tricks1-61bc67478f8d9.jpg', 'IMG', 25, '2021-12-17 10:32:39', 'rrree', NULL),
(7, 'istockphoto-153551554-612x612-61bc6bb63412a.jpg', 'IMG', 24, '2021-12-17 10:51:34', 'Image snow', NULL),
(8, 'rc-fbn-persian-mv-eretailkit-fr-fr-Copie-61bcb34a412bf.jpg', 'IMG', 30, '2021-12-17 15:56:58', 'BAM', NULL),
(17, '20211211-153757-61c194a5bae13.jpg', 'IMG', 25, '2021-12-21 08:47:33', 'Test', NULL),
(18, '20211211-153435-61c196254939e.jpg', 'IMG', 25, '2021-12-21 08:53:57', 'Leo', NULL),
(19, 'telechargement-snow-61c1b7aeda922.jpg', 'IMG', 25, '2021-12-21 11:17:02', 'black and white', NULL),
(20, 'steep-snowboarding-game-art-61c45e2cf36e3.jpg', 'IMG', 27, '2021-12-23 11:31:56', 'Image snow', NULL),
(21, 'processed-61c463bfbd90a.jpg', 'IMG', 25, '2021-12-23 11:55:43', 'TEST SOMA', NULL),
(22, 'wpid-craziest-tricks1-61dec1e67fd03.jpg', 'IMG', 37, '2022-01-12 11:56:22', 'Selfie stick', NULL),
(23, 'snowboard-save-trick-61efc6d7290f7.gif', 'IMG', 42, '2022-01-25 09:45:59', 'Image snow', NULL),
(24, 'http-wordpress-604950-1959020-cloudwaysapps-com-wp-content-uploads-2021-04-jon01102-61efc6f3ab604.jpg', 'IMG', 42, '2022-01-25 09:46:27', 'C\'est la description', NULL),
(25, 'steep-snowboarding-game-art-61efca1632614.jpg', 'IMG', 42, '2022-01-25 09:59:50', 'test', NULL),
(26, 'snowboard-save-trick-61efcd6942f36.gif', 'IMG', 37, '2022-01-25 10:14:01', 'test', NULL),
(27, 'istockphoto-153551554-612x612-61f933b7d5dc4.jpg', 'IMG', 24, '2022-02-01 13:20:55', 'Grab cork', NULL),
(28, 'https://www.youtube.com/embed/SQyTWk7OxSI', 'VID', 26, '2022-02-01 13:23:50', 'ddd', 'https://youtu.be/SQyTWk7OxSI'),
(29, 'https://www.youtube.com/embed/QMrelVooJR4', 'VID', 25, '2022-02-02 14:51:39', 'Tricks snowboard', 'https://youtu.be/QMrelVooJR4'),
(30, 'telechargement-snow-61fa9ac51f9b8.jpg', 'IMG', 26, '2022-02-02 14:52:53', 'Image snow', NULL),
(31, 'https://www.youtube.com/embed/QMrelVooJR4', 'VID', 26, '2022-02-02 14:53:09', 'Video snow', 'https://youtu.be/QMrelVooJR4'),
(32, 'https://www.youtube.com/embed/PxhfDec8Ays', 'VID', 37, '2022-02-03 15:23:59', 'Test vidéo', 'https://youtu.be/PxhfDec8Ays');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(3, 14, 'rxIbAgX9406OMM3ABmED', 'NwKbJVZwSkTBMcZ8brU4yV5u2YmiuqFMpS4uKBCfdiY=', '2022-01-12 08:20:57', '2022-01-12 09:20:57'),
(4, 1, 'j0afYpCGVNNrwggGWe6W', 'seoFAwVDQNYixd0ZvEOx/KGoCVCq4j4fia766I4HB/s=', '2022-01-12 09:19:12', '2022-01-12 10:19:12'),
(5, 14, '8vXmgBdmqoa1ysEzOftU', 'izHS17eDCQ24QN9UXz5Y/hIPBxP3nH1CSnXM6xnYyvo=', '2022-01-12 09:35:25', '2022-01-12 10:35:25'),
(6, 2, '7zd41l4lv9HQO2hOpjck', 'TbM3+DphBH/fNc7u+S+PAtNAXurkhnC3wU9SEEjhj8U=', '2022-01-12 10:13:37', '2022-01-12 11:13:37');

-- --------------------------------------------------------

--
-- Structure de la table `tricks`
--

CREATE TABLE `tricks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_background` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupe` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tricks`
--

INSERT INTO `tricks` (`id`, `name`, `img_background`, `description`, `groupe`, `user_id`, `date_modification`, `date_creation`) VALUES
(24, 'Gravity snow', 'telechargement-1-61bc6ba686609.jpg', 'Ce trick consiste à défier les lois de la gravité en faisant des photos qu\'on ne peut normalement pas obtenir d\'un geste ordinaire en snowboard', 'Gravity', NULL, '2021-12-23 09:50:20', '2021-12-01 10:49:41'),
(25, 'Salto', 'telechargement-snow-61bc6b9f2e9d9.jpg', 'Saut à snow', 'Saut', NULL, '2021-12-17 10:53:44', '2021-11-15 13:59:46'),
(26, 'Selfie-Jump', 'wpid-craziest-tricks1-61bc6bccd9727.jpg', 'description', 'Jump trick', NULL, '2021-12-23 09:49:08', '2021-12-01 10:49:23'),
(27, 'Extrême carving', 'telechargement-snow-2-61bc6bd425325.jpg', 'description', 'Closed-snow', NULL, '2021-12-23 09:49:26', '2021-12-01 10:50:55'),
(32, 'trick', 'istockphoto-153551554-612x612-61bc6be5644d5.jpg', 'un salto comme on aime.', 'Salto', NULL, '2021-12-17 10:52:21', '2021-12-10 08:34:03'),
(36, 'Carver snowboard', 'http-wordpress-604950-1959020-cloudwaysapps-com-wp-content-uploads-2021-04-jon01102-61c445a31c7d7.jpg', 'Carver en snowboard est une figure qui nécessite beaucoup d\'équilibre et de la vitesse', 'Field trick', NULL, '2021-12-23 09:50:49', '2021-12-23 09:47:15'),
(37, 'Backflip', 'steep-snowboarding-game-art-61c445be29272.jpg', 'Saut périlleux arrière', 'Salto', NULL, '2021-12-23 09:50:59', '2021-12-23 09:47:42'),
(42, '360 Jump on floor', 'snowboard-save-trick-61e8130391136.gif', '360 Jump on floor', '360', NULL, NULL, '2022-01-19 13:32:51'),
(48, 'Saltorrr', 'steep-snowboarding-game-art-61efd21bc6392.jpg', 'eee', 't', NULL, NULL, '2022-01-25 10:34:03'),
(9999999, 'Saltorrr', 'steep-snowboarding-game-art-61efd21bc6392.jpg', 'eee', 't', NULL, NULL, '2022-01-25 10:34:03');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_reset_password` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `image`, `roles`, `password`, `token_reset_password`, `is_verified`) VALUES
(1, 'cyrilg86@glanum.com', NULL, NULL, '[]', '$2y$13$yjkcLIeNPrK937GAWJ6BeucjR43ITD5dUxOPT5LSfKQ9HBEWr.YVi', NULL, 0),
(2, 'cyrilg86t@glanum.com', NULL, NULL, '[]', '$2y$13$p2aipvQvltC9JV6h68tPR.YOXJqd91KceohyK.31xSohZVUeDNrSO', NULL, 0),
(3, 'cyrilg86rr@glanum.com', NULL, NULL, '[]', '$2y$13$SJQ1lJQEDMiQOEdLJEoYUewiKy9UvbWfNwR5DPdIteU/Iuczb6uOa', NULL, 0),
(4, 'cyrilg86rrr@glanum.com', NULL, NULL, '[]', '$2y$13$nyPTzu.lrIHTq49Ku/hUlOLV0urQk4oUSHYLKoo1e9HHzaNb06SAu', NULL, 0),
(5, 'cyrilg86rrrr@glanum.com', NULL, NULL, '[]', '$2y$13$jN9t4Kr0L2URIMEgCFSMfO715BmrelcxbIPXVr4H3tzFN52iKZr1y', NULL, 1),
(6, 'cyrilg86rr@glanum.comz', NULL, NULL, '[]', '$2y$13$cO1ERMDW2bSbN9ed3vtj7epxlxTtfRSP/KAMe.T4KtPw2BEV3XlAK', NULL, 0),
(7, 'cyril@glanum.comezz', NULL, NULL, '[]', '$2y$13$Cpx89LT.XE1Z.bS.txohOOMRKc1c1CSjfN6OtSaCEDZiQDOApHs7m', NULL, 0),
(8, 'cyril@glanum.comarrrrrrrrrr', NULL, NULL, '[]', '$2y$13$UPbFUfvRkQ61jDizJDvVTOVB9PHJ5lsJd9qsYIlbRYG8Jxfaq0aKa', NULL, 0),
(9, 'cyril@glanum.comaaaaaaaaa', NULL, NULL, '[]', '$2y$13$r2nF8V/8jJCoGmOs7f/yTeMCNEimEg3rOo3UmsXMJUzSnybXI0MIi', NULL, 0),
(10, 'jdefois@gggg.com', NULL, NULL, '[]', '$2y$13$dzQALFZbu1ytZtrT3pWgIeAXR3BhxlOs/Kth0YeupPBEShtdJVweW', NULL, 0),
(11, 'cyril@glanum.comeaazze', NULL, NULL, '[]', '$2y$13$TuUBqX9BABq.bRQWzjYng.rBWU1nMtTssaQsAHm5WRpGtK0I3qRga', NULL, 0),
(12, 'jdefois@nantesfraeeeeis.com', 'flaski', NULL, '[]', '$2y$13$Kz8NIKCEnUSdXiulY17SkOkv8TmeEQpqG.9dMAHxAjZIJY/Ws6ZLO', NULL, 0),
(13, 'cyril@glanum.comtt', 'flaski', NULL, '[]', '$2y$13$8IKcbblJHRE8gYPwOXgJOeOk7Blyi.qL/LZDrxFj5fdpLcmV1qT9y', NULL, 0),
(14, 'cyril@glanum.com', 'Flaski', 'snowboard-save-trick-61e9774a8a363.gif', '[]', '$2y$13$Cpx89LT.XE1Z.bS.txohOOMRKc1c1CSjfN6OtSaCEDZiQDOApHs7m', 'ResetPassword-14$2y$13$RASXymZz7SdVtg1dpy1sA.WjJHbZY4OXsl4opbh3velhpIAckK5Q6tok', 0),
(15, 'thomas@glanum.com', 'Thomas', NULL, '[]', '$2y$13$uuFdGP0y3Ab2qpouSG/1be2UiBYGy8/Wr79xbsiJ3tuMxZaCI2Uey', NULL, 0),
(16, 'valerie.jouve@dbmail.fr', 'JVARTS', NULL, '[]', '$2y$13$x7lsu/OOSjgdoKfxrEpUhenIDqHWCAmP5wAzD9eVZzE/ExXTYRrnS', NULL, 0),
(17, 'jouve.valerie@gmail.com', 'jvarts', NULL, '[]', '$2y$13$5RnOT4uKb/4wblQf1INBbuakfcjOP65z6LMlmgvuv3.RKo1rdorxu', NULL, 0),
(18, 'valerie.jouve@dbmail.com', 'JVARTS', NULL, '[]', '$2y$13$4CHT1XYLII7x5MXFIsVdSOaupBEBKxD03.M7spkJAlmHCWdQxCMUS', NULL, 0),
(19, 'valerie.jouve@gmail.comcom', 'jvarts', NULL, '[]', '$2y$13$3zNhJT1u5m.5P6USpPa/Iev5Ns.LmNrwo9wVCk8euHpdxB70YANga', NULL, 0),
(20, 'cyril+test@glanum.com', 'cyril@glanum.com', NULL, '[]', '$2y$13$kvZ0TBfT3pJPI0Xbg0Zy3.5eaL90pDatltkDf9VPMJ0mE1c111oMO', NULL, 0),
(21, 'cyrilg868686@glanum.com', 'cyril@glanum.com', NULL, '[]', '$2y$13$ICA/y7zOxJLuHBZmrAZpsurkZ8WFyaMUha8/oATiF0na8NUgayRCu', NULL, 0),
(23, 'cyrilg8686@gmail.com', 'cyril@glanum.com', NULL, '[]', '$2y$13$rfRxeFd9utHKPBte0VuXSOaRHX6Y2PRpwIKcSp8nsYZ/zytzPy0h.', NULL, 0),
(24, 'leo+cyril@glanum.com', 'Spirit qui test les champs voir si c\'est fait pour ceux qui ont des long speudo car il aime bien tout casser quand il CC ! kissi siiis i 🥰⚠🥵🚸🦺🦺🦺🚧🚧🚧😉', NULL, '[]', '$2y$13$aWpPYVDRDtY1NVMzBMfSKO0xykhCcU7zswi8L7puxu6qombZVMJcS', NULL, 0),
(25, 'leo@glanum.com', 'Spirit qui test les champs voir si c\'est fait pour ceux qui ont des long speudo car il aime bien tout casser quand il CC ! kissi siiis i 🥰⚠🥵🚸🦺🦺🦺🚧🚧🚧😉', NULL, '[]', '$2y$13$VvtKpNwtFm4HswOXxTv8YuyRVr0AQ0h7G1iqE1d9vXD/vd90Y.eue', NULL, 0),
(26, 'rrr@gmail.com', '18415121', NULL, '[]', '$2y$13$ydcFjVSgD6gcir4dXsL5V.m2IRBNd0zahzNfTZBH/CV//b7PMKphe', NULL, 0),
(27, 'cyrilg868686@gmail.com', 'Flaskix', 'Avatar-Cyril-disc-61fa9a1d1b721.png', '[]', '$2y$13$/nzAobdpVAo0lakCffqT1udvnnsSDXk9wYCYMiXkV9so3Nxjk4RRi', NULL, 0),
(28, 'cyrilg8686886@gmail.com', 'flaski', NULL, '[]', '$2y$13$NznPgK7fUZedzeXzjhtGaOW.xsbL/oYz67B3LsN2siKCX.3QAPqZa', NULL, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `tricks`
--
ALTER TABLE `tricks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tricks`
--
ALTER TABLE `tricks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000002;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
