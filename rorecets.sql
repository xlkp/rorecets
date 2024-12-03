-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-db
-- Generation Time: Dec 03, 2024 at 07:37 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rorecets`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id_comment` int NOT NULL,
  `id_recipe` int NOT NULL,
  `id_user` int NOT NULL,
  `description` text NOT NULL,
  `comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_recipe_comment` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id_comment`, `id_recipe`, `id_user`, `description`, `comment_date`, `id_recipe_comment`) VALUES
(23, 11, 2, 'me ha encantado sigue así', '2024-11-27 15:26:20', NULL),
(29, 11, 1, 'Me he lucido esta vez😋😋😋', '2024-11-27 16:32:28', NULL),
(31, 12, 1, 'Perfecta pentru postasii!!👍🙉', '2024-11-28 14:25:52', NULL),
(43, 15, 1, 'Que pintazaaaa!!!!🙉🙉', '2024-11-28 15:48:10', NULL),
(45, 11, 9, 'la mejor receta', '2024-11-30 22:02:46', NULL),
(47, 11, 1, 'jeje', '2024-12-03 15:36:51', 45),
(50, 15, 2, 'a que siii, me encanta la comida con cerveza jeje😅😅😋😋', '2024-12-03 19:02:25', 43);

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id_follower` int NOT NULL,
  `id_followed` int NOT NULL,
  `follow_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id_follower`, `id_followed`, `follow_date`) VALUES
