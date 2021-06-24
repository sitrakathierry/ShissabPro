-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 24 juin 2021 à 06:49
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `shissab`
--

-- --------------------------------------------------------

--
-- Structure de la table `access_role`
--

CREATE TABLE `access_role` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `access_role`
--

INSERT INTO `access_role` (`id`, `menu_id`, `role`) VALUES
(4, 12, 'ROLE_RESPONSABLE'),
(5, 13, 'ROLE_RESPONSABLE'),
(6, 15, 'ROLE_RESPONSABLE'),
(7, 14, 'ROLE_RESPONSABLE'),
(8, 1, 'ROLE_RESPONSABLE'),
(9, 2, 'ROLE_RESPONSABLE'),
(10, 7, 'ROLE_RESPONSABLE'),
(11, 17, 'ROLE_RESPONSABLE'),
(12, 18, 'ROLE_RESPONSABLE'),
(13, 19, 'ROLE_RESPONSABLE'),
(14, 20, 'ROLE_RESPONSABLE'),
(15, 21, 'ROLE_RESPONSABLE'),
(16, 22, 'ROLE_RESPONSABLE'),
(17, 8, 'ROLE_ADMIN'),
(18, 9, 'ROLE_ADMIN'),
(19, 10, 'ROLE_ADMIN'),
(20, 16, 'ROLE_ADMIN'),
(21, 11, 'ROLE_ADMIN'),
(22, 3, 'ROLE_ADMIN'),
(23, 4, 'ROLE_ADMIN'),
(24, 6, 'ROLE_ADMIN'),
(25, 5, 'ROLE_ADMIN');

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

