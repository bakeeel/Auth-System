-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 08:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auth_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `reset_token`, `reset_token_expiry`, `created_at`) VALUES
(1, 'Administrator', 'admin', 'admin@example.com', '$2y$10$8TqRXB3YQdwLfPuLVhUQxuPsrUb4RgDJ.8s3ZrL1ZtWqn3Wx.NBha', NULL, NULL, '2024-12-05 13:36:43'),
(2, 'ALI', 'Ali', 'ali@saeed.com', '$2y$10$6gwwqC04gsPTihenPG47f.V6se8zjDE1JXln8LFn0jvoY678SJdzy', NULL, NULL, '2024-12-05 14:14:24'),
(3, 'بكيل احمد محمد مرشد', 'admin1', 'test@example.com', '$2y$10$bz9ESS.l4wI515WrifHVPeIrVavIx7ivqsoUgS4bOYnn/IelAjItu', 'e25cf438c23d765aa9c90a6b9d636fc5cc0ecbe194918408c88f16d075badf86', '2024-12-05 16:27:49', '2024-12-05 14:18:01'),
(4, 'admin77', 'admin2', 'abdulrahamanalasmry@gmail.com', '$2y$10$5YDaYfYcf.WzOC.IFOPiOuWL7uyLGqzxlaRhBnDttjve6cObahDHa', 'cd4b75a114c9743ed009c07ff4a8e5f83bd754f45235d13f711d7ffdc931d66e', '2024-12-05 16:31:03', '2024-12-05 14:19:49'),
(5, 'نبيل محمد محمد حسن', 'nabel', 'nabelhassen@gmail.com', '$2y$10$09YFwti26U1A0LlU5uzbeuQ7FJ3ypPEMnzFN5U64OKlIMEmjlH7VK', NULL, NULL, '2024-12-06 13:34:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