(1, 9, '2024-12-03 08:01:15'),
(2, 9, '2024-12-03 19:01:40'),
(9, 2, '2024-11-30 22:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id_ingredient` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id_ingredient`, `name`) VALUES
(16, '1 cățel de usturoi pisat'),
(52, '1 catel usturoi'),
(28, '1 foaie dafin'),
(50, '1 kg carne de porc'),
(34, '1 linguriță cu vârf praf de copt'),
(15, '1 ou'),
(41, '1 păstârnac'),
(46, '1 pui'),
(42, '1 rădăcină pătrunjel'),
(43, '1 ţelină rădăcină şi frunze'),
(45, '1-2 cepe'),
(37, '1-2 legaturi pătrunjel'),
(55, '100 ml ulei'),
(36, '130 g unt topit'),
(14, '150 g parmezan ras'),
(44, '2 ardei'),
(12, '2 felii de pâine'),
(35, '2 fiole esență de vanilie'),
(47, '2 morcovi'),
(31, '200 g făină'),
(32, '200 g zahăr tos'),
(8, '2243'),
(24, '3 cepe roşii'),
(7, '3 huevos'),
(53, '3 linguri miere'),
(49, '3 lingurite făină'),
(40, '3-4 boabe piper'),
(25, '3-4 căţei usturoi'),
(30, '3-4 linguri ulei'),
(29, '4 linguri pastă de roşii'),
(23, '400 g fasole'),
(11, '450 g carne tocată de porc'),
(22, '450 grame carne'),
(51, '5 cepe'),
(33, '5 ouă'),
(48, '50 g unt'),
(54, '500 ml bere neagră'),
(13, '60 ml lapte'),
(26, 'boia dulce şi iute'),
(27, 'cimbru'),
(19, 'fulgi de ardei iute'),
(17, 'o legătură pătrunjel tocat'),
(18, 'oregano'),
(21, 'piper'),
(39, 'piper măcinat'),
(9, 'qeqeqdsa'),
(20, 'sare'),
(10, 'sdfgsdgsdg'),
(38, 'zeama de la 1-2 lămâi');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id_rating` int NOT NULL,
  `id_recipe` int NOT NULL,
  `id_user` int NOT NULL,
  `score` tinyint DEFAULT NULL,
  `rating_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id_rating`, `id_recipe`, `id_user`, `score`, `rating_date`) VALUES
(19, 11, 1, 5, '2024-11-26 19:56:40'),
(22, 11, 2, 3, '2024-11-27 15:26:20'),
(30, 12, 1, 5, '2024-11-28 14:25:52'),
(42, 15, 1, 5, '2024-11-28 15:48:10'),
(43, 13, 1, 5, '2024-11-28 18:24:00'),
(44, 11, 9, 5, '2024-11-30 22:02:46'),
(45, 15, 2, 5, '2024-12-03 19:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id_recipe` int NOT NULL,
  `id_user` int NOT NULL,
  `recipe_type` varchar(20) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `instructions` text,
  `difficulty` tinyint DEFAULT NULL,
  `publication_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image_recipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'assets/img/recipes/default.png'
) ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id_recipe`, `id_user`, `recipe_type`, `title`, `description`, `instructions`, `difficulty`, `publication_date`, `image_recipe`) VALUES
(11, 1, 'Tradicional', 'Chiftele de porc marinate', '30 minutos', 'Se prepară sosul de roșii. Într-o tigaie mare antiaderentă, la foc mediu-mare, se rumenește ușor usturoiul în ulei cu frunza de dafin și fulgii de ardei. Se adaugă roșiile și se fierb timp de 30 de minute. Când încep să se înmoaie, se zdrobesc grosier roșiile cu ajutorul unui zdrobitor pentru cartofi. Se condimentează cu sare și piper. Tigaia se ține la cald.\r\nSe pregătesc chitelele. Într-un robot de bucătărie, pisați pâinea până devine pesmet. Într-un castron mare, înmuiați pesmetul în lapte timp de 5 minute. Adăugați carnea tocată, parmezanul, oul, usturoiul, pătrunjelul, sarea, piperul, oregano,fulgii de ardei și, folosind mâinile, amestecați bine.', 2, '2024-11-26 18:21:28', 'chiftele_20241126182128.png'),
(12, 1, 'Tradicional', 'Iahnie de fasole de post', '40 minutos', 'Fasolea se alege, se spală în câteva ape şi apoi se lasă de seara până dimineaţa să se înmoaie. Dimineaţa se scoate şi se pune să fiarbă în 2-3 ape, pentru a elimina toate toxinele. După ce a fiert şi bobul este moale, dar întreg, se pune să se scurgă în sită.\r\n\r\nÎn acest timp, se taie ceapa solzişori, usturoiul se pisează şi se pun să se călească într-o cratiţă cu ulei. Când ceapa a devenit sticloasă, se adaugă fasolea şi puţină apă.\r\n\r\nSeparat, se pregăteşte pasta de roşii cu sare, piper, cimbru şi boia, iar dacă pasta este prea groasă, se adaugă puţină apă călduţă şi se toarnă peste fasole. Se adaugă şi o foaie de dafin şi se lasă să scadă, la foc potrivit, la cuptor. Se serveşte cu murături şi se ornează cu pătrunjel verde.', 1, '2024-11-28 14:22:09', 'fasole de post_20241128142209.webp'),
(13, 1, 'Lamington', 'Prăjitura „Tăvălită cu cocos”', 'Media hora aprox', 'Pentru început, preîncălzește cuptorul la treaptă mare.\r\n\r\nÎntr-un vas mare, bate ouăle cu ajutorul unui mixer. Adaugă, treptat, zahărul și vanilia, până obții un amestec omogen și fin. Toarnă treptat, făina și praful de copt, amestecând ușor și cu gesturi largi cu o spatulă.\r\n\r\nCând s-a omogenizat, toarnă untul (topit și lăsat la temperatura camerei) și amestecă. Pune compoziția într-o tavă care nu se lipește și dă la cuptor 25 de minute. Lasă pandișpanul la răcit și apucă-te de glazură. Topește untul ușor într-o cratiță, toarnă laptele și amestecă. Cerne zahărul și pudra de cacao, amestecă încet până începe să se îngroașe glazura.\r\n\r\nSpre final, adaugă esența de rom. Lasă un pic la răcit glazura. Între timp taie pandișpanul în cubulețe. Înfige, pe rând, o furculiță în fiecare cub în parte, înmoaie bine în glazură până se acoperă uniform, scurge excesul și dă prin nuca de cocos.', 3, '2024-11-28 15:02:56', 'prajitura_20241128150256.webp'),
(15, 1, 'Tradicional', 'Friptură de porc cu bere', '20 minutos si no te bebes la cerveza', 'Friptura de porc este întotdeauna cel mai bun fel principal de mâncare. Printre cele mai consumate rețete, friptura de porc la cuptor, se pregătește extrem de repede, iar gustul este cu adevărat savuros. Astăzi îți spunem o rețetă la fel de simplă, dar în care adăugăm un ingredient în plus: friptură de porc cu bere!\r\n\r\n1. Într-un vas, se topeşte untul şi se pun două cepe tăiate peştişori şi usturoiul. Se potriveşte de sare şi piper, se toarnă berea şi se lasă pe foc circa 10 minute, până se reduce la jumătate.\r\n\r\n2. Între timp, celelalte trei cepe se taie şi ele peştişori şi se pun în tava de copt. Carnea se sărează şi se piperează şi se unge din abundenţă cu ulei. Se aşează carnea peste ceapă şi se unge cu miere. Se dă la cuptor pentru o oră.\r\n\r\n3. După acest timp se adaugă sosul de bere şi ceapă şi se lasă din nou la cuptor până ce şi ceapa şi carnea se rumenesc frumos.\r\n\r\n4. După coacere, se mai lasă 10 minute înainte de a fi tăiată şi adusă la masă.', 3, '2024-11-28 15:14:25', 'fripturabere_20241128151425.webp');

