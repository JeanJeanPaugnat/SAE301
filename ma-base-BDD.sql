-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 24 oct. 2025 à 12:51
-- Version du serveur : 10.11.14-MariaDB-0+deb12u2
-- Version de PHP : 8.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `paugnat7`
--

-- --------------------------------------------------------

--
-- Structure de la table `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `image` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `Category`
--

INSERT INTO `Category` (`id`, `name`, `image`) VALUES
(1, 'Sacs', 'louis-vuitton-sac-keepall-noir.png'),
(2, 'Blousons', 'louis-vuitton-blouson-en-polaire-bleu.png'),
(3, 'Souliers', 'louis-vuitton-bottine-chelsea-varenne-rouge.png');

-- --------------------------------------------------------

--
-- Structure de la table `Images`
--

CREATE TABLE `Images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `ordre` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Images`
--

INSERT INTO `Images` (`id`, `product_id`, `url`, `alt_text`, `ordre`) VALUES
(6, 39, 'louis-vuitton-blouson-en-polaire-bleu.png', 'front-view', 1),
(7, 40, 'louis-vuitton-blouson-en-polaire-vert.png', 'front-view', 1),
(8, 41, 'louis-vuitton-blouson-fourre-en-maille-monogram.png', 'front-view', 1),
(9, 42, 'louis-vuitton-veste-classique-en-denim-damier.png', 'front-view', 1),
(10, 35, 'louis-vuitton-bottine-chelsea-varenne-noir.png', 'front-view', 1),
(11, 36, 'louis-vuitton-bottine-chelsea-varenne-rouge.png', 'front-view', 1),
(12, 38, 'louis-vuitton-mocassin-major.png', 'front-view', 1),
(13, 37, 'louis-vuitton-sneaker-lv-trainer.png', 'front-view', 1),
(14, 34, 'louis-vuitton-sac-a-dos-christopher-side.png', 'side-view', 2),
(15, 31, 'louis-vuitton-sac-keepall-noir.png', 'front-view', 1),
(16, 32, 'louis-vuitton-sac-keepall-vert.png', 'front-view', 1),
(17, 33, 'louis-vuitton-sac-speedy.png', 'front-view', 1),
(18, 34, 'louis-vuitton-sac-a-dos-christopher.png', 'front-view', 1),
(19, 31, 'louis-vuitton-sac-keepall-noir-side.png', 'side', 2),
(20, 31, 'louis-vuitton-sac-keepall-noir-zoom.png', 'zoom', 3),
(21, 32, 'louis-vuitton-sac-keepall-vert-side.png', 'side', 2),
(22, 32, 'louis-vuitton-sac-keepall-vert-inside.png', 'inside', 3),
(23, 33, 'louis-vuitton-sac-speedy-inside.png', 'inside', 2),
(24, 34, 'louis-vuitton-sac-a-dos-christopher-man.png', 'man', 3),
(25, 36, 'louis-vuitton-bottine-chelsea-varenne-rouge-back.png', 'back', 2),
(26, 35, 'louis-vuitton-bottine-chelsea-varenne-zoom.png', 'zoom', 2);

-- --------------------------------------------------------

--
-- Structure de la table `OrderItems`
--

CREATE TABLE `OrderItems` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `OrderItems`
--

INSERT INTO `OrderItems` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `total_price`) VALUES
(6, 2, 32, 'test', 3, '5000.00', '15000.00'),
(7, 3, 32, 'Sac Keepall Bandoulière', 1, '2400.00', '2400.00'),
(8, 4, 32, 'Sac Keepall Bandoulière', 1, '2400.00', '2400.00'),
(9, 5, 32, 'Sac Keepall Bandoulière', 1, '2400.00', '2400.00'),
(10, 6, 41, 'Blouson fourré en maille Monogram', 3, '3500.00', '10500.00'),
(11, 7, 31, 'Sac Keepall Bandoulière', 1, '2350.00', '2350.00'),
(12, 7, 35, 'Bottine Chelsea Varenne', 1, '1550.00', '1550.00'),
(13, 7, 38, 'Mocassin Major', 1, '990.00', '990.00'),
(14, 8, 31, 'Sac Keepall Bandoulière', 1, '2350.00', '2350.00'),
(15, 9, 34, 'Sac à dos Christopher', 4, '4300.00', '17200.00'),
(16, 10, 34, 'Sac à dos Christopher', 4, '4300.00', '17200.00'),
(17, 10, 41, 'Blouson fourré en maille Monogram', 2, '3500.00', '7000.00'),
(18, 11, 31, 'Sac Keepall Bandoulière', 2, '2350.00', '4700.00'),
(19, 11, 38, 'Mocassin Major', 1, '990.00', '990.00'),
(20, 12, 33, 'Sac Speedy Bandoulière', 10, '3000.70', '30007.00'),
(21, 13, 33, 'Sac Speedy Bandoulière', 20, '3000.70', '60014.00'),
(22, 13, 35, 'Bottine Chelsea Varenne', 5, '1550.00', '7750.00');

-- --------------------------------------------------------

--
-- Structure de la table `Orders`
--

CREATE TABLE `Orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Orders`
--

INSERT INTO `Orders` (`id`, `user_id`, `created_at`) VALUES
(2, 43, '2025-10-23 17:06:36'),
(3, 43, '2025-10-23 15:13:42'),
(4, 43, '2025-10-23 15:16:45'),
(5, 43, '2025-10-23 17:50:45'),
(6, 43, '2025-10-23 17:54:33'),
(7, 43, '2025-10-23 18:14:44'),
(8, 44, '2025-10-24 07:48:25'),
(9, 43, '2025-10-24 11:27:22'),
(10, 43, '2025-10-24 11:37:28'),
(11, 43, '2025-10-24 11:38:01'),
(12, 43, '2025-10-24 11:43:40'),
(13, 43, '2025-10-24 11:45:40');

-- --------------------------------------------------------

--
-- Structure de la table `Product`
--

CREATE TABLE `Product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `category` int(11) NOT NULL,
  `price` decimal(7,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `Product`
--

INSERT INTO `Product` (`id`, `name`, `category`, `price`, `quantity`) VALUES
(31, 'Sac Keepall Bandoulière', 1, '2350.00', 50),
(32, 'Sac Keepall Bandoulière', 1, '2400.00', 45),
(33, 'Sac Speedy Bandoulière', 1, '3000.70', 0),
(34, 'Sac à dos Christopher', 1, '4300.00', 4),
(35, 'Bottine Chelsea Varenne', 3, '1550.00', 20),
(36, 'Bottine Chelsea Varenne', 3, '1650.00', 15),
(37, 'Sneaker LV Trainer', 3, '1100.00', 40),
(38, 'Mocassin Major', 3, '990.00', 35),
(39, 'Blouson en polaire', 2, '1700.00', 0),
(40, 'Blouson en polaire', 2, '1700.00', 55),
(41, 'Blouson fourré en maille Monogram', 2, '3500.00', 18),
(42, 'Veste en denim Damier', 2, '2600.00', 22);

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lastName` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `User`
--

INSERT INTO `User` (`id`, `name`, `lastName`, `email`, `password`) VALUES
(23, 'fff', 'fff', 'ff@gmail.com', 'dfdf'),
(34, 'jeannne', 'Dupont', 'jean@gmail.com', '$2y$10$lr/raxqEDXmXQiof1DqzB.HJrOR/orymW5gKAqbCQ938kqOIzCODS'),
(35, 'dfsdf', 'sdfsdf', 'sf@gmail.com', '$2y$10$JXoDy7RdEB1QBuwgd9DOPO2ie.aM6oIIbj9tjFP.kxlmHR7qsciB2'),
(36, 'vv', 'vv', 'vvvv@gmail.com', '$2y$10$NB8.b9CACKrpREalP.lB.uwqvESLQUqz7fYEsSXOez7Nj6gNrkduK'),
(37, 'qqqq', 'qqqq', 'qq@gmail.com', '$2y$10$MzOVFu/t0rIrLhpSNvbwEuimXe2Tb.c26j3PuSTl.3ffWbkiLDhMy'),
(38, 'hhhh', 'hh', 'hhhh@gmail.com', ''),
(39, 'nn', 'nnnn', 'nn@gmail.com', ''),
(41, 'Inspi', 'inspi', 'inspi@gmail.com', ''),
(42, 'kjhf', 'jhgf', 'kjhgd@gmail.com', '$2y$10$kV5OyuGYaaniACyju79RJ.ThhZs85xTBr/s9CrzwGpmoty5wx7WQu'),
(43, 'bravofffff', 'bravo', 'bravo@gmail.com', '$2y$10$5dJmxgtYyNPfh03WDXo8BOdrhd/HwqbCucob7MOMJCJkOw28n8sLe'),
(44, 'paul', 'paul', 'paul@gmail.com', '$2y$10$lzx1Xa/sycak6TbubMgzgOAFXknrwf.6Zgk4PN.TMXeW4qRidLo0W');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Index pour la table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Index pour la table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Images`
--
ALTER TABLE `Images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `Product`
--
ALTER TABLE `Product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Images`
--
ALTER TABLE `Images`
  ADD CONSTRAINT `Images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD CONSTRAINT `OrderItems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

--
-- Contraintes pour la table `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `category` FOREIGN KEY (`category`) REFERENCES `Category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