CREATE TABLE `agence` (
  `id` int(11) NOT NULL,
  `nom` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id`, `nom`, `region`, `code`) VALUES
(1, 'SHISSAB', 'Moroni', 0),
(3, 'HIKAM', 'Moroni', 0);

-- --------------------------------------------------------

--
-- Structure de la table `agent_gap`
--

CREATE TABLE `agent_gap` (
  `id` int(11) NOT NULL,
  `agence` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `responsable` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `num_police` int(11) NOT NULL,
  `id_client_morale` int(11) DEFAULT NULL,
  `id_client_physique` int(11) DEFAULT NULL,
  `agence` int(11) DEFAULT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`num_police`, `id_client_morale`, `id_client_physique`, `agence`, `statut`) VALUES
(1, NULL, 1, 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `client_morale`
--

CREATE TABLE `client_morale` (
  `id` int(11) NOT NULL,
  `id_type_societe` int(11) DEFAULT NULL,
  `nom_societe` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_gerant` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_fixe` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domaine` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num_registre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client_physique`
--

CREATE TABLE `client_physique` (
  `id` int(11) NOT NULL,
  `id_type_social` int(11) DEFAULT NULL,
  `nom` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nin` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quartier` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sexe` int(11) DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `situation` int(11) DEFAULT NULL,
  `lieu_travail` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adresse_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lien_parente` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_pers_contact` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ddn` date DEFAULT NULL,
  `ldn` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profession` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `client_physique`
--

INSERT INTO `client_physique` (`id`, `id_type_social`, `nom`, `nin`, `adresse`, `quartier`, `tel`, `sexe`, `email`, `situation`, `lieu_travail`, `tel_pers_contact`, `adresse_pers_contact`, `email_pers_contact`, `lien_parente`, `observation`, `nom_pers_contact`, `ddn`, `ldn`, `profession`) VALUES
(1, 1, 'TEST TEST', '', 'TEST', '', '0123456789', 1, 'andriramananarivo@gmail.com', 1, '', '', '', '', '', '                                            ', '', NULL, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id` int(11) NOT NULL,
  `client` int(11) DEFAULT NULL,
  `agence` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `remise_pourcentage` int(11) DEFAULT NULL,
  `remise_valeur` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `somme` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`id`, `client`, `agence`, `type`, `date_creation`, `date`, `num`, `montant`, `remise_pourcentage`, `remise_valeur`, `total`, `somme`, `descr`) VALUES
(1, 1, 3, 2, '2021-06-17', '2021-06-15', 1, '10000.00', 0, '0.00', '10000.00', 'dix mille francs comorien', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p><p>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p><p>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</p><p>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse</p><p>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non</p><p>proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>');

-- --------------------------------------------------------

--
-- Structure de la table `facture_details`
--

CREATE TABLE `facture_details` (
  `id` int(11) NOT NULL,
  `facture` int(11) DEFAULT NULL,
  `designation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `facture_details`
--

INSERT INTO `facture_details` (`id`, `facture`, `designation`, `prix`, `montant`) VALUES
(1, 1, 'test', '10000.00', '10000.00');

-- --------------------------------------------------------

--
-- Structure de la table `fos_user`
--

CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `fos_user`
--

INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 'admin', 'admin', 'admin@shissab.com', 'admin@shissab.com', 1, NULL, '$2y$13$eMR5y6fmad0J3fISKtcqcOAiJb9.Es.FQL6KDb3eovRF8eBuybWtO', '2021-06-24 06:07:23', NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}'),
(6, 'usershissab1', 'usershissab1', 'usershissab1@gmail.com', 'usershissab1@gmail.com', 1, NULL, '$2y$13$s2j0uPRyeI7Q4Uw2Ik97U.DIse4ABbt5myj8Djpy/atI1jaUTUk8O', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(7, 'adminhikam', 'adminhikam', 'adminhikam@gmail.com', 'adminhikam@gmail.com', 1, NULL, '$2y$13$8pEsyAJsGVDHsYghtZpnIuBgpsZLNuUK8jjC7XeSRcQJSAEGx9chS', '2021-06-23 18:52:26', NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_RESPONSABLE\";}'),
(8, 'agent1hikam', 'agent1hikam', 'agent1hikam@gmail.com', 'agent1hikam@gmail.com', 1, NULL, '$2y$13$yZnUqWp6wk.FTKGcT0JQDeLK5hu5y2EiXguoAPYjzeFiY6Z5.aaP.', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_AGENT\";}'),
(9, 'agent2hikam', 'agent2hikam', 'agent2hikam@gmail.com', 'agent2hikam@gmail.com', 1, NULL, '$2y$13$lB0WZZCNKaX.dEVffZVxP.AXyrOHjdxZaq0wBBUaGOc2naUyu5TZu', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_AGENT\";}'),
(10, 'agent3hikam', 'agent3hikam', 'agent3hikam@gmail.com', 'agent3hikam@gmail.com', 1, NULL, '$2y$13$i3brA4P/vYHKDYk1rcDYS.VG8xpTNy9ScuNophCGZCHrFJS3doNli', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_AGENT\";}'),
(22, 'test', 'test', 'client120test@gmail.com', 'client120test@gmail.com', 1, NULL, '$2y$13$PF/DYfbfNN2cCr/NTlDVu.iwnOww67V6l3.gOQ.wAzKRclLZgtBBG', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}'),
(23, 'test2', 'test2', 'test2', 'test2', 1, NULL, '$2y$13$NA/6EGdf9moHN263fQ5wle2lTKihpjT38SzaooYRnlDadv55pCiA2', NULL, NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_AGENT\";}'),
(24, 'admin2hikam', 'admin2hikam', 'adminhikam2@gmail.com', 'adminhikam2@gmail.com', 1, NULL, '$2y$13$FvuywDpx0W9r6HLHX62tXe8l72diaITP2RrnibbHZtOBgZxlJRMuG', NULL, NULL, NULL, 'a:1:{i:0;s:16:\"ROLE_RESPONSABLE\";}');

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `user_update` int(11) DEFAULT NULL,
  `date_modification` datetime NOT NULL,
  `motif` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`id`, `user`, `user_update`, `date_modification`, `motif`) VALUES
(20, 1, NULL, '2021-06-14 16:01:34', 'Deconnexion Utilisateur'),
(21, 7, NULL, '2021-06-14 16:01:49', 'Connexion Utilisateur'),
(22, 7, NULL, '2021-06-14 16:25:58', 'Deconnexion Utilisateur'),
(23, 1, NULL, '2021-06-14 16:26:05', 'Connexion Utilisateur'),
(24, 1, NULL, '2021-06-15 06:16:16', 'Connexion Utilisateur'),
(25, 1, NULL, '2021-06-15 06:43:03', 'Deconnexion Utilisateur'),
(26, 7, NULL, '2021-06-15 06:43:17', 'Connexion Utilisateur'),
(27, 1, NULL, '2021-06-15 06:59:21', 'Connexion Utilisateur'),
(28, 7, NULL, '2021-06-15 07:16:29', 'Création Fiche Client N°000001'),
(29, 1, NULL, '2021-06-15 12:47:36', 'Connexion Utilisateur'),
(30, 1, NULL, '2021-06-17 09:08:51', 'Connexion Utilisateur'),
(31, 1, NULL, '2021-06-17 09:09:39', 'Deconnexion Utilisateur'),
(32, 7, NULL, '2021-06-17 09:09:48', 'Connexion Utilisateur'),
(33, 7, NULL, '2021-06-17 09:14:55', 'Deconnexion Utilisateur'),
(34, 1, NULL, '2021-06-17 09:15:03', 'Connexion Utilisateur'),
(35, 1, NULL, '2021-06-17 09:57:59', 'Connexion Utilisateur'),
(36, 1, NULL, '2021-06-18 09:01:05', 'Connexion Utilisateur'),
(37, 1, NULL, '2021-06-18 09:43:01', 'Connexion Utilisateur'),
(38, 1, NULL, '2021-06-22 10:37:50', 'Connexion Utilisateur'),
(39, 1, NULL, '2021-06-22 10:38:06', 'Deconnexion Utilisateur'),
(40, 7, NULL, '2021-06-22 10:38:14', 'Connexion Utilisateur'),
(41, 1, NULL, '2021-06-22 10:38:38', 'Connexion Utilisateur'),
(42, 1, NULL, '2021-06-23 18:49:08', 'Connexion Utilisateur'),
(43, 1, NULL, '2021-06-23 18:51:19', 'Deconnexion Utilisateur'),
(44, 1, NULL, '2021-06-23 18:51:26', 'Connexion Utilisateur'),
(45, 1, NULL, '2021-06-23 18:52:18', 'Deconnexion Utilisateur'),
(46, 7, NULL, '2021-06-23 18:52:26', 'Connexion Utilisateur'),
(47, 7, NULL, '2021-06-23 19:06:33', 'Deconnexion Utilisateur'),
(48, 1, NULL, '2021-06-23 19:06:40', 'Connexion Utilisateur'),
(49, 1, NULL, '2021-06-24 06:07:23', 'Connexion Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rang` int(11) NOT NULL,
  `disabled` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `menu_id`, `route`, `name`, `icon`, `rang`, `disabled`) VALUES
(1, NULL, 'client_dashboard', 'Client', 'fa-address-card', 2, 0),
(2, 1, 'client_add', 'Création', 'fa-edit', 1, 0),
(3, NULL, 'parametre_homepage', 'Paramètres', 'fa-cogs', 100, 0),
(4, 3, 'parametre_client', 'Fiche Client', 'fa-user', 1, 0),
(5, 4, 'typesocial_add', 'Situation social', 'fa-user', 2, 0),
(6, 4, 'typesociete_add', 'Type de société', 'fa-bank', 1, 0),
(7, 1, 'client_list', 'Consultation', 'fa-search', 2, 0),
(8, NULL, 'user_homepage', 'Utilisateurs', 'fa-users', 90, NULL),
(9, 8, 'agence_add', 'Gestion société', 'fa-user', 1, 0),
(10, 8, 'user_add', 'Création compte', 'fa-user', 2, NULL),
(11, 8, 'menu_access', 'Gestion des menus', 'fa-lock', 4, NULL),
(12, NULL, 'agence_homepage', 'Société', 'fa-building', 1, NULL),
(13, 12, 'agence_details', 'Details', 'fa-building', 1, NULL),
(14, 12, 'agence_user_list', 'Liste des agents', 'fa-users', 3, NULL),
(15, 12, 'agence_add_user', 'Création agent', 'fa-add', 2, NULL),
(16, 8, 'user_list', 'Liste des utilisateurs', 'fa-list', 3, NULL),
(17, NULL, 'facture_homepage', 'Facture', 'fa-file', 3, NULL),
(18, 17, 'facture_add', 'Création', 'fa-edit', 1, NULL),
(19, NULL, 'pdf_homepage', 'Modèle PDF', 'fa-file-pdf-o', 5, NULL),
(20, 19, 'pdf_add', 'Création modèle', 'fa-edit', 1, NULL),
(21, 19, 'pdf_consultation', 'Liste des modèles', 'fa-list', 2, NULL),
(22, 19, 'pdf_attribution', 'Attribution des modèles', 'fa-edit', 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `menu_utilisateur`
--

CREATE TABLE `menu_utilisateur` (
  `id` int(11) NOT NULL,
  `menu` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `can_edit` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modele_pdf`
--

CREATE TABLE `modele_pdf` (
  `id` int(11) NOT NULL,
  `agence` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modele` int(11) NOT NULL,
  `logo_droite` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo_centre` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo_gauche` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `texte_haut` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `modele_pdf`
--

INSERT INTO `modele_pdf` (`id`, `agence`, `nom`, `modele`, `logo_droite`, `logo_centre`, `logo_gauche`, `texte_haut`) VALUES
(2, 1, 'modele pdf 1', 1, 'data:image/png;base64,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27buLtu8ceOIQeWFAe9Jx07eu3tPD52MxaKTxo69+frvpX3+9ddfTRwz1gv4wJ/+2N1vLdv68d13v/jC88k/LevM007xKPDnBx9IuzILnTEMY9asWUcddZSiaAvnL2xra8sqJeYvmNsWi5x0ysmZX1144QWqmvQlSSlnz349N8f/ne9+N+2yvLy80047LVWSc84ZMkRkvLcqd4xxztICO1VFZYjOV+lduujiSZOPEUJ88P773d1z8eLF7e3tQyqH1NfXzZs3r4cdhIA4Vzau33DbD24Kh5u6u5Jzpqpqmvupqanpzttu27R189Xfu/43v/2fbkkKVx76y1+mTju3c2RcLhcQZImCzPzx228tSiQS/3r26UBezratW95d8nb2TXjbNoX1nl8SjUZrampUVe3uytSoXFVRGGeAwHordsMQGbI0pBlnjDEEwGxV1gYPrpQCIpHu8ono6aee/OlPf3bNdddJW8yf83oinr30JkmBwM+ZOtUfDKxcseKHt9+eUYNm34xUFLVzNjvtT/fdt+7Lr0pKS+/59a97fke3211cXNw5SoqiEEFmEHOWV33h+eenTZs24ciJJ5xwgqHrb8yek71/jDOGyz9c3styQWSMxWLRDz/6qFcK5/QPARnDXq9EhjwjhqXju2yTKRITUpaVl2W94Zo1q7dt33bplVdecPHFubm5G9at//jjj7NeKUmapvmLX//XnT/6KQC8tWjRr3718+4g5ApPhXD79q1vLpivKsqUY48dN+6IPikPvJuI0HQIN25Yt37TxquuvhoALrviKrfX8+knK7Zs3Jz5yzGjx2lcefedJY89+jBRt2F9Pr9/UOVgzvjjjz669O2lPffSoViMMbYfxQoxAyvEbr3TTfU1X36x2uP1nXv+hVkveOnFF0866eRQbmjUmDFTvnt8LBGbO3t29lVIgEg20S9//ZtrrrmOE3v13zP++tD92eYZ44yrKfEJK1esbAu3EskpU6b0WYVnjGA/VuHTzzxz7LHHDh8xEgDOPPPsCROOrK+vnjVrZuYdr7j66vLKyoSe+NMf7rv8kgvfWrjQMIysEu/WW2/litLSUHvr92+4+/Y7V69a1S2EQhCRs3B7X4WIkA6hE3IHatcqD81NTXffdeemLVvv+dWvTjv9jKwK69KlS2+77QfOna+59jqXpr377uJd2co4S5E8IhcZPvzPf150ySW2ZT/4wIN/uO++bFs2U1JOsv7qqy8RgHM2ZsyYvkLoBORlBnZ0GammhsYlixbeccedzp8ej+eyy64Ext+cP7c9g9RUDh3yzAsvDhs+wjD09955/6brb5h61plPPfl4W2t6BYHzzr/oob894g0Eo+2tM15+6dILLrzysksXzJ8rpZ0JYXK77jVgBwEYpm2ZiMAYIMoNGzds2rRp/fp17733zu/uvfeUU05av2nLcy+++Nvf/y7rzWa89NKQwYOOPS5JuM6ZNm30mCNqqmvemP16lnlmCymTiTUut+cfTz019dzzpGn9429/+/P992cunVRpX1ddjRy5qhQWFfcdQvL5PHNfn33L9Tdcf9VVV11y6UXnnnv2qad1gfDf06ePGDbsxJP2kczrrr+hsnLI5s2bl7ydRcc//vgTFr/9zm9++/uRI0cLKdauXvWbe35x7lmnz3sj/c1vvuWWN5csveHmW4tKSiLRyNLFb9164/cvv/D8tWu6rEjbsoiIMdZr5hERSCKlq56OyBAZ4/jmggU/vPP2s0465coLL966ZfNDf/7LJ5+tuuF73896q3gsNuu1WT/5yU86H+oPBK6+5lqG7I05s+MZpEZIIaSQHaUD/H7/U88+d8rppwnb/stDf3704b93hRB5Cm3WdR2AuMK1A6rwIaX0BfylFeWVQwaPHDV03BGjjxg/dt/dE/HEq6/MBBA/vOMOQCACkgIAbNPmqLw2Y8YVV1yRyfQKCot+81//feedd721aNErM6Z//uknWzdtvu3Gm/S4cfX116deOe6I8Y8+9vjuXbvmzJkz67WZ2zZtXv7BhxuvunL2vAVHjD8yqc+Y5v6edSilJSy3x9sVVyICYct77/3vgoLiC6edu2PjJmmL86ZNg+4p7ltvLdy2afOs12YtWrhYIjhadl1Nrcfj27xhyxuzZl9/4/e60hkiIVPzY3JCoWdemH7XbT94d8nS+//wR6/b/YM770rZOPdNR01TAUAIaVkHUG2dJRLG1HOn/egnP8suSBctnNfUWHvqmWf4g/5Q0J8fCuSGQqGcnHPOO9cXDHyycuXqz7vdw3JCoWuuu27+wsWPPflMYVEJAjzyt79H2rOU4K0cMuTn99yz9N33f/bLX7q93tra2kcffqQLCABSSiF6OZvDtizdMH3+QFcIJUkJAIlEoqS09NF/Pe7Pz12y6K2f//Sn3a5mKZ97+pkTTj6xpKQ8GAzk5fjzQzmh3NC48eMnTjpa2PZzzz1pGLE0y6ew7TQdqaCw8NkXXzz9rDMt0/yfe3/77LNPd8r2VAgLCgpJStu2us0q7UnwECLoupHlCyISQpxx8gm/uufHWS0FF0w716coN95wfachrYf2ysv/LsoJlBfmfbH6856v/MkPfxh0u4475phYPN5hXvmwODe3vCB/zepeDGxNjQ1DKkof/+ejqR+uWf15eWFhaX7eqs8+cT55/pln8n2BPL//+aefznqfD95/v6KkcOeOHZlfLV/2fnFusDDH//aShV0/X5brda9csSLzJ83NTdPOOjPX7SnJy3v5xReI6OyTTv7T7+/rvOAfjzyc6/fk+z1/+8uD1Md2+cUXuRg89MD/ZrfOfPzxxxvXb/7+92/Jiv9VV13t0lxLly796qsve50sZ559dkFRkWEkYrFeKqief8EFnPF4PG50HNBZVFzs8/t0PVFTXdPzb5uaGhOx2JDKyrRVCF1TsW669dbv33KzbVn3/f53K1ZmUWEff+yxk046acjQoZlfffeEEydOmqgnEjP+PSP1c9MwhRTZTquAvLz8Z194YeLkSYau/+aXv5r58nTgDFPozPHHn+D1eDjnKz7+qK9pqkIK7EEvfPKxxycde+y4CUdm/fHU86YNHlIZaW17dcaMXp/kcmmaqmmqNxTKhR7LgzgOnWAw6PUmt7RBFYPKystMU//sk096fsrqVasAaOy4cekSJWNc/nj//zv2hONbWpp+fNdde7tWp1j/zboP3l/2vZtuzfoIVVUvueIqpijLly3buH79PgOkqZMEs5vNrKy84pmXXho+criRiP321/+1ft26VL3wqEmTjj7mOGHTmk9Xf7FqdV+5TFallwHA5k3r3nn7rRtuuL67H+cXFJwzdSoCvLVgYX1dcn0sXfq2bVvZdKya+rqGoUOGOcplJBJ57733st52+/bt0Xh80qRJLlcy0dDt8Zx++ulAsGD+/Nrauh7Ux6effHbKlO9WDh2WBiEQkJQipdKUz+9/+JFHSktLtm/Z9qO7f5hI7DuS9+mnHi8vLznllG59RhdceFFJSUm4peW1ma+m2pClBNPslo8MHz7i2ZdeKi4tT8TjiVg8VUFSFOW+P/4plJMbaYs89MADord8484D1R1LN2I25yAR/eYXPx05dFBLc3MPgviTlSvKivLzvO5//fMfRCSlvGDq1KeeeDzzyv/+1T0I8Nwzyb1n755dUyZNXLNmTdplhmGceepJhXnBtV90+Wrnjh3jhg3PUbUrL7s0HG7JvL8Q4lf33JPj9a34OH03+vijD0tyc4pDoY8+XJ721UsvPJ8f9IW8rnt+8TPnk6rdu8sKc/9032973oHu+dlPgx5t0hFjmxsbnU9mTJ/u5Th37uyef7hs2bLBZaVBzfXA/fenfTXz3y8X54SCmvaLn//UsoxuWcWMf0+f/lLSU2FaZ596io/DXx9K30SBiAYXl/z8Jz/uuUOGYZx+8gk+Bb5zzDGJhN7Y2DhsSGXI6/nrgw801NUlKUZT4x9/d2+uV/uvX/3cFrbz4fvvLnUDjBk29PXXZ0WjEefDrVu3XH35JYU5/hkvP5/5rCWLF48aPNgFcPxxU2ZMn757565oNBqNxWpqqhfMe2PaaacWB3Nee2VG5g/fXbokx6PluN1LFr+V8aW89aYbQm7V71Ief+xxIvrD//zOr2rffPVVzy++Zs3qkvwcrwJPPJacr0898QQCzJw5vVcC8tqrM72c/+n3f8j8as7rs8aNGq4AXDDtrA/efy8eT6RwosZ5c2dPnXoWZ/Dvl19O+gtj8eOPmRxQ2Q1XX/HqzBkvv/jcc0//67FH//7g/X9UAGD02LGXXHRpzyta07SLL7si3NIKJD9ZueKkk09++OFHn3r8Xw898OBTTzxZOXSoqqqNDfVej/v56TMuvuzyzh8efcyUv/3jkenPP3/7LbdUVFSUl1dIKWprakaOHLH0vWUTs+VUnjN16tzFbz3xz8feXbr0zttu8/sDubkhhfGEnpAkjjv++MUfLJs0aVIWsQM4YvQ4DiizVKzEP97/4I4du+Ox2JzZs0aOGvHFV2svuvzycUeM7/nFjzzyyJNOO3Pn1i2frFxx4803uT0eRVVHjxqlar2fp3HlVVd/8/U3rZEs3rpLL79iynHHvfj8C/Pmzrn+qquLS0vLKyrcbnckEqmp3huPxydOnjx/wZtnn31OkkNZpsvrGT5q5Lp169Z+sdYRzlJKKQQSkaEbmqpiby4627ZNQ2eIthD+QNBxnW3evPGLNat37d5dXFQ0efIxE46cwHiWrLNEPL5+/bov1qxuaWkZNnz45MnHODtlz625uXHHtm0bN21qrG8I+P1jx44bPXZMUUlpd9ebpmnbtuOH0txZEvkTiTggSCnisbjb7Xa5PPuTS6brOoI0TdPt8aqqpuu6lJJzxbUfFpZEIrFu/fopx3Sb/ZswEru2b9+0ceOOHTtsIUpLSkaPGTN8+PCCwuK0wY9E2jjjjinfMfA7ZTEGcir+z7eBIMQBCAfaAIQDbQDCAQgH2gCEA20AwoE2AOEAhANtAMKBNgDhQDuI9v8NADr6Iz+9q32SAAAAAElFTkSuQmCC', 'data:image/png;base64,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', 'data:image/png;base64,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', '                        		                        		                        		                        		                        		                        		                        		                        		                        		<h1>Lorem ipsums</h1><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p><p>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p><p>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</p>\n                        	\n                        	\n                        	\n                        	\n                        	\n                        	\n                        	\n                        	\n                        	');

-- --------------------------------------------------------

--
-- Structure de la table `permission_user`
--

CREATE TABLE `permission_user` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `permission` longtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `nom` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_social`
--

CREATE TABLE `type_social` (
  `id` int(11) NOT NULL,
  `designation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `type_social`
--

INSERT INTO `type_social` (`id`, `designation`, `descr`) VALUES
(1, 'Marié', '                                            ');

-- --------------------------------------------------------

--
-- Structure de la table `type_societe`
--

CREATE TABLE `type_societe` (
  `id` int(11) NOT NULL,
  `designation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_agence`
--

CREATE TABLE `user_agence` (
  `id` int(11) NOT NULL,
  `agence` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `responsable` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user_agence`
--

INSERT INTO `user_agence` (`id`, `agence`, `user`, `responsable`) VALUES
(1, 1, 1, 'Administrateur SHISSAB'),
(3, 1, 6, 'Utilisateur shissab'),
(4, 3, 7, 'Responsable'),
(5, 3, 8, 'Agent HIKAM'),
(6, 3, 9, 'Agent HIKAM'),
(7, 3, 10, 'Agent HIKAM'),
(17, 1, 22, 'test'),
(18, 1, 23, 'test2'),
(19, 3, 24, 'Agent HIKAM');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `access_role`
--
ALTER TABLE `access_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A1B0EC6CCCD7E912` (`menu_id`);

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `agent_gap`
--
ALTER TABLE `agent_gap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agent_gap_user_idx` (`user`),
  ADD KEY `fk_agent_gap_agence_idx` (`agence`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`num_police`),
  ADD KEY `IDX_C744045564C19AA9` (`agence`),
  ADD KEY `fk_client_morale_idx` (`id_client_morale`),
  ADD KEY `fk_client_physique_idx` (`id_client_physique`);

--
-- Index pour la table `client_morale`
--
ALTER TABLE `client_morale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_client_morale_type_societe_idx` (`id_type_societe`);

--
-- Index pour la table `client_physique`
--
ALTER TABLE `client_physique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_client_physique_type_social_idx` (`id_type_social`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facture_client_idx` (`client`),
  ADD KEY `fk_facture_agence_idx` (`agence`);

--
-- Index pour la table `facture_details`
--
ALTER TABLE `facture_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facture_details_facture_idx` (`facture`);

--
-- Index pour la table `fos_user`
--
ALTER TABLE `fos_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`);

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F08FC65C8D93D649` (`user`),
  ADD KEY `IDX_F08FC65CFD772E45` (`user_update`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7D053A93CCD7E912` (`menu_id`);

--
-- Index pour la table `menu_utilisateur`
--
ALTER TABLE `menu_utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_utilisateur_user_idx` (`user`),
  ADD KEY `fk_menu_utilisateur_menu_idx` (`menu`);

--
-- Index pour la table `modele_pdf`
--
ALTER TABLE `modele_pdf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_855D4EB664C19AA9` (`agence`);

--
-- Index pour la table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permission_user_user_idx` (`user`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type_social`
--
ALTER TABLE `type_social`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type_societe`
--
ALTER TABLE `type_societe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_agence`
--
ALTER TABLE `user_agence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_agence_user_idx` (`user`),
  ADD KEY `fk_user_agence_agence_idx` (`agence`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `access_role`
--
ALTER TABLE `access_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `agent_gap`
--
ALTER TABLE `agent_gap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `num_police` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `client_morale`
--
ALTER TABLE `client_morale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client_physique`
--
ALTER TABLE `client_physique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `facture_details`
--
ALTER TABLE `facture_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fos_user`
--
ALTER TABLE `fos_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `menu_utilisateur`
--
ALTER TABLE `menu_utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modele_pdf`
--
ALTER TABLE `modele_pdf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_social`
--
ALTER TABLE `type_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `type_societe`
--
ALTER TABLE `type_societe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_agence`
--
ALTER TABLE `user_agence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `access_role`
--
ALTER TABLE `access_role`
  ADD CONSTRAINT `FK_A1B0EC6CCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Contraintes pour la table `agent_gap`
--
ALTER TABLE `agent_gap`
  ADD CONSTRAINT `FK_62088A0764C19AA9` FOREIGN KEY (`agence`) REFERENCES `agence` (`id`),
  ADD CONSTRAINT `FK_62088A078D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C74404555841DBAA` FOREIGN KEY (`id_client_physique`) REFERENCES `client_physique` (`id`),
  ADD CONSTRAINT `FK_C744045564C19AA9` FOREIGN KEY (`agence`) REFERENCES `agence` (`id`),
  ADD CONSTRAINT `FK_C744045589F7BF11` FOREIGN KEY (`id_client_morale`) REFERENCES `client_morale` (`id`);

--
-- Contraintes pour la table `client_morale`
--
ALTER TABLE `client_morale`
  ADD CONSTRAINT `FK_37DF6E741285273A` FOREIGN KEY (`id_type_societe`) REFERENCES `type_societe` (`id`);

--
-- Contraintes pour la table `client_physique`
--
ALTER TABLE `client_physique`
  ADD CONSTRAINT `FK_B11F18224AA44748` FOREIGN KEY (`id_type_social`) REFERENCES `type_social` (`id`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `FK_FE86641064C19AA9` FOREIGN KEY (`agence`) REFERENCES `agence` (`id`),
  ADD CONSTRAINT `FK_FE866410C7440455` FOREIGN KEY (`client`) REFERENCES `client` (`num_police`);

--
-- Contraintes pour la table `facture_details`
--
ALTER TABLE `facture_details`
  ADD CONSTRAINT `FK_3AC1AAD3FE866410` FOREIGN KEY (`facture`) REFERENCES `facture` (`id`);

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `FK_F08FC65C8D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`),
  ADD CONSTRAINT `FK_F08FC65CFD772E45` FOREIGN KEY (`user_update`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `FK_7D053A93CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Contraintes pour la table `menu_utilisateur`
--
ALTER TABLE `menu_utilisateur`
  ADD CONSTRAINT `FK_75E07DFF7D053A93` FOREIGN KEY (`menu`) REFERENCES `menu` (`id`),
  ADD CONSTRAINT `FK_75E07DFF8D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `modele_pdf`
--
ALTER TABLE `modele_pdf`
  ADD CONSTRAINT `FK_855D4EB664C19AA9` FOREIGN KEY (`agence`) REFERENCES `agence` (`id`);

--
-- Contraintes pour la table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `FK_DC5D4DE98D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);

--
-- Contraintes pour la table `user_agence`
--
ALTER TABLE `user_agence`
  ADD CONSTRAINT `FK_193819464C19AA9` FOREIGN KEY (`agence`) REFERENCES `agence` (`id`),
  ADD CONSTRAINT `FK_19381948D93D649` FOREIGN KEY (`user`) REFERENCES `fos_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