-- --------------------------------------------------------

--
-- Table structure for table `recipes_ingredients`
--

CREATE TABLE `recipes_ingredients` (
  `id_recipe_ingredient` int NOT NULL,
  `id_recipe` int NOT NULL,
  `id_ingredient` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipes_ingredients`
--

INSERT INTO `recipes_ingredients` (`id_recipe_ingredient`, `id_recipe`, `id_ingredient`) VALUES
(162, 11, 22),
(163, 12, 23),
(164, 12, 24),
(165, 12, 25),
(166, 12, 26),
(167, 12, 27),
(168, 12, 28),
(169, 12, 29),
(170, 12, 30),
(171, 13, 31),
(172, 13, 32),
(173, 13, 33),
(174, 13, 34),
(175, 13, 35),
(176, 13, 36),
(177, 15, 20),
(178, 15, 21),
(179, 15, 27),
(180, 15, 48),
(181, 15, 50),
(182, 15, 51),
(183, 15, 52),
(184, 15, 53),
(185, 15, 54),
(186, 15, 55);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `exp` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `pwd`, `exp`, `is_admin`, `registration_date`) VALUES
(1, 'master', 'master@xlkp.com', '$2b$12$qCZuRjvq1qzyaaCkfyMbceqQ/vVT.tAZuvjYUjFnJ33hSZ1F.P9rS', 'creator of many pot holes in history', 1, '2024-11-07 15:16:35'),
(2, 'stef', 'stef@stef.com', '$2y$10$p9KZ6mkL9mvftNY7KvNZmuxCtq2K5O/FRZpjP16SipsgNbthxl3p.', 'im cooked', 0, '2024-11-07 15:23:31'),
(9, 'offmary', 'chirasmaria08@gmail.com', '$2y$10$dungbBNVeIvhGNOWS23xy.094gvkUF8hA/ZSfMQ8SIANPrvap0ajW', 'Intermedio', 0, '2024-11-10 19:24:15');
(13, 'alice', 'alice@example.com', '$2b$12$z.8iAW2FNwHkdXw66Y6Ccu2QV2eaSifS3SLWylzzT92Ah2Dc95.gm', 'Principiante', 0, '2024-12-04 10:30:00'),
(14, 'bob', 'bob@example.com', '$2b$12$phUt8J.U5Uzd7t5ujATKqudGm8DFguBhOdNa0gldOa2KEzozSBoxq', 'Intermedio', 0, '2024-12-04 10:35:00'),
(15, 'charlie', 'charlie@example.com', '$2b$12$VxPt0YsawLpvWl8ArV.YGOnXRelS2PJXLoau7G64n37hw497OUjwe', 'Avanzada', 0, '2024-12-04 10:40:00'),
(16, 'diana', 'diana@example.com', '$2b$12$ZPQO1mPGzI7WLohgF2dO6uL6.sSPVzUK.rxJEiIWSA9Sx.VEJPcOq', 'Principiante', 0, '2024-12-04 10:45:00'),
(17, 'edward', 'edward@example.com', '$2b$12$xzz5OL2NFQeOelkrbak2Y.uYVQxvGPg9XjOUAtx6ovG/fqWrVfDm2', 'Intermedio', 0, '2024-12-04 10:50:00'),
(18, 'fiona', 'fiona@example.com', '$2b$12$bGHshCCGIOdju/bONNqyY.makjcaPlpOkF1tZwS0x.yC2DHWEufVO', 'Avanzada', 0, '2024-12-04 10:55:00'),
(19, 'george', 'george@example.com', '$2b$12$gwg878TkXLeIpTJqiebhSOUSUJUQW2BvDtr4lNeCJUk0JC.NPnPqi', 'Principiante', 0, '2024-12-04 11:00:00'),
(20, 'hannah', 'hannah@example.com', '$2b$12$iPQHBB1jgyR41nhDFXJCR.Ub9QVWmHRN3Jgid0JaatOMiGvVo3e92', 'Intermedio', 0, '2024-12-04 11:05:00'),
(21, 'ian', 'ian@example.com', '$2b$12$TNBT0usyWw/Dt1zjrAaO7e.8lp4Hs5DVySCo0X362ziVxpE9A6i5O', 'Avanzada', 0, '2024-12-04 11:10:00'),
(22, 'julia', 'julia@example.com', '$2b$12$nyiHCtRsRWqXPxP4Oc0JJe7XUTr4sSwQsb.7t/zvB3TEtSQuEeL9K', 'Principiante', 0, '2024-12-04 11:15:00'),
(23, 'kevin', 'kevin@example.com', '$2b$12$DYzOrQH7W5ipa4Siy5M7H.jLaxZ0Yby7vwYtAFTgBbd6Q8Vx/opci', 'Intermedio', 0, '2024-12-04 11:20:00'),
(24, 'linda', 'linda@example.com', '$2b$12$3FKCAX6Z..oJ9sOB8NRJiOZ0WXCVxLqIU6zLqf3hZGptLvzvYuX8O', 'Avanzada', 0, '2024-12-04 11:25:00'),
(25, 'michael', 'michael@example.com', '$2b$12$URuXZimjxoOpOAZoi7GXDe..3kIO3YB4iKXDpufDorpvdPDRUwrRK', 'Principiante', 0, '2024-12-04 11:30:00'),
(26, 'nora', 'nora@example.com', '$2b$12$70l7UsRVQ6ip4kY5LujJNuiJ78WiH2MCmQuWHIsWD2KHkMgV5xMxi', 'Intermedio', 0, '2024-12-04 11:35:00'),
(27, 'oliver', 'oliver@example.com', '$2b$12$.3E0d9VFTOhvpmtD89mbvuAHmn.pUoCoSt07QBntsXTeHM8POJI.C', 'Avanzada', 0, '2024-12-04 11:40:00');



--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_recipe` (`id_recipe`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id_follower`,`id_followed`),
  ADD KEY `id_followed` (`id_followed`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id_ingredient`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id_rating`),
  ADD UNIQUE KEY `id_recipe` (`id_recipe`,`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id_recipe`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `recipes_ingredients`
--
ALTER TABLE `recipes_ingredients`
  ADD PRIMARY KEY (`id_recipe_ingredient`),
  ADD UNIQUE KEY `id_recipe` (`id_recipe`,`id_ingredient`),
  ADD KEY `id_ingredient` (`id_ingredient`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id_ingredient` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id_rating` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id_recipe` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes_ingredients`
--
ALTER TABLE `recipes_ingredients`
  MODIFY `id_recipe_ingredient` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`id_follower`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`id_followed`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `recipes_ingredients`
--
ALTER TABLE `recipes_ingredients`
  ADD CONSTRAINT `recipes_ingredients_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_ingredients_ibfk_2` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id_ingredient`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
