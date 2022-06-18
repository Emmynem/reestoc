-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2022 at 09:20 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cerotics_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `unique_id`, `edit_user_unique_id`, `fullname`, `email`, `phone_number`, `role`, `added_date`, `last_modified`, `access`, `status`) VALUES
(1, 'NlyOCQc69mU7OSWkesn1', 'dAXn9RrXd61LYHNSgI2T', 'Dubur Kingston', 'duburking@gmail.com', '08056845978', 14, '2021-07-24 11:29:43', '2021-09-17 18:15:18', 1, 1),
(2, 'jBWTEIl4vtfwOCjOU28A', 'dAXn9RrXd61LYHNSgI2T', 'Harry Davis', 'davisharry@gmail.com', '09054879632', 15, '2021-07-24 19:32:08', '2021-09-18 01:10:42', 1, 1),
(3, 'KJznfOUtMo5rr4SQux53', 'dAXn9RrXd61LYHNSgI2T', 'Henry Gideon', NULL, '08096556689', 15, '2021-08-07 03:20:34', '2021-09-17 18:14:18', 1, 1),
(4, '6zamLIXmhC0f47T9JVfB', 'dAXn9RrXd61LYHNSgI2T', 'Emmanuel Nwoye', 'emmanuelnwoye5@gmail.com', '+2348093223317', 14, '2021-09-17 18:10:39', '2021-09-17 18:10:39', 1, 1),
(5, '4GhBoOTc1tNxWN8tyFQE', 'dAXn9RrXd61LYHNSgI2T', 'Richard Gigi', 'gigirichardofficial@gmail.com', NULL, 14, '2021-09-18 01:14:54', '2021-09-18 01:17:19', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `agents_addresses`
--

CREATE TABLE `agents_addresses` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `additional_information` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agents_addresses`
--

INSERT INTO `agents_addresses` (`id`, `unique_id`, `user_unique_id`, `firstname`, `lastname`, `address`, `additional_information`, `city`, `state`, `country`, `added_date`, `last_modified`, `status`) VALUES
(1, 'Ak1tP1TuPXOLAeLqdjjL', 'NlyOCQc69mU7OSWkesn1', 'Dubur', 'Kingston', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:30:41', '2021-07-24 11:30:41', 1),
(2, 'EcT0lx2XC5nZiaF7Le9n', 'NlyOCQc69mU7OSWkesn1', 'Dubur', 'Kingston', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:30:42', '2021-07-24 11:30:42', 1),
(3, 'OsfUsLEl7ytOIlBeAcmD', 'NlyOCQc69mU7OSWkesn1', 'Dubur', 'Kingston', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:30:43', '2021-07-24 11:30:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `agents_kyc`
--

CREATE TABLE `agents_kyc` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `front_image` varchar(50) NOT NULL,
  `back_image` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `approval` varchar(20) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL,
  `stripped` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `user_unique_id`, `edit_user_unique_id`, `unique_id`, `category`, `stripped`, `added_date`, `last_modified`, `status`) VALUES
(1, 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AXH230x6heYdHYO4OwOe', 'Daily Dose', 'daily-dose', '2021-09-22 21:15:30', '2021-09-22 21:43:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `blog_images`
--

CREATE TABLE `blog_images` (
  `id` int(11) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `post_title` varchar(150) NOT NULL,
  `stripped` varchar(200) NOT NULL,
  `category_unique_id` varchar(20) NOT NULL,
  `bg_image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `post_details` longtext NOT NULL,
  `views` bigint(15) NOT NULL,
  `drafted` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `user_unique_id`, `edit_user_unique_id`, `unique_id`, `author_name`, `post_title`, `stripped`, `category_unique_id`, `bg_image`, `file`, `file_size`, `post_details`, `views`, `drafted`, `added_date`, `last_modified`, `status`) VALUES
(1, 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NZ2Bn6atrCusMr9iqhMe', 'Emmanuel Nwoye', 'A word for the wise', 'a-word-for-the-wise', 'AXH230x6heYdHYO4OwOe', 'https://www.reestoc.com/images/blog_images/background_images/1632345048.webp', '1632345048.webp', 11507570, '<p><img src=\"https://hellobeautifulworld.net/gallery/1605024111.png\" style=\"width: 100%;\"><br></p><p><u>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium nam quas inventore, ut iure iste modi eos adipisci ad ea itaque labore earum autem nobis et numquam, minima eius. Nam eius, non unde ut aut sunt eveniet rerum repellendus porro.</u></p><p><b>Sint ab voluptates itaque, ipsum porro qui obcaecati cumque quas sit vel. Voluptatum provident id quis quo. Eveniet maiores perferendis officia veniam est laborum, expedita fuga doloribus natus repellendus dolorem ab similique sint eius cupiditate necessitatibus, magni nesciunt ex eos.</b></p><p><i>Quis eius aspernatur, eaque culpa cumque reiciendis, nobis at earum assumenda similique ut? Aperiam vel aut, ex exercitationem eos consequuntur eaque culpa totam, deserunt, aspernatur quae eveniet hic provident ullam tempora error repudiandae sapiente illum rerum itaque voluptatem. Commodi, sequi.</i></p><p><br></p><p><img src=\"https://hellobeautifulworld.net/gallery/1604910925.png\" style=\"width: 100%;\"></p><p><strike>Quibusdam autem, quas molestias recusandae aperiam molestiae modi qui ipsam vel. Placeat tenetur veritatis tempore quos impedit dicta, error autem, quae sint inventore ipsa quidem. Quo voluptate quisquam reiciendis, minus, animi minima eum officia doloremque repellat eos, odio doloribus cum.</strike></p><p><sup>Temporibus quo dolore veritatis doloribus delectus dolores perspiciatis recusandae ducimus, nisi quod, incidunt ut quaerat, magnam cupiditate. Aut, laboriosam magnam, nobis dolore fugiat impedit necessitatibus nisi cupiditate, quas repellat itaque molestias sit libero voluptas eveniet omnis illo ullam dolorem minima.</sup></p><p><sub>Porro amet accusantium libero fugit totam, deserunt ipsa, dolorem, vero expedita illo similique saepe nisi deleniti. Cumque, laboriosam, porro! Facilis voluptatem sequi nulla quidem, provident eius quos pariatur maxime sapiente illo nostrum quibusdam aliquid fugiat! Earum quod fuga id officia.</sub></p><p><img src=\"https://hellobeautifulworld.net/gallery/1607697365.jpg\" style=\"width: 100%;\"><br></p><p><span>Illo magnam at dolore ad enim fugiat ut maxime facilis autem, nulla cumque quis commodi eos nisi unde soluta, ipsa eius aspernatur sint atque! Nihil, eveniet illo ea, mollitia fuga accusamus dolor dolorem perspiciatis rerum hic, consectetur error rem aspernatur!</span></p><p><a href=\"http://www.google.com\" target=\"_blank\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus magni explicabo id molestiae, minima quas assumenda consectetur, nobis neque rem, incidunt quam tempore perferendis provident obcaecati sapiente, animi vel expedita omnis quae ipsa! Obcaecati eligendi sed odio labore vero reiciendis facere accusamus molestias eaque impedit, consequuntur quae fuga vitae fugit?</a></p><p><img src=\"https://hellobeautifulworld.net/gallery/1605124447.png\" style=\"width: 100%;\"><br></p><p><br></p>', 1, 0, '2021-09-22 21:49:02', '2021-09-22 22:10:53', 1),
(2, 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BU4PrTiaQuYAXPOp1gTI', 'Emmanuel Nwoye', 'Maybe I don\'t know but maybe that\'s ok', 'maybe-i-dont-know-but-maybe-thats-ok', 'AXH230x6heYdHYO4OwOe', 'https://www.reestoc.com/images/blog_images/background_images/1632343800.webp', '1632343800.webp', 96455, '<p><img src=\"https://hellobeautifulworld.net/gallery/1605024111.png\" style=\"width: 100%;\"><br></p><p><u>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium nam quas inventore, ut iure iste modi eos adipisci ad ea itaque labore earum autem nobis et numquam, minima eius. Nam eius, non unde ut aut sunt eveniet rerum repellendus porro.</u></p><p><b>Sint ab voluptates itaque, ipsum porro qui obcaecati cumque quas sit vel. Voluptatum provident id quis quo. Eveniet maiores perferendis officia veniam est laborum, expedita fuga doloribus natus repellendus dolorem ab similique sint eius cupiditate necessitatibus, magni nesciunt ex eos.</b></p><p><i>Quis eius aspernatur, eaque culpa cumque reiciendis, nobis at earum assumenda similique ut? Aperiam vel aut, ex exercitationem eos consequuntur eaque culpa totam, deserunt, aspernatur quae eveniet hic provident ullam tempora error repudiandae sapiente illum rerum itaque voluptatem. Commodi, sequi.</i></p><p><br></p><p><img src=\"https://hellobeautifulworld.net/gallery/1604910925.png\" style=\"width: 100%;\"></p><p><strike>Quibusdam autem, quas molestias recusandae aperiam molestiae modi qui ipsam vel. Placeat tenetur veritatis tempore quos impedit dicta, error autem, quae sint inventore ipsa quidem. Quo voluptate quisquam reiciendis, minus, animi minima eum officia doloremque repellat eos, odio doloribus cum.</strike></p><p><sup>Temporibus quo dolore veritatis doloribus delectus dolores perspiciatis recusandae ducimus, nisi quod, incidunt ut quaerat, magnam cupiditate. Aut, laboriosam magnam, nobis dolore fugiat impedit necessitatibus nisi cupiditate, quas repellat itaque molestias sit libero voluptas eveniet omnis illo ullam dolorem minima.</sup></p><p><sub>Porro amet accusantium libero fugit totam, deserunt ipsa, dolorem, vero expedita illo similique saepe nisi deleniti. Cumque, laboriosam, porro! Facilis voluptatem sequi nulla quidem, provident eius quos pariatur maxime sapiente illo nostrum quibusdam aliquid fugiat! Earum quod fuga id officia.</sub></p><p><img src=\"https://hellobeautifulworld.net/gallery/1607697365.jpg\" style=\"width: 100%;\"><br></p><p><span>Illo magnam at dolore ad enim fugiat ut maxime facilis autem, nulla cumque quis commodi eos nisi unde soluta, ipsa eius aspernatur sint atque! Nihil, eveniet illo ea, mollitia fuga accusamus dolor dolorem perspiciatis rerum hic, consectetur error rem aspernatur!</span></p><p><a href=\"http://www.google.com\" target=\"_blank\">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus magni explicabo id molestiae, minima quas assumenda consectetur, nobis neque rem, incidunt quam tempore perferendis provident obcaecati sapiente, animi vel expedita omnis quae ipsa! Obcaecati eligendi sed odio labore vero reiciendis facere accusamus molestias eaque impedit, consequuntur quae fuga vitae fugit?</a></p><p><img src=\"https://hellobeautifulworld.net/gallery/1605124447.png\" style=\"width: 100%;\"><br></p><p><br></p>', 0, 0, '2021-09-22 22:05:35', '2021-09-22 22:08:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stripped` varchar(50) NOT NULL,
  `details` varchar(500) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `name`, `stripped`, `details`, `added_date`, `last_modified`, `status`) VALUES
(1, 'xrxzhl90bs0x4p5KPFCC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Nescafe', 'nescafe', 'NescafÃ© is a brand of coffee made by NestlÃ©. It comes in many different forms. The name is a portmanteau of the words \"NestlÃ©\" and \"cafÃ©\". NestlÃ© first introduced their flagship coffee brand in Switzerland on 1 April 1938. Source : Wikipedia', '2021-09-02 18:05:21', '2021-09-02 18:05:21', 1),
(2, 'C1tHrWOkqevKUrqeh4Fu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Coca-Cola', 'coca-cola', 'The Coca-Cola Company is an American multinational beverage corporation incorporated under Delaware\'s General Corporation Law and headquartered in Atlanta, Georgia. Wikipedia', '2021-09-02 18:38:26', '2021-09-02 18:38:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `brand_images`
--

CREATE TABLE `brand_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `brand_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brand_images`
--

INSERT INTO `brand_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `brand_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(2, 'sxYpBCzsbL0VpmybSSJT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'C1tHrWOkqevKUrqeh4Fu', 'https://www.reestoc.com/brand_images/1630627928.jpg', '1630627928.jpg', 43454, '2021-09-03 01:12:08', '2021-09-03 01:12:08', 1),
(3, 'OjMDvLHAqf1G0ES9Ulz5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'xrxzhl90bs0x4p5KPFCC', 'https://www.reestoc.com/brand_images/1630627968.jpg', '1630627968.jpg', 62414, '2021-09-03 01:12:48', '2021-09-03 01:12:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `shipping_fee_unique_id` varchar(20) DEFAULT NULL,
  `pickup_location` tinyint(1) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `quantity`, `shipping_fee_unique_id`, `pickup_location`, `added_date`, `last_modified`, `status`) VALUES
(1, 'PpoV41JWzwWwJ9iIkdFx', 'rzl5nk7rIHDpqMUbHuz9', 'cgPcth9HTnqGjrCf6VWD', 10, 'kKJikmrpFYJcoVZhf7im', 0, '2021-09-04 04:24:50', '2021-09-04 05:15:36', 2),
(2, '5lfbkHycTriGlcBdGzn7', 'rzl5nk7rIHDpqMUbHuz9', 'KZ7yG9bGYZVI1rjxoh40', 10, 'NfKYg3yUdKiKr3q8nqxY', 0, '2021-09-07 23:38:48', '2021-09-08 00:12:18', 2),
(3, 'EziW6XwKr92XtVGUj2cl', 'rzl5nk7rIHDpqMUbHuz9', 'KZ7yG9bGYZVI1rjxoh40', 1, 'NfKYg3yUdKiKr3q8nqxY', 0, '2021-09-08 02:03:13', '2021-09-08 02:06:07', 2),
(4, '7hyGRa32s1hXwQmjxN0f', 'rzl5nk7rIHDpqMUbHuz9', 'cgPcth9HTnqGjrCf6VWD', 3, 'kKJikmrpFYJcoVZhf7im', 0, '2021-09-08 02:03:21', '2021-09-08 02:06:07', 2),
(5, 'vwyTDlRGhrYIlfAWsGZq', 'oiH9fzKVpI8jLubuBqUK', 'KZ7yG9bGYZVI1rjxoh40', 4, 'AZeQfaY0cg4PNl53RgNJ', 0, '2021-09-18 12:15:15', '2021-09-19 12:54:14', 2),
(6, 'zbZjftYWgBEnp0sa7rK5', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 5, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-09-18 12:23:20', '2021-09-19 12:54:14', 2),
(7, 'FB0igDOKiHAaUlewSFBL', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', 20, 'xJKsWG9DLv0Q2rMZhu1Q', 0, '2021-09-18 20:03:54', '2021-09-19 18:50:07', 2),
(8, 'IjzfXZsUVTjk4vLrI2LJ', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', 30, 'xJKsWG9DLv0Q2rMZhu1Q', 0, '2021-09-19 19:18:29', '2021-09-20 19:15:03', 2),
(9, '65b7etHKs7NAXCeXE6Ja', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 10, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-09-21 15:17:03', '2021-09-21 15:28:17', 2),
(10, 'zZfmh3J4KRsugAetC81F', 'oiH9fzKVpI8jLubuBqUK', 'TO6o8Sx8qr1X0STjB0Cl', 6, '2PR7vMUUFjzefNXgFriD', 0, '2021-09-21 15:21:41', '2021-09-21 15:28:17', 2),
(11, 'OFSQABfRpjl6wie3shFC', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 20, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-09-29 23:03:53', '2021-09-29 23:09:26', 2),
(12, 'Y8HaUYjrGPhLLMlUqFh3', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 10, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-09-29 23:04:10', '2021-09-29 23:09:26', 2),
(13, 'CptI0lSeBSpalLohe916', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 5, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-09-29 23:16:01', '2021-09-29 23:39:01', 2),
(14, 'Vgq4JojQ4Hq7sqAQd4pL', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 5, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-10-04 18:33:33', '2021-10-22 21:16:03', 2),
(15, 'qtPfUJ5ywE8EqMjai1oz', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 30, 'dHnPCd6pV53XT7AYsZxj', 0, '2021-10-04 18:33:46', '2021-10-22 21:19:25', 2),
(16, 'PJDCFirewjzMffLthf7n', 'oiH9fzKVpI8jLubuBqUK', 'TO6o8Sx8qr1X0STjB0Cl', 10, '2PR7vMUUFjzefNXgFriD', 0, '2021-10-04 18:36:09', '2021-10-22 18:49:20', 2),
(17, 'elVqAamp9l4X9ommSrg6', 'oiH9fzKVpI8jLubuBqUK', 'KZ7yG9bGYZVI1rjxoh40', 20, 'AZeQfaY0cg4PNl53RgNJ', 0, '2021-10-04 18:36:20', '2021-10-22 21:19:25', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stripped` varchar(100) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `name`, `stripped`, `added_date`, `last_modified`, `status`) VALUES
(1, '1LMqS4xwFkhIqHw07uKu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Grocery', 'grocery', '2021-08-22 13:13:02', '2021-08-29 22:26:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_images`
--

CREATE TABLE `category_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `the_category_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `post_unique_id` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `unique_id`, `name`, `email`, `message`, `post_unique_id`, `added_date`, `last_modified`, `status`) VALUES
(1, '7ovjBtEfPpKosmcJdOvd', 'Emmanuel Jason', 'emmanu@gmail.com', 'Somethig enee', 'BU4PrTiaQuYAXPOp1gTI', '2021-09-22 00:00:00', '2021-09-22 22:15:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) DEFAULT NULL,
  `sub_product_unique_id` varchar(20) DEFAULT NULL,
  `mini_category_unique_id` varchar(20) DEFAULT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `percentage` double NOT NULL,
  `total_count` int(11) NOT NULL,
  `current_count` int(11) NOT NULL,
  `completion` varchar(20) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `mini_category_unique_id`, `code`, `name`, `percentage`, `total_count`, `current_count`, `completion`, `expiry_date`, `added_date`, `last_modified`, `status`) VALUES
(1, 'gGkGJsF6y5tWqR5QB8i2', NULL, 'GKqmoEXM88Tjj1JXk5UP', NULL, 'WEEKENDSALES', 'Weekend sales', 30, 100, 92, 'Processing', '2021-10-13 23:59:59', '2021-07-16 18:29:21', '2021-09-20 19:35:00', 1),
(2, 'KnmVOJgPSZE4PHZOeDwU', '5t8JzH7pMoiuaisW6dfl', NULL, NULL, 'HFD-2021', 'Happy Father\'s Day', 20, 1, 1, 'Processing', '2021-07-30 23:59:59', '2021-07-16 23:28:54', '2021-07-16 23:28:54', 1),
(3, 'LhW46479S5medVNHh4R1', 'BXveKWZrgUccZ3udo5ef', NULL, NULL, 'HFD-2021', 'Happy Father\'s Day', 20, 1, 1, 'Processing', '2021-07-30 23:59:59', '2021-07-16 23:28:54', '2021-07-16 23:28:54', 1),
(4, 'rk2tj8pmRvTXppBBB1hy', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'HFD-2021', 'Happy Father\'s Day', 20, 1, 0, 'Completed', '2021-07-30 23:59:59', '2021-07-16 23:28:54', '2021-07-18 15:09:32', 1),
(5, 'aMTQqoluLgaYZxFAwoqE', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'HFD-2021', 'Happy Father\'s Day', 20, 1, 1, 'Processing', '2021-07-30 23:59:59', '2021-07-16 23:28:54', '2021-07-16 23:28:54', 1),
(6, 'MmrmI7Y7uyFsO054vr5s', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'PROMOND', 'Monday Promo', 10, 1, 0, 'Completed', '2021-07-20 00:00:00', '2021-07-16 23:32:06', '2021-07-18 16:08:27', 1),
(7, '0ubeJOnYJx0Wx5k8qMBd', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'PROMOND', 'Monday Promo', 20, 1, 1, 'Processing', '2021-10-20 23:59:59', '2021-07-16 23:32:06', '2021-09-19 16:50:58', 1),
(8, 'L7ny9DwWgtyFqpnmdgs2', NULL, 'KZ7yG9bGYZVI1rjxoh40', NULL, 'WEEKEND78', 'Weekend sales', 20, 100, 99, 'Processing', '2021-10-25 23:59:59', '2021-07-17 23:41:46', '2021-09-19 17:33:37', 1),
(9, 'NEOXNkzcn1e6tP6e2tKL', NULL, 'cgPcth9HTnqGjrCf6VWD', NULL, 'WEEKEND89', 'Weekend sales 89', 50, 50, 48, 'Processing', '2021-09-30 23:59:59', '2021-09-04 05:19:48', '2021-09-04 23:51:35', 1),
(10, 'zI0MgLo2ABffS0GsyWwx', NULL, 'TO6o8Sx8qr1X0STjB0Cl', NULL, 'TUFROZEN', 'Tuesday Elsa', 5, 10, 10, 'Processing', '2021-09-22 05:00:00', '2021-09-21 05:00:26', '2021-09-21 05:00:26', 1),
(12, '7DcTdmzxRTkNJJpxUNCM', NULL, NULL, 'B9ngayUK3lbQruGbzqat', 'CHILLEDTUESDAY', 'Tuesday Chilled Drinks', 10, 100, 98, 'Processing', '2021-09-21 23:59:00', '2021-09-21 12:02:14', '2021-10-21 16:29:20', 1),
(15, '6zBWiXZnBIXc3sLSQQ76', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'WEDNEXTDAY', 'Wednesday specials', 10, 1, 1, 'Processing', '2021-09-22 11:59:00', '2021-09-21 19:17:17', '2021-09-21 19:17:17', 1),
(16, 'u1XMRojTYzp4q3OZkPpB', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'WEDNEXTDAY', 'Wednesday specials', 10, 1, 1, 'Processing', '2021-09-22 11:59:00', '2021-09-21 19:17:17', '2021-09-21 19:17:17', 1),
(17, 'Opcpgbfj0cgedDNsyzsF', 'tPBIE40TyKjOu0A35Joe', NULL, NULL, 'WEDNEXTDAY', 'Wednesday specials', 10, 1, 1, 'Processing', '2021-09-22 11:59:00', '2021-09-21 19:17:17', '2021-09-21 19:17:17', 1),
(18, 'FF2Hl3CrolRTngtPL4xQ', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'WEDNEXTDAY', 'Wednesday specials', 10, 1, 1, 'Processing', '2021-09-22 11:59:00', '2021-09-21 19:17:17', '2021-09-21 19:17:17', 1),
(23, 'FfWz1yQy9iEGkYyNYa0w', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'WELCOME-BACK', 'Welcome back', 7, 1, 1, 'Processing', '2021-09-22 23:59:00', '2021-09-22 07:26:38', '2021-09-22 07:26:38', 1),
(24, 'VwsdqKtzmTlrlgVvFDHb', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'WELCOME-BACK', 'Welcome back', 7, 1, 1, 'Processing', '2021-09-22 23:59:00', '2021-09-22 07:26:38', '2021-09-22 07:26:38', 1),
(27, 'jcQuy9G4RF6KgUMc452b', 'tPBIE40TyKjOu0A35Joe', NULL, NULL, 'WEL-STOCKER', 'Sign up coupon', 10, 1, 1, 'Processing', '2021-09-30 12:00:00', '2021-09-22 08:42:52', '2021-09-22 08:42:52', 1),
(28, 'ihBf1oCBatRuBZzccz49', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'WEL-STOCKER', 'Sign up coupon', 10, 1, 1, 'Processing', '2021-09-30 12:00:00', '2021-09-22 08:42:52', '2021-09-22 08:42:52', 1),
(29, 'ZVDInEmx23daqq2qwNXV', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'WEL-STOCKER', 'Sign up coupon', 10, 1, 0, 'Completed', '2021-09-30 12:00:00', '2021-09-22 08:42:52', '2021-09-29 23:42:13', 1),
(30, 'MtsVqB0wmBMrAn4eUWZF', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'WEL-STOCKER', 'Sign up coupon', 10, 1, 1, 'Processing', '2021-09-30 12:00:00', '2021-09-22 08:42:52', '2021-09-22 08:42:52', 1),
(31, 'YCcAsCCIZpa3thIiYlSb', NULL, NULL, 'HE2cBUz5YoVsM5RwWy5m', 'GO-FRESHERS', 'Refreshing week', 5, 50, 50, 'Processing', '2021-10-09 18:00:00', '2021-09-22 08:44:31', '2021-09-22 08:44:31', 1),
(32, 'xYav0mSh2IZ5ZjmE3Y7F', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'TENOCT', 'Tenth October Sales', 10, 5, 5, 'Processing', '2021-10-28 23:59:00', '2021-10-04 16:40:42', '2021-10-04 16:40:42', 1),
(33, 'caPnlp6OApXguRJihBEV', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'TENOCT', 'Tenth October Sales', 10, 5, 5, 'Processing', '2021-10-04 23:59:00', '2021-10-04 16:40:42', '2021-10-04 16:40:42', 1),
(34, 'EfqBDH8H44CZr2gWlcC7', 'FIx1NsUOzWnIeLp970CQ', NULL, NULL, 'WELCOME-COUPON', 'Welcome to reestoc coupon', 5, 1, 1, 'Processing', '2021-11-06 00:00:00', '2021-10-06 16:53:55', '2021-10-06 16:53:55', 1),
(35, '3i9MmcRM0zJlEVRbDe9z', 'ejm525FluTiIUQkSTNqK', NULL, NULL, 'WELCOME-COUPON', 'Welcome to reestoc coupon', 5, 1, 1, 'Processing', '2021-11-06 00:00:00', '2021-10-06 16:56:51', '2021-10-06 16:56:51', 1),
(36, 'Emu5V2lOCVOZ2gn8J12z', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(37, 'Bhb6bLlYqjVdrdc7sQ7d', 'bztRqJ7WTLOC32XmOwws', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(38, 'lztv5EPwgMMkq1GtfDoL', 'tPBIE40TyKjOu0A35Joe', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(39, 'ntRiHs2f720fjtD5QT2e', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(40, 'ZV2NgRJps3KWMU82w14L', 'FIx1NsUOzWnIeLp970CQ', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(41, '5kJ3rciQjEA0EYUW0M3E', 'ejm525FluTiIUQkSTNqK', NULL, NULL, 'ENDSARS', 'End police brutality coupon', 20, 1, 1, 'Processing', '2021-10-31 16:26:00', '2021-10-21 16:26:23', '2021-10-21 16:26:23', 1),
(42, 'NTs1prXU5MzkhPWfTd4z', 'UGjnvlj9mzxoc6qn5pwq', NULL, NULL, 'WELCOME-COUPON', 'Welcome to reestoc coupon', 5, 1, 1, 'Processing', '2021-11-25 00:00:00', '2021-10-25 22:39:46', '2021-10-25 22:39:46', 1),
(43, '8M6JoPt5mpVcxAHaMX4N', 'RAMspiJHFk2hc2Dmyb7J', NULL, NULL, 'WELCOME-COUPON', 'Welcome to reestoc coupon', 5, 1, 1, 'Processing', '2021-12-18 00:00:00', '2021-11-18 12:18:14', '2021-11-18 12:18:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupon_history`
--

CREATE TABLE `coupon_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) DEFAULT NULL,
  `sub_product_unique_id` varchar(20) DEFAULT NULL,
  `mini_category_unique_id` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `completion` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coupon_history`
--

INSERT INTO `coupon_history` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `mini_category_unique_id`, `name`, `price`, `completion`, `added_date`, `last_modified`, `status`) VALUES
(1, 'xjiDTOki5afyfB7ZJSVK', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'Happy Father\'s Day', 2000, 'Completed', '2021-07-18 15:09:32', '2021-07-18 15:09:32', 1),
(2, 'SCk17zDNcmqdRbrzpasn', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 1000, 'Completed', '2021-07-18 15:12:27', '2021-07-18 15:12:27', 1),
(3, 'qq97sEAvH8SJliJUaYhs', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 1000, 'Completed', '2021-07-18 16:05:23', '2021-07-18 16:05:23', 1),
(4, 'aICzwubHoELS7066hL7V', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 1000, 'Completed', '2021-07-18 16:08:27', '2021-07-18 16:08:27', 1),
(5, 'kU0PSPoj7sj6SpC1YWuZ', 'rzl5nk7rIHDpqMUbHuz9', NULL, NULL, 'Monday Promo', 1000, 'Completed', '2021-07-18 16:08:27', '2021-07-18 16:08:27', 1),
(6, 'SHvQcRJaedg4pvhkjuW6', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 3000, 'Completed', '2021-07-23 04:42:44', '2021-07-23 04:42:44', 1),
(7, 'X3LKkzaCZumYLMk18qeB', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 3000, 'Completed', '2021-07-23 04:59:49', '2021-07-23 04:59:49', 1),
(8, 'u7cF65p1QQw0Ct9sfvIx', 'rzl5nk7rIHDpqMUbHuz9', 'mn7uFgo9HyoUi0G13mCs', NULL, 'Weekend sales', 3000, 'Completed', '2021-07-24 22:54:37', '2021-07-24 22:54:37', 1),
(10, '01f5QvuLLoCGYV6TJ8my', 'rzl5nk7rIHDpqMUbHuz9', 'cgPcth9HTnqGjrCf6VWD', NULL, 'Weekend sales 89', 2000, 'Completed', '2021-09-04 23:51:35', '2021-09-04 23:51:35', 1),
(11, 'H47JFwtYDIesvAYYf24A', 'oiH9fzKVpI8jLubuBqUK', 'KZ7yG9bGYZVI1rjxoh40', NULL, 'Weekend sales', 700, 'Completed', '2021-09-19 17:33:37', '2021-09-19 17:33:37', 1),
(12, '5jnsuoyGhdAwKjPfm2FR', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', NULL, 'Weekend sales', 30, 'Completed', '2021-09-19 19:07:22', '2021-09-19 19:07:22', 1),
(13, 'sGJVHsKxvAnfOmbdBuqM', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', NULL, 'Weekend sales', 900, 'Completed', '2021-09-20 19:35:00', '2021-09-20 19:35:00', 1),
(14, 'iCUfBE1acnBH0zpTArSO', 'oiH9fzKVpI8jLubuBqUK', NULL, 'B9ngayUK3lbQruGbzqat', 'Tuesday Chilled Drinks', 200, 'Completed', '2021-09-21 16:27:15', '2021-09-21 16:27:15', 1),
(15, 'btZ9yJF0J24djxKz1gwi', 'oiH9fzKVpI8jLubuBqUK', NULL, 'B9ngayUK3lbQruGbzqat', 'Tuesday Chilled Drinks', 210, 'Completed', '2021-09-21 16:29:20', '2021-09-21 16:29:20', 1),
(16, 'oZQ33WXcE45M98LflCZ9', 'oiH9fzKVpI8jLubuBqUK', NULL, NULL, 'Sign up coupon', 100, 'Completed', '2021-09-29 23:42:13', '2021-09-29 23:42:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `default_pickup_locations`
--

CREATE TABLE `default_pickup_locations` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `additional_information` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `default_pickup_locations`
--

INSERT INTO `default_pickup_locations` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `firstname`, `lastname`, `address`, `additional_information`, `city`, `state`, `country`, `added_date`, `last_modified`, `status`) VALUES
(1, 'c8Rja9R3TQKwJLxbVZPN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Reestoc', 'Diobu', 'No 34 Emekuku Street, Diobu, Port Harcourt, Rivers State, Nigeria', 'Opposite Fabros lightning', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', '2021-10-08 11:00:00', '2021-10-11 15:32:22', 1),
(2, 'haIM5KUVluo9APCBM2lF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Reestoc', 'Mile 1', 'No 88 Ikewerre Road, Mile 1, Port Harcourt, Rivers State, Nigeria', 'Close to Mentos market complex', 'PORTHARCOURT-MILE 1', 'Rivers', 'Nigeria', '2021-10-08 06:00:00', '2021-10-08 09:00:00', 1),
(5, 'Q0SVc2OQUdSo4ZQH35DB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Reestoc', 'Agip', '16 Agip road, Port Harcourt, Rivers, Nigeria', 'After kilimanjaro fast food', 'PORTHARCOURT-AGIP', 'Rivers', 'Nigeria', '2021-10-11 11:02:08', '2021-10-12 17:23:49', 1),
(6, 'bc2XFVE9YlBcxGuEvUAV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Reestoc', 'Diobu 2', '45 Aguma Street, Diobu, Port Harcourt, Rivers, Country', NULL, 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', '2021-10-11 15:57:02', '2021-10-11 15:59:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `order_unique_id` varchar(20) NOT NULL,
  `message` varchar(500) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disputes`
--

INSERT INTO `disputes` (`id`, `unique_id`, `user_unique_id`, `order_unique_id`, `message`, `added_date`, `last_modified`, `status`) VALUES
(1, 'xK0U1mzf0aUF5dHMMm5n', 'rzl5nk7rIHDpqMUbHuz9', 'tUfKMiOHTyXaVGXKcgqr', 'I don\'t want it again', '2021-07-19 00:18:59', '2021-07-19 00:18:59', 1),
(2, 'zOEJLIawEGhJw4NWGjOu', 'rzl5nk7rIHDpqMUbHuz9', 'tUfKMiOHTyXaVGXKcgqr', 'I don\'t want it again', '2021-07-19 00:19:58', '2021-07-19 00:19:58', 1),
(3, '4ElTFT921C7U17HecqSD', 'rzl5nk7rIHDpqMUbHuz9', 'AmhpU0yAzti0pm7PP4Q7', 'Wrong order', '2021-09-08 00:05:37', '2021-09-08 00:05:37', 1),
(4, 'IyVgqNpM8Xsajm7mZSuS', 'oiH9fzKVpI8jLubuBqUK', '64SKQYcPGH5vUW0Nwu0C', 'Order is Unpaid', '2021-09-29 23:29:54', '2021-09-29 23:29:54', 1),
(5, 'QIW87mxmQUhHswKolERk', 'oiH9fzKVpI8jLubuBqUK', 'OY4X4RYTLw5D7hFrgrTx', 'Order is Unpaid', '2021-09-29 23:33:00', '2021-09-29 23:33:00', 1),
(9, 'zI31j1Y17Dx10Rvz0UZ4', 'oiH9fzKVpI8jLubuBqUK', 'XoYW3nv8cdEvP2WF70tl', 'It comes with an opener also which you don\'t easily find in the open market.', '2021-10-22 21:14:25', '2021-10-22 21:14:25', 1),
(10, 'vxCeKvfYRy44WRJht75n', 'oiH9fzKVpI8jLubuBqUK', 'J2I8mW34opd5YK4WL3Xb', 'Not really feeling the product you know', '2021-10-22 21:17:18', '2021-10-22 21:17:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `enquiry_status` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `display_title` varchar(300) NOT NULL,
  `stripped` varchar(350) NOT NULL,
  `event_name` longtext NOT NULL,
  `event_date_start` date NOT NULL,
  `event_time_start` time NOT NULL,
  `event_date_end` date NOT NULL,
  `event_time_end` time NOT NULL,
  `event_location` varchar(100) NOT NULL,
  `event_image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `event_categories` varchar(30) NOT NULL,
  `event_tags` varchar(30) NOT NULL,
  `event_venue` text NOT NULL,
  `event_organizers` text NOT NULL,
  `total_no_of_tickets` int(11) NOT NULL,
  `tickets_left` int(11) NOT NULL,
  `drafted` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_unique_id`, `edit_user_unique_id`, `unique_id`, `display_title`, `stripped`, `event_name`, `event_date_start`, `event_time_start`, `event_date_end`, `event_time_end`, `event_location`, `event_image`, `file`, `file_size`, `event_categories`, `event_tags`, `event_venue`, `event_organizers`, `total_no_of_tickets`, `tickets_left`, `drafted`, `added_date`, `last_modified`, `status`) VALUES
(1, 'U7F3B0DFAE', 'U7F3B0DFAE', 'ee8634b91a', 'WAAS 2021', 'waas-2021', 'The West Africa Automotive Show 2021 (WAAS)', '2021-07-13', '16:00:00', '2021-07-15', '21:00:00', '02 Arena, Gra, Port Harcourt, Rivers State, Nigeria', 'office/images/events/1608586757.JPG', '', 0, 'Car Show', 'showcases, automobile, africa', '<p>Visit our Website: https://westafricaautomotive.com/<br><br>For more information please contact Ken Baber:<br>T: +44 2476 158098<br>M: +44 7478 010029<br>Whatsapp: +234 8090657106<br>Email: ken.baber@btob-events.com<br></p>', '<p>The West Africa Automotive Show is the largest automotive spare parts and services exhibition across sub-Saharan Africa. WAAS brings together suppliers, dealers and manufacturers, providing a networking platform for all those involved in the West Africa Automotive Industry, including auto parts and solutions. The 2020 edition will host over 150 exhibitors and 3000+ visitors across 2 halls, enabling industry professionals to see what automotive products are available from across the globe.<br></p>', 20000, 20000, 0, '2020-12-19 20:12:51', '2020-12-21 22:39:17', 1),
(2, 'UB4FA2D7E2', 'UB4FA2D7E2', 'ed006f166d', 'Starboy Fest 2020', 'starboy-fest-2020', '<b>At vero eos</b> et accusamus et iusto odi odgnissimos ducimus qui blanditiis praesentium volup tatum deleniti atque <u>corrupti quos dolores et </u>quas molestias excepturi sint occaecati cupiditate non provident, <a href=\"http://www.google.com\" target=\"_blank\">similique sunt in culpa</a> qui officia deserunt mollitia animi quod justo&nbsp;', '2020-12-21', '19:00:00', '2020-12-25', '22:00:00', 'Arena Victoria Island, Lekki phase 1', 'office/images/events/1608409700.jpg', '', 0, 'Festival', 'festivals, music', '<p><a href=\"https://www.beautywestafrica.com\" target=\"_blank\">Beauty West Africa</a> is Africa&#8217;s largest professional Beauty Exhibition &amp; Conference. For the 2020 edition, exhibitors will showcase the latest in beauty, including makeup, hair styling, wigs and extensions, skincare, perfumes and fragrances, spa and professional equipment and more across 3 halls. Companies from across the world will come together under one roof and do business with West Africa&#8217;s largest importers, distributors, retailers and other stakeholders across the billion-dollar beauty sector. The three-day event will see 250+ exhibitors and 5000+ industry professionals in attendance and will also host an educational conference with talks from keynote speakers, beauty influencers and celebrity stylists.<br></p>', '<p><a href=\"https://www.beautywestafrica.com/\" target=\"_blank\">Beauty West Africa</a>&#160;is Africa&#8217;s largest professional Beauty Exhibition &amp; Conference. For the 2020 edition, exhibitors will showcase the latest in beauty, including makeup, hair styling, wigs and extensions, skincare, perfumes and fragrances, spa and professional equipment and more across 3 halls. Companies from across the world will come together under one roof and do business with West Africa&#8217;s largest importers, distributors, retailers and other stakeholders across the billion-dollar beauty sector. The three-day event will see 250+ exhibitors and 5000+ industry professionals in attendance and will also host an educational conference with talks from keynote speakers, beauty influencers and celebrity stylists.<br></p>', 200000, 200000, 0, '2020-12-19 21:59:27', '2021-03-14 14:47:29', 1),
(3, 'UB4FA2D7E2', 'U4636809AF', 'e2f3d43dca', 'Inauguration of the Trinidad Beach party', 'inauguration-of-the-trinidad-beach-party', '<p>Held in Europeâ€™s electronic music capital on New Yearâ€™s Eve, the seminal Funkhaus Berlin hosts an impressive roster of techno artists worthy. Soundtracking the leap from 2019 into 2020 in what is one of the most anticipated nights of the year, in one of the cityâ€™s most hyped venues, HYTE Berli</p><p><br></p><p><img src=\"https://hellobeautifulworld.net/gallery/1604910925.png\" style=\"width: 100%;\"><br></p>', '2021-05-12', '16:00:00', '2021-06-12', '22:00:00', 'Funkhaus Berlin, Berlin, Germany', 'office/images/events/1623194111.jpg', '', 0, 'Party, Beach, Opening', 'party, beach, opening', '<p>Visit our Website: <a href=\"https://westafricaautomotive.com/\" target=\"_blank\">Click to visit</a><br><br>For more information please contact Ken Baber:<br>T: +44 2476 158098<br>M: +44 7478 010029<br>Whatsapp: +234 8090657106<br>Email: ken.baber@btob-events.com<br></p>', '<p>Held in Europe&#8217;s electronic music capital on New Year&#8217;s Eve, the seminal Funkhaus Berlin hosts an impressive roster of techno artists worthy.</p><p><br></p><p>Soundtracking the leap from 2019 into 2020 in what is one of the most anticipated nights of the year, in one of the city&#8217;s most hyped venues, <a href=\"http://www.google.com\" target=\"_blank\">HYTE </a>&#160;Berlin NYE is the</p>', 5000, 5000, 0, '2021-03-07 14:18:04', '2021-06-09 00:15:11', 1),
(4, 'UB4FA2D7E2', 'U4636809AF', 'e9b7f3fed0', 'Guts over fear rave party By Eminem and Sia', 'guts-over-fear-rave-party-by-eminem-and-sia', '<p><a href=\"https://www.beautywestafrica.com/\" target=\"_blank\" style=\"touch-action: manipulation;\">Beauty West Africa</a>&nbsp;is Africaâ€™s largest professional Beauty Exhibition &amp; Conference. For the 2020 edition, exhibitors will showcase the latest in beauty, including makeup, hair styling, wigs and extensions, skincare, perfumes and fragrances, spa and professional equipment and more across 3 halls. Companies from across the world will come together under one roof and do business with West Africaâ€™s largest importers, distributors, retailers and other stakeholders across the billion-dollar beauty sector. The three-day event will see 250+ exhibitors and 5000+ industry professionals in attendance and will also host an educational conference with talks from keynote speakers, beauty influencers and celebrity stylists.<br></p>', '2021-08-15', '20:00:00', '2021-08-18', '23:00:00', 'Kilimanjaro GRA', 'office/images/events/1623191623.jpg', '', 0, 'Music, Rave Party', 'music, rave, party', '<p><span>When my sounds of anguish were muted, a notable improvement from the screams of my first bikini-line wax. The real surprise came when I got on all fours to wax my nether region. Let me tell you, I was bracing for pain, hands down.&#160; No lie!&#160; I braced for the pain, closed my eyes, and popped them open with an &#8220; oof!&#8221; shuck and awe replaced any expectant pain when all&#160;</span><span><b>I FELT WAS A TUG</b></span><span>. According to the esthetician, Crysande, the nether region has the least nerve endings so very little pain is experienced.&#160; The next time someone calls you &#8220;A pain in the arse&#8221;, keep whistling and say &#8220;not as much as you would think&#8221;.&#160;</span><br></p>', '<p><span>Never have I ever been more disappointed to win a prize, as I was in 2019, to win a BRAZILIAN Wax from</span><a href=\"https://www.facebook.com/groups/101396683240325/\">&#160;Crysande&#8217;s Spa and&#160; Make-up Studio</a><span>. OUCH!!!&#160; Little did I know it would become one of the handiest prizes in a life-altering journey of 2020.&#160;</span><br></p>', 0, 0, 0, '2021-03-14 15:12:31', '2021-08-12 16:44:11', 1),
(5, 'U4636809AF', 'U4636809AF', 'e2ae5b06d3', 'Tones and I hangout party', 'tones-and-i-hangout-party', '<p><img src=\"https://hellobeautifulworld.net/gallery/1604840744.png\" style=\"width: 100%;\"><br></p><p>Held in Europeâ€™s electronic music capital on New Yearâ€™s Eve, the seminal Funkhaus Berlin hosts an impressive roster of techno artists worthy. Soundtracking the leap from 2019 into 2020 in what is one of the most anticipated nights of the year, in one of the cityâ€™s most hyped venues, HYTE Berli</p><p><img src=\"https://hellobeautifulworld.net/gallery/1603861505.jpg\" style=\"width: 100%;\"><br></p>', '2021-08-19', '17:00:00', '2021-08-26', '22:30:00', 'Funkhaus Berlin, Berlin, Germany', 'office/images/events/1624305466.webp', '', 0, 'Festival', 'festivals, car show', '<p>Visit our Website: https://westafricaautomotive.com/<br><br>For more information please contact Ken Baber:<br>T: +44 2476 158098<br>M: +44 7478 010029<br>Whatsapp: +234 8090657106<br>Email: ken.baber@btob-events.com<br></p>', '<p>The West Africa Automotive Show is the largest automotive spare parts and services exhibition across sub-Saharan Africa. WAAS brings together suppliers, dealers and manufacturers, providing a networking platform for all those involved in the West Africa Automotive Industry, including auto parts and solutions. The 2020 edition will host over 150 exhibitors and 3000+ visitors across 2 halls, enabling industry professionals to see what automotive products are available from across the globe.<br></p>', 0, 0, 0, '2021-06-21 20:56:24', '2021-08-16 15:11:31', 1),
(6, 'U4636809AF', 'U4636809AF', 'eb2cca8e9b', 'Mufasa hangout - Preacher Kingz', 'mufasa-hangout-preacher-kingz', '<p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><u>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium nam quas inventore, ut iure iste modi eos adipisci ad ea itaque labore earum autem nobis et numquam, minima eius. Nam eius, non unde ut aut sunt eveniet rerum repellendus porro.</u></p><p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><span style=\"font-weight: bolder;\">Sint ab voluptates itaque, ipsum porro qui obcaecati cumque quas sit vel. Voluptatum provident id quis quo. Eveniet maiores perferendis officia veniam est laborum, expedita fuga doloribus natus repellendus dolorem ab similique sint eius cupiditate necessitatibus, magni nesciunt ex eos.</span></p><p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><i>Quis eius aspernatur, eaque culpa cumque reiciendis, nobis at earum assumenda similique ut? Aperiam vel aut, ex exercitationem eos consequuntur eaque culpa totam, deserunt, aspernatur quae eveniet hic provident ullam tempora error repudiandae sapiente illum rerum itaque voluptatem. Commodi, sequi.</i></p>', '2021-06-10', '17:00:00', '2021-06-15', '23:30:00', 'Arena Victoria Island, Lekki phase 1', 'office/images/events/1623193626.jpg', '', 0, 'Music, Hangout', 'hangout, preacher kingz', '<p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><br></p><p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><i>Quis eius aspernatur, eaque culpa cumque reiciendis, nobis at earum assumenda similique ut? Aperiam vel aut, ex exercitationem eos consequuntur eaque culpa totam, deserunt, aspernatur quae eveniet hic provident ullam tempora error repudiandae sapiente illum rerum itaque voluptatem. Commodi, sequi.</i></p>', '<p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><br></p><p style=\"margin-bottom: 1rem; color: rgb(108, 117, 125); font-family: &quot;Josefin Sans&quot;, arial, sans-serif; font-size: 18px;\"><span style=\"font-weight: bolder;\">Sint ab voluptates itaque, ipsum porro qui obcaecati cumque quas sit vel. Voluptatum provident id quis quo. Eveniet maiores perferendis officia veniam est laborum, expedita fuga doloribus natus repellendus dolorem ab similique sint eius cupiditate necessitatibus, magni nesciunt ex eos.</span></p>', 0, 0, 1, '2021-06-08 23:50:20', '2021-06-09 00:07:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `product_unique_id` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `unique_id`, `user_unique_id`, `product_unique_id`, `added_date`, `last_modified`, `status`) VALUES
(1, 'DYuSilkhx3h2TgpenGBl', 'rzl5nk7rIHDpqMUbHuz9', 'ZYmiB5A4ec2dpqRc9RjL', '2021-07-07 13:02:54', '2021-07-07 13:02:54', 1),
(2, 'WDejNO3N6RCCPCKRYPts', 'oiH9fzKVpI8jLubuBqUK', 'ZuGUnC2vz8pFOOyxwVFj', '2021-07-07 13:03:00', '2021-07-07 13:03:00', 1),
(3, 'tCeCfaQCPPPZoyhi73pl', 'oiH9fzKVpI8jLubuBqUK', 'NoCDRMowFlelV4crZLuL', '2021-07-07 13:03:16', '2021-07-07 13:03:16', 1),
(4, 'GpkuJOX0rT7VLaq7oW2W', 'oiH9fzKVpI8jLubuBqUK', 'BIGfY7300msTzFiD1Mu3', '2021-07-07 13:03:42', '2021-10-22 03:11:28', 1),
(5, 'QqFiPniBfanSNpia5sun', 'oiH9fzKVpI8jLubuBqUK', 'ZYmiB5A4ec2dpqRc9RjL', '2021-10-20 15:34:06', '2021-10-22 03:08:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `save_as_name` varchar(150) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_extension` varchar(200) NOT NULL,
  `file_size` bigint(10) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_unique_id`, `unique_id`, `save_as_name`, `file_name`, `file_extension`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(2, 'dAXn9RrXd61LYHNSgI2T', 'rgsYKn7rzJJL4UNZ0sMI', 'Go daddy', '1630247934_kusina-master.zip', 'office/files-manager/1630247934_kusina-master.zip', 11997836, '2021-08-29 15:38:58', '2021-08-29 15:38:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `flash_deals`
--

CREATE TABLE `flash_deals` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `flash_deals`
--

INSERT INTO `flash_deals` (`id`, `unique_id`, `user_unique_id`, `url`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(6, 'FJ41E370AChZiuYBvW2r', 'dAXn9RrXd61LYHNSgI2T', 'reestoc.com', 'https://www.reestoc.com/images/flash_deal_images/1632701679.webp', '1632701679.webp', 96455, '2021-09-27 01:14:39', '2021-09-27 01:14:39', 1),
(7, 'LRJr1fzozySw2YIkdp5e', 'dAXn9RrXd61LYHNSgI2T', 'reestoc.com/p/honeycrisp-apples-bag', 'https://www.reestoc.com/images/flash_deal_images/1632701843.gif', '1632701843.gif', 2065454, '2021-09-27 01:17:23', '2021-09-27 01:17:23', 1),
(8, 'CcawhTdzBCPOHEGlwYGN', 'dAXn9RrXd61LYHNSgI2T', 'reestoc.com/p/honeycrisp-apples-bag', 'https://www.reestoc.com/images/flash_deal_images/1632702152.gif', '1632702152.gif', 5325452, '2021-09-27 01:22:32', '2021-09-27 01:22:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE `management` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`id`, `unique_id`, `edit_user_unique_id`, `fullname`, `email`, `phone_number`, `role`, `added_date`, `last_modified`, `access`, `status`) VALUES
(1, 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Emmanuel Nwoye', 'emmanuelnwoye5@gmail.com', '08093223317', 1, '2021-07-01 00:00:00', '2021-07-03 17:29:23', 1, 1),
(2, 'IirXfq2xquI8F7NRWIk3', 'dAXn9RrXd61LYHNSgI2T', 'Emmanuel David', 'emmanueldavid@gmail.com', '08065321478', 2, '2021-07-02 12:38:54', '2021-07-02 12:38:54', 1, 1),
(3, 'hyVF49B9QnMdeOJGIXex', 'IirXfq2xquI8F7NRWIk3', 'David Kennedy', 'davidkennedy@gmail.com', '08156987523', 10, '2021-07-02 13:11:58', '2021-07-02 13:11:58', 1, 1),
(4, 'EvSE5PFX0zm04C7pnh3I', 'dAXn9RrXd61LYHNSgI2T', 'Jennifer Hudson', 'jhudson@gmail.com', '08092147788', 3, '2021-07-02 13:37:45', '2021-09-17 13:32:35', 3, 1),
(5, 'HIerdyQ177F8KzYAzOtq', 'dAXn9RrXd61LYHNSgI2T', 'Wisdom Barisuka Jake', 'wisdomjake@gmail.com', '08096454548', 3, '2021-07-02 18:33:19', '2021-07-03 17:42:32', 1, 1),
(7, 'aR7pz31cHCD8T1Cq3UDu', 'dAXn9RrXd61LYHNSgI2T', 'Richard Gigi', 'gigirichardofficial@gmail.com', NULL, 1, '2021-09-17 15:59:08', '2021-09-17 16:02:43', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `management_addresses`
--

CREATE TABLE `management_addresses` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `additional_information` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `management_addresses`
--

INSERT INTO `management_addresses` (`id`, `unique_id`, `user_unique_id`, `firstname`, `lastname`, `address`, `additional_information`, `city`, `state`, `country`, `added_date`, `last_modified`, `status`) VALUES
(1, 'ykEpaZWLQYEpmE3s6XON', 'IirXfq2xquI8F7NRWIk3', 'Emmanuel', 'David', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:26:35', '2021-07-24 11:26:35', 1),
(2, '8oAfpIbtDeAlS3mOcxm8', 'IirXfq2xquI8F7NRWIk3', 'Emmanuel', 'David', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:26:37', '2021-07-24 11:26:37', 1),
(3, '8GjGctq4H96oRrIOORJA', 'IirXfq2xquI8F7NRWIk3', 'Emmanuel', 'David', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:26:39', '2021-07-24 11:26:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `management_kyc`
--

CREATE TABLE `management_kyc` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `front_image` varchar(50) NOT NULL,
  `back_image` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `approval` varchar(20) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `management_navigation`
--

CREATE TABLE `management_navigation` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `nav_title` varchar(30) NOT NULL,
  `nav_link` varchar(30) NOT NULL,
  `nav_icon` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `management_navigation`
--

INSERT INTO `management_navigation` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `nav_title`, `nav_link`, `nav_icon`, `added_date`, `last_modified`, `status`) VALUES
(1, 'GNiJuO7lSmyMcsuaVnew', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Product Categories', 'product-categories', 'layout', '2021-08-29 15:55:37', '2021-08-29 21:14:25', 1),
(2, 'fdUXbNSD9JgHpVs5cjTu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Product Mini Categories', 'product-mini-categories', 'layout', '2021-08-29 20:21:32', '2021-08-29 21:32:51', 1),
(3, 'b83G36PSpYsBpll93duW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Product Sub Categories', 'product-sub-categories', 'layout', '2021-08-29 21:47:49', '2021-08-29 21:47:49', 1),
(4, 'cBr7VIc3TfWARYQl9Yj8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Products', 'products', 'shopping-bag', '2021-08-30 16:17:55', '2021-08-30 16:17:55', 1),
(5, 'qbpGj6CQ8DA45nW2AODq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Brands', 'brands', 'award', '2021-09-02 17:05:22', '2021-09-11 23:53:23', 1),
(6, 'arkSO7c0CU8eVTl81BTM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Sub Products', 'sub-products', 'shopping-bag', '2021-09-03 01:16:28', '2021-09-03 01:16:28', 1),
(7, '3HfRJvDiCfwUJYpqKeMn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Cart', 'carts', 'shopping-cart', '2021-09-05 03:32:37', '2021-09-05 03:32:37', 1),
(8, 'NqmUs5r98amVEB8JEQWF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Orders', 'orders', 'server', '2021-09-07 21:29:49', '2021-09-07 21:29:49', 1),
(9, 'dgZDzyZqUeAznRx7z60F', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Shipments', 'shipments', 'truck', '2021-09-10 11:38:04', '2021-09-10 11:38:04', 1),
(10, 'PPLVpYJJqqxT3zCuXb7i', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Riders', 'riders', 'users', '2021-09-11 17:25:43', '2021-09-11 17:25:43', 1),
(11, 'y4m9P9bS4mIPvVk6i2YZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Offered Services', 'offered-services', 'cloud-drizzle', '2021-09-11 23:51:46', '2021-09-12 00:40:04', 1),
(12, 'RBt0rqOqi4l4ScWo9A25', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'O-S\'s Category', 'offered-services-categories', 'cloud', '2021-09-12 00:57:32', '2021-09-12 01:00:42', 1),
(13, 'GnZAFkbcs2wf6aEjxmKV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Shipping Fees', 'shipping-fees', 'dollar-sign', '2021-09-15 17:24:54', '2021-09-15 17:24:54', 1),
(14, 'AQlgLlfiMro1OORGQT1I', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Management Users', 'management-users', 'users', '2021-09-17 09:59:13', '2021-09-17 09:59:13', 1),
(15, 'LjlOd5bgCFPWXsPvbmVV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Agents', 'agents', 'users', '2021-09-17 17:55:24', '2021-09-17 17:55:24', 1),
(16, 'eD8ljbUWLgfPsmmy0wMY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Users', 'users', 'users', '2021-09-17 17:55:49', '2021-09-17 17:57:23', 1),
(17, 'uC6FPBTpXy6L85S4UHU9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Coupons', 'coupons', 'percent', '2021-09-18 02:51:09', '2021-09-18 02:51:09', 1),
(18, '0meYczN6Wu069OHm7yRx', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Blog posts', 'all-posts', 'rss', '2021-09-22 13:43:13', '2021-09-22 13:43:13', 1),
(19, 'BQKWWRZy7XG0RnlJMpGQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Blog Images', 'blog-images', 'image', '2021-09-22 13:44:06', '2021-09-22 13:44:06', 1),
(20, 'GBZIAEaNZS1ZKeWzh8rV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Blog Categories', 'categories', 'target', '2021-09-22 21:06:33', '2021-09-22 21:06:33', 1),
(21, '3d6VmnmWNRst3oHk4oUQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Flash Deals', 'flash-deals', 'slash', '2021-09-26 22:25:42', '2021-09-26 22:25:42', 1),
(22, 'hpu5yrga6WmDfuxmPZVd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Pop Up Deals', 'pop-up-deals', 'slash', '2021-09-27 01:39:53', '2021-09-27 01:39:53', 1),
(23, 'trGLF8thgKkEhwrR670m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Sharing', 'sharing', 'share-2', '2021-09-30 13:23:02', '2021-09-30 13:23:02', 1),
(24, 'ZHqLrJ89fhGRZASuryBc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Pickup Locations', 'pickup-locations', 'map-pin', '2021-10-10 23:17:49', '2021-10-10 23:17:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mini_category`
--

CREATE TABLE `mini_category` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `category_unique_id` varchar(20) DEFAULT NULL,
  `sub_category_unique_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `stripped` varchar(100) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mini_category`
--

INSERT INTO `mini_category` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `category_unique_id`, `sub_category_unique_id`, `name`, `stripped`, `added_date`, `last_modified`, `status`) VALUES
(1, 'HE2cBUz5YoVsM5RwWy5m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Fresh Fruits', 'fresh-fruits', '2021-08-22 13:15:58', '2021-08-22 13:15:58', 1),
(2, 'KQ52lrrBu6QBbH3GCe3L', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Fresh Vegetables', 'fresh-vegetables', '2021-08-22 13:16:09', '2021-08-30 15:54:35', 1),
(3, 'kyvVXFKDIK6btZHTtD3f', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Fresh Prepared Produce', 'fresh-prepared-produce', '2021-08-25 00:02:09', '2021-08-25 00:02:09', 1),
(4, 'BBH1CfRdxGGxt6tGSO4y', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Fresh Herbs', 'fresh-herbs', '2021-08-25 00:02:18', '2021-08-25 00:02:18', 1),
(5, 'Y7U6gX8iOLN7oj91ivhd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Nuts Dried Fruit & Healthy Snacks', 'nuts-dried-fruit-healthy-snacks', '2021-08-25 00:02:31', '2021-08-25 00:02:31', 1),
(6, 'k5oirUXl97TysStvP39f', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'XbHIBZ6c7CreZvnUvH41', 'Fresh Flowers', 'fresh-flowers', '2021-08-25 00:02:39', '2021-08-25 00:02:39', 1),
(7, 's9YhtbevvFXlGfOBbx4P', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Canned goods', 'canned-goods', '2021-08-25 00:05:06', '2021-08-25 00:05:06', 1),
(8, 'trYXDR62v5klBzec5KKl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Condiments', 'condiments', '2021-08-25 00:05:14', '2021-08-25 00:05:14', 1),
(9, 'BD4qkuH0viRwqpthyPAc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Baking', 'baking', '2021-08-25 00:05:21', '2021-08-25 00:05:21', 1),
(10, 'WHbWX01KMxceo5FrAGVk', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Cereal & Breakfast Food', 'cereal-breakfast-food', '2021-08-25 00:05:28', '2021-08-25 00:05:28', 1),
(11, 'ZAYADqisVryiGMfmlwGS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Pasta & Pizza', 'pasta-pizza', '2021-08-25 00:05:39', '2021-08-25 00:05:39', 1),
(12, 'KtfzNPic18XjDzRbLnPh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Herbs, Spices & Seasoning', 'herbs-spices-seasoning', '2021-08-25 00:05:47', '2021-08-25 00:05:47', 1),
(13, 'UnW3ch8IwqPN4TryfeXL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Soup', 'soup', '2021-08-25 00:05:55', '2021-08-25 00:05:55', 1),
(14, 'ySzETokablXrLPXUbtlK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Boxed Dinners', 'boxed-dinners', '2021-08-25 00:06:03', '2021-08-25 00:06:03', 1),
(15, 'jBAynH9RxkVLUZmKzVC9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'International Foods', 'international-foods', '2021-08-25 00:06:10', '2021-08-25 00:06:10', 1),
(16, 'JV4wiZfoMItPokOXoM5B', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Stock Up Pantry', 'stock-up-pantry', '2021-08-25 00:06:17', '2021-08-25 00:06:17', 1),
(17, 'NrwNgs9WEqVNECBM0gAZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Cooking Oils & Vinegar', 'cooking-oils-vinegar', '2021-08-25 00:06:28', '2021-08-25 00:06:28', 1),
(18, 'xkNIOtcmyKf3ofKngXUN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Rice, Grains & Dried Beans', 'rice-grains-dried-beans', '2021-08-25 00:07:01', '2021-08-25 00:07:01', 1),
(19, 'oLYE9T2ei9zaa82ZKgts', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'QrQp4BxphMlHGBXnZcvm', 'Potatoes & Stuffing', 'potatoes-stuffing', '2021-08-25 00:07:12', '2021-08-25 00:07:12', 1),
(20, 'TJ4cntibVGHN3QsaDFbU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Cheese', 'cheese', '2021-08-25 00:08:43', '2021-08-25 00:08:43', 1),
(21, 'TbQoaIASm9oWjRjCEkmr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Milk', 'milk', '2021-08-25 00:08:50', '2021-08-25 00:08:50', 1),
(22, '21mDSvdH2YUrB3X5WZeY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Yogurt', 'yogurt', '2021-08-25 00:08:59', '2021-08-25 00:08:59', 1),
(23, 'ig2GXIFmm8wvWiJ7zhfT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Eggs', 'eggs', '2021-08-25 00:09:06', '2021-08-25 00:09:06', 1),
(24, 'iUQqhbykr4EL10Y1n5gT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Cream & Creamers', 'cream-creamers', '2021-08-25 00:09:13', '2021-08-25 00:09:13', 1),
(25, 'N1usPsq0QJumSV4G4SHX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Butter & Margarine', 'butter-margarine', '2021-08-25 00:09:21', '2021-08-25 00:09:21', 1),
(26, 'zlaO6VvopryThdEwsRas', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Sour Cream & Dip', 'sour-cream-dip', '2021-08-25 00:09:27', '2021-08-25 00:09:27', 1),
(27, 'DA5Z0cglHYnLZAQGVlDa', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Biscuits Cookies Doughs & Crusts', 'biscuits-cookies-doughs-crusts', '2021-08-25 00:09:36', '2021-08-25 00:09:36', 1),
(28, 'GarEvjyQ1ZSjjvRGMXkK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Pudding & Gelatin', 'pudding-gelatin', '2021-08-25 00:09:45', '2021-08-25 00:09:45', 1),
(29, '9hWFT0rtlEgrt9k2odv5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '9x1V9hsrIEYbtf3dBRfv', 'Healthy Snacks & Beverages', 'healthy-snacks-beverages', '2021-08-25 00:09:53', '2021-08-25 00:09:53', 1),
(30, 'YnfGOBYo9Od4gLjxHlkG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Ice Cream', 'ice-cream', '2021-08-25 00:11:17', '2021-08-25 00:11:17', 1),
(31, 'bow1qB0EZ5EGuHfoGAte', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Fruits & Vegetables', 'frozen-fruits-vegetables', '2021-08-25 00:11:26', '2021-08-25 00:11:26', 1),
(32, 'y3y1aJvhWcl3OGpsEEui', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Individual Meals', 'frozen-individual-meals', '2021-08-25 00:11:37', '2021-08-25 00:11:37', 1),
(33, 'kO0lG8TUnC20POxTl9Ty', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Meat & Seafood', 'frozen-meat-seafood', '2021-08-25 00:11:45', '2021-08-25 00:11:45', 1),
(34, 'bwkIYiQkgEbiR8DKCVV1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Family Meals', 'frozen-family-meals', '2021-08-25 00:11:51', '2021-08-25 00:11:51', 1),
(35, 'g7FUAG2n0iCFTfLez9Ju', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Bread & Potatoes', 'frozen-bread-potatoes', '2021-08-25 00:12:00', '2021-08-25 00:12:00', 1),
(36, '5Xjxst2zgRph7D3sYbGX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Snacks & Appetizers', 'frozen-snacks-appetizers', '2021-08-25 00:12:16', '2021-08-25 00:12:16', 1),
(37, 'crsJZ1AR7WuC3STfpKri', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Breakfast', 'frozen-breakfast', '2021-08-25 00:12:23', '2021-08-25 00:12:23', 1),
(38, 'ljJZyrrF3jQXNLTrUHWe', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Pizza & Pasta', 'frozen-pizza-pasta', '2021-08-25 00:12:31', '2021-08-25 00:12:31', 1),
(39, 'WTsRJgIRSzQCxhSwWt7c', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Desserts', 'frozen-desserts', '2021-08-25 00:12:38', '2021-08-25 00:12:38', 1),
(40, 'eHJ7zKDmiiwt5fCRuT6Q', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Kids Meals', 'frozen-kids-meals', '2021-08-25 00:12:46', '2021-08-25 00:12:46', 1),
(41, 'kVz3eA679qosb0lfXRCD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Meals', 'frozen-meals', '2021-08-25 00:12:53', '2021-08-25 00:12:53', 1),
(42, 'KPcKHAhBoNwoNRidFHpC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Frozen Beverages & Ice', 'frozen-beverages-ice', '2021-08-25 00:13:02', '2021-08-25 00:13:02', 1),
(43, '0lFVsv0RFeq2each8RCr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'tiSwMuRqIw1fl9FU349n', 'Healthy Meals & Snacks', 'healthy-meals-snacks', '2021-08-25 00:13:12', '2021-08-25 00:13:12', 1),
(44, 'vIYUBHEvqdPhR4GTqMSR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Fruit Juice', 'fruit-juice', '2021-08-25 00:14:42', '2021-08-25 00:14:42', 1),
(45, 'B9ngayUK3lbQruGbzqat', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Soft Drinks', 'soft-drinks', '2021-08-25 00:14:51', '2021-08-25 00:14:51', 1),
(46, '97bnNADg85ZC0zXXldJw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Water', 'water', '2021-08-25 00:14:58', '2021-08-25 00:14:58', 1),
(47, 'xgFxabZpFukDD623D8ji', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Cocktails & Mixers', 'cocktails-mixers', '2021-08-25 00:15:19', '2021-08-25 00:15:19', 1),
(48, 'xNfnKRoOXO754FJ4F88A', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Sports & Energy Drinks', 'sports-energy-drinks', '2021-08-25 00:15:26', '2021-08-25 00:15:26', 1),
(49, 'yjqojjbositQem00rdKJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Coffee', 'coffee', '2021-08-25 00:15:34', '2021-08-25 00:15:34', 1),
(50, 'okXpP9Pn1fkh4m1Dh5K5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Fresh Juice & Chilled Beverage', 'fresh-juice-chilled-beverage', '2021-08-25 00:15:43', '2021-08-25 00:15:43', 1),
(51, 'WYw25aWCkrZWRYFUKpky', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Tea & Hot Chocolate', 'tea-hot-chocolate', '2021-08-25 00:15:52', '2021-08-25 00:15:52', 1),
(52, 'Ql9yufuKndxBJPkq0Ol8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Specialty Drinks', 'specialty-drinks', '2021-08-25 00:15:59', '2021-08-25 00:15:59', 1),
(53, 'HJw6klaHKtD93xCGHEfM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'qDKEDodAwF7kPVSzdlml', 'Beers', 'beers', '2021-08-25 00:16:07', '2021-08-25 00:16:07', 1),
(54, 'J17THOIDzT1Exjw7BeT5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Chips & Dip', 'chips-dip', '2021-08-25 00:16:46', '2021-08-25 00:16:46', 1),
(55, 'pm0lyzxKCZQGhMWuI47q', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Cookies & Crackers', 'cookies-crackers', '2021-08-25 00:16:53', '2021-08-25 00:16:53', 1),
(56, 'eUVY7qdeAMIJTheiuQhY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Chocolate, Candy & Gum', 'chocolate-candy-gum', '2021-08-25 00:17:02', '2021-08-25 00:17:02', 1),
(57, 'KiZpdqd4D1HheMpE0mVB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Nuts & Dried Fruit', 'nuts-dried-fruit', '2021-08-25 00:17:09', '2021-08-25 00:17:09', 1),
(58, 'WzJqXPPcswc1aiZGGh9D', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Popcorn & Pretzels', 'popcorn-pretzels', '2021-08-25 00:17:16', '2021-08-25 00:17:16', 1),
(59, '1xrKDlRJJzBWnTwhOONP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Fruit Snacks', 'fruit-snacks', '2021-08-25 00:17:23', '2021-08-25 00:17:23', 1),
(60, '16DkgYAhchKuvitvXtQk', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Jerky & Rinds', 'jerky-rinds', '2021-08-25 00:17:31', '2021-08-25 00:17:31', 1),
(61, 'esdWYpyGmLsq1vDxMG66', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Snack Bars', 'snack-bars', '2021-08-25 00:17:38', '2021-08-30 15:40:03', 1),
(62, '3wKIwytOzzrDmc2jemnI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'OIJ4vZ9FCQ0nbnbPdTAa', 'Snack Mixes', 'snack-mixes', '2021-08-25 00:17:47', '2021-08-25 00:17:47', 1),
(63, 'mWfBgZzqo3lNSlWTrzgP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Sliced Bread', 'sliced-bread', '2021-08-25 00:18:32', '2021-08-25 00:18:32', 1),
(64, 's1xwUUrkDgU4sm4RMsrx', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Whole Bread', 'whole-bread', '2021-08-25 00:19:39', '2021-08-25 00:19:39', 1),
(65, 'Us7GaBsBEa9ExhXcpxz3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Desserts', 'desserts', '2021-08-25 00:19:51', '2021-08-25 00:19:51', 1),
(66, 'yHtcoIRJZfNiR9TBZho8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Breakfast Bread & Bakery', 'breakfast-bread-bakery', '2021-08-25 00:19:59', '2021-08-25 00:19:59', 1),
(67, 'VXqNOZrssbbeZ9VdI4z8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Rolls & Buns', 'rolls-buns', '2021-08-25 00:20:08', '2021-08-25 00:20:08', 1),
(68, 'mkpeVZnGEsZiTGeekccL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Tortillas & Flatbread', 'tortillas-flatbread', '2021-08-25 00:20:16', '2021-08-25 00:20:16', 1),
(69, 'FTzlei2m2T8o3YR9CVM8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Donuts, Muffins & Pastries', 'donuts-muffins-pastries', '2021-08-25 00:20:25', '2021-08-25 00:20:25', 1),
(70, '4P1ZPf2yFbyJHtsmSkPf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '8fJh4qjZ8zoiPJr7us30', 'Pies', 'pies', '2021-08-25 00:20:32', '2021-08-25 00:20:32', 1),
(71, 'dUVI7iUpSlERm4My8oy1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'All Natural Poultry', 'all-natural-poultry', '2021-08-25 00:21:09', '2021-08-25 00:21:09', 1),
(72, '7pmMTLgcVJGqIuMDRXlK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Packaged Meat', 'packaged-meat', '2021-08-25 00:21:17', '2021-08-25 00:21:17', 1),
(73, '1LoG1tNrxs13AJn7yrAq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Frozen Meat & Seafood', 'frozen-meat-seafood', '2021-08-25 00:21:25', '2021-08-25 00:21:25', 1),
(74, 'h9O3lmJ54WTNeu0qpWrF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'All Natural Beef', 'all-natural-beef', '2021-08-25 00:21:33', '2021-08-25 00:21:33', 1),
(75, 'DzuMNkSB00Db39VqDKp6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Seafood', 'seafood', '2021-08-25 00:21:41', '2021-08-25 00:21:41', 1),
(76, 'u1kAfejleNZPsTL7IDWn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Prepared Meats', 'prepared-meats', '2021-08-25 00:21:49', '2021-08-25 00:21:49', 1),
(77, 'Lhey7pktIRjKd07NX7It', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Handcrafted Sausage', 'handcrafted-sausage', '2021-08-25 00:21:57', '2021-08-25 00:21:57', 1),
(78, 'yDXF1pcOVQu3RYdgN7vv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'All Natural Pork', 'all-natural-pork', '2021-08-25 00:22:04', '2021-08-25 00:22:04', 1),
(79, 'WXtG9EW45OUYFNfNmt3C', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', '92li75qyoAo8WUDqTfSK', 'Seasonings & Marinades', 'seasonings-marinades', '2021-08-25 00:22:12', '2021-08-25 00:22:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mini_category_images`
--

CREATE TABLE `mini_category_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `mini_category_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mini_category_images`
--

INSERT INTO `mini_category_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `mini_category_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, 'NTSGMuYeCT1nZDOt3HZo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'h9O3lmJ54WTNeu0qpWrF', 'https://www.reestoc.com/images/mini_category_images/1635108141.png', '1635108141.png', 2074946, '2021-10-24 21:42:21', '2021-10-24 21:42:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `action` varchar(200) NOT NULL,
  `added_date` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_unique_id`, `unique_id`, `type`, `action`, `added_date`, `status`) VALUES
(1, 'dAXn9RrXd61LYHNSgI2T', 'jOi5i11ZQbryHfCAFNUj', 'Login Activity', 'User logged in successfully.', '2021-08-27 01:42:12', 1),
(2, 'dAXn9RrXd61LYHNSgI2T', 'H5NHuUnOgAcylcrmEf4a', 'Add Activity', 'File uploaded in file manager', '2021-08-29 15:15:10', 1),
(3, 'dAXn9RrXd61LYHNSgI2T', 'aFsGS7iSKxYoOe1qgYOU', 'Add Activity', 'Previewed file from file manager', '2021-08-29 15:16:08', 1),
(4, 'dAXn9RrXd61LYHNSgI2T', '6WCsXzplJNA0Q8u5lxpo', 'Delete Activity', 'File deleted from file manager', '2021-08-29 15:38:31', 1),
(5, 'dAXn9RrXd61LYHNSgI2T', 'oAT8ghzQYHZYbK0JyqO0', 'Add Activity', 'File uploaded in file manager', '2021-08-29 15:38:58', 1),
(6, 'dAXn9RrXd61LYHNSgI2T', 'PIFxliDbTXxDv2nbVKN3', 'Add Activity', 'Navigation Added', '2021-08-29 15:55:37', 1),
(7, 'dAXn9RrXd61LYHNSgI2T', 'BrmbIFRDCvAUMVB0nHbO', 'Add Activity', 'Navigation Added', '2021-08-29 20:21:32', 1),
(8, 'dAXn9RrXd61LYHNSgI2T', 'C8pwqCk823dRdpyDcq0H', 'Delete Activity', 'Navigation Link removed', '2021-08-29 21:14:05', 1),
(9, 'dAXn9RrXd61LYHNSgI2T', 'YtaDH9tEXvSTUZqfN3S7', 'Add Activity', 'Navigation Link restored', '2021-08-29 21:14:25', 1),
(10, 'dAXn9RrXd61LYHNSgI2T', 'WjftpRtKzpwTeG8qmZZE', 'Add Activity', 'Navigation Link restored', '2021-08-29 21:14:41', 1),
(11, 'dAXn9RrXd61LYHNSgI2T', 'hmAfuoR1Ic2a4d48pR9B', 'Edit Activity', 'Navigation Link edited', '2021-08-29 21:32:23', 1),
(12, 'dAXn9RrXd61LYHNSgI2T', '9d3KLWZ8mfneAUMlyA4B', 'Edit Activity', 'Navigation Link edited', '2021-08-29 21:32:51', 1),
(13, 'dAXn9RrXd61LYHNSgI2T', 'FoDUZQX9iynUNrP06jtk', 'Add Activity', 'Navigation Added', '2021-08-29 21:47:49', 1),
(14, 'dAXn9RrXd61LYHNSgI2T', 'MhHac25Zxl8RSEInJwtS', 'Edit Activity', 'Product Category edited', '2021-08-29 22:25:53', 1),
(15, 'dAXn9RrXd61LYHNSgI2T', 'EeI4aIAALAqKnlBTlPyF', 'Edit Activity', 'Product Category edited', '2021-08-29 22:26:42', 1),
(16, 'dAXn9RrXd61LYHNSgI2T', 'Me9nb2JeKuTXp8PHEQVj', 'Add Activity', 'Product Category Added', '2021-08-29 22:32:01', 1),
(17, 'dAXn9RrXd61LYHNSgI2T', 'PgJ179OAFZQtFljpZRex', 'Delete Activity', 'Product Category deleted', '2021-08-29 22:36:24', 1),
(18, 'dAXn9RrXd61LYHNSgI2T', 'l611vM2jbj4VP8r9iCX8', 'Add Activity', 'Product Category Added', '2021-08-29 22:36:53', 1),
(19, 'dAXn9RrXd61LYHNSgI2T', 'AaA8xOwgVXwmnvaXU2sX', 'Add Activity', 'Product Category Added', '2021-08-29 22:37:09', 1),
(20, 'dAXn9RrXd61LYHNSgI2T', 'VhDpGWSxSAb6SCTlTycu', 'Add Activity', 'Product Sub Category Added', '2021-08-30 13:57:38', 1),
(21, 'dAXn9RrXd61LYHNSgI2T', 'nvTS6AU7phJOM4eQzXFr', 'Edit Activity', 'Product Sub Category edited', '2021-08-30 14:01:02', 1),
(22, 'dAXn9RrXd61LYHNSgI2T', '847GVrxp85OvCrBgIkzj', 'Edit Activity', 'Product Sub Category edited', '2021-08-30 14:01:24', 1),
(23, 'dAXn9RrXd61LYHNSgI2T', 'P4UExNPwPtToMSAvjvyT', 'Edit Activity', 'Product Sub Category repositioned', '2021-08-30 14:29:27', 1),
(24, 'dAXn9RrXd61LYHNSgI2T', '7KuTc12huHy7B3h9mRSq', 'Edit Activity', 'Product Sub Category repositioned', '2021-08-30 14:31:46', 1),
(25, 'dAXn9RrXd61LYHNSgI2T', 'KoGJBujIbiyN7cPCv1AC', 'Delete Activity', 'Product Sub Category deleted', '2021-08-30 14:38:52', 1),
(26, 'dAXn9RrXd61LYHNSgI2T', '7uMeYFM3vcM9LAzIXRYZ', 'Edit Activity', 'Product Mini Category edited', '2021-08-30 15:31:40', 1),
(27, 'dAXn9RrXd61LYHNSgI2T', 'DhcRbRoVgxrq41TPnG5H', 'Edit Activity', 'Product Mini Category edited', '2021-08-30 15:32:02', 1),
(28, 'dAXn9RrXd61LYHNSgI2T', '7dIaVjJZyjNUuSsY5SQL', 'Edit Activity', 'Product Mini Category repositioned', '2021-08-30 15:40:03', 1),
(29, 'dAXn9RrXd61LYHNSgI2T', 'nUvr7iIEedi9lrkE9YXE', 'Edit Activity', 'Product Mini Category repositioned', '2021-08-30 15:53:39', 1),
(30, 'dAXn9RrXd61LYHNSgI2T', 'qB9gBDEb0mLIrrQAeGC6', 'Edit Activity', 'Product Mini Category repositioned', '2021-08-30 15:54:35', 1),
(31, 'dAXn9RrXd61LYHNSgI2T', 'cBlSg1zmo5yE9mthRmcj', 'Add Activity', 'Product Sub Category Added', '2021-08-30 15:55:33', 1),
(32, 'dAXn9RrXd61LYHNSgI2T', 'opJevuUDIniEmFCyMvpw', 'Add Activity', 'Product Mini Category Added', '2021-08-30 15:58:06', 1),
(33, 'dAXn9RrXd61LYHNSgI2T', 'et1gZkW8HST60aO4OjjG', 'Delete Activity', 'Product Mini Category deleted', '2021-08-30 16:01:46', 1),
(34, 'dAXn9RrXd61LYHNSgI2T', 'RjrLjOrSYaaqurxpeTTQ', 'Add Activity', 'Navigation Added', '2021-08-30 16:17:55', 1),
(35, 'dAXn9RrXd61LYHNSgI2T', 'UQVbOZkvakXotHK0R0oy', 'Add Activity', 'Product Added', '2021-08-31 10:24:46', 1),
(36, 'dAXn9RrXd61LYHNSgI2T', '1uziQIAaFXS2YzwXvwCo', 'Edit Activity', 'Product edited', '2021-08-31 10:52:32', 1),
(37, 'dAXn9RrXd61LYHNSgI2T', '2VhEHhVrPpZBAsYNS8SI', 'Add Activity', 'Product Mini Category Added', '2021-08-31 12:06:38', 1),
(38, 'dAXn9RrXd61LYHNSgI2T', 'oFNHs7NaPPJPx6olHmOQ', 'Add Activity', 'Product Added', '2021-08-31 12:08:46', 1),
(39, 'dAXn9RrXd61LYHNSgI2T', 'xQr0CDGhdhZiD3Znq9kB', 'Add Activity', 'Product Sub Category Added', '2021-08-31 12:14:41', 1),
(40, 'dAXn9RrXd61LYHNSgI2T', 'NbKCYr9QpKhyFf9BqlK8', 'Add Activity', 'Product Mini Category Added', '2021-08-31 12:17:25', 1),
(41, 'dAXn9RrXd61LYHNSgI2T', 'GlQ7i98iOOfyzr3Xspj1', 'Edit Activity', 'Product Mini Category edited', '2021-08-31 12:18:35', 1),
(42, 'dAXn9RrXd61LYHNSgI2T', 'ThLomDQP2UvQ1TLYAOtp', 'Add Activity', 'Product Added', '2021-08-31 12:20:26', 1),
(43, 'dAXn9RrXd61LYHNSgI2T', 'OiwlJedVKtG86O9B5rCk', 'Edit Activity', 'Product Categories edited', '2021-08-31 12:23:46', 1),
(44, 'dAXn9RrXd61LYHNSgI2T', '5Bf8MesL5znRf65VuuOb', 'Edit Activity', 'Product Categories edited', '2021-08-31 12:24:12', 1),
(45, 'dAXn9RrXd61LYHNSgI2T', 'gOKuKBRWY09wzdlyEqIV', 'Delete Activity', 'Product Restored', '2021-08-31 13:08:38', 1),
(46, 'dAXn9RrXd61LYHNSgI2T', 'EFUPvu4iSZjWCxf0aXaQ', 'Delete Activity', 'Product Removed', '2021-08-31 13:08:52', 1),
(47, 'dAXn9RrXd61LYHNSgI2T', 'NTjbuOsMpbyTHWGuyiF9', 'Delete Activity', 'Product Restored', '2021-08-31 13:09:49', 1),
(48, 'dAXn9RrXd61LYHNSgI2T', 'EVVllg0uqKAYmj7UjbYb', 'Delete Activity', 'Product Image Removed', '2021-09-02 16:57:56', 1),
(49, 'dAXn9RrXd61LYHNSgI2T', 'D8KDmnxbt9y5wy7B9LI2', 'Delete Activity', 'Product Image Removed', '2021-09-02 17:00:06', 1),
(50, 'dAXn9RrXd61LYHNSgI2T', 'LWT7K6JhEDcQQa1VEllU', 'Delete Activity', 'Product Removed', '2021-09-02 17:01:09', 1),
(51, 'dAXn9RrXd61LYHNSgI2T', 'hxYiUHD54FZ9r2E7dh3k', 'Delete Activity', 'Product Restored', '2021-09-02 17:01:30', 1),
(52, 'dAXn9RrXd61LYHNSgI2T', 'NZ5oPlhqHzzHnWiaJbUa', 'Add Activity', 'Navigation Added', '2021-09-02 17:05:22', 1),
(53, 'dAXn9RrXd61LYHNSgI2T', 'N6gNmqshpimMERoyZ5X8', 'Add Activity', 'Brand Added', '2021-09-02 18:05:21', 1),
(54, 'dAXn9RrXd61LYHNSgI2T', 'RXPbBmYqYYRA3eB4Ps9K', 'Add Activity', 'Brand Added', '2021-09-02 18:38:26', 1),
(55, 'dAXn9RrXd61LYHNSgI2T', 'eFfqLHE2NhWpMtvHtlFO', 'Edit Activity', 'Product edited', '2021-09-02 23:38:50', 1),
(56, 'dAXn9RrXd61LYHNSgI2T', 'D0X6IfFbQUBra7unZZD2', 'Add Activity', 'Product Sub Category Added', '2021-09-02 23:44:15', 1),
(57, 'dAXn9RrXd61LYHNSgI2T', 'qBIXVCvcOVsgaX8hfiJc', 'Add Activity', 'Product Mini Category Added', '2021-09-02 23:50:48', 1),
(58, 'dAXn9RrXd61LYHNSgI2T', 'bRRy5Y09wLQlaSw443Wz', 'Add Activity', 'Product Added', '2021-09-02 23:52:15', 1),
(59, 'dAXn9RrXd61LYHNSgI2T', 'z2Q5veIvj8YSeqRIBDcf', 'Edit Activity', 'Product edited', '2021-09-02 23:52:38', 1),
(60, 'dAXn9RrXd61LYHNSgI2T', 'l1QOb8er415vgK2ei88N', 'Edit Activity', 'Product edited', '2021-09-02 23:52:55', 1),
(61, 'dAXn9RrXd61LYHNSgI2T', 'NRkHh3yHMFmujtFqn10Q', 'Edit Activity', 'Product Categories edited', '2021-09-02 23:53:23', 1),
(62, 'dAXn9RrXd61LYHNSgI2T', 'AML4MwjbP11Z703ItkCE', 'Add Activity', 'Product Added', '2021-09-02 23:59:34', 1),
(63, 'dAXn9RrXd61LYHNSgI2T', 'k4PEN9xk1HQVpItagDZ4', 'Add Activity', 'Product Added', '2021-09-03 00:01:03', 1),
(64, 'dAXn9RrXd61LYHNSgI2T', 'mElEhdIMj9r0R2WCnL4x', 'Add Activity', 'Brand Image added', '2021-09-03 00:39:33', 1),
(65, 'dAXn9RrXd61LYHNSgI2T', 'x1eAXZVvtuDOVK8z4Gl8', 'Edit Activity', 'Brand Image edited', '2021-09-03 00:42:48', 1),
(66, 'dAXn9RrXd61LYHNSgI2T', 'Vb4j88iu9nxcrX7XTROE', 'Delete Activity', 'Brand Image Removed', '2021-09-03 01:11:30', 1),
(67, 'dAXn9RrXd61LYHNSgI2T', 'riAbEOhbH3Jjc2Q5GElv', 'Add Activity', 'Brand Image added', '2021-09-03 01:12:09', 1),
(68, 'dAXn9RrXd61LYHNSgI2T', 'LKH04TTeMwcLGGEoOPgy', 'Add Activity', 'Brand Image added', '2021-09-03 01:12:49', 1),
(69, 'dAXn9RrXd61LYHNSgI2T', 'nk0WQQaZThcLneG2MXEp', 'Add Activity', 'Brand Image added', '2021-09-03 01:13:24', 1),
(70, 'dAXn9RrXd61LYHNSgI2T', 'dLSfE3RUUHbY5U3whZVo', 'Add Activity', 'Navigation Added', '2021-09-03 01:16:28', 1),
(71, 'dAXn9RrXd61LYHNSgI2T', 'eNPRg2yRt394PTm68PKv', 'Add Activity', 'Sub Product Image added', '2021-09-03 05:33:47', 1),
(72, 'dAXn9RrXd61LYHNSgI2T', 'uWQvs91sy4AZWRyYprvI', 'Delete Activity', 'Product Image Removed', '2021-09-03 10:40:07', 1),
(73, 'dAXn9RrXd61LYHNSgI2T', 'Q1Yz3ssbz49yip9tSfEh', 'Edit Activity', 'Sub Product edited', '2021-09-03 11:32:14', 1),
(74, 'dAXn9RrXd61LYHNSgI2T', 'oNdVxpqupVGfSHdQVQ8f', 'Edit Activity', 'Sub Product edited', '2021-09-03 11:33:16', 1),
(75, 'dAXn9RrXd61LYHNSgI2T', 'mpL4oWYJLS9QqgctbTzI', 'Edit Activity', 'Sub Product edited', '2021-09-03 11:34:39', 1),
(76, 'dAXn9RrXd61LYHNSgI2T', 'bWcbL0BX6jASLBV1Fh4r', 'Edit Activity', 'Sub Product Price edited', '2021-09-03 16:04:50', 1),
(77, 'dAXn9RrXd61LYHNSgI2T', 'uldwnHL7Hsr4TkSjbPss', 'Edit Activity', 'Sub Product Price edited', '2021-09-03 16:11:47', 1),
(78, 'dAXn9RrXd61LYHNSgI2T', 'Gn7EGSbAwngIoHetqgOD', 'Add Activity', 'Product Added', '2021-09-03 16:15:56', 1),
(79, 'dAXn9RrXd61LYHNSgI2T', 'eOzUTCWbE2FYSOsTAxK3', 'Edit Activity', 'Sub Product Price edited', '2021-09-03 16:20:17', 1),
(80, 'dAXn9RrXd61LYHNSgI2T', 'jLh2fkFEooWifNUzEO1h', 'Edit Activity', 'Sub Product Price edited', '2021-09-03 16:20:35', 1),
(81, 'dAXn9RrXd61LYHNSgI2T', 'sR2y3WODQjnDpcm9ruiQ', 'Edit Activity', 'Sub Product edited', '2021-09-03 16:20:54', 1),
(82, 'dAXn9RrXd61LYHNSgI2T', '3QPffQBCB7mvok36IyqZ', 'Add Activity', 'Sub Product Image added', '2021-09-03 16:32:28', 1),
(83, 'dAXn9RrXd61LYHNSgI2T', 'mLy0C7Ec3Bb6b98W8N8D', 'Edit Activity', 'Product Image edited', '2021-09-03 16:47:57', 1),
(84, 'dAXn9RrXd61LYHNSgI2T', 'U8oXwXeOF2ygV8vHDWnu', 'Delete Activity', 'Product Removed', '2021-09-03 16:48:28', 1),
(85, 'dAXn9RrXd61LYHNSgI2T', 'e1PgUNvaraSugw10d5wg', 'Delete Activity', 'Product Restored', '2021-09-03 16:49:35', 1),
(86, 'dAXn9RrXd61LYHNSgI2T', 'cCslLK2hu2e2KF8veGUL', 'Add Activity', 'Sub Product Image added', '2021-09-03 16:56:48', 1),
(87, 'dAXn9RrXd61LYHNSgI2T', 'sq7zbslD6cGPW33c91xa', 'Add Activity', 'Navigation Added', '2021-09-05 03:32:38', 1),
(88, 'dAXn9RrXd61LYHNSgI2T', 'DnDrjYMV5FvRxQfWK3p2', 'Logout Activity', 'User logged out successfully.', '2021-09-05 03:59:45', 1),
(89, 'dAXn9RrXd61LYHNSgI2T', 'rnI7jyJDuQBrfgGe5TGA', 'Login Activity', 'User logged in successfully.', '2021-09-05 03:59:53', 1),
(90, 'dAXn9RrXd61LYHNSgI2T', 'Co8F6XxT4Vtez4o6Ti5I', 'Add Activity', 'Navigation Added', '2021-09-07 21:29:50', 1),
(91, 'dAXn9RrXd61LYHNSgI2T', 'Wdpt4VqqfBu3TNUJugTS', 'Add Activity', 'Product Added', '2021-09-07 23:17:24', 1),
(92, 'dAXn9RrXd61LYHNSgI2T', 'MD4tPIRkQm9KZ9fYEGaK', 'Add Activity', 'Sub Product Image added', '2021-09-07 23:20:05', 1),
(93, 'dAXn9RrXd61LYHNSgI2T', '94gaQA7u1JTo35P9Eyht', 'Edit Activity', 'Updated order - (9kAgISKaM0KZ102BtqEQ) paid', '2021-09-08 04:48:21', 1),
(94, 'dAXn9RrXd61LYHNSgI2T', 'sakuJfxk03m4shmprdNo', 'Edit Activity', 'Updated order - (JadlmdauaXRRUovWJ2Do) paid', '2021-09-08 04:51:12', 1),
(95, 'dAXn9RrXd61LYHNSgI2T', 'yjAlhK93Jzb3jBGsDKBy', 'Edit Activity', 'Updated order - (JadlmdauaXRRUovWJ2Do) shipped (NNcKAUuBonJTimmCCIIw)', '2021-09-08 23:50:37', 1),
(96, 'dAXn9RrXd61LYHNSgI2T', 'FXSLBOu8O3MGLaIqpib6', 'Edit Activity', 'Updated order - (JadlmdauaXRRUovWJ2Do) shipped (z5DTP6kXrEcu1wyeglmN)', '2021-09-09 09:11:39', 1),
(97, 'dAXn9RrXd61LYHNSgI2T', '79gluhziiRJnpJoLzp3J', 'Edit Activity', 'Updated order - (9kAgISKaM0KZ102BtqEQ) shipped (z5DTP6kXrEcu1wyeglmN)', '2021-09-09 09:45:44', 1),
(98, 'dAXn9RrXd61LYHNSgI2T', 'IcT0KnhUNkjtdt62TtZm', 'Edit Activity', 'Updated order - (2vb4KUjolLZZsAZDsi4E) paid', '2021-09-09 09:46:05', 1),
(99, 'dAXn9RrXd61LYHNSgI2T', 'gA4BLyl6bcXVBkR0X2Dv', 'Edit Activity', 'Updated order - (2vb4KUjolLZZsAZDsi4E) shipped (xe9RjnjLOABza8GvLyM7)', '2021-09-09 09:46:35', 1),
(100, 'dAXn9RrXd61LYHNSgI2T', 'Us0qBG2RFRdZKKyhTcAl', 'Edit Activity', 'Updated order - (9kAgISKaM0KZ102BtqEQ) completed', '2021-09-09 09:49:26', 1),
(101, 'dAXn9RrXd61LYHNSgI2T', 'Emvqk37uEvg4QZCuBtcz', 'Edit Activity', 'Updated order - (2vb4KUjolLZZsAZDsi4E) completed', '2021-09-09 09:49:43', 1),
(102, 'dAXn9RrXd61LYHNSgI2T', 'te0rtglt6yEr1Nroomko', 'Edit Activity', 'Updated order - (JadlmdauaXRRUovWJ2Do) completed', '2021-09-09 09:50:00', 1),
(103, 'dAXn9RrXd61LYHNSgI2T', 'vRGvrfkyq7L0uvjSZkwb', 'Add Activity', 'Navigation Added', '2021-09-10 11:38:04', 1),
(104, 'dAXn9RrXd61LYHNSgI2T', 'Eyswii6YuaIm5W4xmq3E', 'Edit Activity', 'Updated shipment - (z5DTP6kXrEcu1wyeglmN) started', '2021-09-11 00:09:25', 1),
(105, 'dAXn9RrXd61LYHNSgI2T', 'LgnBWBq7djrrDCXvFkIW', 'Edit Activity', 'Updated shipment - (xe9RjnjLOABza8GvLyM7) rider changed (EAqXSSPExWfYQUaDvBKF)', '2021-09-11 10:34:54', 1),
(106, 'dAXn9RrXd61LYHNSgI2T', 'V2KsfUgeqKPD8lwzpm4V', 'Edit Activity', 'Updated shipment - (xe9RjnjLOABza8GvLyM7) rider changed (vaEd4ZvDguobtFSv6Woy)', '2021-09-11 10:35:09', 1),
(107, 'dAXn9RrXd61LYHNSgI2T', 'jGMVNRkExrH4bgUUVB1Q', 'Edit Activity', 'Updated shipment - (xe9RjnjLOABza8GvLyM7) rider changed (EAqXSSPExWfYQUaDvBKF)', '2021-09-11 10:36:40', 1),
(108, 'dAXn9RrXd61LYHNSgI2T', '9wsV7qilElyHkNqgXe1d', 'Edit Activity', 'Updated shipment - (xe9RjnjLOABza8GvLyM7) started', '2021-09-11 10:36:52', 1),
(109, 'dAXn9RrXd61LYHNSgI2T', 'DsPD6XoyGp1cVHj6peIg', 'Edit Activity', 'Updated shipment - (z5DTP6kXrEcu1wyeglmN) completed', '2021-09-11 10:37:49', 1),
(110, 'dAXn9RrXd61LYHNSgI2T', 'W2sMnKcWCCkstwNM5AZH', 'Edit Activity', 'Updated shipment - (xe9RjnjLOABza8GvLyM7) completed', '2021-09-11 10:39:50', 1),
(111, 'dAXn9RrXd61LYHNSgI2T', 'MzUtMaMEvm6iEuxecOPn', 'Add Activity', 'Shipment created - rider (vaEd4ZvDguobtFSv6Woy)', '2021-09-11 11:06:40', 1),
(112, 'dAXn9RrXd61LYHNSgI2T', 'KiT9ULYlutheBZrgfYZJ', 'Edit Activity', 'Updated shipment - (9TqA4ulhqmmy8lFknM6t) rider changed (iFPbKDm3YqoruhLWJWAv)', '2021-09-11 11:07:12', 1),
(113, 'dAXn9RrXd61LYHNSgI2T', 'w40tBTEyzbLXHZqQol6k', 'Add Activity', 'Shipment created - rider (2IznNBIz5lnURQpdGG3w)', '2021-09-11 11:09:41', 1),
(114, 'dAXn9RrXd61LYHNSgI2T', 'kRFNWWT7mxmEFnWJHNS9', 'Delete Activity', 'Deleted shipment - (9TqA4ulhqmmy8lFknM6t)', '2021-09-11 11:24:29', 1),
(115, 'dAXn9RrXd61LYHNSgI2T', '7tixqJpLYOl0gDSmSje9', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) started', '2021-09-11 13:51:08', 1),
(116, 'dAXn9RrXd61LYHNSgI2T', 'E3GFoif7uu2SEQIoji20', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) location updated (4.843785,7.17911)', '2021-09-11 14:00:24', 1),
(117, 'dAXn9RrXd61LYHNSgI2T', 'OvtscyQSqmx6fGRCF98S', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) location updated (4.843785,7.017911)', '2021-09-11 14:03:44', 1),
(118, 'dAXn9RrXd61LYHNSgI2T', 'u0ym73Me0APVqbfd3HcB', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) location updated (4.843785,7.017911)', '2021-09-11 14:30:41', 1),
(119, 'dAXn9RrXd61LYHNSgI2T', 'yEWM8X3zASgYgMmhh0GE', 'Add Activity', 'Navigation Added', '2021-09-11 17:25:43', 1),
(120, 'dAXn9RrXd61LYHNSgI2T', 'UvHNr5TecnipAxohgoYq', 'Edit Activity', 'Updated rider - (2IznNBIz5lnURQpdGG3w) details', '2021-09-11 18:28:09', 1),
(121, 'dAXn9RrXd61LYHNSgI2T', 'yjJKLSF7bBOVkQb8nOxz', 'Edit Activity', 'Updated rider - (2IznNBIz5lnURQpdGG3w) details', '2021-09-11 18:31:04', 1),
(122, 'dAXn9RrXd61LYHNSgI2T', 'PQ1z3NleXEBGSHrI8UDm', 'Edit Activity', 'Updated rider - (2IznNBIz5lnURQpdGG3w) details', '2021-09-11 18:31:23', 1),
(123, 'dAXn9RrXd61LYHNSgI2T', 'uJiYVl68ouIJNzejlv3s', 'Edit Activity', 'Updated rider - (2IznNBIz5lnURQpdGG3w) details', '2021-09-11 18:31:42', 1),
(124, 'dAXn9RrXd61LYHNSgI2T', 'RZXVsbNRjRIzdBT4hDGl', 'Edit Activity', 'Updated rider - (iFPbKDm3YqoruhLWJWAv) details', '2021-09-11 19:32:26', 1),
(125, 'dAXn9RrXd61LYHNSgI2T', '1I1ONNevlip6EuDRDqZC', 'Edit Activity', 'Sub Product Price edited', '2021-09-11 19:38:20', 1),
(126, 'dAXn9RrXd61LYHNSgI2T', 'cR6LiiDiLiyYJG9R0cGp', 'Add Activity', 'Rider added', '2021-09-11 23:04:47', 1),
(127, 'dAXn9RrXd61LYHNSgI2T', 'tUS4kLIoIZe5mIoTXSF5', 'Delete Activity', 'Rider (A21uaBJKTMFuPdHPgryV) Removed', '2021-09-11 23:24:53', 1),
(128, 'dAXn9RrXd61LYHNSgI2T', '7QarTTfj4MYMat8QmRQv', 'Delete Activity', 'Rider (vaEd4ZvDguobtFSv6Woy) Removed', '2021-09-11 23:31:14', 1),
(129, 'dAXn9RrXd61LYHNSgI2T', 'XxsAEXwCtu7xaElriV5g', 'Add Activity', 'Rider (vaEd4ZvDguobtFSv6Woy) Restored', '2021-09-11 23:43:29', 1),
(130, 'dAXn9RrXd61LYHNSgI2T', '83JtRVtd5J2bt4BeF51Z', 'Add Activity', 'Rider (A21uaBJKTMFuPdHPgryV) Restored', '2021-09-11 23:43:46', 1),
(131, 'dAXn9RrXd61LYHNSgI2T', 'xMVWCLcc3LIl9nlitXMd', 'Add Activity', 'Navigation Added', '2021-09-11 23:51:46', 1),
(132, 'dAXn9RrXd61LYHNSgI2T', 'qn7IBSPWZJcZjn2cp62t', 'Edit Activity', 'Navigation Link edited', '2021-09-11 23:53:23', 1),
(133, 'dAXn9RrXd61LYHNSgI2T', 'ACPWfXx6AaJbC7ZYueOY', 'Edit Activity', 'Navigation Link edited', '2021-09-12 00:40:04', 1),
(134, 'dAXn9RrXd61LYHNSgI2T', 'E6gMWKLRY8L3eBuqCLBT', 'Add Activity', 'Navigation Added', '2021-09-12 00:57:32', 1),
(135, 'dAXn9RrXd61LYHNSgI2T', 'LSVYjuy88enLd2SI2Gcd', 'Edit Activity', 'Navigation Link edited', '2021-09-12 00:59:03', 1),
(136, 'dAXn9RrXd61LYHNSgI2T', '1UPDgkWRCUkTqMrt7VCK', 'Edit Activity', 'Navigation Link edited', '2021-09-12 01:00:42', 1),
(137, 'dAXn9RrXd61LYHNSgI2T', 'hgybG6xVO7q7tL2wkWDi', 'Edit Activity', 'Offered Service Category edited', '2021-09-12 16:41:22', 1),
(138, 'dAXn9RrXd61LYHNSgI2T', 'xoKp0wZR32dKQfLtRLRe', 'Edit Activity', 'Offered Service Category edited', '2021-09-12 16:41:40', 1),
(139, 'dAXn9RrXd61LYHNSgI2T', '5vDM1E61p9iJ9HnZQ4pv', 'Delete Activity', 'Offered Service Category deleted', '2021-09-12 16:43:29', 1),
(140, 'dAXn9RrXd61LYHNSgI2T', 'vyDCI8s4seOWizgt02ad', 'Add Activity', 'Offered Service Category restored', '2021-09-12 16:47:05', 1),
(141, 'dAXn9RrXd61LYHNSgI2T', 'm7nJe0xavBFysp9dbBAZ', 'Add Activity', 'Offered Service Category Added', '2021-09-12 16:49:37', 1),
(142, 'dAXn9RrXd61LYHNSgI2T', 'CBkWXEMSAPQ7lB0Z6u9Q', 'Edit Activity', 'Sub Product Price edited', '2021-09-12 17:21:06', 1),
(143, 'dAXn9RrXd61LYHNSgI2T', 'It8MDrDYqn4t7zkN82R8', 'Edit Activity', 'Offered Service Category edited', '2021-09-14 19:10:08', 1),
(144, 'dAXn9RrXd61LYHNSgI2T', 'ZzQ0MSX5UNTPUxPfG9Em', 'Add Activity', 'Offered Service Added', '2021-09-14 20:18:20', 1),
(145, 'dAXn9RrXd61LYHNSgI2T', 'i19tQbSpDjERObqGtWAc', 'Add Activity', 'Offered Service Added', '2021-09-14 20:26:16', 1),
(146, 'dAXn9RrXd61LYHNSgI2T', 'BWOFzMQIU01I65Ksdhxw', 'Add Activity', 'Offered Service Added', '2021-09-14 22:28:49', 1),
(147, 'dAXn9RrXd61LYHNSgI2T', 'b6FBwp9rQb8E7w5HRNEE', 'Add Activity', 'Offered Service Added', '2021-09-14 22:55:54', 1),
(148, 'dAXn9RrXd61LYHNSgI2T', '7m6IZkFdb4TLjQZmFtBk', 'Add Activity', 'Offered Service Added with Image', '2021-09-14 23:41:49', 1),
(149, 'dAXn9RrXd61LYHNSgI2T', '350TmGgQkFDFNL2KvPPv', 'Add Activity', 'Offered Service Added with Image', '2021-09-14 23:46:33', 1),
(150, 'dAXn9RrXd61LYHNSgI2T', '5gPUFn5zbZ5RY41TYTuc', 'Edit Activity', 'Offered Service edited', '2021-09-15 15:47:20', 1),
(151, 'dAXn9RrXd61LYHNSgI2T', '0IoPQ5rBoRCDC6RgyByH', 'Edit Activity', 'Offered Service edited', '2021-09-15 15:47:44', 1),
(152, 'dAXn9RrXd61LYHNSgI2T', '4zDrFbtn8yBK1wUi6qO0', 'Edit Activity', 'Offered Service Image edited', '2021-09-15 16:38:06', 1),
(153, 'dAXn9RrXd61LYHNSgI2T', 'VhlASmKfjpM5ItCrTP42', 'Edit Activity', 'Offered Service Image edited', '2021-09-15 16:48:47', 1),
(154, 'dAXn9RrXd61LYHNSgI2T', 'HWGPeLC84ylBAmIvO56h', 'Delete Activity', 'Offered Service deleted', '2021-09-15 17:13:04', 1),
(155, 'dAXn9RrXd61LYHNSgI2T', 'ogCMb5iXBPMAOZbKVuHg', 'Delete Activity', 'Offered Service deleted', '2021-09-15 17:13:21', 1),
(156, 'dAXn9RrXd61LYHNSgI2T', 'bR1dqATCTtELGgUTkjvz', 'Edit Activity', 'Offered Service (cc4sB6V0c246Zvx8R9oi) Image edited', '2021-09-15 17:14:14', 1),
(157, 'dAXn9RrXd61LYHNSgI2T', 'xWft0HhIR8oOsySn1rnM', 'Add Activity', 'Offered Service Added', '2021-09-15 17:14:56', 1),
(158, 'dAXn9RrXd61LYHNSgI2T', '85BsYmAANMQShf0e3HnB', 'Add Activity', 'Navigation Added', '2021-09-15 17:24:54', 1),
(159, 'dAXn9RrXd61LYHNSgI2T', 'IZBARs2to2whqf2lluqU', 'Add Activity', 'Shipping Fee Added', '2021-09-16 22:11:55', 1),
(160, 'dAXn9RrXd61LYHNSgI2T', 'FGLnrU3IxGbgDaZwi6Zh', 'Add Activity', 'Shipping Fee Added', '2021-09-16 22:13:41', 1),
(161, 'dAXn9RrXd61LYHNSgI2T', 'Q0FuBNGui6W51J8HqaZd', 'Edit Activity', 'Offered Service edited', '2021-09-16 22:23:46', 1),
(162, 'dAXn9RrXd61LYHNSgI2T', '0D137hLG7LYLx5xdLYpS', 'Edit Activity', 'Offered Service edited', '2021-09-16 22:24:04', 1),
(163, 'dAXn9RrXd61LYHNSgI2T', 'sGS9ZnRDWseDSJRC0cfN', 'Delete Activity', 'Offered Service deleted', '2021-09-16 22:24:25', 1),
(164, 'dAXn9RrXd61LYHNSgI2T', 'L1iAD1CZtiunShAL239Q', 'Delete Activity', 'Offered Service deleted', '2021-09-16 22:25:01', 1),
(165, 'dAXn9RrXd61LYHNSgI2T', 'WVHs7OhcA0VxVqbrJbKe', 'Edit Activity', 'Offered Service (FcGFpa0Mn7xTzCwos6gs) Image edited', '2021-09-16 22:26:06', 1),
(166, 'dAXn9RrXd61LYHNSgI2T', 'pGXfWGo9WOylxyVe7z3R', 'Delete Activity', 'Shipping fee (dgozQPFB6hJETx6JCWAW) deleted', '2021-09-16 22:27:00', 1),
(167, 'dAXn9RrXd61LYHNSgI2T', 'HNt4aTHxX4L40XKLJH7W', 'Delete Activity', 'Shipping fee (VTCU8yng9w6LaA6SuvE6) deleted', '2021-09-16 22:27:47', 1),
(168, 'dAXn9RrXd61LYHNSgI2T', 'dFE4jUuqVTFyfo5YpbE7', 'Logout Activity', 'User logged out successfully.', '2021-09-17 09:46:33', 1),
(169, 'dAXn9RrXd61LYHNSgI2T', 'I6uoSXYilMRIvNgcbWld', 'Login Activity', 'User logged in successfully.', '2021-09-17 09:46:39', 1),
(170, 'dAXn9RrXd61LYHNSgI2T', 'zmiwkHH1mhiYwnncbGd9', 'Add Activity', 'Shipping fee (dgozQPFB6hJETx6JCWAW) restored', '2021-09-17 09:47:22', 1),
(171, 'dAXn9RrXd61LYHNSgI2T', 'oUVnx5ryF5n0XUYQZF3E', 'Add Activity', 'Shipping fee (VTCU8yng9w6LaA6SuvE6) restored', '2021-09-17 09:47:56', 1),
(172, 'dAXn9RrXd61LYHNSgI2T', 'J8PfiN11f6PPCRPXISSi', 'Edit Activity', 'Shipping Fee (g2vaZF56xec58zMzzThI) edited', '2021-09-17 09:50:19', 1),
(173, 'dAXn9RrXd61LYHNSgI2T', 'nxXjl1dGXejwxG67hYSB', 'Edit Activity', 'Shipping Fee (ZbTaCisQsYC5fZPR4I3l) edited', '2021-09-17 09:51:20', 1),
(174, 'dAXn9RrXd61LYHNSgI2T', 'Atn8XMNq8102T1PIl67b', 'Edit Activity', 'Shipping Fee (mXGONTUow0WQVodYjUSs) edited', '2021-09-17 09:52:12', 1),
(175, 'dAXn9RrXd61LYHNSgI2T', 'Q1MvYkg7OlTnDGnueOae', 'Edit Activity', 'Shipping Fee (kKJikmrpFYJcoVZhf7im) edited', '2021-09-17 09:53:11', 1),
(176, 'dAXn9RrXd61LYHNSgI2T', 'UkVfXaI4EiPcNsfPhXHX', 'Edit Activity', 'Shipping Fee (NfKYg3yUdKiKr3q8nqxY) edited', '2021-09-17 09:54:00', 1),
(177, 'dAXn9RrXd61LYHNSgI2T', 'pxXwXOpf0Ilscn90hrUK', 'Edit Activity', 'Shipping Fee (OO8MThjTBNJJxTtC6e3m) edited', '2021-09-17 09:54:44', 1),
(178, 'dAXn9RrXd61LYHNSgI2T', 'VwFJuDD1eeYfYwz0TiKu', 'Delete Activity', 'Shipping fee (VTCU8yng9w6LaA6SuvE6) deleted', '2021-09-17 09:55:44', 1),
(179, 'dAXn9RrXd61LYHNSgI2T', '3CsxzDZVKj4jKmHbBoVN', 'Add Activity', 'Navigation Added', '2021-09-17 09:59:13', 1),
(180, 'dAXn9RrXd61LYHNSgI2T', 'wQ2I7MPHDonqPCLHfU3T', 'Add Activity', 'Management User added', '2021-09-17 13:12:09', 1),
(181, 'dAXn9RrXd61LYHNSgI2T', 'VxN5IuvwTOrgYlhz1e76', 'Edit Activity', 'Updated management user - (dw58MKtwhl6JHQzfoQqL) details', '2021-09-17 13:12:32', 1),
(182, 'dAXn9RrXd61LYHNSgI2T', '55tlTQaapxuR1JSDzZhu', 'Edit Activity', 'Updated management user - (dw58MKtwhl6JHQzfoQqL) details', '2021-09-17 13:12:59', 1),
(183, 'dAXn9RrXd61LYHNSgI2T', 'LXnR2LH5yJ0Iw99ctmdM', 'Logout Activity', 'User logged out successfully.', '2021-09-17 13:13:24', 1),
(184, 'dAXn9RrXd61LYHNSgI2T', 'JI5wE2dosWG3g1E60oNI', 'Login Activity', 'User logged in successfully.', '2021-09-17 13:14:15', 1),
(185, 'dAXn9RrXd61LYHNSgI2T', 'db3ZtZMEIoiidJUpy56a', 'Edit Activity', 'Updated management user - (dw58MKtwhl6JHQzfoQqL) details', '2021-09-17 13:28:54', 1),
(186, 'dAXn9RrXd61LYHNSgI2T', 'K66dqDRVSfI0N365UeTI', 'Edit Activity', 'Updated management user - (dw58MKtwhl6JHQzfoQqL) details', '2021-09-17 13:29:45', 1),
(187, 'dAXn9RrXd61LYHNSgI2T', '51PZGYwyl4qNYTaL1mPE', 'Delete Activity', 'Management User (EvSE5PFX0zm04C7pnh3I) Removed', '2021-09-17 13:30:32', 1),
(188, 'dAXn9RrXd61LYHNSgI2T', 'pZT7RtKwbwvzuWL8uxaQ', 'Add Activity', 'Management User (EvSE5PFX0zm04C7pnh3I) Restored', '2021-09-17 13:31:01', 1),
(189, 'dAXn9RrXd61LYHNSgI2T', 'YbHVWUoKQ0HogwNaUYwL', 'Edit Activity', 'Updated management user - (EvSE5PFX0zm04C7pnh3I) details', '2021-09-17 13:31:43', 1),
(190, 'dAXn9RrXd61LYHNSgI2T', 'JLMbNzOki0crVNunIquR', 'Delete Activity', 'Management User (EvSE5PFX0zm04C7pnh3I) Removed', '2021-09-17 13:32:18', 1),
(191, 'dAXn9RrXd61LYHNSgI2T', 'eqEI5KiLGFBZ2LlbHOUK', 'Add Activity', 'Management User (EvSE5PFX0zm04C7pnh3I) Restored', '2021-09-17 13:32:35', 1),
(192, 'dAXn9RrXd61LYHNSgI2T', 'DxQDH6vVjbPPCnZZAhoF', 'Logout Activity', 'User logged out successfully.', '2021-09-17 15:55:58', 1),
(193, 'dAXn9RrXd61LYHNSgI2T', 'Gjffjwvkz4LCWjLdzIyE', 'Login Activity', 'User logged in successfully.', '2021-09-17 15:56:02', 1),
(194, 'dAXn9RrXd61LYHNSgI2T', 'dAd4xYrkpkBZcQhmWaH9', 'Add Activity', 'Management User added', '2021-09-17 15:59:08', 1),
(195, 'dAXn9RrXd61LYHNSgI2T', 'L48SnG8zzp8eKStxXlVI', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 15:59:39', 1),
(196, 'dAXn9RrXd61LYHNSgI2T', 'aCv0EjfMxSqq7T4eO8PE', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 15:59:59', 1),
(197, 'dAXn9RrXd61LYHNSgI2T', 'ndlKie0QVCTxskzzqZjf', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:00:16', 1),
(198, 'dAXn9RrXd61LYHNSgI2T', 'YsJ1N6aoSv8sOIGkd1JA', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:00:31', 1),
(199, 'dAXn9RrXd61LYHNSgI2T', 'p9QVeucFAyIehdSQCllZ', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:00:47', 1),
(200, 'dAXn9RrXd61LYHNSgI2T', 'G0StZ9Z0TkVUgyNUljWS', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:01:01', 1),
(201, 'dAXn9RrXd61LYHNSgI2T', 'DprBY5ITA6w17btI2MYX', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:01:24', 1),
(202, 'dAXn9RrXd61LYHNSgI2T', 'SijLbVVF0I8PkLWlFKlG', 'Edit Activity', 'Updated management user - (aR7pz31cHCD8T1Cq3UDu) details', '2021-09-17 16:02:44', 1),
(203, 'dAXn9RrXd61LYHNSgI2T', 'cmH1YdRRunlvG7tvJnPA', 'Logout Activity', 'User logged out successfully.', '2021-09-17 16:03:16', 1),
(204, 'dAXn9RrXd61LYHNSgI2T', 'v5eMWUZJUJa5zKLXvou5', 'Login Activity', 'User logged in successfully.', '2021-09-17 16:03:22', 1),
(205, 'dAXn9RrXd61LYHNSgI2T', 'P5nbCyGKBc0w000r4t1O', 'Logout Activity', 'User logged out successfully.', '2021-09-17 16:03:29', 1),
(206, 'dAXn9RrXd61LYHNSgI2T', 'T9qHrTh6VBUXlEZRGbb1', 'Login Activity', 'User logged in successfully.', '2021-09-17 16:03:44', 1),
(207, 'dAXn9RrXd61LYHNSgI2T', '1rtqtwS0qf2oxgIjokTJ', 'Logout Activity', 'User logged out successfully.', '2021-09-17 16:10:13', 1),
(208, 'dAXn9RrXd61LYHNSgI2T', 'NlEP6KJIv3ZYLs3xj2Q9', 'Login Activity', 'User logged in successfully.', '2021-09-17 16:10:27', 1),
(209, 'dAXn9RrXd61LYHNSgI2T', 'QNw1pFfGEikv1aAa6Jd1', 'Logout Activity', 'User logged out successfully.', '2021-09-17 16:14:36', 1),
(210, 'dAXn9RrXd61LYHNSgI2T', 'tjxtH7yKbfgencViTC76', 'Login Activity', 'User logged in successfully.', '2021-09-17 16:14:49', 1),
(211, 'dAXn9RrXd61LYHNSgI2T', 'b5s2GLT9KQBpib5S8Qlh', 'Login Activity', 'User logged in successfully.', '2021-09-17 16:15:01', 1),
(212, 'dAXn9RrXd61LYHNSgI2T', 'Y0oiNr68sCgt2dEOr0yR', 'Add Activity', 'Shipping Fee Added', '2021-09-17 16:37:09', 1),
(213, 'dAXn9RrXd61LYHNSgI2T', 'S2K7ISH9qHAeh8vLQ1cp', 'Add Activity', 'Navigation Added', '2021-09-17 17:55:24', 1),
(214, 'dAXn9RrXd61LYHNSgI2T', '3fovTIl1PANwyPTMwMHi', 'Add Activity', 'Navigation Added', '2021-09-17 17:55:49', 1),
(215, 'dAXn9RrXd61LYHNSgI2T', 'qAyUog8WvaXe9JoU0Xh0', 'Delete Activity', 'Navigation Link removed', '2021-09-17 17:57:10', 1),
(216, 'dAXn9RrXd61LYHNSgI2T', '16rnn1wDj0BPliBYgv3P', 'Add Activity', 'Navigation Link restored', '2021-09-17 17:57:23', 1),
(217, 'dAXn9RrXd61LYHNSgI2T', '2NKynsNDy2qzWDaTJCGs', 'Add Activity', 'Agent added', '2021-09-17 18:10:39', 1),
(218, 'dAXn9RrXd61LYHNSgI2T', 'dkulIOUblfsN9LI16aRZ', 'Edit Activity', 'Updated agent - (KJznfOUtMo5rr4SQux53) details', '2021-09-17 18:11:05', 1),
(219, 'dAXn9RrXd61LYHNSgI2T', 'w7WLI39Uen4yoX0wPoEI', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-17 18:11:38', 1),
(220, 'dAXn9RrXd61LYHNSgI2T', 'YLMGqkvA5DNlyEoMEub5', 'Delete Activity', 'Agent (jBWTEIl4vtfwOCjOU28A) Removed', '2021-09-17 18:13:49', 1),
(221, 'dAXn9RrXd61LYHNSgI2T', 'YlMJ9do0MRqOIBDbcXxt', 'Add Activity', 'Agent (jBWTEIl4vtfwOCjOU28A) Restored', '2021-09-17 18:14:01', 1),
(222, 'dAXn9RrXd61LYHNSgI2T', 'W3bYzWGvry2jAb2evkmh', 'Edit Activity', 'Updated agent - (KJznfOUtMo5rr4SQux53) details', '2021-09-17 18:14:19', 1),
(223, 'dAXn9RrXd61LYHNSgI2T', 'e6p6HoqOjuWOUa48D9g9', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-17 18:14:41', 1),
(224, 'dAXn9RrXd61LYHNSgI2T', 'zlzZljo1wgjxgZBnsD88', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-17 18:14:57', 1),
(225, 'dAXn9RrXd61LYHNSgI2T', 'OYAciUvodGdujYoSFOMf', 'Edit Activity', 'Updated agent - (NlyOCQc69mU7OSWkesn1) details', '2021-09-17 18:15:18', 1),
(226, 'dAXn9RrXd61LYHNSgI2T', 'fHg27DzGA5iPiJj8q0qf', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-18 01:09:06', 1),
(227, 'dAXn9RrXd61LYHNSgI2T', 'evom6C22uvBNvjTBgNgm', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-18 01:09:36', 1),
(228, 'dAXn9RrXd61LYHNSgI2T', 'ButeRMYS1tnYks6ueOhg', 'Edit Activity', 'Updated agent - (jBWTEIl4vtfwOCjOU28A) details', '2021-09-18 01:10:04', 1),
(229, 'dAXn9RrXd61LYHNSgI2T', 'W38rKK2r4zj6K4rnf1rP', 'Delete Activity', 'Agent (jBWTEIl4vtfwOCjOU28A) Removed', '2021-09-18 01:10:30', 1),
(230, 'dAXn9RrXd61LYHNSgI2T', 'OtcuzDqyo4bbuMrvx1Uu', 'Add Activity', 'Agent (jBWTEIl4vtfwOCjOU28A) Restored', '2021-09-18 01:10:42', 1),
(231, 'dAXn9RrXd61LYHNSgI2T', 'CX3V72jdDs634E9c3W3L', 'Add Activity', 'Agent added', '2021-09-18 01:14:54', 1),
(232, 'dAXn9RrXd61LYHNSgI2T', '6Q2mWDuEiG1ozJcGNxmt', 'Edit Activity', 'Updated agent - (4GhBoOTc1tNxWN8tyFQE) details', '2021-09-18 01:16:23', 1),
(233, 'dAXn9RrXd61LYHNSgI2T', '6outUIqv2vAnIuR3SGY9', 'Edit Activity', 'Updated agent - (4GhBoOTc1tNxWN8tyFQE) details', '2021-09-18 01:16:48', 1),
(234, 'dAXn9RrXd61LYHNSgI2T', 'NJI7SFj5Bg6XUBQDCpPl', 'Delete Activity', 'Agent (4GhBoOTc1tNxWN8tyFQE) Removed', '2021-09-18 01:17:05', 1),
(235, 'dAXn9RrXd61LYHNSgI2T', 'KppnJAQepzyNBQD02JHe', 'Add Activity', 'Agent (4GhBoOTc1tNxWN8tyFQE) Restored', '2021-09-18 01:17:19', 1),
(236, 'dAXn9RrXd61LYHNSgI2T', 'F61RIGKtDthbjAtlXJIe', 'Edit Activity', 'Updated user - (tPBIE40TyKjOu0A35Joe) access', '2021-09-18 01:25:52', 1),
(237, 'dAXn9RrXd61LYHNSgI2T', 'yJDD2vn8PvS85jMB9iFz', 'Edit Activity', 'Updated user - (tPBIE40TyKjOu0A35Joe) access', '2021-09-18 01:26:17', 1),
(238, 'dAXn9RrXd61LYHNSgI2T', 'haOG7Ji5wXeLeYdOvTXE', 'Edit Activity', 'Updated user - (tPBIE40TyKjOu0A35Joe) access', '2021-09-18 01:26:33', 1),
(239, 'dAXn9RrXd61LYHNSgI2T', 'FKer617g10XuetjjrTJ6', 'Delete Activity', 'User (rzl5nk7rIHDpqMUbHuz9) Removed', '2021-09-18 01:26:59', 1),
(240, 'dAXn9RrXd61LYHNSgI2T', 'rI5vpGZ71J2LIn2e6Q9T', 'Add Activity', 'User (rzl5nk7rIHDpqMUbHuz9) Restored', '2021-09-18 01:27:09', 1),
(241, 'dAXn9RrXd61LYHNSgI2T', 'dOQtK6CVz2dapHb88O34', 'Edit Activity', 'Updated user - (BXveKWZrgUccZ3udo5ef) access', '2021-09-18 01:29:50', 1),
(242, 'dAXn9RrXd61LYHNSgI2T', 'hQOOs4qDRz358FgdxtUI', 'Edit Activity', 'Updated user - (BXveKWZrgUccZ3udo5ef) access', '2021-09-18 01:31:04', 1),
(243, 'dAXn9RrXd61LYHNSgI2T', 'J62Jz8K1FjsgXxRxuy26', 'Edit Activity', 'Updated user - (BXveKWZrgUccZ3udo5ef) access', '2021-09-18 01:31:30', 1),
(244, 'dAXn9RrXd61LYHNSgI2T', 'mV79jBG3ky10qHFD1utO', 'Delete Activity', 'User (BXveKWZrgUccZ3udo5ef) Removed', '2021-09-18 01:31:51', 1),
(245, 'dAXn9RrXd61LYHNSgI2T', '73W6BOgANAD7ocLHtx78', 'Add Activity', 'User (BXveKWZrgUccZ3udo5ef) Restored', '2021-09-18 01:32:17', 1),
(246, 'dAXn9RrXd61LYHNSgI2T', 'IlWaMv454QIJYuWi77cV', 'Edit Activity', 'Updated user - (BXveKWZrgUccZ3udo5ef) access', '2021-09-18 01:32:33', 1),
(247, 'dAXn9RrXd61LYHNSgI2T', '2vwxSkf8nc6YwwEgVZER', 'Add Activity', 'User undefined/undefined added', '2021-09-18 01:39:48', 1),
(248, 'dAXn9RrXd61LYHNSgI2T', 'IzmduYWYTMoouAnO7O9u', 'Add Activity', 'Rider added', '2021-09-18 01:46:08', 1),
(249, 'dAXn9RrXd61LYHNSgI2T', 'j4cUMZTPYk45ckFUBAkK', 'Edit Activity', 'Product Categories edited', '2021-09-18 02:08:57', 1),
(250, 'dAXn9RrXd61LYHNSgI2T', 'kRLdielYMlOa4sq8Pt5E', 'Edit Activity', 'Product Categories edited', '2021-09-18 02:09:33', 1),
(251, 'dAXn9RrXd61LYHNSgI2T', 'DqPPO2eSOEqQOl6MsjAg', 'Edit Activity', 'Product Categories edited', '2021-09-18 02:10:15', 1),
(252, 'dAXn9RrXd61LYHNSgI2T', 'ZjWmYfcmujUMTbokMQlY', 'Edit Activity', 'Product Categories edited', '2021-09-18 02:10:42', 1),
(253, 'dAXn9RrXd61LYHNSgI2T', 'F0u8MKbpnc4OtII7hHna', 'Edit Activity', 'Product Categories edited', '2021-09-18 02:11:14', 1),
(254, 'dAXn9RrXd61LYHNSgI2T', '78neA0k6PO0l3JzAPVjv', 'Add Activity', 'Navigation Added', '2021-09-18 02:51:09', 1),
(255, 'dAXn9RrXd61LYHNSgI2T', '35OhCwuf3xp0Xs5KNnBZ', 'Add Activity', 'User emmanuelnwoye5@gmail.com/+2348093223317 added', '2021-09-18 03:08:44', 1),
(256, 'dAXn9RrXd61LYHNSgI2T', 'Pe4Dl2O4CzvXDFwxqaoL', 'Add Activity', 'Shipping Fee Added', '2021-09-18 12:14:50', 1),
(257, 'dAXn9RrXd61LYHNSgI2T', 'yLoiDOjGHEbWHEcGdSZN', 'Edit Activity', 'Product Image edited', '2021-09-18 12:50:55', 1),
(258, 'dAXn9RrXd61LYHNSgI2T', 'atcZKJe3icJ9KxMc6mdb', 'Login Activity', 'User logged in successfully.', '2021-09-18 15:59:46', 1),
(259, 'dAXn9RrXd61LYHNSgI2T', 'G4hIFwexiFHVC2cNolxV', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) paid', '2021-09-19 16:50:58', 1),
(260, 'dAXn9RrXd61LYHNSgI2T', 'QzXrkgnNfz78LkI52Gos', 'Add Activity', 'Shipping Fee Added', '2021-09-19 17:14:18', 1),
(261, 'dAXn9RrXd61LYHNSgI2T', 'X031i9MXwRa6Yjfn3t4U', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) shipped (KHzhth7j6s4VBd9MNuvW)', '2021-09-19 17:31:52', 1),
(262, 'dAXn9RrXd61LYHNSgI2T', 'sKtslttk6YFyMNhqW6Sf', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) shipped (KHzhth7j6s4VBd9MNuvW)', '2021-09-19 17:32:48', 1),
(263, 'dAXn9RrXd61LYHNSgI2T', 'Dd05CX1Otesb1Al1OD6e', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) paid', '2021-09-19 17:33:17', 1),
(264, 'dAXn9RrXd61LYHNSgI2T', 'h4pryD0iACb7v2JVVeYU', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) paid', '2021-09-19 17:33:37', 1),
(265, 'dAXn9RrXd61LYHNSgI2T', 'fQFCMPOsec0vzxzwWmeE', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 17:36:37', 1),
(266, 'dAXn9RrXd61LYHNSgI2T', 'XqgB8QFR3l9inluH8qib', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 17:47:16', 1),
(267, 'dAXn9RrXd61LYHNSgI2T', 'Rg4xaZoUdXrljlAhEGbh', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:10:29', 1),
(268, 'dAXn9RrXd61LYHNSgI2T', 'Wvjqiviw1K9gsQMBfwoT', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:31:55', 1),
(269, 'dAXn9RrXd61LYHNSgI2T', 'pff6nVkw8ssmCC3WEOFh', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:34:25', 1),
(270, 'dAXn9RrXd61LYHNSgI2T', 'hZZnENXrOn6qjQagXOnb', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:36:37', 1),
(271, 'dAXn9RrXd61LYHNSgI2T', '5RASxmXoRTF3Uc5Mqky7', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:37:15', 1),
(272, 'dAXn9RrXd61LYHNSgI2T', 'pkZMXqAfwktK0c8PisEJ', 'Edit Activity', 'Updated order - (XA0VSuonQkFKdFve66li) completed', '2021-09-19 18:39:33', 1),
(273, 'dAXn9RrXd61LYHNSgI2T', '4w2xvBwSnrafk6CAZc7C', 'Edit Activity', 'Sub Product Price edited', '2021-09-19 18:42:48', 1),
(274, 'dAXn9RrXd61LYHNSgI2T', 'q4mBlyaYRYQFNkm501dd', 'Edit Activity', 'Updated order - (gW1NM3ha11ubta0Z2M6t) shipped (KHzhth7j6s4VBd9MNuvW)', '2021-09-19 19:07:03', 1),
(275, 'dAXn9RrXd61LYHNSgI2T', 'fla6t6sJb12iOSznx3Wc', 'Edit Activity', 'Updated order - (gW1NM3ha11ubta0Z2M6t) paid', '2021-09-19 19:07:22', 1),
(276, 'dAXn9RrXd61LYHNSgI2T', 'eLXx4PaSTSfSM2NZS9Wh', 'Edit Activity', 'Updated order - (gW1NM3ha11ubta0Z2M6t) completed', '2021-09-19 19:14:35', 1),
(277, 'dAXn9RrXd61LYHNSgI2T', 'wXeDCHZLhj5D9qTaCDcc', 'Edit Activity', 'Shipping Fee (xJKsWG9DLv0Q2rMZhu1Q) edited', '2021-09-20 19:13:59', 1),
(278, 'dAXn9RrXd61LYHNSgI2T', 'cvAbjfpbO0V6KiFMcqin', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) started', '2021-09-20 19:17:42', 1),
(279, 'dAXn9RrXd61LYHNSgI2T', 'hbD5jRoXYvpYS2tJbxHE', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) location updated (4.76607,7.01762)', '2021-09-20 19:24:41', 1),
(280, 'dAXn9RrXd61LYHNSgI2T', 'iK7HQIopE4CL2aXBqNoT', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) location updated (4.775343,7.0133627)', '2021-09-20 19:26:50', 1),
(281, 'dAXn9RrXd61LYHNSgI2T', '2slzjueYSai0mqHE8EMF', 'Edit Activity', 'Updated shipment - (KHzhth7j6s4VBd9MNuvW) completed', '2021-09-20 19:27:10', 1),
(282, 'dAXn9RrXd61LYHNSgI2T', 'LGvWHHLyKWcd3dxhpTMK', 'Add Activity', 'Shipment created - rider (gWzAWjgmcuFMLnqMn1GL)', '2021-09-20 19:27:43', 1),
(283, 'dAXn9RrXd61LYHNSgI2T', 'GWfAT0vWMeuLEXRf3kAs', 'Edit Activity', 'Updated order - (n1KwNRTxsEJif46hPWAO) shipped (CoCHQH7x61BuAwPimuCf)', '2021-09-20 19:30:47', 1),
(284, 'dAXn9RrXd61LYHNSgI2T', 'iTzO7MyeginrlxKsEVvv', 'Edit Activity', 'Updated shipment - (CoCHQH7x61BuAwPimuCf) started', '2021-09-20 19:33:25', 1),
(285, 'dAXn9RrXd61LYHNSgI2T', 'aPIFK4jw8sgLCTsRQd4I', 'Edit Activity', 'Updated shipment - (CoCHQH7x61BuAwPimuCf) location updated (4.796654,7.0024703)', '2021-09-20 19:34:19', 1),
(286, 'dAXn9RrXd61LYHNSgI2T', 'EG07989qer24iacQypg1', 'Edit Activity', 'Updated order - (n1KwNRTxsEJif46hPWAO) paid', '2021-09-20 19:35:00', 1),
(287, 'dAXn9RrXd61LYHNSgI2T', 'IkCZ1XoLpchUsSNX7ipf', 'Edit Activity', 'Updated order - (n1KwNRTxsEJif46hPWAO) completed', '2021-09-20 19:47:08', 1),
(288, 'dAXn9RrXd61LYHNSgI2T', 'PHK4UTu2ov0866OqrnLc', 'Add Activity', 'Category Coupon (tufresh) Added', '2021-09-21 03:54:48', 1),
(289, 'dAXn9RrXd61LYHNSgI2T', 'yX9pCUCsdzqKhHR1Rwh3', 'Delete Activity', 'All (TUFRESH) Coupons Removed', '2021-09-21 03:55:38', 1),
(290, 'dAXn9RrXd61LYHNSgI2T', 'gIJR2zRnrdd2738pJ5tO', 'Delete Activity', 'All (TUFRESH) Coupons Removed', '2021-09-21 04:12:03', 1),
(291, 'dAXn9RrXd61LYHNSgI2T', 'x1bZHWuntKVEdNaruS9k', 'Add Activity', 'Sub Product Added', '2021-09-21 04:29:27', 1),
(292, 'dAXn9RrXd61LYHNSgI2T', '4DAusr3hwEpb3eeYQKZt', 'Add Activity', 'Category Coupon (tufresh) Added', '2021-09-21 04:29:52', 1),
(293, 'dAXn9RrXd61LYHNSgI2T', 'WHxOLdJDXk7jSwXpsVoW', 'Delete Activity', 'Coupon Removed', '2021-09-21 04:31:10', 1),
(294, 'dAXn9RrXd61LYHNSgI2T', '2e0Gv1FtQkfJDXKxAjGg', 'Add Activity', 'Category Coupon (TUFRESH) Added', '2021-09-21 04:51:57', 1),
(295, 'dAXn9RrXd61LYHNSgI2T', 'yFOqtZeEdMM6j5rnutc3', 'Add Activity', 'Category Coupon (TUFRESH) Added', '2021-09-21 04:55:40', 1),
(296, 'dAXn9RrXd61LYHNSgI2T', 'cuwV2g10cCDy0quTvjIv', 'Add Activity', 'Category Coupon (TUFROZEN) Added', '2021-09-21 05:00:26', 1),
(297, 'dAXn9RrXd61LYHNSgI2T', 'cIx0L9KA6koXUFkzkm10', 'Delete Activity', 'Coupon Removed', '2021-09-21 05:02:14', 1),
(298, 'dAXn9RrXd61LYHNSgI2T', '3uRQs7skMdWRZ97KiMn3', 'Delete Activity', 'All (TUFRESH) Coupons Removed', '2021-09-21 05:02:28', 1),
(299, 'dAXn9RrXd61LYHNSgI2T', 'Ig6yHN18xIkPawbeUAQV', 'Add Activity', 'Category Coupon (FRESHTUESDAY) Added', '2021-09-21 11:51:19', 1),
(300, 'dAXn9RrXd61LYHNSgI2T', '7phrde1wz4CIPa46hf4Y', 'Add Activity', 'Category Coupon (CHILLEDTUESDAY) Added', '2021-09-21 12:02:14', 1),
(301, 'dAXn9RrXd61LYHNSgI2T', 'sADcpOLNpY7dDZZybFZ4', 'Delete Activity', 'Coupon Removed', '2021-09-21 15:03:50', 1),
(302, 'dAXn9RrXd61LYHNSgI2T', 'pn7lpL1MZnyCuwmS3Onv', 'Add Activity', 'Shipping Fee Added', '2021-09-21 15:20:02', 1),
(303, 'dAXn9RrXd61LYHNSgI2T', 'B4UgJQZiXY4g0yTVNYbq', 'Edit Activity', 'Updated shipment - (CoCHQH7x61BuAwPimuCf) completed', '2021-09-21 16:25:30', 1),
(304, 'dAXn9RrXd61LYHNSgI2T', 'gEwtTVUJzmhUuXpSAlBc', 'Add Activity', 'Shipment created - rider (gWzAWjgmcuFMLnqMn1GL)', '2021-09-21 16:25:59', 1),
(305, 'dAXn9RrXd61LYHNSgI2T', 'xTPRRnNZ1sR8BPC3C5ib', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) shipped (lyRyue9ZCSOLR0gxlG0m)', '2021-09-21 16:26:27', 1),
(306, 'dAXn9RrXd61LYHNSgI2T', 'iQv4iS6fOilrbXQzG1U3', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) shipped (lyRyue9ZCSOLR0gxlG0m)', '2021-09-21 16:26:44', 1),
(307, 'dAXn9RrXd61LYHNSgI2T', 'AD18FtfjQ3NzYouwO9sV', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) paid', '2021-09-21 16:27:15', 1),
(308, 'dAXn9RrXd61LYHNSgI2T', 'Uym4LsjrYZsBnNXqJ9jz', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) paid', '2021-09-21 16:29:20', 1),
(309, 'dAXn9RrXd61LYHNSgI2T', 'ewAn2L89pCfV7coaeUQI', 'Add Activity', 'Event Coupon (WEDNEXTDAY) Added', '2021-09-21 19:17:17', 1),
(310, 'dAXn9RrXd61LYHNSgI2T', 'fduvcJBm7BKKnt8OgRec', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) completed', '2021-09-22 06:07:00', 1),
(311, 'dAXn9RrXd61LYHNSgI2T', 'MVgMFO3a6gav7XN9yhbX', 'Edit Activity', 'Updated order - (rIhkfEjlDGWpWHJCbXs3) completed', '2021-09-22 06:07:45', 1),
(312, 'dAXn9RrXd61LYHNSgI2T', 'XSRVSmnJdkCMR8ED8l7m', 'Add Activity', 'Sub Product(s) Coupon (WEDBER) Added', '2021-09-22 06:15:22', 1),
(313, 'dAXn9RrXd61LYHNSgI2T', 'zNRV4DhLQreFtYKFa9DR', 'Add Activity', 'User(s) Personalized Coupon (WELCOME BACK) Added', '2021-09-22 07:26:38', 1),
(314, 'dAXn9RrXd61LYHNSgI2T', 'S13dCoWD8KPFaroviAp5', 'Add Activity', 'User(s) Personalized Coupon (WEDBER) Added', '2021-09-22 07:34:34', 1),
(315, 'dAXn9RrXd61LYHNSgI2T', 'xxZrYDQwkm6zMvtCqcN8', 'Delete Activity', 'All (WEDBER) Coupons Removed', '2021-09-22 07:35:12', 1),
(316, 'dAXn9RrXd61LYHNSgI2T', 'KFdSuUnvotBWaCZwJGUE', 'Edit Activity', 'Updated shipment - (lyRyue9ZCSOLR0gxlG0m) started', '2021-09-22 07:37:26', 1),
(317, 'dAXn9RrXd61LYHNSgI2T', 'pBl6UZwCI5zhwSZUn4rU', 'Edit Activity', 'Updated shipment - (lyRyue9ZCSOLR0gxlG0m) completed', '2021-09-22 07:37:54', 1),
(318, 'dAXn9RrXd61LYHNSgI2T', 'k3XGrllh8GVdmKT2ODNm', 'Add Activity', 'User(s) Personalized Coupon (WEL-STOCKER) Added', '2021-09-22 08:42:52', 1),
(319, 'dAXn9RrXd61LYHNSgI2T', 'zGhfnBrpCx1e4wiCDenU', 'Add Activity', 'Category Coupon (GO FRESHERS) Added', '2021-09-22 08:44:31', 1),
(320, 'dAXn9RrXd61LYHNSgI2T', 'zfiiI23xolbRsnkTxb5Z', 'Add Activity', 'Navigation Added', '2021-09-22 13:43:13', 1),
(321, 'dAXn9RrXd61LYHNSgI2T', '8SOnDd4ZBxS6f9UZsuj6', 'Add Activity', 'Navigation Added', '2021-09-22 13:44:06', 1),
(322, 'dAXn9RrXd61LYHNSgI2T', 'svkbGaOoURck1K9P4eiT', 'Add Activity', 'Blog undefined', '2021-09-22 20:27:34', 1),
(323, 'dAXn9RrXd61LYHNSgI2T', '0m5SVMYLVX4E7ciN7KuH', 'Add Activity', 'Blog undefined', '2021-09-22 20:30:29', 1),
(324, 'dAXn9RrXd61LYHNSgI2T', 'uuOVtjv9nbx1oVYi76l2', 'Add Activity', 'Blog undefined', '2021-09-22 20:32:36', 1),
(325, 'dAXn9RrXd61LYHNSgI2T', 'd87owZy5KvMFV0A7FRhW', 'Add Activity', 'Blog undefined', '2021-09-22 20:33:22', 1),
(326, 'dAXn9RrXd61LYHNSgI2T', 'JYAcEJAKC6PJuWqjAKoY', 'Add Activity', 'Blog undefined', '2021-09-22 20:36:53', 1),
(327, 'dAXn9RrXd61LYHNSgI2T', 'Fs24BOA4U1NqH5wHtpqw', 'Add Activity', 'Blog Images Uploaded Successfully', '2021-09-22 20:38:31', 1),
(328, 'dAXn9RrXd61LYHNSgI2T', '7gbZfpMZoT1OBC3SZHBX', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:55:26', 1),
(329, 'dAXn9RrXd61LYHNSgI2T', 'XYWYdjock3paDglV335s', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:55:39', 1),
(330, 'dAXn9RrXd61LYHNSgI2T', 'ncO3MFuF8HSWkbgZ6qZ8', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:55:50', 1),
(331, 'dAXn9RrXd61LYHNSgI2T', 'pop5UukmeSJDrKWk8jLQ', 'Add Activity', 'Blog Images Uploaded Successfully', '2021-09-22 20:57:52', 1),
(332, 'dAXn9RrXd61LYHNSgI2T', 'woL1UJv6ZvaAVKfS8v2r', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:59:23', 1),
(333, 'dAXn9RrXd61LYHNSgI2T', 'uTnzpT5uHLmlkjPLAB0t', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:59:35', 1),
(334, 'dAXn9RrXd61LYHNSgI2T', 'Heik3WI8iVPswFdMLbZG', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:59:45', 1),
(335, 'dAXn9RrXd61LYHNSgI2T', 'IDVLgQdoiokDOXpUSdf3', 'Delete Activity', 'Blog image deleted', '2021-09-22 20:59:56', 1),
(336, 'dAXn9RrXd61LYHNSgI2T', 'jMKpcAZIYmcqanELV6kO', 'Delete Activity', 'Blog image deleted', '2021-09-22 21:00:06', 1),
(337, 'dAXn9RrXd61LYHNSgI2T', '542QmrcnX5ehA67M2IQY', 'Add Activity', 'Navigation Added', '2021-09-22 21:06:33', 1),
(338, 'dAXn9RrXd61LYHNSgI2T', 'Pq05x4jxBYqqZK5LQAKw', 'Add Activity', 'Category created', '2021-09-22 21:15:30', 1),
(339, 'dAXn9RrXd61LYHNSgI2T', 'Epu8FgYnB0RJYPqC3Cpg', 'Edit Activity', 'Category edited', '2021-09-22 21:17:16', 1),
(340, 'dAXn9RrXd61LYHNSgI2T', 'SqYX0T3bpEXkqr17WVyR', 'Edit Activity', 'Category edited', '2021-09-22 21:25:05', 1),
(341, 'dAXn9RrXd61LYHNSgI2T', '2pGFujDjjF9sKX0nXawz', 'Edit Activity', 'Category edited', '2021-09-22 21:43:59', 1),
(342, 'dAXn9RrXd61LYHNSgI2T', 'X58qoAbHjxYChBm1Midt', 'Add Activity', 'Blog post published', '2021-09-22 21:49:02', 1),
(343, 'dAXn9RrXd61LYHNSgI2T', 'HTruDCxQ8T3QsBXhOMAt', 'Add Activity', 'Blog post drafted', '2021-09-22 21:50:02', 1),
(344, 'dAXn9RrXd61LYHNSgI2T', 'nLWYe8ZmDOeL7JPqcVxj', 'Delete Activity', 'Blog post deleted', '2021-09-22 22:05:01', 1),
(345, 'dAXn9RrXd61LYHNSgI2T', 'nKZ0KUThHpGRdsRtykSc', 'Add Activity', 'Blog post restored', '2021-09-22 22:05:16', 1),
(346, 'dAXn9RrXd61LYHNSgI2T', '4F6s32Obpf9csHX4H1qG', 'Add Activity', 'Blog post published from drafts', '2021-09-22 22:05:35', 1),
(347, 'dAXn9RrXd61LYHNSgI2T', 'el7orZevpbrSfZ19EglG', 'Edit Activity', 'Blog post edited and published', '2021-09-22 22:08:38', 1),
(348, 'dAXn9RrXd61LYHNSgI2T', 'KuAL2ylDbdHJJwehZ9Hk', 'Edit Activity', 'Blog post edited and published', '2021-09-22 22:09:57', 1),
(349, 'dAXn9RrXd61LYHNSgI2T', 'RsonGcYVCldKVnk1GRTH', 'Edit Activity', 'Blog post edited and published', '2021-09-22 22:10:53', 1),
(350, 'dAXn9RrXd61LYHNSgI2T', 'JrtkBIQTnPTK2wRDsqkR', 'Add Activity', 'Blog Images Uploaded Successfully', '2021-09-22 22:12:51', 1),
(351, 'dAXn9RrXd61LYHNSgI2T', 'tbCkc1UynwIL5PEQqnsy', 'Delete Activity', 'Post comment deleted', '2021-09-22 22:15:27', 1),
(352, 'dAXn9RrXd61LYHNSgI2T', 'qiop8lMenJUIOQ9uvYS1', 'Add Activity', 'Post comment restored', '2021-09-22 22:15:37', 1),
(353, 'dAXn9RrXd61LYHNSgI2T', '6xa2wQ3gLpUO5AmtppX1', 'Logout Activity', 'User logged out successfully.', '2021-09-23 01:10:27', 1),
(354, 'dAXn9RrXd61LYHNSgI2T', 'S1PoVUqfltoPFqcgRl1W', 'Login Activity', 'User logged in successfully.', '2021-09-23 11:24:48', 1),
(355, 'dAXn9RrXd61LYHNSgI2T', 'hsUFLnFV8KXiHBYVzDgV', 'Add Activity', 'Navigation Added', '2021-09-26 22:25:42', 1),
(356, 'dAXn9RrXd61LYHNSgI2T', 'JC7NgDZJX8qSh9y68b7G', 'Delete Activity', 'Flash Deal Removed', '2021-09-27 00:35:22', 1),
(357, 'dAXn9RrXd61LYHNSgI2T', 'frXBD95fnZdnIVHgLTnd', 'Add Activity', 'Flash Deal added', '2021-09-27 01:14:41', 1),
(358, 'dAXn9RrXd61LYHNSgI2T', 'tezukbcwYm1UsYpEojWj', 'Add Activity', 'Flash Deal added', '2021-09-27 01:17:25', 1),
(359, 'dAXn9RrXd61LYHNSgI2T', 'VeMhRfRyaPjxTyY0Esl6', 'Delete Activity', 'Flash Deal Removed', '2021-09-27 01:21:45', 1),
(360, 'dAXn9RrXd61LYHNSgI2T', 'BhrVHFE8jGEP8N3Zq2qI', 'Add Activity', 'Flash Deal added', '2021-09-27 01:22:33', 1),
(361, 'dAXn9RrXd61LYHNSgI2T', 't00ANTH9xtMVmMjFmjqT', 'Add Activity', 'Navigation Added', '2021-09-27 01:39:53', 1),
(362, 'dAXn9RrXd61LYHNSgI2T', 'YddkNSf0S5F2vZOZdala', 'Add Activity', 'Pop Up Deal added', '2021-09-27 01:40:51', 1),
(363, 'dAXn9RrXd61LYHNSgI2T', 'xhetNLyLbBqAI37wSoWc', 'Delete Activity', 'Pop Up Deal Removed', '2021-09-27 01:41:10', 1),
(364, 'dAXn9RrXd61LYHNSgI2T', 'WzTcbkiPE7OQlwI3CR1T', 'Delete Activity', 'Pop Up Deal Removed', '2021-09-27 01:41:30', 1),
(365, 'dAXn9RrXd61LYHNSgI2T', 'SeFogVFdB2l0AxtH2Dpk', 'Delete Activity', 'Pop Up Deal Removed', '2021-09-27 01:41:50', 1),
(366, 'dAXn9RrXd61LYHNSgI2T', 'pUZOPoeHx4CREGSh7zS9', 'Edit Activity', 'Updated order - (ektMaeZyXEUsVvd3VNNX) unpaid', '2021-09-29 23:29:55', 1),
(367, 'dAXn9RrXd61LYHNSgI2T', 'wgACK1UFaDbHkRgVzpEJ', 'Edit Activity', 'Updated order - (ektMaeZyXEUsVvd3VNNX) unpaid', '2021-09-29 23:33:00', 1),
(368, 'dAXn9RrXd61LYHNSgI2T', 'pgS3SpEVXoq2zQs3CDBz', 'Edit Activity', 'Updated order - (jd89G6hx9zwKBgI0XTxD) paid', '2021-09-29 23:42:13', 1),
(369, 'dAXn9RrXd61LYHNSgI2T', 'VyIA6NDGn6SYvHm1fHGX', 'Add Activity', 'Shipment created - rider (gWzAWjgmcuFMLnqMn1GL)', '2021-09-29 23:43:31', 1),
(370, 'dAXn9RrXd61LYHNSgI2T', 'CCUnkLlhNp5e4qozBRNS', 'Edit Activity', 'Updated order - (jd89G6hx9zwKBgI0XTxD) shipped (vIZVEQbEGAZxHuDyGALL)', '2021-09-29 23:43:53', 1),
(371, 'dAXn9RrXd61LYHNSgI2T', 'zSOuQJd9y3P4eQHsDkDI', 'Edit Activity', 'Updated order - (jd89G6hx9zwKBgI0XTxD) completed', '2021-09-29 23:44:32', 1),
(372, 'dAXn9RrXd61LYHNSgI2T', 'ptYwYxsGGetme4GbATgR', 'Edit Activity', 'Updated order - (jd89G6hx9zwKBgI0XTxD) completed', '2021-09-29 23:51:04', 1),
(373, 'dAXn9RrXd61LYHNSgI2T', 'QEd4aeVw2PnDvrA1dmBY', 'Add Activity', 'Navigation Added', '2021-09-30 13:23:03', 1),
(374, 'dAXn9RrXd61LYHNSgI2T', 'RMNeot699EMtOmwmRZPn', 'Add Activity', 'Sharing Item Added', '2021-10-01 17:19:16', 1),
(375, 'dAXn9RrXd61LYHNSgI2T', 'orXyZ3PWVZee5bL0qsLK', 'Add Activity', 'Sharing Item Added', '2021-10-01 17:29:58', 1),
(376, 'dAXn9RrXd61LYHNSgI2T', 'FzWnkURThF2EQ70DYwkl', 'Add Activity', 'Sharing Item Added', '2021-10-01 19:57:58', 1),
(377, 'dAXn9RrXd61LYHNSgI2T', 'IBHRaupPSva4VwFEQtE5', 'Edit Activity', 'Sharing item edited', '2021-10-01 21:01:02', 1);
INSERT INTO `notifications` (`id`, `user_unique_id`, `unique_id`, `type`, `action`, `added_date`, `status`) VALUES
(378, 'dAXn9RrXd61LYHNSgI2T', 'v77zjFD3t54wGsHMT8SR', 'Edit Activity', 'Sharing item edited', '2021-10-01 21:01:34', 1),
(379, 'dAXn9RrXd61LYHNSgI2T', 'sbDIDglp5oddRfMGJkZe', 'Edit Activity', 'Sharing item edited', '2021-10-01 21:02:10', 1),
(380, 'dAXn9RrXd61LYHNSgI2T', 'Wd4nhUuyFMlel8MVMvNH', 'Add Activity', 'Sharing Image added', '2021-10-01 21:14:08', 1),
(381, 'dAXn9RrXd61LYHNSgI2T', 'zckrLjVzCmozpSpxvvKj', 'Delete Activity', 'Sharing Image Removed', '2021-10-01 21:15:22', 1),
(382, 'dAXn9RrXd61LYHNSgI2T', 'AuHKtf5Tl41I1hlEjOmU', 'Edit Activity', 'Sharing Image edited', '2021-10-01 21:17:07', 1),
(383, 'dAXn9RrXd61LYHNSgI2T', 'jWD4JxI7VRSYn8gsXduP', 'Add Activity', 'Sharing Image added', '2021-10-01 21:20:12', 1),
(384, 'dAXn9RrXd61LYHNSgI2T', 'hW5G5mbJOx1ehzl380PW', 'Delete Activity', 'Sharing Removed', '2021-10-01 21:29:09', 1),
(385, 'dAXn9RrXd61LYHNSgI2T', 'g2HP6osBZIGvkUwJ1lQ9', 'Add Activity', 'Sharing Restored', '2021-10-01 21:31:08', 1),
(386, 'dAXn9RrXd61LYHNSgI2T', 'aSlhNpRJ7bqQF1WSJcby', 'Add Activity', 'User(s) Personalized Coupon (TENOCT) Added', '2021-10-04 16:40:42', 1),
(387, 'dAXn9RrXd61LYHNSgI2T', '1MNvMv10IrItJOUXulfz', 'Add Activity', 'Sharing Item Added', '2021-10-05 03:41:19', 1),
(388, 'dAXn9RrXd61LYHNSgI2T', 'X9OTUvvWoQ4JL416H5U2', 'Edit Activity', 'Sharing item edited', '2021-10-05 03:48:40', 1),
(389, 'dAXn9RrXd61LYHNSgI2T', 'IaUq1jMfUTnrdVaWh3ZG', 'Add Activity', 'Sharing Item Added', '2021-10-05 03:51:48', 1),
(390, 'dAXn9RrXd61LYHNSgI2T', 'ZtyTejKSmMBmnUxjCD9m', 'Edit Activity', 'Sharing item edited', '2021-10-05 03:52:35', 1),
(391, 'dAXn9RrXd61LYHNSgI2T', 'fFCX6rxXePv9KdyyHv0X', 'Add Activity', 'Sharing Item Added', '2021-10-05 03:56:32', 1),
(392, 'dAXn9RrXd61LYHNSgI2T', 'DXXgOC1oEGCldFxWW1NU', 'Edit Activity', 'Sharing item edited', '2021-10-05 04:55:20', 1),
(393, 'dAXn9RrXd61LYHNSgI2T', '9ArstolvVXA3aOEwLVjS', 'Edit Activity', 'Sharing item edited', '2021-10-05 04:55:51', 1),
(394, 'dAXn9RrXd61LYHNSgI2T', 'KbXUSVYcpf6Z3kDejd0Y', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-05 05:28:37', 1),
(395, 'dAXn9RrXd61LYHNSgI2T', 'CLOaqDAES9qqGJAH4l0V', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-05 05:30:06', 1),
(396, 'dAXn9RrXd61LYHNSgI2T', '3gE4CDDRG1R85UKgGHo2', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-05 05:33:54', 1),
(397, 'dAXn9RrXd61LYHNSgI2T', '962s4XZC9J97YCBaXmae', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-05 05:51:55', 1),
(398, 'dAXn9RrXd61LYHNSgI2T', 'XZQM4yH8vTiatKyGMwKr', 'Edit Activity', 'Sharing Shipping Fee (s9AEfaDuTVkBwXbVHhmz) edited', '2021-10-05 05:56:28', 1),
(399, 'dAXn9RrXd61LYHNSgI2T', '9XGYpTxIAv8JebWN7wXM', 'Delete Activity', 'Sharing Shipping fee (xnTLxFjbrTpHi54UvR2j) deleted', '2021-10-05 05:56:59', 1),
(400, 'dAXn9RrXd61LYHNSgI2T', 'iKvvW5QUI0XqQ63CV7xj', 'Edit Activity', 'Sharing Shipping Fee (K2Lde6OUCDdjFc5GrFSj) edited', '2021-10-05 05:58:28', 1),
(401, 'dAXn9RrXd61LYHNSgI2T', 'O4ogRv2ykE0h5oeOhU2h', 'Edit Activity', 'Offered Service (hTVJWwhxbs4GlqYh2ve1) edited', '2021-10-05 14:35:16', 1),
(402, 'dAXn9RrXd61LYHNSgI2T', 'NDJCeieKEEGrFdizPct6', 'Add Activity', 'Offered Service Category Added', '2021-10-05 14:37:36', 1),
(403, 'dAXn9RrXd61LYHNSgI2T', 'SHOObmwLZarf4QF1rOGQ', 'Edit Activity', 'Offered Service (zSBE16Bxtu2JJMlbLR2k) edited', '2021-10-05 14:38:24', 1),
(404, 'dAXn9RrXd61LYHNSgI2T', 'XXGIrkp5Dc1PgQYWKdGC', 'Edit Activity', 'Offered Service (zSBE16Bxtu2JJMlbLR2k) edited', '2021-10-05 14:46:17', 1),
(405, 'dAXn9RrXd61LYHNSgI2T', 'RWVOEXl89ApUr8tD0npQ', 'Edit Activity', 'Offered Service (zSBE16Bxtu2JJMlbLR2k) Image edited', '2021-10-05 14:47:09', 1),
(406, 'dAXn9RrXd61LYHNSgI2T', 'dLXPdu5blTUHIwFbLTI7', 'Edit Activity', 'Offered Service Category edited', '2021-10-05 14:51:44', 1),
(407, 'dAXn9RrXd61LYHNSgI2T', 'tQtjtGKwwmt7uwVgkhpQ', 'Edit Activity', 'Offered Service Category edited', '2021-10-05 14:52:00', 1),
(408, 'dAXn9RrXd61LYHNSgI2T', 'wIeCtPy4W99rKTLSSdfq', 'Delete Activity', 'Offered Service Category deleted', '2021-10-05 14:52:46', 1),
(409, 'dAXn9RrXd61LYHNSgI2T', 'tujFrNcJh8Bv7TeC19Kx', 'Add Activity', 'Offered Service Category restored', '2021-10-05 14:54:40', 1),
(410, 'dAXn9RrXd61LYHNSgI2T', 'LImo5lMdUssOf7vaW3Du', 'Delete Activity', 'Sharing Shipping fee (c7Dc4CIraSng5LnUuyaH) deleted', '2021-10-05 15:40:08', 1),
(411, 'dAXn9RrXd61LYHNSgI2T', 'ihho98TAGSx4TNYDVf7Q', 'Add Activity', 'Sharing Shipping fee (c7Dc4CIraSng5LnUuyaH) restored', '2021-10-05 15:41:31', 1),
(412, 'dAXn9RrXd61LYHNSgI2T', 'dhPWieITG9gBBYTDCJMi', 'Edit Activity', 'Sharing Shipping Fee (c7Dc4CIraSng5LnUuyaH) edited', '2021-10-05 15:41:52', 1),
(413, 'dAXn9RrXd61LYHNSgI2T', 'jvRIwAiYJ1ptAeCb6B8f', 'Delete Activity', 'Sharing Removed', '2021-10-05 20:10:55', 1),
(414, 'dAXn9RrXd61LYHNSgI2T', 'RVZ9WhFQ3APFL8LgPX4e', 'Add Activity', 'Sharing Restored', '2021-10-05 20:11:12', 1),
(415, 'dAXn9RrXd61LYHNSgI2T', 'iScK9p6qLoHUQtdpe44u', 'Delete Activity', 'Sharing Removed', '2021-10-05 20:12:10', 1),
(416, 'dAXn9RrXd61LYHNSgI2T', 'wNdXgnhoi2PcaeovltJe', 'Add Activity', 'Sharing Restored', '2021-10-05 20:12:26', 1),
(417, 'dAXn9RrXd61LYHNSgI2T', '4h9kEnFPhtJA6bZShBOD', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 02:15:16', 1),
(418, 'dAXn9RrXd61LYHNSgI2T', 'qITXM4oRZG2pZ6qe9FEf', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 02:17:02', 1),
(419, 'dAXn9RrXd61LYHNSgI2T', 'vB4dwLYUbXHQHBjuy61n', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 02:47:49', 1),
(420, 'dAXn9RrXd61LYHNSgI2T', 'PRK908m4bt5kLoYdVnj5', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 02:48:05', 1),
(421, 'dAXn9RrXd61LYHNSgI2T', '8h7hlbMQuoTBvPP4njwD', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-06 09:56:01', 1),
(422, 'dAXn9RrXd61LYHNSgI2T', 'urtXwaitkzaVPQlvkhKF', 'Edit Activity', 'Sharing item edited', '2021-10-06 10:06:20', 1),
(423, 'dAXn9RrXd61LYHNSgI2T', 'zybPqSVOaJ9WtrwSESkx', 'Edit Activity', 'Sharing item edited', '2021-10-06 10:06:49', 1),
(424, 'dAXn9RrXd61LYHNSgI2T', 'jhzfdbaxYa4L4sGzrAxH', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-06 10:10:38', 1),
(425, 'dAXn9RrXd61LYHNSgI2T', 'Z3txHZGS75Gg7lDUeRkh', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 15:00:59', 1),
(426, 'dAXn9RrXd61LYHNSgI2T', 'x6Ims1vIE3aVxw3KJLKH', 'Edit Activity', 'Sharing item edited', '2021-10-06 15:31:24', 1),
(427, 'dAXn9RrXd61LYHNSgI2T', 'YgenMhVR8W7FsUAk4twC', 'Edit Activity', 'Updated sharing item (VdyB8MWSZAwuvTDc7nkf) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 15:40:02', 1),
(428, 'dAXn9RrXd61LYHNSgI2T', 'iDjYVWheNiiPTVcg7ChR', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-06 15:43:43', 1),
(429, 'dAXn9RrXd61LYHNSgI2T', 'VYMmxQ590fNYyRIhuGHq', 'Edit Activity', 'Sharing Shipping Fee (67hULpzCVnZFdJXcSbnt) edited', '2021-10-06 15:44:16', 1),
(430, 'dAXn9RrXd61LYHNSgI2T', 'c9G2eFjfo79fx1f1clR4', 'Edit Activity', 'Sharing Shipping Fee (mLwUcF1LQBq6bsppj2E8) edited', '2021-10-06 15:44:39', 1),
(431, 'dAXn9RrXd61LYHNSgI2T', 'khugv8aqMhZISSnyFl6t', 'Add Activity', 'Sharing Shipping Fee Added', '2021-10-06 15:45:41', 1),
(432, 'dAXn9RrXd61LYHNSgI2T', '71cuOAZBmeweHYivwnX7', 'Edit Activity', 'Updated sharing item (7DveqPCRwLpaxpeucxfm) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 15:48:29', 1),
(433, 'dAXn9RrXd61LYHNSgI2T', '4nKl3b67VHR96UfQs0rb', 'Edit Activity', 'Updated sharing item (7DveqPCRwLpaxpeucxfm) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 15:48:40', 1),
(434, 'dAXn9RrXd61LYHNSgI2T', 'UuQnscsVPMwrohkCqWnb', 'Edit Activity', 'Updated sharing item (Pld2WCjJoNWfE6jtKwFj) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 15:48:51', 1),
(435, 'dAXn9RrXd61LYHNSgI2T', '1JXPcOmPuAuTpbIsDh3c', 'Edit Activity', 'Updated sharing item (Pld2WCjJoNWfE6jtKwFj) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 15:48:57', 1),
(436, 'dAXn9RrXd61LYHNSgI2T', 'zuyGWGi8YVX4Rw4Vwvt7', 'Edit Activity', 'Updated sharing item (jRua3gvrhKfVsa0MY5Rj) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-06 15:51:12', 1),
(437, 'dAXn9RrXd61LYHNSgI2T', 'lqMMmk1XXWxpcrD2dISH', 'Edit Activity', 'Updated sharing item (jRua3gvrhKfVsa0MY5Rj) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-06 15:53:05', 1),
(438, 'dAXn9RrXd61LYHNSgI2T', 'AIUyKbRbEVFx8NKLXw6l', 'Add Activity', 'Pickup Location(s) Added', '2021-10-10 10:13:43', 1),
(439, 'dAXn9RrXd61LYHNSgI2T', '75RQjGBjNIbnRrgExAPo', 'Edit Activity', 'Pickup Location Shipping Fee (WH0rsQw6MfMlpVIpUbrS) edited', '2021-10-10 11:59:04', 1),
(440, 'dAXn9RrXd61LYHNSgI2T', 'oMzs4bHm3bPhBED67g9J', 'Edit Activity', 'Pickup Location Shipping Fee (WH0rsQw6MfMlpVIpUbrS) edited', '2021-10-10 12:00:07', 1),
(441, 'dAXn9RrXd61LYHNSgI2T', 'jNdd2FtAhs9oqOI0ZqO4', 'Add Activity', 'Pickup Location(s) Shipping Fees Added', '2021-10-10 12:04:47', 1),
(442, 'dAXn9RrXd61LYHNSgI2T', 'rwPcy8y9tRVZ4Yku7OtT', 'Delete Activity', 'Pickup Location Shipping fee (2vuqLLNKgHiREahUtWds) deleted', '2021-10-10 12:20:07', 1),
(443, 'dAXn9RrXd61LYHNSgI2T', 'RwmFIhBk7T2JQ86zD4l7', 'Add Activity', 'Pickup Location Shipping fee (2vuqLLNKgHiREahUtWds) restored', '2021-10-10 12:22:13', 1),
(444, 'dAXn9RrXd61LYHNSgI2T', 'nWAiQVTsKv8xS7Sar82W', 'Add Activity', 'Sharing Item Added', '2021-10-10 12:59:54', 1),
(445, 'dAXn9RrXd61LYHNSgI2T', 'dybs7mhNU9OteUjAuo5v', 'Add Activity', 'Sharing Item(s) Pickup Location(s) Shipping Fee(s) Added', '2021-10-10 13:45:58', 1),
(446, 'dAXn9RrXd61LYHNSgI2T', 'iKWoRKRaZabPv9XRoJzm', 'Edit Activity', 'Sharing Item Pickup Location Shipping Fee (fO92cGYP6jvNWnqINmEk) edited', '2021-10-10 13:46:46', 1),
(447, 'dAXn9RrXd61LYHNSgI2T', 'pdRNPa5DjtwrSDjr0vVw', 'Edit Activity', 'Sharing Item Pickup Location Shipping Fee (fO92cGYP6jvNWnqINmEk) edited', '2021-10-10 13:47:24', 1),
(448, 'dAXn9RrXd61LYHNSgI2T', 'IAJMC4ltn41rYMazj3zJ', 'Delete Activity', 'Sharing Item Pickup Location Shipping fee (fO92cGYP6jvNWnqINmEk) deleted', '2021-10-10 13:47:41', 1),
(449, 'dAXn9RrXd61LYHNSgI2T', 'Z4TVRuvwOPzJdXvdAhu8', 'Add Activity', 'Sharing Item Pickup Location Shipping fee (fO92cGYP6jvNWnqINmEk) restored', '2021-10-10 13:47:54', 1),
(450, 'dAXn9RrXd61LYHNSgI2T', 'ktZwLeE0Fde9UyaGVXij', 'Add Activity', 'Sharing Item(s) Pickup Location(s) Shipping Fee(s) Added', '2021-10-10 13:48:41', 1),
(451, 'dAXn9RrXd61LYHNSgI2T', 'oX9rgUhkKg779sPZVXdl', 'Add Activity', 'Sharing Item(s) Pickup Location(s) Shipping Fee(s) Added', '2021-10-10 13:55:33', 1),
(452, 'dAXn9RrXd61LYHNSgI2T', 'xjFXuJQXB4cddTqiN4ej', 'Add Activity', 'Navigation Added', '2021-10-10 23:17:50', 1),
(453, 'dAXn9RrXd61LYHNSgI2T', 'h0sk0gDI4lrIUPuLgzwN', 'Edit Activity', 'Updated sharing item (xVhH0hrgLrkEhUn53BY1) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-11 00:18:52', 1),
(454, 'dAXn9RrXd61LYHNSgI2T', 'v5jK59V32lsgXCAY1kC0', 'Add Activity', 'Default Pickup Location Added', '2021-10-11 11:02:08', 1),
(455, 'dAXn9RrXd61LYHNSgI2T', 'HJ4hmQjUmBkDk5oVCN5M', 'Edit Activity', 'Default Pickup Location (Q0SVc2OQUdSo4ZQH35DB) edited', '2021-10-11 15:29:00', 1),
(456, 'dAXn9RrXd61LYHNSgI2T', 'TnZPDMnKBQLAOqm01UU8', 'Delete Activity', 'Default Pickup Location (c8Rja9R3TQKwJLxbVZPN) deleted', '2021-10-11 15:31:09', 1),
(457, 'dAXn9RrXd61LYHNSgI2T', '38U6FuogkHxWWj6SUYWl', 'Delete Activity', 'Default Pickup Location (Q0SVc2OQUdSo4ZQH35DB) deleted', '2021-10-11 15:32:07', 1),
(458, 'dAXn9RrXd61LYHNSgI2T', 'pgLBjGOiTCsQLXG4ng2P', 'Add Activity', 'Default Pickup Location (c8Rja9R3TQKwJLxbVZPN) restored', '2021-10-11 15:32:22', 1),
(459, 'dAXn9RrXd61LYHNSgI2T', 'amZaYyXiXM7HHHVCl5LC', 'Add Activity', 'Default Pickup Location (Q0SVc2OQUdSo4ZQH35DB) restored', '2021-10-11 15:32:36', 1),
(460, 'dAXn9RrXd61LYHNSgI2T', 'jsQRZP8adsqb6KdvJ1vt', 'Add Activity', 'Pickup Location(s) Shipping Fees Added', '2021-10-11 15:37:51', 1),
(461, 'dAXn9RrXd61LYHNSgI2T', 'fzau00k8aOPkHllzVmqV', 'Add Activity', 'Default Pickup Location Added', '2021-10-11 15:57:02', 1),
(462, 'dAXn9RrXd61LYHNSgI2T', 'St9E8qOssIYCKD4Z75vs', 'Edit Activity', 'Default Pickup Location (bc2XFVE9YlBcxGuEvUAV) edited', '2021-10-11 15:59:51', 1),
(463, 'dAXn9RrXd61LYHNSgI2T', 'McQ5FIe869nCHivnAZgG', 'Edit Activity', 'Default Pickup Location (Q0SVc2OQUdSo4ZQH35DB) edited', '2021-10-12 17:23:49', 1),
(464, 'dAXn9RrXd61LYHNSgI2T', 'PdcV1uadUMuSkcWMBNUP', 'Login Activity', 'User logged in successfully.', '2021-10-21 11:16:10', 1),
(465, 'dAXn9RrXd61LYHNSgI2T', 'TSsJyz2Q3gTguZoKrAHd', 'Add Activity', 'Event Coupon (ENDSARS) Added', '2021-10-21 16:26:23', 1),
(466, 'dAXn9RrXd61LYHNSgI2T', '5bXVKViSfkc8BAZ6fX1k', 'Logout Activity', 'User logged out successfully.', '2021-10-22 03:34:50', 1),
(467, 'dAXn9RrXd61LYHNSgI2T', 'lGC4cXmshlRB65zpi9Wm', 'Login Activity', 'User logged in successfully.', '2021-10-22 03:34:58', 1),
(468, 'dAXn9RrXd61LYHNSgI2T', 'GPcjPPMkqlG9LQjVMvdG', 'Edit Activity', 'Updated order - (XoYW3nv8cdEvP2WF70tl) unpaid', '2021-10-22 19:47:11', 1),
(469, 'dAXn9RrXd61LYHNSgI2T', 'jkwXaBL8WjScXMOMwGDQ', 'Edit Activity', 'Updated order - (2ONMz9gT1dmRM0FdzOeI) shipped (vIZVEQbEGAZxHuDyGALL)', '2021-10-22 19:49:21', 1),
(470, 'dAXn9RrXd61LYHNSgI2T', 'zFo3rpjaL281rijSA76p', 'Edit Activity', 'Updated order - (2ONMz9gT1dmRM0FdzOeI) paid', '2021-10-22 21:02:49', 1),
(471, 'dAXn9RrXd61LYHNSgI2T', '8B8yTG4T6XsPbnXPPiYD', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) shipped (vIZVEQbEGAZxHuDyGALL)', '2021-10-22 21:22:15', 1),
(472, 'dAXn9RrXd61LYHNSgI2T', '9Xh0N8hcHjjayCDaWQ8I', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) shipped (vIZVEQbEGAZxHuDyGALL)', '2021-10-22 21:22:37', 1),
(473, 'dAXn9RrXd61LYHNSgI2T', '04HcJ6gD7eGAyjJgBf2s', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) paid', '2021-10-22 21:22:55', 1),
(474, 'dAXn9RrXd61LYHNSgI2T', 'zdE4KeDEOvZSEL5PZMZe', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) paid', '2021-10-22 21:23:08', 1),
(475, 'dAXn9RrXd61LYHNSgI2T', '2QPK0qUdR4275YYJmryK', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) completed', '2021-10-22 21:23:25', 1),
(476, 'dAXn9RrXd61LYHNSgI2T', 'F0zUbwlq0MQONiOzHSIn', 'Edit Activity', 'Updated order - (xaTVUcb5RYmQvvbSQ2hE) completed', '2021-10-22 21:23:37', 1),
(477, 'dAXn9RrXd61LYHNSgI2T', 'y9X0UpTkpsdKQHP0MBm4', 'Add Activity', 'Sharing Item Added', '2021-10-24 01:04:00', 1),
(478, 'dAXn9RrXd61LYHNSgI2T', 'yy2p5yuXiQ8jgbEBvpM6', 'Add Activity', 'Sharing Item(s) Pickup Location(s) Shipping Fee(s) Added', '2021-10-24 01:07:45', 1),
(479, 'dAXn9RrXd61LYHNSgI2T', 'Vb5Nygim7C5bT9UoEcQR', 'Edit Activity', 'Sharing Item Pickup Location Shipping Fee (ObebRXLrvYH3wYNa5SMG) edited', '2021-10-24 01:08:21', 1),
(480, 'dAXn9RrXd61LYHNSgI2T', 'Z2bGndT6KiA3EAfz5BEK', 'Edit Activity', 'Sharing item edited', '2021-10-24 01:15:44', 1),
(481, 'dAXn9RrXd61LYHNSgI2T', 'YzhMEdJy6ZOhUYSSLxyh', 'Edit Activity', 'Sharing item edited', '2021-10-24 01:17:05', 1),
(482, 'dAXn9RrXd61LYHNSgI2T', 'MsXWUhOgeO4QvCZ9pdT6', 'Edit Activity', 'Updated sharing item (2HEugCfvFeI7TelOGJGD) user - (ejm525FluTiIUQkSTNqK) paid', '2021-10-24 01:37:51', 1),
(483, 'dAXn9RrXd61LYHNSgI2T', 'ajEYOaS2LOjVCZZqNK4z', 'Edit Activity', 'Updated sharing item (2HEugCfvFeI7TelOGJGD) user - (rzl5nk7rIHDpqMUbHuz9) paid', '2021-10-24 01:38:02', 1),
(484, 'dAXn9RrXd61LYHNSgI2T', 'mWMuvUcXs5bzUzbbjlSw', 'Edit Activity', 'Updated sharing item (2HEugCfvFeI7TelOGJGD) user - (bztRqJ7WTLOC32XmOwws) paid', '2021-10-24 01:38:22', 1),
(485, 'dAXn9RrXd61LYHNSgI2T', 'nejiwEI4bKNYjUkeA52F', 'Edit Activity', 'Updated sharing item (2HEugCfvFeI7TelOGJGD) user - (tPBIE40TyKjOu0A35Joe) paid', '2021-10-24 01:38:28', 1),
(486, 'dAXn9RrXd61LYHNSgI2T', 'bfDCzy2kUbNesbwrJrzA', 'Edit Activity', 'Updated sharing item (2HEugCfvFeI7TelOGJGD) user - (oiH9fzKVpI8jLubuBqUK) paid', '2021-10-24 10:36:25', 1),
(487, 'dAXn9RrXd61LYHNSgI2T', 'Z21ZaXIgSyPL6rCRde46', 'Add Activity', 'Sharing Item Added', '2021-10-24 13:02:50', 1),
(488, 'dAXn9RrXd61LYHNSgI2T', 'guC1D4YweGxyIzB3JagQ', 'Add Activity', 'Sub Category Image added', '2021-10-24 20:58:26', 1),
(489, 'dAXn9RrXd61LYHNSgI2T', 'XHvpQT9VE5ZDw0w0LP9B', 'Edit Activity', 'Sub Category Image edited', '2021-10-24 21:00:43', 1),
(490, 'dAXn9RrXd61LYHNSgI2T', 'OvbCrfz8R3dFNQNpqJLm', 'Delete Activity', 'Sub Category Image Removed', '2021-10-24 21:01:26', 1),
(491, 'dAXn9RrXd61LYHNSgI2T', 'N3vyRh1q2Uowppshft5o', 'Add Activity', 'Sub Category Image added', '2021-10-24 21:02:38', 1),
(492, 'dAXn9RrXd61LYHNSgI2T', 'AbaBvNRKM2H3nushZIcH', 'Add Activity', 'Sub Category Image added', '2021-10-24 21:03:22', 1),
(493, 'dAXn9RrXd61LYHNSgI2T', 'tbXJAm5LgS5Y2jzF7L0Y', 'Add Activity', 'Mini Category Image added', '2021-10-24 21:42:22', 1),
(494, 'dAXn9RrXd61LYHNSgI2T', 'p0qNeXFzthTQsTUjSkKh', 'Add Activity', 'User aswaggnigga@yahoo.com/null added', '2021-10-25 22:39:47', 1),
(495, 'dAXn9RrXd61LYHNSgI2T', 'YbT6vjb5fep0zCC8C9CU', 'Login Activity', 'User logged in successfully.', '2021-10-26 13:26:05', 1),
(496, 'dAXn9RrXd61LYHNSgI2T', 'CWyUl2Krko3SfxPU9zwS', 'Login Activity', 'User logged in successfully.', '2021-10-26 18:58:04', 1),
(497, 'dAXn9RrXd61LYHNSgI2T', 'xFBWEV26fQoCZlGe7nFt', 'Add Activity', 'User alabiubani5@gmail.com/+2347081616034 added', '2021-11-18 12:18:14', 1),
(498, 'dAXn9RrXd61LYHNSgI2T', 'vCiXTc1l57GFiij1i7uP', 'Login Activity', 'User logged in successfully.', '2022-06-18 01:41:03', 1),
(499, 'dAXn9RrXd61LYHNSgI2T', 'JqkPfboOMd7oWFhvVExH', 'Add Activity', 'Downloaded file from file manager', '2022-06-18 02:09:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offered_services`
--

CREATE TABLE `offered_services` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `service` varchar(30) NOT NULL,
  `offered_service_category_unique_id` varchar(20) NOT NULL,
  `details` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `file` varchar(20) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offered_services`
--

INSERT INTO `offered_services` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_product_unique_id`, `service`, `offered_service_category_unique_id`, `details`, `price`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, 'cc4sB6V0c246Zvx8R9oi', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'Package 1', 'N5N0hjrw1gccFi3tGOhZ', 'This service wraps the product in a brown paper bag for descretion', 50, 'https://www.reestoc.com/offered_service_images/1631722453.webp', '1631722453.webp', 155514, '2021-07-07 10:52:24', '2021-09-15 17:14:13', 1),
(2, '7sQ0wApcXWUdUoh1M2ic', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'Nylon black', 'L6wNYaNCAxGjfGlf5TSU', 'This service adds a special opener for the product ', 50, NULL, NULL, 0, '2021-07-07 10:53:45', '2021-07-07 10:53:45', 1),
(3, 'BumfTH4rTVXyZu6n1LdS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'Slice all', 'fGF1KDB9q2sDUWRr1H5b', 'This service adds a special opener for the product ', 50, NULL, NULL, 0, '2021-07-07 10:54:18', '2021-09-12 16:47:05', 1),
(7, 'hTVJWwhxbs4GlqYh2ve1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'Slice half', 'fGF1KDB9q2sDUWRr1H5b', 'This service wraps the product in a brown paper bag for descretion', 0, NULL, NULL, 0, '2021-08-03 23:32:32', '2021-10-05 14:35:16', 1),
(8, 'SH2R4ChVC8jTv9DiT5md', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'Grind all', 'aegi0y9dF4m7wVaw4QyB', 'This service grinds all the product ', 100, NULL, NULL, 0, '2021-08-21 20:13:58', '2021-08-21 20:13:58', 1),
(10, 'xfeSxvKEYXEU0fSXt1k1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'Half pounded', 'MAT8LS1Yn4L0a0zwbUPz', 'Just pound half of it', 30, 'https://www.reestoc.com/offered_service_images/1631720285.jpg', '1631720285.jpg', 248439, '2021-09-14 20:18:20', '2021-09-15 16:38:05', 1),
(13, 'zSBE16Bxtu2JJMlbLR2k', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'Remove head and tip', 'IU7lBmg7NTxNas5OoaRB', 'Remove head and tip', 50, 'https://www.reestoc.com/images/offered_service_images/1633441628.png', '1633441628.png', 125143, '2021-09-14 23:41:48', '2021-10-05 14:54:40', 1),
(15, 'FcGFpa0Mn7xTzCwos6gs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'Into half', 'fGF1KDB9q2sDUWRr1H5b', 'Into half', 20, 'https://www.reestoc.com/offered_service_images/1631827565.jpg', '1631827565.jpg', 11136, '2021-09-15 17:14:56', '2021-09-16 22:26:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offered_services_category`
--

CREATE TABLE `offered_services_category` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `service_category` varchar(30) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offered_services_category`
--

INSERT INTO `offered_services_category` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_product_unique_id`, `service_category`, `added_date`, `last_modified`, `status`) VALUES
(1, 'N5N0hjrw1gccFi3tGOhZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'Packaging', '2021-07-07 10:52:24', '2021-07-07 10:52:24', 1),
(2, 'L6wNYaNCAxGjfGlf5TSU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'Nylon', '2021-07-07 10:53:45', '2021-09-12 16:41:40', 1),
(3, 'fGF1KDB9q2sDUWRr1H5b', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'Slicing', '2021-07-07 10:54:18', '2021-09-12 16:47:05', 1),
(8, 'aegi0y9dF4m7wVaw4QyB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'Grinding', '2021-08-21 20:10:39', '2021-08-21 20:10:39', 1),
(9, 'MAT8LS1Yn4L0a0zwbUPz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'Pounding', '2021-09-12 16:49:37', '2021-09-14 19:10:08', 1),
(10, 'IU7lBmg7NTxNas5OoaRB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'Slicing', '2021-10-05 14:37:36', '2021-10-05 14:54:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `tracker_unique_id` varchar(20) NOT NULL,
  `shipment_unique_id` varchar(20) DEFAULT NULL,
  `coupon_unique_id` varchar(20) DEFAULT NULL,
  `shipping_fee_unique_id` varchar(20) DEFAULT NULL,
  `pickup_location` tinyint(1) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `checked_out` int(2) NOT NULL,
  `paid` int(2) NOT NULL,
  `shipped` int(2) NOT NULL,
  `disputed` int(2) NOT NULL,
  `delivery_status` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `tracker_unique_id`, `shipment_unique_id`, `coupon_unique_id`, `shipping_fee_unique_id`, `pickup_location`, `quantity`, `payment_method`, `checked_out`, `paid`, `shipped`, `disputed`, `delivery_status`, `added_date`, `last_modified`, `status`) VALUES
(1, 'r86IRJeWzAJGpK8tlSeX', 'rzl5nk7rIHDpqMUbHuz9', 'cgPcth9HTnqGjrCf6VWD', 'aAwMWDXt2Kmy8I6olH6s', 'z5DTP6kXrEcu1wyeglmN', 'NEOXNkzcn1e6tP6e2tKL', 'kKJikmrpFYJcoVZhf7im', 0, 10, 'Cash', 1, 1, 1, 0, 'Completed', '2021-09-04 05:15:36', '2021-09-11 10:37:49', 1),
(2, 'AmhpU0yAzti0pm7PP4Q7', 'rzl5nk7rIHDpqMUbHuz9', 'KZ7yG9bGYZVI1rjxoh40', 'iFj4pQbltOxPqx3WvSvq', NULL, NULL, 'NfKYg3yUdKiKr3q8nqxY', 0, 10, 'Cash', 1, 0, 0, 1, 'Cancelled', '2021-09-08 00:04:28', '2021-09-08 00:05:37', 1),
(3, 'VUx24EZQatAwg0LgtOaa', 'rzl5nk7rIHDpqMUbHuz9', 'KZ7yG9bGYZVI1rjxoh40', '9kAgISKaM0KZ102BtqEQ', 'z5DTP6kXrEcu1wyeglmN', NULL, 'NfKYg3yUdKiKr3q8nqxY', 0, 10, 'Cash', 1, 1, 1, 0, 'Completed', '2021-09-08 00:12:18', '2021-09-11 10:37:49', 1),
(4, 'qw9LNCtGeVFUzj12tFQC', 'rzl5nk7rIHDpqMUbHuz9', 'KZ7yG9bGYZVI1rjxoh40', 'JadlmdauaXRRUovWJ2Do', 'z5DTP6kXrEcu1wyeglmN', NULL, 'NfKYg3yUdKiKr3q8nqxY', 0, 1, 'Cash', 1, 1, 1, 0, 'Completed', '2021-09-08 02:06:07', '2021-09-11 10:37:49', 1),
(5, 'ANQQlDuIK28PzOhpPrFw', 'rzl5nk7rIHDpqMUbHuz9', 'cgPcth9HTnqGjrCf6VWD', '2vb4KUjolLZZsAZDsi4E', 'xe9RjnjLOABza8GvLyM7', NULL, 'kKJikmrpFYJcoVZhf7im', 0, 3, 'Cash', 1, 1, 1, 0, 'Completed', '2021-09-08 02:06:07', '2021-09-11 10:39:50', 1),
(6, 'pShCsP6dj1UnZPgdvCFC', 'oiH9fzKVpI8jLubuBqUK', 'KZ7yG9bGYZVI1rjxoh40', 'XA0VSuonQkFKdFve66li', 'KHzhth7j6s4VBd9MNuvW', NULL, 'AZeQfaY0cg4PNl53RgNJ', 0, 4, 'Transfer', 1, 1, 1, 0, 'Completed', '2021-09-19 12:54:14', '2021-09-20 19:27:10', 1),
(7, 'LyRoxlKB2Y0VQ9U0sMpX', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'XA0VSuonQkFKdFve66li', 'KHzhth7j6s4VBd9MNuvW', NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 5, 'Transfer', 1, 1, 1, 0, 'Completed', '2021-09-19 12:54:14', '2021-09-20 19:27:10', 1),
(8, 'ZzuUZXSDwX4dQYw5ztCS', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', 'gW1NM3ha11ubta0Z2M6t', 'KHzhth7j6s4VBd9MNuvW', NULL, 'xJKsWG9DLv0Q2rMZhu1Q', 0, 20, 'POS', 1, 1, 1, 0, 'Completed', '2021-09-19 18:50:07', '2021-09-20 19:27:10', 1),
(9, '5LHhpSuJsqfP0jCCwRxB', 'oiH9fzKVpI8jLubuBqUK', 'GKqmoEXM88Tjj1JXk5UP', 'n1KwNRTxsEJif46hPWAO', 'CoCHQH7x61BuAwPimuCf', NULL, 'xJKsWG9DLv0Q2rMZhu1Q', 0, 30, 'POS', 1, 1, 1, 0, 'Completed', '2021-09-20 19:15:03', '2021-09-21 16:25:30', 1),
(10, 'RoiR5NXOkJDfgR9LrnrM', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'rIhkfEjlDGWpWHJCbXs3', 'lyRyue9ZCSOLR0gxlG0m', NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 10, 'POS', 1, 1, 1, 0, 'Completed', '2021-09-21 15:28:17', '2021-09-22 07:37:54', 1),
(11, 'fSDT7R6tUpRAvN4HAPWs', 'oiH9fzKVpI8jLubuBqUK', 'TO6o8Sx8qr1X0STjB0Cl', 'rIhkfEjlDGWpWHJCbXs3', 'lyRyue9ZCSOLR0gxlG0m', NULL, '2PR7vMUUFjzefNXgFriD', 0, 6, 'POS', 1, 1, 1, 0, 'Completed', '2021-09-21 15:28:17', '2021-09-22 07:37:54', 1),
(12, 'OY4X4RYTLw5D7hFrgrTx', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'ektMaeZyXEUsVvd3VNNX', NULL, NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 20, 'Card', 1, 0, 0, 1, 'Unpaid', '2021-09-29 23:09:26', '2021-09-29 23:33:00', 1),
(13, '64SKQYcPGH5vUW0Nwu0C', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'ektMaeZyXEUsVvd3VNNX', NULL, NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 10, 'Card', 1, 0, 0, 1, 'Unpaid', '2021-09-29 23:09:26', '2021-09-29 23:29:54', 1),
(14, 'T4AANz0aM20b5GCBr8oj', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'jd89G6hx9zwKBgI0XTxD', 'vIZVEQbEGAZxHuDyGALL', NULL, 'dHnPCd6pV53XT7AYsZxj', 1, 5, 'Card', 1, 1, 1, 0, 'Completed', '2021-09-29 23:39:01', '2021-09-29 23:51:04', 1),
(15, 'XoYW3nv8cdEvP2WF70tl', 'oiH9fzKVpI8jLubuBqUK', 'TO6o8Sx8qr1X0STjB0Cl', '2ONMz9gT1dmRM0FdzOeI', 'vIZVEQbEGAZxHuDyGALL', NULL, '2PR7vMUUFjzefNXgFriD', 0, 10, 'POS', 1, 1, 1, 1, 'Refunded', '2021-10-22 18:49:20', '2021-10-22 21:14:25', 1),
(16, 'J2I8mW34opd5YK4WL3Xb', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'M0UcjlynFlUPqiTuOgqa', NULL, NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 5, 'Transfer', 1, 0, 0, 1, 'Cancelled', '2021-10-22 21:16:03', '2021-10-22 21:17:18', 1),
(17, 'exAflZD5Mg75ouRhz8kz', 'oiH9fzKVpI8jLubuBqUK', 'cgPcth9HTnqGjrCf6VWD', 'xaTVUcb5RYmQvvbSQ2hE', 'vIZVEQbEGAZxHuDyGALL', NULL, 'dHnPCd6pV53XT7AYsZxj', 0, 30, 'Transfer', 1, 1, 1, 0, 'Completed', '2021-10-22 21:19:25', '2021-10-22 21:30:46', 1),
(18, 'hY1hmBN1LPwEGlCf4qQ8', 'oiH9fzKVpI8jLubuBqUK', 'KZ7yG9bGYZVI1rjxoh40', 'xaTVUcb5RYmQvvbSQ2hE', 'vIZVEQbEGAZxHuDyGALL', NULL, 'AZeQfaY0cg4PNl53RgNJ', 0, 20, 'Transfer', 1, 1, 1, 0, 'Completed', '2021-10-22 21:19:25', '2021-10-22 21:23:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders_completed`
--

CREATE TABLE `orders_completed` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `order_unique_id` varchar(20) NOT NULL,
  `tracker_unique_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `sub_product_name` varchar(200) NOT NULL,
  `sub_product_size` varchar(30) NOT NULL,
  `rider_fullname` varchar(50) NOT NULL,
  `rider_phone_number` varchar(20) NOT NULL,
  `coupon_name` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(30) DEFAULT NULL,
  `coupon_percentage` varchar(11) DEFAULT NULL,
  `coupon_price` double DEFAULT NULL,
  `user_address_fullname` varchar(100) NOT NULL,
  `user_full_address` varchar(350) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `offered_services` varchar(300) DEFAULT NULL,
  `offered_services_prices` varchar(100) DEFAULT NULL,
  `offered_services_total_amount` int(11) DEFAULT NULL,
  `shipping_fee_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_completed`
--

INSERT INTO `orders_completed` (`id`, `unique_id`, `user_unique_id`, `order_unique_id`, `tracker_unique_id`, `quantity`, `payment_method`, `sub_product_name`, `sub_product_size`, `rider_fullname`, `rider_phone_number`, `coupon_name`, `coupon_code`, `coupon_percentage`, `coupon_price`, `user_address_fullname`, `user_full_address`, `city`, `state`, `country`, `offered_services`, `offered_services_prices`, `offered_services_total_amount`, `shipping_fee_price`, `total_price`, `added_date`, `last_modified`, `status`) VALUES
(1, 'MIXQpacwbcovpZyOv6IJ', 'oiH9fzKVpI8jLubuBqUK', 'LyRoxlKB2Y0VQ9U0sMpX', 'XA0VSuonQkFKdFve66li', 5, 'Transfer', 'Coca-Cola Low Sugar 35cl', '35cl', 'Kelvin Dubur', '08095454548', NULL, NULL, NULL, NULL, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 'Nylon black', '50', 250, 0, 1250, '2021-09-19 18:36:37', '2021-09-19 18:36:37', 1),
(2, 'OtnZ7cfXwvKdtrEvKYl3', 'oiH9fzKVpI8jLubuBqUK', 'pShCsP6dj1UnZPgdvCFC', 'XA0VSuonQkFKdFve66li', 4, 'Transfer', 'Orlu bananas', '1 basket', 'Kelvin Dubur', '08095454548', 'Weekend sales', 'WEEKEND78', '20', 700, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 'Slice half, Grind all', '0, 100', 400, 100, 14100, '2021-09-19 18:39:33', '2021-09-19 18:39:33', 1),
(5, 'ziYp2VN7WFpSKxt1fWzs', 'oiH9fzKVpI8jLubuBqUK', 'ZzuUZXSDwX4dQYw5ztCS', 'gW1NM3ha11ubta0Z2M6t', 20, 'POS', 'Garlic', '8 pcs', 'Kelvin Dubur', '08095454548', 'Weekend sales', 'WEEKENDSALES', '30', 30, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', NULL, NULL, 0, 0, 1970, '2021-09-19 19:14:35', '2021-09-19 19:14:35', 1),
(6, 'E9NClHPTkBIh4M2GfID8', 'oiH9fzKVpI8jLubuBqUK', '5LHhpSuJsqfP0jCCwRxB', 'n1KwNRTxsEJif46hPWAO', 30, 'POS', 'Garlic', '8 pcs', 'Emmanuel Nwoye', '+2348093223317', 'Weekend sales', 'WEEKENDSALES', '30', 900, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 'Half pounded', '30', 900, 20, 3600, '2021-09-20 19:47:08', '2021-09-20 19:47:08', 1),
(7, '5RDpjUGoNTgFOKIPRfxQ', 'oiH9fzKVpI8jLubuBqUK', 'RoiR5NXOkJDfgR9LrnrM', 'rIhkfEjlDGWpWHJCbXs3', 10, 'POS', 'Coca-Cola Low Sugar 35cl', '35cl', 'Emmanuel Nwoye', '+2348093223317', 'Tuesday Chilled Drinks', 'CHILLEDTUESDAY', '10', 200, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 'Nylon black', '50', 500, 0, 2300, '2021-09-22 06:07:00', '2021-09-22 06:07:00', 1),
(8, 'MlIGUaTpgOWoJgaTZuHp', 'oiH9fzKVpI8jLubuBqUK', 'fSDT7R6tUpRAvN4HAPWs', 'rIhkfEjlDGWpWHJCbXs3', 6, 'POS', 'Coca-Cola Low Sugar 75cl', '75cl', 'Emmanuel Nwoye', '+2348093223317', 'Tuesday Chilled Drinks', 'CHILLEDTUESDAY', '10', 210, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', NULL, NULL, 0, 50, 2190, '2021-09-22 06:07:44', '2021-09-22 06:07:44', 1),
(10, 'VDu8fqtqXPc3LkP8FP0F', 'oiH9fzKVpI8jLubuBqUK', 'T4AANz0aM20b5GCBr8oj', 'jd89G6hx9zwKBgI0XTxD', 5, 'Card', 'Coca-Cola Low Sugar 35cl', '35cl', 'Emmanuel Nwoye', '+2348093223317', 'Sign up coupon', 'WEL-STOCKER', '10', 100, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', NULL, NULL, 0, 0, 900, '2021-09-29 23:51:04', '2021-09-29 23:51:04', 1),
(11, '7NHUz8NS7TeommNMLsKN', 'oiH9fzKVpI8jLubuBqUK', 'exAflZD5Mg75ouRhz8kz', 'xaTVUcb5RYmQvvbSQ2hE', 30, 'Transfer', 'Coca-Cola Low Sugar 35cl', '35cl', 'Emmanuel Nwoye', '+2348093223317', NULL, NULL, NULL, NULL, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, Rivers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', NULL, NULL, 0, 0, 6000, '2021-10-22 21:23:25', '2021-10-22 21:23:25', 1),
(12, 'KsMxpnTNRNrGytjl6u4K', 'oiH9fzKVpI8jLubuBqUK', 'hY1hmBN1LPwEGlCf4qQ8', 'xaTVUcb5RYmQvvbSQ2hE', 20, 'Transfer', 'Orlu bananas', '1 basket', 'Emmanuel Nwoye', '+2348093223317', NULL, NULL, NULL, NULL, 'Emmanuel Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, Rivers State ', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', NULL, NULL, 0, 100, 82000, '2021-10-22 21:23:37', '2021-10-22 21:23:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_coupons`
--

CREATE TABLE `order_coupons` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `tracker_unique_id` varchar(20) NOT NULL,
  `coupon_unique_id` varchar(20) NOT NULL,
  `completion` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_coupons`
--

INSERT INTO `order_coupons` (`id`, `unique_id`, `user_unique_id`, `tracker_unique_id`, `coupon_unique_id`, `completion`, `added_date`, `last_modified`, `status`) VALUES
(1, '3LJvm7vs2caZAXZ1VzGM', 'oiH9fzKVpI8jLubuBqUK', 'XA0VSuonQkFKdFve66li', 'L7ny9DwWgtyFqpnmdgs2', 'Completed', '2021-09-19 16:09:21', '2021-09-19 16:09:21', 1),
(2, 'mtr0ezIKTkGsKnraUxDt', 'oiH9fzKVpI8jLubuBqUK', 'gW1NM3ha11ubta0Z2M6t', 'gGkGJsF6y5tWqR5QB8i2', 'Completed', '2021-09-19 15:46:33', '2021-09-19 18:46:33', 1),
(3, 'SFYFqV3SbNpUuJyai1nE', 'oiH9fzKVpI8jLubuBqUK', 'n1KwNRTxsEJif46hPWAO', 'gGkGJsF6y5tWqR5QB8i2', 'Completed', '2021-09-20 19:12:16', '2021-09-20 19:12:16', 1),
(4, '9Uot1QPdaWg18GHVq3k1', 'oiH9fzKVpI8jLubuBqUK', 'rIhkfEjlDGWpWHJCbXs3', '7DcTdmzxRTkNJJpxUNCM', 'Completed', '2021-09-21 15:13:46', '2021-09-21 15:13:46', 1),
(5, 'eM6FdKpofwzUZY0Ct60A', 'oiH9fzKVpI8jLubuBqUK', 'jd89G6hx9zwKBgI0XTxD', 'ZVDInEmx23daqq2qwNXV', 'Completed', '2021-09-29 23:37:13', '2021-09-29 23:37:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `order_unique_id` varchar(20) NOT NULL,
  `price` double DEFAULT NULL,
  `completion` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `unique_id`, `user_unique_id`, `order_unique_id`, `price`, `completion`, `added_date`, `last_modified`, `status`) VALUES
(1, 'rnJqR7MSHSh6TvOpdxCr', 'rzl5nk7rIHDpqMUbHuz9', 'r86IRJeWzAJGpK8tlSeX', NULL, 'Checked Out', '2021-09-04 05:15:36', '2021-09-04 05:15:36', 1),
(2, 'AmguWBske82xYWhNTjro', 'rzl5nk7rIHDpqMUbHuz9', 'r86IRJeWzAJGpK8tlSeX', NULL, 'Coupon Added', '2021-09-04 05:21:26', '2021-09-04 05:21:26', 1),
(4, '2mLHSOrtSI4AfPccJbLM', 'rzl5nk7rIHDpqMUbHuz9', 'r86IRJeWzAJGpK8tlSeX', 2000, 'Paid', '2021-09-04 23:51:35', '2021-09-04 23:51:35', 1),
(5, 'M16EgIm9Y9K4CSleAKGg', 'rzl5nk7rIHDpqMUbHuz9', 'r86IRJeWzAJGpK8tlSeX', NULL, 'Shipped', '2021-09-05 00:18:56', '2021-09-05 00:18:56', 1),
(7, 'D78i1rg59Xi0MxFhhIxy', 'rzl5nk7rIHDpqMUbHuz9', 'r86IRJeWzAJGpK8tlSeX', NULL, 'Completed', '2021-09-05 02:26:49', '2021-09-05 02:26:49', 1),
(8, 'wXCrVl4xB32oDmZIfzya', 'rzl5nk7rIHDpqMUbHuz9', 'AmhpU0yAzti0pm7PP4Q7', NULL, 'Cancelled', '2021-09-08 00:05:37', '2021-09-08 00:05:37', 1),
(9, 'Ts5UmDUOYDp5Ct2HQlNk', 'rzl5nk7rIHDpqMUbHuz9', 'VUx24EZQatAwg0LgtOaa', 35000, 'Paid', '2021-09-08 04:48:21', '2021-09-08 04:48:21', 1),
(10, 'kzc185y5j7bZ5zC3XYL8', 'rzl5nk7rIHDpqMUbHuz9', 'qw9LNCtGeVFUzj12tFQC', 3500, 'Paid', '2021-09-08 04:51:12', '2021-09-08 04:51:12', 1),
(11, 'A9bdYbEpfhwa1wUdr6KS', 'rzl5nk7rIHDpqMUbHuz9', 'qw9LNCtGeVFUzj12tFQC', NULL, 'Shipped', '2021-09-08 23:50:37', '2021-09-08 23:50:37', 1),
(12, 'UvAhoxnd99nBi2LkPJL0', 'rzl5nk7rIHDpqMUbHuz9', 'qw9LNCtGeVFUzj12tFQC', NULL, 'Shipped', '2021-09-09 09:11:38', '2021-09-09 09:11:38', 1),
(13, 'stayQ7lqyV597hHp1pjR', 'rzl5nk7rIHDpqMUbHuz9', 'VUx24EZQatAwg0LgtOaa', NULL, 'Shipped', '2021-09-09 09:45:44', '2021-09-09 09:45:44', 1),
(14, 'oxn00hHYbsLmYea7CLJp', 'rzl5nk7rIHDpqMUbHuz9', 'ANQQlDuIK28PzOhpPrFw', 630, 'Paid', '2021-09-09 09:46:05', '2021-09-09 09:46:05', 1),
(15, '7Nl1ky6oDPvzpGoqrfkG', 'rzl5nk7rIHDpqMUbHuz9', 'ANQQlDuIK28PzOhpPrFw', NULL, 'Shipped', '2021-09-09 09:46:35', '2021-09-09 09:46:35', 1),
(16, 'WfIDrti6yi6HgSqiBnr9', 'rzl5nk7rIHDpqMUbHuz9', 'VUx24EZQatAwg0LgtOaa', NULL, 'Completed', '2021-09-09 09:49:26', '2021-09-09 09:49:26', 1),
(17, 'tPVEN4ylbWWEE1c9OumD', 'rzl5nk7rIHDpqMUbHuz9', 'ANQQlDuIK28PzOhpPrFw', NULL, 'Completed', '2021-09-09 09:49:43', '2021-09-09 09:49:43', 1),
(18, '6QIrxdZu8dj8hOF7eA0H', 'rzl5nk7rIHDpqMUbHuz9', 'qw9LNCtGeVFUzj12tFQC', NULL, 'Completed', '2021-09-09 09:50:00', '2021-09-09 09:50:00', 1),
(20, 'EmlcmNO4ApkUIC09r6D0', 'oiH9fzKVpI8jLubuBqUK', 'pShCsP6dj1UnZPgdvCFC', NULL, 'Checked Out', '2021-09-19 12:54:14', '2021-09-19 12:54:14', 1),
(21, 'SAVonbpjksyDVJiMqzSs', 'oiH9fzKVpI8jLubuBqUK', 'LyRoxlKB2Y0VQ9U0sMpX', NULL, 'Checked Out', '2021-09-19 12:54:14', '2021-09-19 12:54:14', 1),
(22, 'dSrgBVBGAjv4pCqK4qvo', 'oiH9fzKVpI8jLubuBqUK', 'LyRoxlKB2Y0VQ9U0sMpX', NULL, 'Shipped', '2021-09-19 17:31:52', '2021-09-19 17:31:52', 1),
(23, '91uljwfxMPh8CvjfqQ2m', 'oiH9fzKVpI8jLubuBqUK', 'pShCsP6dj1UnZPgdvCFC', NULL, 'Shipped', '2021-09-19 17:32:48', '2021-09-19 17:32:48', 1),
(24, 'No4LKZlB6Y758OCfDBOW', 'oiH9fzKVpI8jLubuBqUK', 'LyRoxlKB2Y0VQ9U0sMpX', 1250, 'Paid', '2021-09-19 17:33:17', '2021-09-19 17:33:17', 1),
(25, '5MfCULrWuAV0M2x3EVui', 'oiH9fzKVpI8jLubuBqUK', 'pShCsP6dj1UnZPgdvCFC', 14100, 'Paid', '2021-09-19 17:33:37', '2021-09-19 17:33:37', 1),
(26, 'KRw5ciSnKUZPe7ztHntl', 'oiH9fzKVpI8jLubuBqUK', 'LyRoxlKB2Y0VQ9U0sMpX', NULL, 'Completed', '2021-09-19 18:36:37', '2021-09-19 18:36:37', 1),
(28, 'O5uX3u6DgheqLmgdbRfr', 'oiH9fzKVpI8jLubuBqUK', 'pShCsP6dj1UnZPgdvCFC', NULL, 'Completed', '2021-09-19 18:39:33', '2021-09-19 18:39:33', 1),
(29, 'w9YJCxVxjIRCRM4Pmiz0', 'oiH9fzKVpI8jLubuBqUK', 'ZzuUZXSDwX4dQYw5ztCS', NULL, 'Checked Out', '2021-09-19 18:50:07', '2021-09-19 18:50:07', 1),
(30, '0FF7b5KH18URFihMesEX', 'oiH9fzKVpI8jLubuBqUK', 'ZzuUZXSDwX4dQYw5ztCS', NULL, 'Shipped', '2021-09-19 19:07:03', '2021-09-19 19:07:03', 1),
(31, '7SqZhGwnzEBMcrpx5Bwz', 'oiH9fzKVpI8jLubuBqUK', 'ZzuUZXSDwX4dQYw5ztCS', 1970, 'Paid', '2021-09-19 19:07:22', '2021-09-19 19:07:22', 1),
(35, 'gTy5m19QXmqiymCWk4I9', 'oiH9fzKVpI8jLubuBqUK', '5LHhpSuJsqfP0jCCwRxB', NULL, 'Checked Out', '2021-09-20 19:15:03', '2021-09-20 19:15:03', 1),
(36, 'Sjn6mOPtDwZfDiI1Axtu', 'oiH9fzKVpI8jLubuBqUK', '5LHhpSuJsqfP0jCCwRxB', NULL, 'Shipped', '2021-09-20 19:30:47', '2021-09-20 19:30:47', 1),
(37, 'HEJS703iNhYl6QGRsFVq', 'oiH9fzKVpI8jLubuBqUK', '5LHhpSuJsqfP0jCCwRxB', 3600, 'Paid', '2021-09-20 19:35:00', '2021-09-20 19:35:00', 1),
(38, 'hhmGPDXJSdjYnr8TGNEm', 'oiH9fzKVpI8jLubuBqUK', '5LHhpSuJsqfP0jCCwRxB', NULL, 'Completed', '2021-09-20 19:47:08', '2021-09-20 19:47:08', 1),
(39, '2pFpX4hVCP5UK15iiuB1', 'oiH9fzKVpI8jLubuBqUK', 'RoiR5NXOkJDfgR9LrnrM', NULL, 'Checked Out', '2021-09-21 15:28:17', '2021-09-21 15:28:17', 1),
(40, 'DtizsUjjhnkoX6K1WOGc', 'oiH9fzKVpI8jLubuBqUK', 'fSDT7R6tUpRAvN4HAPWs', NULL, 'Checked Out', '2021-09-21 15:28:17', '2021-09-21 15:28:17', 1),
(41, 'ZqgdipDjq8M8pcan3hRB', 'oiH9fzKVpI8jLubuBqUK', 'fSDT7R6tUpRAvN4HAPWs', NULL, 'Shipped', '2021-09-21 16:26:27', '2021-09-21 16:26:27', 1),
(42, 'JO95SLjfaw8GqerNNIR6', 'oiH9fzKVpI8jLubuBqUK', 'RoiR5NXOkJDfgR9LrnrM', NULL, 'Shipped', '2021-09-21 16:26:44', '2021-09-21 16:26:44', 1),
(43, 'xQKutoqF3BC1qmNJyVjx', 'oiH9fzKVpI8jLubuBqUK', 'RoiR5NXOkJDfgR9LrnrM', 2300, 'Paid', '2021-09-21 16:27:15', '2021-09-21 16:27:15', 1),
(44, 'N705KhxgDD5ReXYg6QyH', 'oiH9fzKVpI8jLubuBqUK', 'fSDT7R6tUpRAvN4HAPWs', 2190, 'Paid', '2021-09-21 16:29:20', '2021-09-21 16:29:20', 1),
(45, 'QZU1X2anpmOtnRrbQhkE', 'oiH9fzKVpI8jLubuBqUK', 'RoiR5NXOkJDfgR9LrnrM', NULL, 'Completed', '2021-09-22 06:07:00', '2021-09-22 06:07:00', 1),
(46, 'tGQQ8YVMGP0hQUwmkgkT', 'oiH9fzKVpI8jLubuBqUK', 'fSDT7R6tUpRAvN4HAPWs', NULL, 'Completed', '2021-09-22 06:07:44', '2021-09-22 06:07:44', 1),
(47, 'lCKBwth6fixWgL9xkaYR', 'oiH9fzKVpI8jLubuBqUK', 'OY4X4RYTLw5D7hFrgrTx', NULL, 'Checked Out', '2021-09-29 23:09:26', '2021-09-29 23:09:26', 1),
(48, 'Slt9ddvmI3VfTkVNPXyc', 'oiH9fzKVpI8jLubuBqUK', '64SKQYcPGH5vUW0Nwu0C', NULL, 'Checked Out', '2021-09-29 23:09:26', '2021-09-29 23:09:26', 1),
(49, 'nL4lInYUBI5SqGTmIGuc', 'oiH9fzKVpI8jLubuBqUK', '64SKQYcPGH5vUW0Nwu0C', NULL, 'Order is Unpaid', '2021-09-29 23:29:54', '2021-09-29 23:29:54', 1),
(50, 'IY4RTCQ2HJvGve55vWK4', 'oiH9fzKVpI8jLubuBqUK', 'OY4X4RYTLw5D7hFrgrTx', NULL, 'Unpaid', '2021-09-29 23:33:00', '2021-09-29 23:33:00', 1),
(51, '7hTcMvM9ZWaqP5ZhZs7o', 'oiH9fzKVpI8jLubuBqUK', 'T4AANz0aM20b5GCBr8oj', NULL, 'Checked Out', '2021-09-29 23:39:01', '2021-09-29 23:39:01', 1),
(52, '8Oj7TzTqOpNbisrwDgNg', 'oiH9fzKVpI8jLubuBqUK', 'T4AANz0aM20b5GCBr8oj', 900, 'Paid', '2021-09-29 23:42:13', '2021-09-29 23:42:13', 1),
(53, 'MnY6yZYWCZlXMdsE577H', 'oiH9fzKVpI8jLubuBqUK', 'T4AANz0aM20b5GCBr8oj', NULL, 'Shipped', '2021-09-29 23:43:53', '2021-09-29 23:43:53', 1),
(55, 'tpqPgy3v2DyTouJ57uoj', 'oiH9fzKVpI8jLubuBqUK', 'T4AANz0aM20b5GCBr8oj', NULL, 'Completed', '2021-09-29 23:51:04', '2021-09-29 23:51:04', 1),
(56, 'In9tSMcR8HFTfQLp5eD6', 'oiH9fzKVpI8jLubuBqUK', 'XoYW3nv8cdEvP2WF70tl', NULL, 'Checked Out', '2021-10-22 18:49:20', '2021-10-22 18:49:20', 1),
(60, 'mGvFvOqOpw1YgNEKHY7k', 'oiH9fzKVpI8jLubuBqUK', 'XoYW3nv8cdEvP2WF70tl', NULL, 'Shipped', '2021-10-22 19:49:21', '2021-10-22 19:49:21', 1),
(61, 'x7SOhDMZ1v5Iav7OBRHL', 'oiH9fzKVpI8jLubuBqUK', 'XoYW3nv8cdEvP2WF70tl', 4000, 'Paid', '2021-10-22 21:02:49', '2021-10-22 21:02:49', 1),
(62, 'JVdIt1TDQmt7gm9Pwqxm', 'oiH9fzKVpI8jLubuBqUK', 'XoYW3nv8cdEvP2WF70tl', NULL, 'Refunded', '2021-10-22 21:14:25', '2021-10-22 21:14:25', 1),
(63, 'Mhu1aWKdtDAzPEKv5dv6', 'oiH9fzKVpI8jLubuBqUK', 'J2I8mW34opd5YK4WL3Xb', NULL, 'Checked Out', '2021-10-22 21:16:03', '2021-10-22 21:16:03', 1),
(64, '9llhG3kAzDFzM2digSZU', 'oiH9fzKVpI8jLubuBqUK', 'J2I8mW34opd5YK4WL3Xb', NULL, 'Cancelled', '2021-10-22 21:17:18', '2021-10-22 21:17:18', 1),
(65, 'qOVzxJvSUcsXmuPzvg9X', 'oiH9fzKVpI8jLubuBqUK', 'exAflZD5Mg75ouRhz8kz', NULL, 'Checked Out', '2021-10-22 21:19:25', '2021-10-22 21:19:25', 1),
(66, 'CtDFU2odSx5hpv5luARi', 'oiH9fzKVpI8jLubuBqUK', 'hY1hmBN1LPwEGlCf4qQ8', NULL, 'Checked Out', '2021-10-22 21:19:25', '2021-10-22 21:19:25', 1),
(67, 'dNPxTvEn6LcaGKYB1RQX', 'oiH9fzKVpI8jLubuBqUK', 'exAflZD5Mg75ouRhz8kz', NULL, 'Shipped', '2021-10-22 21:22:15', '2021-10-22 21:22:15', 1),
(68, 'A5kWd94ssZglMxpnxdVF', 'oiH9fzKVpI8jLubuBqUK', 'hY1hmBN1LPwEGlCf4qQ8', NULL, 'Shipped', '2021-10-22 21:22:36', '2021-10-22 21:22:36', 1),
(69, 'OBZwdwpdPxs3lpMeZTDL', 'oiH9fzKVpI8jLubuBqUK', 'hY1hmBN1LPwEGlCf4qQ8', 80000, 'Paid', '2021-10-22 21:22:55', '2021-10-22 21:22:55', 1),
(70, 'Knd3I8jY15vHpAt0HarI', 'oiH9fzKVpI8jLubuBqUK', 'exAflZD5Mg75ouRhz8kz', 6000, 'Paid', '2021-10-22 21:23:08', '2021-10-22 21:23:08', 1),
(71, 'sMA9XAQ93p2JuWbn8cdk', 'oiH9fzKVpI8jLubuBqUK', 'exAflZD5Mg75ouRhz8kz', NULL, 'Completed', '2021-10-22 21:23:25', '2021-10-22 21:23:25', 1),
(72, 'MhjU0hFPdDQZTj1nuIaU', 'oiH9fzKVpI8jLubuBqUK', 'hY1hmBN1LPwEGlCf4qQ8', NULL, 'Completed', '2021-10-22 21:23:37', '2021-10-22 21:23:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_services`
--

CREATE TABLE `order_services` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `cart_unique_id` varchar(20) DEFAULT NULL,
  `order_unique_id` varchar(20) DEFAULT NULL,
  `offered_service_unique_id` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_services`
--

INSERT INTO `order_services` (`id`, `unique_id`, `user_unique_id`, `cart_unique_id`, `order_unique_id`, `offered_service_unique_id`, `added_date`, `last_modified`, `status`) VALUES
(1, '4cSo82a959D15s2dwXLG', 'rzl5nk7rIHDpqMUbHuz9', 'PpoV41JWzwWwJ9iIkdFx', 'r86IRJeWzAJGpK8tlSeX', 'cc4sB6V0c246Zvx8R9oi', '2021-09-04 04:24:50', '2021-09-04 05:15:36', 1),
(2, 'CHaKe96rYzNeJRnJwBIA', 'rzl5nk7rIHDpqMUbHuz9', 'PpoV41JWzwWwJ9iIkdFx', 'r86IRJeWzAJGpK8tlSeX', '7sQ0wApcXWUdUoh1M2ic', '2021-09-04 04:24:50', '2021-09-04 05:15:36', 1),
(3, 'SWp0OG7Q02E8WDI8M8ZA', 'oiH9fzKVpI8jLubuBqUK', 'gI1uRpNMamEnGkUx5GI0', NULL, 'hTVJWwhxbs4GlqYh2ve1', '2021-09-18 12:13:46', '2021-09-18 12:13:46', 1),
(4, 'qv9M76v0d0XS6SXR8Bc4', 'oiH9fzKVpI8jLubuBqUK', 'gI1uRpNMamEnGkUx5GI0', NULL, 'SH2R4ChVC8jTv9DiT5md', '2021-09-18 12:13:46', '2021-09-18 12:13:46', 1),
(5, 'rNnSDEwPvWIhqGZpf6S3', 'oiH9fzKVpI8jLubuBqUK', 'vwyTDlRGhrYIlfAWsGZq', 'pShCsP6dj1UnZPgdvCFC', 'hTVJWwhxbs4GlqYh2ve1', '2021-09-18 12:15:15', '2021-09-19 12:54:14', 1),
(6, 'vKPCoi5GP8yvdXUtT1Vi', 'oiH9fzKVpI8jLubuBqUK', 'vwyTDlRGhrYIlfAWsGZq', 'pShCsP6dj1UnZPgdvCFC', 'SH2R4ChVC8jTv9DiT5md', '2021-09-18 12:15:15', '2021-09-19 12:54:14', 1),
(7, 'WZXAo37JD2JdUKP3ahuG', 'oiH9fzKVpI8jLubuBqUK', 'zbZjftYWgBEnp0sa7rK5', 'LyRoxlKB2Y0VQ9U0sMpX', '7sQ0wApcXWUdUoh1M2ic', '2021-09-18 12:23:20', '2021-09-19 12:54:14', 1),
(8, 'DLrQht3HRiEVJZ6eYYMs', 'oiH9fzKVpI8jLubuBqUK', 'IjzfXZsUVTjk4vLrI2LJ', '5LHhpSuJsqfP0jCCwRxB', 'xfeSxvKEYXEU0fSXt1k1', '2021-09-19 19:18:29', '2021-09-20 19:15:03', 1),
(9, 'z8ametLJojzrnHr2VjBC', 'oiH9fzKVpI8jLubuBqUK', '65b7etHKs7NAXCeXE6Ja', 'RoiR5NXOkJDfgR9LrnrM', '7sQ0wApcXWUdUoh1M2ic', '2021-09-21 15:17:03', '2021-09-21 15:28:17', 1),
(10, 'j4Grh43z4soBEGdoUWd0', 'oiH9fzKVpI8jLubuBqUK', 'OFSQABfRpjl6wie3shFC', 'OY4X4RYTLw5D7hFrgrTx', '7sQ0wApcXWUdUoh1M2ic', '2021-09-29 23:03:53', '2021-09-29 23:09:26', 1),
(13, 'B31HT9HzV2X8BL0xdeci', 'oiH9fzKVpI8jLubuBqUK', 'Vgq4JojQ4Hq7sqAQd4pL', 'J2I8mW34opd5YK4WL3Xb', '7sQ0wApcXWUdUoh1M2ic', '2021-10-04 18:33:33', '2021-10-22 21:16:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pickup_locations`
--

CREATE TABLE `pickup_locations` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) DEFAULT NULL,
  `sharing_unique_id` varchar(20) DEFAULT NULL,
  `savings_unique_id` varchar(20) DEFAULT NULL,
  `default_pickup_location_unique_id` varchar(20) NOT NULL,
  `price` double NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pickup_locations`
--

INSERT INTO `pickup_locations` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_product_unique_id`, `sharing_unique_id`, `savings_unique_id`, `default_pickup_location_unique_id`, `price`, `added_date`, `last_modified`, `status`) VALUES
(1, 'c8Rja9R3TQKwJLxbVZPN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, 'VdyB8MWSZAwuvTDc7nkf', NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-08 11:00:00', '2021-10-08 12:12:00', 1),
(3, 'WH0rsQw6MfMlpVIpUbrS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 10:13:43', '2021-10-11 15:32:22', 1),
(4, 'oyCMDiMYe9MCkkVV9OmH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TO6o8Sx8qr1X0STjB0Cl', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 10:13:43', '2021-10-11 15:32:22', 1),
(5, 'dm2QtNbovwypwlSI5154', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 10:13:43', '2021-10-11 15:32:22', 1),
(6, '2N3F1YAuaGtp4FyPr1wv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 10:13:43', '2021-10-11 15:32:22', 1),
(7, '2vuqLLNKgHiREahUtWds', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:22:13', 1),
(8, '6xaoMrz6PpgBULDGm7R9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(9, 'UG1tYlu9VajTyvlMPalS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(10, 'yXi1Vm5O2BYS9HLgBWDo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TO6o8Sx8qr1X0STjB0Cl', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(11, 'b2YgXPgeGacWRmUNc8T0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(12, '2vGMAiRSDzihcOobDF60', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GIrLFCYpNPdpg3NxrUbr', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(13, 'uwp92XGeemo4zCghliBN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(14, 'cCyH4R6GIUfymIBqQIPS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(15, 'yJrmHrWMSZN7NARaqzEU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', NULL, NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 12:04:47', '2021-10-10 12:04:47', 1),
(16, 'jnVRSbNTfFPGPMC13PAk', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 12:04:47', '2021-10-11 15:32:22', 1),
(17, 'zHDJAHkwBdzD4VJsgDlI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GIrLFCYpNPdpg3NxrUbr', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 12:04:47', '2021-10-11 15:32:22', 1),
(18, '8o1mZncH5OHrT98gj9Y2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 12:04:47', '2021-10-11 15:32:22', 1),
(19, 'R5FPqFy5LNJnnNsufxzj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 12:04:47', '2021-10-11 15:32:22', 1),
(20, 'ROFpU1Jj8LYRywJPba0m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', NULL, NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 12:04:47', '2021-10-11 15:32:22', 1),
(21, 'fO92cGYP6jvNWnqINmEk', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, 'xVhH0hrgLrkEhUn53BY1', NULL, 'haIM5KUVluo9APCBM2lF', 0, '2021-10-10 13:45:58', '2021-10-10 13:47:54', 1),
(24, 'kDBmuXiNUBkPM826Ik19', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, 'xVhH0hrgLrkEhUn53BY1', NULL, 'c8Rja9R3TQKwJLxbVZPN', 0, '2021-10-10 13:55:33', '2021-10-11 15:32:22', 1),
(25, 'PbsKo8DN9YYmMOBLcaQ7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', NULL, NULL, 'Q0SVc2OQUdSo4ZQH35DB', 0, '2021-10-11 15:37:50', '2021-10-11 15:37:50', 1),
(26, 'IMBUpBmDDZfIkSnZTpGR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', NULL, NULL, 'Q0SVc2OQUdSo4ZQH35DB', 0, '2021-10-11 15:37:50', '2021-10-11 15:37:50', 1),
(27, 'zOZeIfpX3FHBeV9trWm4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TO6o8Sx8qr1X0STjB0Cl', NULL, NULL, 'Q0SVc2OQUdSo4ZQH35DB', 0, '2021-10-11 15:37:50', '2021-10-11 15:37:50', 1),
(28, '8KrxvyMCSdvuG9qmeFtp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', NULL, NULL, 'Q0SVc2OQUdSo4ZQH35DB', 0, '2021-10-11 15:37:50', '2021-10-11 15:37:50', 1),
(29, 'uJ0ZOcR4xji4jnZqB7HY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, '2HEugCfvFeI7TelOGJGD', NULL, 'bc2XFVE9YlBcxGuEvUAV', 500, '2021-10-24 01:07:45', '2021-10-24 01:07:45', 1),
(30, 'ObebRXLrvYH3wYNa5SMG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, '2HEugCfvFeI7TelOGJGD', NULL, 'Q0SVc2OQUdSo4ZQH35DB', 700, '2021-10-24 01:07:45', '2021-10-24 01:08:21', 1),
(31, '9ofVgKle6WzplLVE5nw2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, '2HEugCfvFeI7TelOGJGD', NULL, 'c8Rja9R3TQKwJLxbVZPN', 500, '2021-10-24 01:07:45', '2021-10-24 01:07:45', 1),
(32, 'HT4goQeRPOXHhuTxU8Ng', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', NULL, '2HEugCfvFeI7TelOGJGD', NULL, 'haIM5KUVluo9APCBM2lF', 500, '2021-10-24 01:07:45', '2021-10-24 01:07:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pop_up_deals`
--

CREATE TABLE `pop_up_deals` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `mini_category_unique_id` varchar(20) DEFAULT NULL,
  `sub_category_unique_id` varchar(20) DEFAULT NULL,
  `category_unique_id` varchar(20) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `stripped` varchar(250) NOT NULL,
  `brand_unique_id` varchar(20) DEFAULT NULL,
  `description` varchar(3000) NOT NULL,
  `favorites` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `mini_category_unique_id`, `sub_category_unique_id`, `category_unique_id`, `name`, `stripped`, `brand_unique_id`, `description`, `favorites`, `added_date`, `last_modified`, `status`) VALUES
(1, 'ZYmiB5A4ec2dpqRc9RjL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Honeycrisp Apple', 'honeycrisp-apple', NULL, 'Honeycrisp Apple', 2, '2021-08-22 13:42:04', '2021-10-22 03:08:42', 1),
(2, '3Pwx8h1DGPuHGmtskkdN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Honeycrisp Apples (Bag)', 'honeycrisp-apples-bag', NULL, 'Honeycrisp Apples (Bag)', 1, '2021-08-22 13:43:49', '2021-08-30 14:31:46', 1),
(3, 'CMfhOfeEBc7prqbh5S1h', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Fuji Apple', 'fuji-apple', NULL, 'Fuji Apple', 1, '2021-08-22 13:46:06', '2021-08-30 14:31:46', 1),
(4, '8n79czfWKvTK5mf9SmPP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Fuji Apples (half bag)', 'fuji-apples-half-bag', NULL, 'Fuji Apples (half bag)', 1, '2021-08-22 13:46:35', '2021-08-30 14:31:46', 1),
(5, 'yMyB6vU6RhPWz62Txz4j', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Fuji Apples (bag)', 'fuji-apples-bag', NULL, 'Fuji Apples (bag)', 1, '2021-08-22 13:46:52', '2021-08-30 14:31:46', 1),
(6, 'pLmFhuwLB0qyx3Np2M1l', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Organic Apple', 'green-organic-apple', NULL, 'Green Organic Apple', 1, '2021-08-22 13:49:36', '2021-08-30 14:31:46', 1),
(7, 'YpFVXTZamfSmtjomZvqw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Organic Apple (half bag)', 'green-organic-apple-half-bag', NULL, 'Green Organic Apple (half bag)', 1, '2021-08-22 13:50:00', '2021-08-30 14:31:46', 1),
(8, '6u5SAmZ6EB2cEJXWvhdl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Organic Apple (bag)', 'green-organic-apple-bag', NULL, 'Green Organic Apple (bag)', 1, '2021-08-22 13:50:19', '2021-08-30 14:31:46', 1),
(9, 'TLgRemghfULk2YLIHiuj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Pineapple', 'pineapple', NULL, 'Pineapple', 1, '2021-08-22 13:54:36', '2021-08-30 14:31:46', 1),
(10, 'Ed003fk4XIumWEXMM4Ll', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Dragon Fruit', 'yellow-dragon-fruit', NULL, 'Yellow Dragon Fruit', 1, '2021-08-22 13:55:09', '2021-08-30 14:31:46', 1),
(11, 'F608qWR3vQUsQ9dBUASS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Raspberries ', 'raspberries-', NULL, 'Raspberries ', 1, '2021-08-22 13:56:34', '2021-08-30 14:31:46', 1),
(12, 'bmXMoM9YcIVBxmyNwgj8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Strawberries ', 'strawberries-', NULL, 'Strawberries ', 1, '2021-08-22 13:57:29', '2021-08-30 14:31:46', 1),
(13, '1dSg8Z4r8qGkOgiq1GgU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Lime', 'lime', NULL, 'Lime', 1, '2021-08-22 13:59:41', '2021-08-30 14:31:46', 1),
(14, 'RDe5xNbjllZLDx3C06Bb', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Limes (half bag)', 'limes-half-bag', NULL, 'Limes (half bag)', 1, '2021-08-22 14:00:07', '2021-08-30 14:31:46', 1),
(15, 'PuRy7q00tSddczYjqlKs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Pear ', 'pear-', NULL, 'Pear', 1, '2021-08-22 14:00:47', '2021-08-30 14:31:46', 1),
(16, 'roCiLQZYlSEDfynesYyd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Pears (half bag)', 'pears-half-bag', NULL, 'Pears (half bag)', 1, '2021-08-22 14:01:34', '2021-08-30 14:31:46', 1),
(17, 'o8BQRqrpzbJu5GnXNH40', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Pawpaw (Papaya)', 'pawpaw-papaya', NULL, 'Pawpaw (Papaya)', 1, '2021-08-22 14:02:48', '2021-08-30 14:31:46', 1),
(18, 'rmATmc82fAsWTFNdUCne', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Lemons', 'lemons', NULL, 'Lemons', 1, '2021-08-22 14:03:42', '2021-08-30 14:31:46', 1),
(19, 'BIGfY7300msTzFiD1Mu3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Bananas', 'bananas', NULL, 'Bananas', 1, '2021-08-22 14:12:11', '2021-10-22 03:11:28', 1),
(20, 'GPb4qvv0cVkAmtrQYPJ7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green seedless grapes', 'green-seedless-grapes', NULL, 'Green seedless grapes', 1, '2021-08-22 14:13:35', '2021-08-30 14:31:46', 1),
(21, 'viSSVJKUcdTxD8PiXMXQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Red seedless grapes', 'red-seedless-grapes', NULL, 'Red seedless grapes', 1, '2021-08-22 14:13:41', '2021-08-30 14:31:46', 1),
(22, 'K9U5133FieONokuFjx5j', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Coconut', 'coconut', NULL, 'Coconut', 1, '2021-08-22 14:14:39', '2021-08-30 14:31:46', 1),
(23, 'fPo1wAt1cqNBacWWXWwh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Mango', 'mango', NULL, 'Mango', 1, '2021-08-22 14:15:02', '2021-08-30 14:31:46', 1),
(24, 'RxKF7kjcwewxdxyfgpVG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Mangoes', 'mangoes', NULL, 'Mangoes', 1, '2021-08-22 14:15:17', '2021-08-30 14:31:46', 1),
(25, '121T7LP8ddRjfQysdo4G', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Peach', 'yellow-peach', NULL, 'Yellow Peach', 1, '2021-08-22 14:16:55', '2021-08-30 14:31:46', 1),
(26, 'NoCDRMowFlelV4crZLuL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Watermelon', 'watermelon', NULL, 'Watermelon', 1, '2021-08-22 14:17:17', '2021-08-30 14:31:46', 1),
(27, '6kEPf5H8FFuIaOXGCqzx', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Avocado', 'avocado', NULL, 'Avocado', 1, '2021-08-22 14:17:57', '2021-08-30 14:31:46', 1),
(28, 'CR2VgdgHnN510w7kPo6t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Kiwi', 'kiwi', NULL, 'Kiwi', 1, '2021-08-22 14:18:40', '2021-08-30 14:31:46', 1),
(29, 'oWVneQLUU10lOGZlzJNl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Blackberries', 'blackberries', NULL, 'Blackberries', 1, '2021-08-22 14:21:25', '2021-08-30 14:31:46', 1),
(30, 'w0dYyhZusHJQ79Nt3O32', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Blueberries', 'blueberries', NULL, 'Blueberries', 1, '2021-08-22 14:21:41', '2021-08-30 14:31:46', 1),
(31, '2CgmFpBo8Cn8UMQW7tW8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jumbo Cantaloupe', 'jumbo-cantaloupe', NULL, 'Jumbo Cantaloupe', 1, '2021-08-22 14:22:33', '2021-08-30 14:31:46', 1),
(32, 'KjZEuKd57qFTIPhDOwLZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Plantain', 'plantain', NULL, 'Plantain', 1, '2021-08-23 18:32:25', '2021-08-30 14:31:46', 1),
(33, 'zvlPfnTpos50OTYN1WBE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Red Dragon Fruit (Pitaya)', 'red-dragon-fruit-pitaya', NULL, 'Red Dragon Fruit (Pitaya)', 1, '2021-08-23 19:25:12', '2021-08-30 14:31:46', 1),
(34, 'BEVIo47cxgHInm44GhdN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Oranges (small)', 'oranges-small-3-pcs', NULL, 'Oranges (small)', 1, '2021-08-23 19:35:23', '2021-08-30 14:31:46', 1),
(35, '4QTT7wp7v8RGSMLTGq2W', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Oranges (small)', 'oranges-small-6-pcs', NULL, 'Oranges (small)', 1, '2021-08-23 19:36:15', '2021-08-30 14:31:46', 1),
(36, 'Qm3SiE1uJZPiUZsMbdmt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Oranges (big)', 'oranges-small-3-pcs', NULL, 'Oranges (big)', 1, '2021-08-23 19:40:40', '2021-08-30 14:31:46', 1),
(37, 'R6PWOkPpmfHbg1oJTUnN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Oranges (big)', 'oranges-big-6-pcs', NULL, 'Oranges (big)', 1, '2021-08-23 19:42:08', '2021-08-30 14:31:46', 1),
(38, '4xw7vU8hAWZmxkqRSpXp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Apricot', 'apricot-1-pc', NULL, 'Apricot', 1, '2021-08-23 21:47:11', '2021-08-30 14:31:46', 1),
(39, 'z8SQw7F6HzBojlyKcrx7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Red Cabbage', 'red-cabbage-1-pc', NULL, 'Red Cabbage', 1, '2021-08-23 22:10:46', '2021-08-30 15:54:35', 1),
(40, 'oZobMY6DynqEc00brHuF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Cabbage', 'green-cabbage-1-pc', NULL, 'Green Cabbage', 1, '2021-08-23 22:11:59', '2021-08-30 15:54:35', 1),
(41, 'njaQcTQGciA4tXDoDE8Z', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Potatoes', 'potatoes-8-pc', NULL, 'Potatoes', 1, '2021-08-23 22:13:19', '2021-08-30 15:54:35', 1),
(42, 'XPBqbxn5edePf6UNQGhX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Potatoes ', 'potatoes-1-custard-rubber', NULL, 'Potatoes', 1, '2021-08-23 22:14:02', '2021-08-30 15:54:35', 1),
(43, 'Qz6NgIp3lhzko1JXkWkr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Potatoes ', 'potatoes-half-custard-rubber', NULL, 'Potatoes', 1, '2021-08-23 22:15:17', '2021-08-30 15:54:35', 1),
(44, '4ue2Ou9euLUOkX1QNv3A', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Sweet Potatoes ', 'sweet-potatoes-8-pc', NULL, 'Sweet Potatoes', 1, '2021-08-23 22:21:17', '2021-08-30 15:54:35', 1),
(45, 'ubqX3PhJZHAnd1owZwtF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Sweet Potatoes ', 'sweet-potatoes-1-custard-rubber', NULL, 'Sweet Potatoes', 1, '2021-08-23 22:21:38', '2021-08-30 15:54:35', 1),
(46, 'V0r3ceyqIEbrYG9mAKXh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Sweet Potatoes ', 'sweet-potatoes-half-custard-rubber', NULL, 'Sweet Potatoes', 1, '2021-08-23 22:21:58', '2021-08-30 15:54:35', 1),
(47, 'GIrLFCYpNPdpg3NxrUbr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Ginger', 'ginger-5-pcs', NULL, 'Ginger', 1, '2021-08-23 22:32:30', '2021-08-30 15:54:35', 1),
(48, '4FbzqICLp5TN4J40kgSp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Thai Chile Pepper', 'thai-chile-pepper-6-pcs', NULL, 'Thai Chile Pepper', 1, '2021-08-23 22:33:16', '2021-08-30 15:54:35', 1),
(49, 'CPmEWz6ZyQZNUh4oKqNl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Tomatoes', 'tomatoes-1-small-bowl', NULL, 'Tomatoes', 1, '2021-08-23 22:40:06', '2021-08-30 15:54:35', 1),
(50, 'Ly0q4AxQ3YLBeX73yuGG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Tomatoes', 'tomatoes-1-big-bowl', NULL, 'Tomatoes', 1, '2021-08-23 22:40:25', '2021-08-30 15:54:35', 1),
(51, 'CsbrUs4NhzHMXJylSQE0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Tomatoes', 'tomatoes-1-custard-rubber', NULL, 'Tomatoes', 1, '2021-08-23 22:41:31', '2021-08-30 15:54:35', 1),
(52, 'RiKvkhKmhzd4KYhxKViZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Romaine Lettuce', 'romaine-lettuce-1-bundle', NULL, 'Romaine Lettuce', 1, '2021-08-23 22:43:43', '2021-08-30 15:54:35', 1),
(53, 'Fk9scOOt8VNT1wJOSKPL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Leaf Lettuce', 'green-leaf-lettuce-1-bundle', NULL, 'Green Leaf Lettuce', 1, '2021-08-23 22:44:41', '2021-08-30 15:54:35', 1),
(54, 'B1LpuPX2quhHKstAsRn7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Onions', 'yellow-onions-half-bunch', NULL, 'Yellow Onions', 1, '2021-08-23 22:47:01', '2021-08-30 15:54:35', 1),
(55, 'vXflwq2tex9cAUXYY4xq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Red Onions', 'red-onions-half-bunch', NULL, 'Red Onions', 1, '2021-08-23 22:47:21', '2021-08-30 15:54:35', 1),
(56, 'AjeQr1WU0OgkuIuyGgk7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Onions', 'yellow-onions-1-bunch', NULL, 'Yellow Onions', 1, '2021-08-23 22:47:40', '2021-08-30 15:54:35', 1),
(57, 'v2aJaDOC8iHAHYx6Ms0E', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Red Onions', 'red-onions-1-bunch', NULL, 'Red Onions', 1, '2021-08-23 22:48:13', '2021-08-30 15:54:35', 1),
(58, '0Cb5pHO3ceHwxJ6lLFL2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'White Onions', 'white-onions-1-bunch', NULL, 'White Onions', 1, '2021-08-23 22:48:55', '2021-08-30 15:54:35', 1),
(59, 'n36Ane9qXyVSU0WUUlis', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'White Onions', 'white-onions-half-bunch', NULL, 'White Onions', 1, '2021-08-23 22:49:05', '2021-08-30 15:54:35', 1),
(60, 'lf69BgYAqllUwawzMwdE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Cucumber', 'cucumber-3-pcs', NULL, 'Cucumber', 1, '2021-08-24 19:39:34', '2021-08-30 15:54:35', 1),
(61, 't0gqOhd5KvXkFBlPi9IE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Cucumber', 'cucumber-6-pcs', NULL, 'Cucumber', 1, '2021-08-24 19:40:03', '2021-08-30 15:54:35', 1),
(62, 'MWBDhY9b6l2fsgk2RsxR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Okra', 'green-okra-1-bunch', NULL, 'Green Okra', 1, '2021-08-24 19:40:58', '2021-08-30 15:54:35', 1),
(63, 'caH7cuXhgZ1HHftXSQXI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jumbo Green Pepper', 'jumbo-green-pepper-1-pc', NULL, 'Jumbo Green Pepper', 1, '2021-08-24 19:41:37', '2021-08-30 15:54:35', 1),
(64, '14KmvpIZw2AHDaQdPkjq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jumbo Green Pepper', 'jumbo-green-pepper-3-pc', NULL, 'Jumbo Green Pepper', 1, '2021-08-24 19:41:48', '2021-08-30 15:54:35', 1),
(65, 'IH8aF3hjtakJLQcWEkHV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jumbo Green Pepper', 'jumbo-green-pepper-6-pc', NULL, 'Jumbo Green Pepper', 1, '2021-08-24 19:41:58', '2021-08-30 15:54:35', 1),
(66, 'BAkeyPT6bLp1Sa6ZRBwO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Celery', 'celery-1-bundle', NULL, 'Celery', 1, '2021-08-24 19:42:59', '2021-08-30 15:54:35', 1),
(67, 'RfQh8qCsKx0iPjVBkDJz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Celery', 'celery-3-bundles', NULL, 'Celery', 1, '2021-08-24 19:45:54', '2021-08-30 15:54:35', 1),
(68, 'hnorIWyffhmFwB5RAUVC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Celery', 'celery-6-bundles', NULL, 'Celery', 1, '2021-08-24 19:46:05', '2021-08-30 15:54:35', 1),
(69, 'FK9OMzBpP4UrxPpoq00I', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Kale Bunch', 'kale-bunch-1-bunch', NULL, 'Kale Bunch', 1, '2021-08-24 19:46:47', '2021-08-30 15:54:35', 1),
(70, 'buubKOU2qn5LEqwhrvon', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Kale Bunch', 'kale-bunch-4-bunches', NULL, 'Kale Bunch', 1, '2021-08-24 19:47:09', '2021-08-30 15:54:35', 1),
(71, 'rZUC7rAmM35lTZm6AUei', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Zucchini Squash', 'zucchini-squash-1-pc', NULL, 'Zucchini Squash', 1, '2021-08-24 19:48:05', '2021-08-30 15:54:35', 1),
(72, 'KYQfY8po1sJyAiIJzsmo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Carrots', 'carrots-1-bundle', NULL, 'Carrots', 1, '2021-08-24 19:48:30', '2021-08-30 15:54:35', 1),
(73, 'rJ5pWCIox0hJsqqKmrN7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Carrots', 'carrots-3-bundles', NULL, 'Carrots', 1, '2021-08-24 19:48:41', '2021-08-30 15:54:35', 1),
(74, 'rM6UuKxkkzOKYiTGik9K', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Carrots', 'carrots-6-bundles', NULL, 'Carrots', 1, '2021-08-24 19:48:52', '2021-08-30 15:54:35', 1),
(75, '6gyD9GjRmk1JxwyfDBC0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Egg Plant', 'egg-plant-1-bunch', NULL, 'Egg Plant', 1, '2021-08-24 19:49:28', '2021-08-30 15:54:35', 1),
(76, 'MAI33CYT9cU4uQzhJw5t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Onions', 'green-onions-1-bundle', NULL, 'Green Onions', 1, '2021-08-24 19:51:22', '2021-08-30 15:54:35', 1),
(77, 'R8EjUXBd4Ws8rSxqbvnU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Green Onions', 'green-onions-3-bundles', NULL, 'Green Onions', 1, '2021-08-24 19:51:44', '2021-08-30 15:54:35', 1),
(78, 'TCV804vAclOg0ABHuDfQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Broccoli', 'broccoli-1-bundle', NULL, 'Broccoli', 1, '2021-08-24 19:52:27', '2021-08-30 15:54:35', 1),
(79, '20EmDuRA4JkTQYtavkX5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Broccoli', 'broccoli-3-bundles', NULL, 'Broccoli', 1, '2021-08-24 19:52:39', '2021-08-30 15:54:35', 1),
(80, '5t8EMBqYo2zP3Z8fTWQR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Squash', 'yellow-squash-1-pc', NULL, 'Yellow Squash', 1, '2021-08-24 19:54:13', '2021-08-30 15:54:35', 1),
(81, 'KEFFJDzBx3Ovn50qkx2u', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Chayote', 'chayote-1-pc', NULL, 'Chayote', 1, '2021-08-24 19:54:37', '2021-08-30 15:54:35', 1),
(82, 'iY1QfTt2xGvFzDkdt9eO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yellow Sweet Corn', 'yellow-sweet-corn-1-pc', NULL, 'Yellow Sweet Corn', 1, '2021-08-24 19:54:57', '2021-08-30 15:54:35', 1),
(83, 'XjxMjQ05J3qJgSazyrGn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jalapeno Pepper', 'jalapeno-pepper-6-pcs', NULL, 'Jalapeno Pepper', 1, '2021-08-24 19:55:35', '2021-08-30 15:54:35', 1),
(84, 'StZD6JMMZcxgjjVZr9dD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Malanga (Yautia)', 'malanga-yautia-3-pcs', NULL, 'Malanga (Yautia)', 1, '2021-08-24 19:56:13', '2021-08-30 15:54:35', 1),
(85, 'GKqmoEXM88Tjj1JXk5UP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Garlic', 'garlic-8-pcs', NULL, 'Garlic', 1, '2021-08-24 19:57:20', '2021-08-30 15:54:35', 1),
(86, 'TtJqtqBAHhBW7RAePZR3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Jumbo Red Pepper', 'jumbo-red-pepper-1-pc', NULL, 'Jumbo Red Pepper', 1, '2021-08-24 20:04:34', '2021-08-30 15:54:35', 1),
(87, 'tNaI6YFU28TVzhpoRbOX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KQ52lrrBu6QBbH3GCe3L', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Yucca', 'yucca', NULL, 'This is a Yucca', 1, '2021-08-24 20:05:07', '2021-08-31 10:52:31', 1),
(88, 'r6hZdyqKuNWjHb2oph1A', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Tangerine', 'tangerine', NULL, '<p>This is a tangerine</p>', 1, '2021-08-31 10:16:54', '2021-08-31 10:16:54', 1),
(89, 'dLoi1kU6euKoyGWXZQqL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'HE2cBUz5YoVsM5RwWy5m', 'XbHIBZ6c7CreZvnUvH41', '1LMqS4xwFkhIqHw07uKu', 'Bitter Kola', 'bitter-kola', NULL, '<p>This is a bitter kola</p>', 1, '2021-08-31 10:24:46', '2021-08-31 10:24:46', 1),
(90, 'bQ1oAE3LFsPg3eOUG7yZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'yjqojjbositQem00rdKJ', 'qDKEDodAwF7kPVSzdlml', '1LMqS4xwFkhIqHw07uKu', 'Nescafe Gold Cappuccino Decaf', 'nescafe-gold-cappuccino-decaf', 'xrxzhl90bs0x4p5KPFCC', '<p>Nescafe Gold Cappuccino Decaf</p>', 1, '2021-08-31 12:08:46', '2021-09-18 02:08:57', 1),
(91, 'yEOdrmh7DvR3CjhEVyVc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'xNfnKRoOXO754FJ4F88A', 'qDKEDodAwF7kPVSzdlml', '1LMqS4xwFkhIqHw07uKu', 'Lucozade Boost', 'lucozade-boost', NULL, '<p>This is lucozade boost</p>', 1, '2021-08-31 12:20:26', '2021-09-18 02:09:33', 1),
(92, 'ZuGUnC2vz8pFOOyxwVFj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B9ngayUK3lbQruGbzqat', 'qDKEDodAwF7kPVSzdlml', '1LMqS4xwFkhIqHw07uKu', 'Coca-Cola Canned', 'coca-cola-canned', 'C1tHrWOkqevKUrqeh4Fu', '<p>This the Coca-Cola that is canned</p>', 1, '2021-09-02 23:52:15', '2021-09-18 02:10:14', 1),
(93, '1CEde4tNg5zvwZjLIAfH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B9ngayUK3lbQruGbzqat', 'qDKEDodAwF7kPVSzdlml', '1LMqS4xwFkhIqHw07uKu', 'Coca-Cola Bottle', 'coca-cola-bottle', 'C1tHrWOkqevKUrqeh4Fu', '<p>This is the Coca-Cola bottled soft drink</p>', 1, '2021-09-02 23:59:34', '2021-09-18 02:10:42', 1),
(94, 'Fx86O2htSlPlb9wB0Lvc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B9ngayUK3lbQruGbzqat', 'qDKEDodAwF7kPVSzdlml', '1LMqS4xwFkhIqHw07uKu', 'Coca-Cola Plastic Bottle', 'coca-cola-plastic-bottle', 'C1tHrWOkqevKUrqeh4Fu', '<p>This is the Coca-Cola plastic bottled soft drink</p>', 1, '2021-09-03 00:01:03', '2021-09-18 02:11:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `product_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `product_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, 'GGfbWE2sjCve70njSdPJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KjZEuKd57qFTIPhDOwLZ', 'https://www.reestoc.com/product_images/1629742506.jpg', '1629742506.jpg', 4887, '2021-08-23 19:15:06', '2021-08-23 19:15:06', 1),
(2, '5q3jpy9tVvvyPVkl6JqU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KjZEuKd57qFTIPhDOwLZ', 'https://www.reestoc.com/product_images/1629742507.jpeg', '1629742507.jpeg', 4998, '2021-08-23 19:15:06', '2021-08-23 19:15:06', 1),
(3, '3ugJ8X7WA0ncsSjA8jR4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'zvlPfnTpos50OTYN1WBE', 'https://www.reestoc.com/product_images/1629743172.jpg', '1629743172.jpg', 13566, '2021-08-23 19:26:12', '2021-08-23 19:26:12', 1),
(4, 'hw4VGotP6GaGt7fQGrUY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '3Pwx8h1DGPuHGmtskkdN', 'https://www.reestoc.com/product_images/1629743259.jpg', '1629743259.jpg', 7117, '2021-08-23 19:27:39', '2021-08-23 19:27:39', 1),
(5, '1j0SmS7ca2NPTMAzA5pU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'F608qWR3vQUsQ9dBUASS', 'https://www.reestoc.com/product_images/1629743308.jpg', '1629743308.jpg', 10712, '2021-08-23 19:28:28', '2021-08-23 19:28:28', 1),
(6, 'sjbzC6AbvSVM9tTFIXtm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BEVIo47cxgHInm44GhdN', 'https://www.reestoc.com/product_images/1629744216.jpg', '1629744216.jpg', 5749, '2021-08-23 19:43:36', '2021-08-23 19:43:36', 1),
(7, 'mQF2xbhVZB5LCuXuBHzs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BEVIo47cxgHInm44GhdN', 'https://www.reestoc.com/product_images/1629744217.jpg', '1629744217.jpg', 6335, '2021-08-23 19:43:36', '2021-08-23 19:43:36', 1),
(8, 'yiyGpmsbGIAVxI0A4JyZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'https://www.reestoc.com/product_images/1629744256.jpg', '1629744256.jpg', 5749, '2021-08-23 19:44:16', '2021-08-23 19:44:16', 1),
(9, 'AJSJcr6HlkAbCSEdAjVn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'https://www.reestoc.com/product_images/1629744257.jpg', '1629744257.jpg', 6335, '2021-08-23 19:44:16', '2021-08-23 19:44:16', 1),
(10, 'fKh5Zw8NdzlHM80v4RU5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qm3SiE1uJZPiUZsMbdmt', 'https://www.reestoc.com/product_images/1629744315.jpg', '1629744315.jpg', 5749, '2021-08-23 19:45:15', '2021-08-23 19:45:15', 1),
(11, 'KNyUXaQ0s3TzJ8pc6R3g', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qm3SiE1uJZPiUZsMbdmt', 'https://www.reestoc.com/product_images/1629744316.jpg', '1629744316.jpg', 6335, '2021-08-23 19:45:15', '2021-08-23 19:45:15', 1),
(12, 'J4MJHKsLHoohYj1pkqyh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R6PWOkPpmfHbg1oJTUnN', 'https://www.reestoc.com/product_images/1629744355.jpg', '1629744355.jpg', 5749, '2021-08-23 19:45:55', '2021-08-23 19:45:55', 1),
(13, 'E50TaH4LTtf8t7xYJznH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R6PWOkPpmfHbg1oJTUnN', 'https://www.reestoc.com/product_images/1629744356.jpg', '1629744356.jpg', 6335, '2021-08-23 19:45:55', '2021-08-23 19:45:55', 1),
(14, 'cw8gSjWj3v8Q0xqnjTn5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1dSg8Z4r8qGkOgiq1GgU', 'https://www.reestoc.com/product_images/1629744707.jpeg', '1629744707.jpeg', 6594, '2021-08-23 19:51:47', '2021-08-23 19:51:47', 1),
(15, 'Shf8L9RUCNE6oYeBFy0P', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RDe5xNbjllZLDx3C06Bb', 'https://www.reestoc.com/product_images/1629744820.jpg', '1629744820.jpg', 51384, '2021-08-23 19:53:40', '2021-08-23 19:53:40', 1),
(16, 'h2wjM47EU02lMqp3M7m6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'https://www.reestoc.com/product_images/1629749355.jpeg', '1629749355.jpeg', 5656, '2021-08-23 21:09:15', '2021-08-23 21:09:15', 1),
(17, 'KRR0MEmRsaHINXvkteCu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'https://www.reestoc.com/product_images/1629749356.jpg', '1629749356.jpg', 44550, '2021-08-23 21:09:15', '2021-08-23 21:09:15', 1),
(18, 'VDijzUuPRFGkt75njak1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', 'https://www.reestoc.com/product_images/1629749468.jpg', '1629749468.jpg', 11495, '2021-08-23 21:11:08', '2021-08-23 21:11:08', 1),
(19, 'bp3ONGDDCRyDP44tYe5n', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', 'https://www.reestoc.com/product_images/1629749469.jpg', '1629749469.jpg', 8823, '2021-08-23 21:11:08', '2021-08-23 21:11:08', 1),
(20, 'Xn5Ri62rgQd65LH6ZEtG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'https://www.reestoc.com/product_images/1629749567.jpg', '1629749567.jpg', 6181, '2021-08-23 21:12:47', '2021-08-23 21:12:47', 1),
(21, '6UB7OSJEBYzFcgYDj260', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'https://www.reestoc.com/product_images/1629749569.jpg', '1629749569.jpg', 13309, '2021-08-23 21:12:47', '2021-08-23 21:12:47', 1),
(22, 'nACxwVmogRrbc1OtszdD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TLgRemghfULk2YLIHiuj', 'https://www.reestoc.com/product_images/1629750063.jpg', '1629750063.jpg', 6687, '2021-08-23 21:21:03', '2021-08-23 21:21:03', 1),
(23, 'nan70v7waGmKECMMzr8U', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TLgRemghfULk2YLIHiuj', 'https://www.reestoc.com/product_images/1629750064.jpeg', '1629750064.jpeg', 45751, '2021-08-23 21:21:03', '2021-08-23 21:21:03', 1),
(24, 'kpi4KdiJaQDtvh2zSsPq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZYmiB5A4ec2dpqRc9RjL', 'https://www.reestoc.com/product_images/1629750104.jpeg', '1629750104.jpeg', 56417, '2021-08-23 21:21:44', '2021-08-23 21:21:44', 1),
(25, '5fJXGx7XLYOuARiIBthu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ed003fk4XIumWEXMM4Ll', 'https://www.reestoc.com/product_images/1629750138.jpg', '1629750138.jpg', 6311, '2021-08-23 21:22:18', '2021-08-23 21:22:18', 1),
(26, 'jwlJJCIQEZqDZxROCEcy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '121T7LP8ddRjfQysdo4G', 'https://www.reestoc.com/product_images/1629750203.jpg', '1629750203.jpg', 6092, '2021-08-23 21:23:23', '2021-08-23 21:23:23', 1),
(27, '0sQyILT0qREEzzwla3i8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '121T7LP8ddRjfQysdo4G', 'https://www.reestoc.com/product_images/1629750204.jpg', '1629750204.jpg', 8006, '2021-08-23 21:23:23', '2021-08-23 21:23:23', 1),
(28, 'vykw0yMGofM2HCgQn14J', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/product_images/1629750273.jpeg', '1629750273.jpeg', 8238, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(29, 'mZInFJ6JmfkEGwA3avL8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/product_images/1629750274.jpg', '1629750274.jpg', 6859, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(30, 'bL4UEnjM8cF6ZhvGHB19', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/product_images/1629750275.jpg', '1629750275.jpg', 7380, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(31, 'ule2OjhJgfrlRuIm7nHV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'K9U5133FieONokuFjx5j', 'https://www.reestoc.com/product_images/1629750328.jpg', '1629750328.jpg', 6742, '2021-08-23 21:25:28', '2021-08-23 21:25:28', 1),
(32, 'dkqXuoQBa4TeoLrj6hqM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'fPo1wAt1cqNBacWWXWwh', 'https://www.reestoc.com/product_images/1629750577.jpg', '1629750577.jpg', 6065, '2021-08-23 21:29:37', '2021-08-23 21:29:37', 1),
(33, 'SuQb9JL2xQBU0fdtW17s', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RxKF7kjcwewxdxyfgpVG', 'https://www.reestoc.com/product_images/1629750603.jpg', '1629750603.jpg', 6065, '2021-08-23 21:30:03', '2021-08-23 21:30:03', 1),
(34, 'zKqp9aSgGgU44OSOl6q0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CMfhOfeEBc7prqbh5S1h', 'https://www.reestoc.com/product_images/1629750704.jpeg', '1629750704.jpeg', 61814, '2021-08-23 21:31:44', '2021-08-23 21:31:44', 1),
(35, 'euZXlwIFlpY1JBkE9YtU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '8n79czfWKvTK5mf9SmPP', 'https://www.reestoc.com/product_images/1629750788.jpg', '1629750788.jpg', 6923, '2021-08-23 21:33:08', '2021-08-23 21:33:08', 1),
(36, 'CkFEx6urJzytNmDkLwoE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'yMyB6vU6RhPWz62Txz4j', 'https://www.reestoc.com/product_images/1629750829.jpg', '1629750829.jpg', 6580, '2021-08-23 21:33:49', '2021-08-23 21:33:49', 1),
(37, 't7dIgUR050XOma003Tnd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'https://www.reestoc.com/product_images/1629750908.jpg', '1629750908.jpg', 6096, '2021-08-23 21:35:08', '2021-08-23 21:35:08', 1),
(38, 'nT1pVjnIEesIcVBc4320', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'https://www.reestoc.com/product_images/1629750909.jpeg', '1629750909.jpeg', 3265, '2021-08-23 21:35:08', '2021-08-23 21:35:08', 1),
(39, 'ZNuO5QQt8D3xxlHYqytc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'w0dYyhZusHJQ79Nt3O32', 'https://www.reestoc.com/product_images/1629751041.jpg', '1629751041.jpg', 10735, '2021-08-23 21:37:21', '2021-08-23 21:37:21', 1),
(40, 'uvRA8SEpcUIaoJvk8ryT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'PuRy7q00tSddczYjqlKs', 'https://www.reestoc.com/product_images/1629751165.jpeg', '1629751165.jpeg', 5868, '2021-08-23 21:39:25', '2021-08-23 21:39:25', 1),
(41, '2k9JQeLgZyMBirfekW3e', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'roCiLQZYlSEDfynesYyd', 'https://www.reestoc.com/product_images/1629751192.jpg', '1629751192.jpg', 6435, '2021-08-23 21:39:52', '2021-08-23 21:39:52', 1),
(42, '89GYLJP8suu4XZLWDYWi', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4xw7vU8hAWZmxkqRSpXp', 'https://www.reestoc.com/product_images/1629751735.jpg', '1629751735.jpg', 7649, '2021-08-23 21:48:55', '2021-08-23 21:48:55', 1),
(43, '0qzPHdj1fSs2ifzF4MvI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'pLmFhuwLB0qyx3Np2M1l', 'https://www.reestoc.com/product_images/1629751855.jpeg', '1629751855.jpeg', 73247, '2021-08-23 21:50:55', '2021-08-23 21:50:55', 1),
(44, '2twHlZ58SyrHr2MJdGZf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'YpFVXTZamfSmtjomZvqw', 'https://www.reestoc.com/product_images/1629751884.jpg', '1629751884.jpg', 47277, '2021-08-23 21:51:24', '2021-08-23 21:51:24', 1),
(45, 'F78YOmbsVZD7SCHG41Xf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6u5SAmZ6EB2cEJXWvhdl', 'https://www.reestoc.com/product_images/1629751913.jpeg', '1629751913.jpeg', 12761, '2021-08-23 21:51:53', '2021-08-23 21:51:53', 1),
(46, 'J7wOosmIdrBpkeqvgTTs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'https://www.reestoc.com/product_images/1629751974.jpg', '1629751974.jpg', 4362, '2021-08-23 21:52:54', '2021-08-23 21:52:54', 1),
(47, 'HS9Arif5cq86lhVCBgit', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'https://www.reestoc.com/product_images/1629751975.jpeg', '1629751975.jpeg', 7779, '2021-08-23 21:52:54', '2021-08-23 21:52:54', 1),
(48, 'exF8UIKU9W3dsffo7Tt9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GPb4qvv0cVkAmtrQYPJ7', 'https://www.reestoc.com/product_images/1629752020.jpg', '1629752020.jpg', 6161, '2021-08-23 21:53:40', '2021-08-23 21:53:40', 1),
(49, 't9DW0LlCJRGSMctK6WdS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'viSSVJKUcdTxD8PiXMXQ', 'https://www.reestoc.com/product_images/1629752097.jpg', '1629752097.jpg', 7042, '2021-08-23 21:54:57', '2021-08-23 21:54:57', 1),
(50, 'Vu5X1uI4PVW1GDpE0xcG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oWVneQLUU10lOGZlzJNl', 'https://www.reestoc.com/product_images/1629752138.jpg', '1629752138.jpg', 13124, '2021-08-23 21:55:38', '2021-08-23 21:55:38', 1),
(51, 'uOwitXQADn8d1VChMRCK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '2CgmFpBo8Cn8UMQW7tW8', 'https://www.reestoc.com/product_images/1629752174.jpeg', '1629752174.jpeg', 8438, '2021-08-23 21:56:14', '2021-08-23 21:56:14', 1),
(52, 'xH3IPFTY7r57hrieBtya', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CR2VgdgHnN510w7kPo6t', 'https://www.reestoc.com/product_images/1629752206.jpeg', '1629752206.jpeg', 7997, '2021-08-23 21:56:46', '2021-08-23 21:56:46', 1),
(53, 'shrNFchJTn20moCKSsi3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z8SQw7F6HzBojlyKcrx7', 'https://www.reestoc.com/product_images/1629831998.jpeg', '1629831998.jpeg', 61731, '2021-08-24 20:06:38', '2021-08-24 20:06:38', 1),
(54, 'wU1JhdX6hBu9xdxJfw27', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z8SQw7F6HzBojlyKcrx7', 'https://www.reestoc.com/product_images/1629831999.jpg', '1629831999.jpg', 5376, '2021-08-24 20:06:38', '2021-08-24 20:06:38', 1),
(55, 'RrinGviVvweOZJF8kqiZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oZobMY6DynqEc00brHuF', 'https://www.reestoc.com/product_images/1629832136.jpg', '1629832136.jpg', 32926, '2021-08-24 20:08:56', '2021-08-24 20:08:56', 1),
(56, 'C3BqPm9XUUNnHDBbHgGU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oZobMY6DynqEc00brHuF', 'https://www.reestoc.com/product_images/1629832137.jpg', '1629832137.jpg', 7763, '2021-08-24 20:08:56', '2021-08-24 20:08:56', 1),
(57, 'agOcmUYXYKxZrD7xVIWr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'njaQcTQGciA4tXDoDE8Z', 'https://www.reestoc.com/product_images/1629832961.jpeg', '1629832961.jpeg', 24514, '2021-08-24 20:22:41', '2021-08-24 20:22:41', 1),
(58, 'nAIn6U6dBMWsFTz6jCvn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XPBqbxn5edePf6UNQGhX', 'https://www.reestoc.com/product_images/1629832981.jpeg', '1629832981.jpeg', 24514, '2021-08-24 20:23:01', '2021-08-24 20:23:01', 1),
(59, 'llqd80bYaMFohVgSx5M2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qz6NgIp3lhzko1JXkWkr', 'https://www.reestoc.com/product_images/1629833012.jpeg', '1629833012.jpeg', 24514, '2021-08-24 20:23:32', '2021-08-24 20:23:32', 1),
(60, 'yNEyfRni9sUx9aKJDSTS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4ue2Ou9euLUOkX1QNv3A', 'https://www.reestoc.com/product_images/1629833049.jpg', '1629833049.jpg', 4960, '2021-08-24 20:24:09', '2021-08-24 20:24:09', 1),
(61, 'd2hQjbL7O1RRml9bSDBP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4ue2Ou9euLUOkX1QNv3A', 'https://www.reestoc.com/product_images/1629833050.jpeg', '1629833050.jpeg', 21980, '2021-08-24 20:24:09', '2021-08-24 20:24:09', 1),
(62, '6k666j3N3ch8AJQProC0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ubqX3PhJZHAnd1owZwtF', 'https://www.reestoc.com/product_images/1629833107.jpeg', '1629833107.jpeg', 21980, '2021-08-24 20:25:07', '2021-08-24 20:25:07', 1),
(63, 'ux2hTBCYbD97yVpZek9C', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ubqX3PhJZHAnd1owZwtF', 'https://www.reestoc.com/product_images/1629833108.jpg', '1629833108.jpg', 4960, '2021-08-24 20:25:07', '2021-08-24 20:25:07', 1),
(64, 'm8NGYL9t1ewIuBxR21bU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'V0r3ceyqIEbrYG9mAKXh', 'https://www.reestoc.com/product_images/1629833149.jpeg', '1629833149.jpeg', 21980, '2021-08-24 20:25:49', '2021-08-24 20:25:49', 1),
(65, 'jnkQNLkxJXY0ZXr2kHAU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'V0r3ceyqIEbrYG9mAKXh', 'https://www.reestoc.com/product_images/1629833150.jpg', '1629833150.jpg', 4960, '2021-08-24 20:25:49', '2021-08-24 20:25:49', 1),
(66, 'MyHVelqY49zLPu29n73x', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GIrLFCYpNPdpg3NxrUbr', 'https://www.reestoc.com/product_images/1629833193.jpg', '1629833193.jpg', 5240, '2021-08-24 20:26:33', '2021-08-24 20:26:33', 1),
(67, 'Q2Qy6Yhb4pTQqdSEyeyH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4FbzqICLp5TN4J40kgSp', 'https://www.reestoc.com/product_images/1629833225.jpeg', '1629833225.jpeg', 33104, '2021-08-24 20:27:05', '2021-08-24 20:27:05', 1),
(68, 'flWNQoAhJABPJZFW7qh4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', 'https://www.reestoc.com/product_images/1629833268.jpg', '1629833268.jpg', 50249, '2021-08-24 20:27:48', '2021-08-24 20:27:48', 1),
(69, 'NnBHKGa1HtJOs606hQ9t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', 'https://www.reestoc.com/product_images/1629833269.jpeg', '1629833269.jpeg', 37273, '2021-08-24 20:27:48', '2021-08-24 20:27:48', 1),
(70, 'Xcw12rXrU0eJiC9hE2E8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ly0q4AxQ3YLBeX73yuGG', 'https://www.reestoc.com/product_images/1629833287.jpg', '1629833287.jpg', 50249, '2021-08-24 20:28:07', '2021-08-24 20:28:07', 1),
(71, '2YeYYKABdFnSwozsT7ny', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ly0q4AxQ3YLBeX73yuGG', 'https://www.reestoc.com/product_images/1629833288.jpeg', '1629833288.jpeg', 37273, '2021-08-24 20:28:07', '2021-08-24 20:28:07', 1),
(72, 'S9yZAdnhyFq83FY4U9ec', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', 'https://www.reestoc.com/product_images/1629833382.jpg', '1629833382.jpg', 50249, '2021-08-24 20:29:42', '2021-08-24 20:29:42', 1),
(73, 'rrgi87YgSJHszsFZ925q', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', 'https://www.reestoc.com/product_images/1629833384.jpeg', '1629833384.jpeg', 37273, '2021-08-24 20:29:42', '2021-08-24 20:29:42', 1),
(74, 'YAfVGJN11Cc01KOlgtAs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RiKvkhKmhzd4KYhxKViZ', 'https://www.reestoc.com/product_images/1629834348.jpeg', '1629834348.jpeg', 56215, '2021-08-24 20:45:48', '2021-08-24 20:45:48', 1),
(75, 'v1CtrEYbrp88DtCHJz17', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RiKvkhKmhzd4KYhxKViZ', 'https://www.reestoc.com/product_images/1629834349.jpg', '1629834349.jpg', 7298, '2021-08-24 20:45:48', '2021-08-24 20:45:48', 1),
(76, 'ACK1skVDR9TmJyR3xOw8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Fk9scOOt8VNT1wJOSKPL', 'https://www.reestoc.com/product_images/1629834413.jpeg', '1629834413.jpeg', 63498, '2021-08-24 20:46:53', '2021-08-24 20:46:53', 1),
(77, 'mhrqrvYDb1jeJsPVVrFY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B1LpuPX2quhHKstAsRn7', 'https://www.reestoc.com/product_images/1629835194.jpeg', '1629835194.jpeg', 22823, '2021-08-24 20:59:54', '2021-08-24 20:59:54', 1),
(78, '8l6NPOGZGd1Di3ISyQiJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B1LpuPX2quhHKstAsRn7', 'https://www.reestoc.com/product_images/1629835195.jpeg', '1629835195.jpeg', 39981, '2021-08-24 20:59:54', '2021-08-24 20:59:54', 1),
(79, 'az5liHGXiiKYRRliuoXw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AjeQr1WU0OgkuIuyGgk7', 'https://www.reestoc.com/product_images/1629835235.jpeg', '1629835235.jpeg', 22823, '2021-08-24 21:00:35', '2021-08-24 21:00:35', 1),
(80, 'L61NogK45E0i3pF4M1OX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AjeQr1WU0OgkuIuyGgk7', 'https://www.reestoc.com/product_images/1629835236.jpeg', '1629835236.jpeg', 39981, '2021-08-24 21:00:35', '2021-08-24 21:00:35', 1),
(81, 'WoTpf8MrfKaIySoAkVWy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vXflwq2tex9cAUXYY4xq', 'https://www.reestoc.com/product_images/1629835271.jpg', '1629835271.jpg', 7603, '2021-08-24 21:01:11', '2021-08-24 21:01:11', 1),
(82, 'YIZDaBpRxzfrBaQVjTqC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vXflwq2tex9cAUXYY4xq', 'https://www.reestoc.com/product_images/1629835272.jpeg', '1629835272.jpeg', 44119, '2021-08-24 21:01:11', '2021-08-24 21:01:11', 1),
(83, 'WXMMGdBhOo5GD7Ki4qfL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'v2aJaDOC8iHAHYx6Ms0E', 'https://www.reestoc.com/product_images/1629835307.jpg', '1629835307.jpg', 7603, '2021-08-24 21:01:47', '2021-08-24 21:01:47', 1),
(84, 'yLVfgivZcUIbdtj4wSAl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'v2aJaDOC8iHAHYx6Ms0E', 'https://www.reestoc.com/product_images/1629835308.jpeg', '1629835308.jpeg', 44119, '2021-08-24 21:01:47', '2021-08-24 21:01:47', 1),
(85, 'qXM4RMKrn1VuVf58yxSJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '0Cb5pHO3ceHwxJ6lLFL2', 'https://www.reestoc.com/product_images/1629835555.jpeg', '1629835555.jpeg', 32465, '2021-08-24 21:05:55', '2021-08-24 21:05:55', 1),
(86, 'yKEYVZi96cVQnXqb8JsD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'n36Ane9qXyVSU0WUUlis', 'https://www.reestoc.com/product_images/1629835587.jpeg', '1629835587.jpeg', 32465, '2021-08-24 21:06:27', '2021-08-24 21:06:27', 1),
(87, 'Tj8q8BSdzyDrdQJgTJ59', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'lf69BgYAqllUwawzMwdE', 'https://www.reestoc.com/product_images/1631965854.webp', '1631965854.webp', 11136, '2021-08-24 21:07:26', '2021-09-18 12:50:54', 1),
(88, 'g0zSZtvIUrnWXuWSE3kd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 't0gqOhd5KvXkFBlPi9IE', 'https://www.reestoc.com/product_images/1629835676.jpeg', '1629835676.jpeg', 20173, '2021-08-24 21:07:56', '2021-08-24 21:07:56', 1),
(89, 'a1UkYy83k1ugF0bheavp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MWBDhY9b6l2fsgk2RsxR', 'https://www.reestoc.com/product_images/1629835712.jpg', '1629835712.jpg', 5512, '2021-08-24 21:08:32', '2021-08-24 21:08:32', 1),
(90, 'T1px2OZXDLbxiB63IUSU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'caH7cuXhgZ1HHftXSQXI', 'https://www.reestoc.com/product_images/1629835771.jpeg', '1629835771.jpeg', 36126, '2021-08-24 21:09:31', '2021-08-24 21:09:31', 1),
(91, 'BPaDrcyOX6eJ8lh0xxTy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '14KmvpIZw2AHDaQdPkjq', 'https://www.reestoc.com/product_images/1629835786.jpeg', '1629835786.jpeg', 36126, '2021-08-24 21:09:46', '2021-08-24 21:09:46', 1),
(92, 'slsRkaSjvSC5eRYxIZmI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'IH8aF3hjtakJLQcWEkHV', 'https://www.reestoc.com/product_images/1629835822.jpeg', '1629835822.jpeg', 36126, '2021-08-24 21:10:22', '2021-08-24 21:10:22', 1),
(93, 'x1Q0bF85t3cb6wvVLMLw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BAkeyPT6bLp1Sa6ZRBwO', 'https://www.reestoc.com/product_images/1629835854.jpg', '1629835854.jpg', 42632, '2021-08-24 21:10:54', '2021-08-24 21:10:54', 1),
(94, 'zD5wBl2B9hWymeVy18pT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RfQh8qCsKx0iPjVBkDJz', 'https://www.reestoc.com/product_images/1629835875.jpg', '1629835875.jpg', 42632, '2021-08-24 21:11:15', '2021-08-24 21:11:15', 1),
(95, 'frSBvznffkk8hDsuzuuy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'https://www.reestoc.com/product_images/1629835899.jpg', '1629835899.jpg', 42632, '2021-08-24 21:11:39', '2021-08-24 21:11:39', 1),
(96, '8lpzS355jI5IneXlrlzM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'FK9OMzBpP4UrxPpoq00I', 'https://www.reestoc.com/product_images/1629835940.jpeg', '1629835940.jpeg', 95499, '2021-08-24 21:12:20', '2021-08-24 21:12:20', 1),
(97, 'WNPwCY3Z3983vAX7iwkv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'buubKOU2qn5LEqwhrvon', 'https://www.reestoc.com/product_images/1629835972.jpeg', '1629835972.jpeg', 95499, '2021-08-24 21:12:52', '2021-08-24 21:12:52', 1),
(98, 'NYT2cmjArXG6LqIrGHK0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rZUC7rAmM35lTZm6AUei', 'https://www.reestoc.com/product_images/1629836009.jpeg', '1629836009.jpeg', 30144, '2021-08-24 21:13:29', '2021-08-24 21:13:29', 1),
(99, 'Z4S8MI2RD41WlEbCKnJX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KYQfY8po1sJyAiIJzsmo', 'https://www.reestoc.com/product_images/1629836091.jpeg', '1629836091.jpeg', 19142, '2021-08-24 21:14:51', '2021-08-24 21:14:51', 1),
(100, 'BfQIXzBRBwD5qraJxN8m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rJ5pWCIox0hJsqqKmrN7', 'https://www.reestoc.com/product_images/1629836132.jpeg', '1629836132.jpeg', 19142, '2021-08-24 21:15:32', '2021-08-24 21:15:32', 1),
(101, 'OdwUj7la6oIKNNxvAHMc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rM6UuKxkkzOKYiTGik9K', 'https://www.reestoc.com/product_images/1629836171.jpeg', '1629836171.jpeg', 19142, '2021-08-24 21:16:11', '2021-08-24 21:16:11', 1),
(102, 'FIAxfhyF8msl97Co3YSq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6gyD9GjRmk1JxwyfDBC0', 'https://www.reestoc.com/product_images/1629845293.jpg', '1629845293.jpg', 4915, '2021-08-24 23:48:13', '2021-08-24 23:48:13', 1),
(103, 'GWpvlbat3QPzO42abwcm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MAI33CYT9cU4uQzhJw5t', 'https://www.reestoc.com/product_images/1629845331.jpeg', '1629845331.jpeg', 27258, '2021-08-24 23:48:51', '2021-08-24 23:48:51', 1),
(104, 'Ooe0m8sd3jiJZ6votkFO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R8EjUXBd4Ws8rSxqbvnU', 'https://www.reestoc.com/product_images/1629845355.jpeg', '1629845355.jpeg', 27258, '2021-08-24 23:49:15', '2021-08-24 23:49:15', 1),
(105, 'aekHQUMyvKDW1hKYrYDt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TCV804vAclOg0ABHuDfQ', 'https://www.reestoc.com/product_images/1630597167.webp', '1630597167.webp', 25670, '2021-08-24 23:49:58', '2021-09-02 16:39:27', 1),
(106, 'N9h5TA4O2k5e9WyULbLp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TCV804vAclOg0ABHuDfQ', 'https://www.reestoc.com/product_images/1629845399.jpg', '1629845399.jpg', 64362, '2021-08-24 23:49:58', '2021-08-24 23:49:58', 1),
(107, 'sCa5QagF5mWrk8JczoCM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '20EmDuRA4JkTQYtavkX5', 'https://www.reestoc.com/product_images/1629845419.jpeg', '1629845419.jpeg', 43316, '2021-08-24 23:50:19', '2021-08-24 23:50:19', 1),
(108, 'aw8MriBnjS5mqmLSrgrE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '20EmDuRA4JkTQYtavkX5', 'https://www.reestoc.com/product_images/1629845420.jpg', '1629845420.jpg', 64362, '2021-08-24 23:50:19', '2021-08-24 23:50:19', 1),
(110, 'b7UDi30s5jF2onGebU16', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '5t8EMBqYo2zP3Z8fTWQR', 'https://www.reestoc.com/product_images/1629845498.jpeg', '1629845498.jpeg', 17929, '2021-08-24 23:51:37', '2021-08-24 23:51:37', 1),
(111, '3SQwvmpwNNjsFgGwkIHz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'https://www.reestoc.com/product_images/1629845544.jpeg', '1629845544.jpeg', 27456, '2021-08-24 23:52:24', '2021-08-24 23:52:24', 1),
(112, 'l8r4nb0ztFCuAfOGSFTu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'iY1QfTt2xGvFzDkdt9eO', 'https://www.reestoc.com/product_images/1629845563.jpeg', '1629845563.jpeg', 24920, '2021-08-24 23:52:43', '2021-08-24 23:52:43', 1),
(113, 'pwGT7VrVezYu1ENySZkP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'https://www.reestoc.com/product_images/1629845599.jpeg', '1629845599.jpeg', 32503, '2021-08-24 23:53:19', '2021-08-24 23:53:19', 1),
(114, 'YldLdvC5ASNTDPuAmQId', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'StZD6JMMZcxgjjVZr9dD', 'https://www.reestoc.com/product_images/1629845660.jpeg', '1629845660.jpeg', 43917, '2021-08-24 23:54:20', '2021-08-24 23:54:20', 1),
(115, 'lkXzF19AdBzrGrovskIJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'https://www.reestoc.com/product_images/1629845694.jpg', '1629845694.jpg', 27108, '2021-08-24 23:54:54', '2021-08-24 23:54:54', 1),
(116, '2iy4yStIjBcmGtew3Wj1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TtJqtqBAHhBW7RAePZR3', 'https://www.reestoc.com/product_images/1629845725.jpeg', '1629845725.jpeg', 35351, '2021-08-24 23:55:25', '2021-08-24 23:55:25', 1),
(117, '9scJ0u1O4SMpAyL7cuyT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'tNaI6YFU28TVzhpoRbOX', 'https://www.reestoc.com/product_images/1629845763.jpeg', '1629845763.jpeg', 33502, '2021-08-24 23:56:03', '2021-08-24 23:56:03', 1),
(119, 'oBSzyxvX3pByiXWq9TF2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bQ1oAE3LFsPg3eOUG7yZ', 'https://www.reestoc.com/product_images/1630592857.png', '1630592857.png', 464824, '2021-09-02 15:27:37', '2021-09-02 15:27:37', 1),
(120, 'K5jghfpuPzDfiIoaSfDY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'r6hZdyqKuNWjHb2oph1A', 'https://www.reestoc.com/product_images/1630593961.jpg', '1630593961.jpg', 35080, '2021-09-02 15:46:01', '2021-09-02 15:46:01', 1),
(121, 'F60TAVT6uwX1IDakKHMH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZuGUnC2vz8pFOOyxwVFj', 'https://www.reestoc.com/product_images/1630623482.jpg', '1630623482.jpg', 133338, '2021-09-02 23:58:02', '2021-09-02 23:58:02', 1),
(122, 'IxrmhZQ43vovNP8wr7Yf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1CEde4tNg5zvwZjLIAfH', 'https://www.reestoc.com/product_images/1630623701.jpg', '1630623701.jpg', 248439, '2021-09-03 00:01:41', '2021-09-03 00:01:41', 1),
(123, 'PiZOPt8EDvDLF7ozAiQs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Fx86O2htSlPlb9wB0Lvc', 'https://www.reestoc.com/product_images/1630623747.jpg', '1630623747.jpg', 116628, '2021-09-03 00:02:27', '2021-09-03 00:02:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `referral_user_unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `user_referral_link` varchar(200) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `unique_id`, `referral_user_unique_id`, `user_unique_id`, `user_referral_link`, `added_date`, `last_modified`, `status`) VALUES
(1, 'JOu0mi1630bKPmS9VYQG', 'oiH9fzKVpI8jLubuBqUK', 'tPBIE40TyKjOu0A35Joe', 'https://auth.reestoc.com/signup/tPBIE40TyKjOu0A35Joe', '2021-08-07 03:30:35', '2021-08-07 03:30:35', 1),
(2, '1znnkGqezmnwOGpPveRZ', 'oiH9fzKVpI8jLubuBqUK', 'bztRqJ7WTLOC32XmOwws', 'https://auth.reestoc.com/signup/bztRqJ7WTLOC32XmOwws', '2021-09-18 01:35:32', '2021-09-18 01:35:32', 1),
(3, 'wriuwNOeKukKIUN73IR0', 'bztRqJ7WTLOC32XmOwws', 'rzl5nk7rIHDpqMUbHuz9', 'https://auth.reestoc.com/signup/rzl5nk7rIHDpqMUbHuz9', '2021-09-18 01:39:48', '2021-09-18 01:39:48', 1),
(4, 'ltB6ZVD6Q7Roiar5dko7', 'Default', 'oiH9fzKVpI8jLubuBqUK', 'https://auth.reestoc.com/signup/oiH9fzKVpI8jLubuBqUK', '2021-09-18 03:08:44', '2021-09-18 03:08:44', 1),
(5, 's9FeLiS2GIJJptO1Bwy1', 'oiH9fzKVpI8jLubuBqUK', 'FIx1NsUOzWnIeLp970CQ', 'https://auth.reestoc.com/signup/FIx1NsUOzWnIeLp970CQ', '2021-10-06 16:53:55', '2021-10-06 16:53:55', 1),
(6, 'yWUPSwcjBffcUXgejJ8a', 'Default', 'ejm525FluTiIUQkSTNqK', 'https://auth.reestoc.com/signup/ejm525FluTiIUQkSTNqK', '2021-10-06 16:56:51', '2021-10-06 16:56:51', 1),
(7, 'i5FBcYpbgfZFEYIWeSgP', 'Default', 'UGjnvlj9mzxoc6qn5pwq', 'https://reestoc.com/sign-up/UGjnvlj9mzxoc6qn5pwq', '2021-10-25 22:39:46', '2021-10-25 22:39:46', 1),
(8, 'faplfIlcEq1OCVWuV9Q0', 'Default', 'RAMspiJHFk2hc2Dmyb7J', 'https://reestoc.com/sign-up/RAMspiJHFk2hc2Dmyb7J', '2021-11-18 12:18:14', '2021-11-18 12:18:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `message` varchar(500) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `message`, `added_date`, `last_modified`, `status`) VALUES
(1, '7ovjBtEfPpKosmcJdOvd', 'bztRqJ7WTLOC32XmOwws', 'mn7uFgo9HyoUi0G13mCs', 'I like the product main main', '2021-07-07 00:00:00', '2021-07-07 00:00:00', 1),
(2, 'vZWkzzDS186pzfbVZ2tr', 'bztRqJ7WTLOC32XmOwws', 'X6GDzLaHVAX4y6JNwjUh', 'I like the product', '2021-07-07 00:00:00', '2021-07-07 00:00:00', 1),
(3, '8MBHecney74vIHPNC1v9', 'BXveKWZrgUccZ3udo5ef', 'mn7uFgo9HyoUi0G13mCs', 'I don\'t like the product', '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(4, 'haIM5KUVluo9APCBM2lF', '5t8JzH7pMoiuaisW6dfl', 'mn7uFgo9HyoUi0G13mCs', 'I don\'t like the product', '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(5, 'HD1JJNedRCZhHvCYlRTm', '5t8JzH7pMoiuaisW6dfl', 'X6GDzLaHVAX4y6JNwjUh', 'I like the product', '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(6, '55So3LbC5HwwOqVnr7KF', 'rzl5nk7rIHDpqMUbHuz9', 'X6GDzLaHVAX4y6JNwjUh', 'Just got my order and it\'s perfect thanks guys', '2021-07-11 15:14:14', '2021-07-11 15:14:14', 1),
(7, 'k10tuh14RHyZtT6U1B3k', 'BXveKWZrgUccZ3udo5ef', 'ouDXX9JskJSXawHkToQT', 'Just got my order and it\'s perfect thanks guys', '2021-07-11 15:16:26', '2021-07-11 15:16:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review_history`
--

CREATE TABLE `review_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) DEFAULT NULL,
  `sub_product_unique_id` varchar(20) DEFAULT NULL,
  `rating` varchar(5) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review_history`
--

INSERT INTO `review_history` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `rating`, `added_date`, `last_modified`, `status`) VALUES
(5, '7ovjBtEfPpKosmcJdOvd', 'BXveKWZrgUccZ3udo5ef', 'mn7uFgo9HyoUi0G13mCs', 'No', '2021-07-11 14:23:24', '2021-07-11 14:23:24', 1),
(9, '55So3LbC5HwwOqVnr7KF', 'rzl5nk7rIHDpqMUbHuz9', 'X6GDzLaHVAX4y6JNwjUh', 'No', '2021-07-11 15:14:14', '2021-07-11 15:19:02', 1),
(10, 'k10tuh14RHyZtT6U1B3k', 'BXveKWZrgUccZ3udo5ef', 'ouDXX9JskJSXawHkToQT', 'Yes', '2021-07-11 15:16:26', '2021-07-11 15:16:26', 1),
(12, '55So3LbC5HwwOqVnr7KF', '5t8JzH7pMoiuaisW6dfl', 'X6GDzLaHVAX4y6JNwjUh', 'No', '2021-07-11 15:19:29', '2021-07-11 15:19:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `review_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review_images`
--

INSERT INTO `review_images` (`id`, `unique_id`, `user_unique_id`, `review_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, 'UQEeDT0vU4A1nFxtTIf5', 'BXveKWZrgUccZ3udo5ef', '55So3LbC5HwwOqVnr7KF', 'https://images.reestoc.com/review_images/1626276139.webp', '1626276139.webp', 340072, '2021-07-14 16:22:19', '2021-07-14 16:22:19', 1),
(2, 'dvkzwPrzWZRUjCDqDX7W', 'BXveKWZrgUccZ3udo5ef', '55So3LbC5HwwOqVnr7KF', 'https://images.reestoc.com/review_images/1626276140.webp', '1626276140.webp', 223180, '2021-07-14 16:22:19', '2021-07-14 16:22:19', 1),
(3, 'n6xRiC5jCZgdWxgjuOyO', 'BXveKWZrgUccZ3udo5ef', '55So3LbC5HwwOqVnr7KF', 'https://images.reestoc.com/review_images/1626276141.webp', '1626276141.webp', 25343, '2021-07-14 16:22:19', '2021-07-14 16:22:19', 1),
(4, 'u9kAPysKup40wI3IunCb', 'BXveKWZrgUccZ3udo5ef', '55So3LbC5HwwOqVnr7KF', 'https://images.reestoc.com/review_images/1626276209.webp', '1626276209.webp', 293076, '2021-07-14 16:23:29', '2021-07-14 16:23:29', 1),
(5, '0pA0KpEpRvX52HNAzi8I', 'BXveKWZrgUccZ3udo5ef', '55So3LbC5HwwOqVnr7KF', 'https://images.reestoc.com/review_images/1626276210.webp', '1626276210.webp', 665552, '2021-07-14 16:23:29', '2021-07-14 16:23:29', 1),
(7, 'EQXpt9s9uGvAC31gzYgD', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626277175.webp', '1626277175.webp', 130427, '2021-07-14 16:30:57', '2021-07-14 16:39:35', 1),
(8, 'RkbxxsONgPrUEKhuhn6W', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626276659.webp', '1626276659.webp', 19157, '2021-07-14 16:30:57', '2021-07-14 16:30:57', 1),
(9, 'XabrTOsmCnzvCjGkg9v7', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626276660.webp', '1626276660.webp', 17934, '2021-07-14 16:30:57', '2021-07-14 16:30:57', 1),
(10, 'F9CV8DtRrBRTH8dZl0c7', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626276661.webp', '1626276661.webp', 96574, '2021-07-14 16:30:57', '2021-07-14 16:30:57', 1),
(11, 'K4w8vVfQSnMMTi91xrmj', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626276662.webp', '1626276662.webp', 29790, '2021-07-14 16:30:57', '2021-07-14 16:30:57', 1),
(12, 'gn7IUHEhLvMd6jKQ01FX', 'BXveKWZrgUccZ3udo5ef', 'k10tuh14RHyZtT6U1B3k', 'https://images.reestoc.com/review_images/1626276663.webp', '1626276663.webp', 23422, '2021-07-14 16:30:57', '2021-07-14 16:30:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review_ratings`
--

CREATE TABLE `review_ratings` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `yes_rating` int(1) NOT NULL,
  `no_rating` int(1) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review_ratings`
--

INSERT INTO `review_ratings` (`id`, `unique_id`, `user_unique_id`, `sub_product_unique_id`, `yes_rating`, `no_rating`, `added_date`, `last_modified`, `status`) VALUES
(1, '7ovjBtEfPpKosmcJdOvd', 'bztRqJ7WTLOC32XmOwws', 'mn7uFgo9HyoUi0G13mCs', 23, 13, '2021-07-07 00:00:00', '2021-07-11 14:23:24', 1),
(2, 'vZWkzzDS186pzfbVZ2tr', 'bztRqJ7WTLOC32XmOwws', 'X6GDzLaHVAX4y6JNwjUh', 129, 82, '2021-07-07 00:00:00', '2021-07-11 14:28:39', 1),
(3, '8MBHecney74vIHPNC1v9', 'BXveKWZrgUccZ3udo5ef', 'mn7uFgo9HyoUi0G13mCs', 213, 34, '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(4, 'haIM5KUVluo9APCBM2lF', '5t8JzH7pMoiuaisW6dfl', 'mn7uFgo9HyoUi0G13mCs', 232, 323, '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(5, 'HD1JJNedRCZhHvCYlRTm', '5t8JzH7pMoiuaisW6dfl', 'X6GDzLaHVAX4y6JNwjUh', 213, 123, '2021-07-08 00:00:00', '2021-07-08 00:00:00', 1),
(6, '55So3LbC5HwwOqVnr7KF', 'BXveKWZrgUccZ3udo5ef', 'X6GDzLaHVAX4y6JNwjUh', 0, 2, '2021-07-11 15:14:14', '2021-07-11 15:19:29', 1),
(7, 'k10tuh14RHyZtT6U1B3k', 'BXveKWZrgUccZ3udo5ef', 'ouDXX9JskJSXawHkToQT', 1, 0, '2021-07-11 15:16:26', '2021-07-11 15:16:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`id`, `unique_id`, `edit_user_unique_id`, `fullname`, `email`, `phone_number`, `role`, `added_date`, `last_modified`, `access`, `status`) VALUES
(1, 'EAqXSSPExWfYQUaDvBKF', 'dAXn9RrXd61LYHNSgI2T', 'Ebuka Davis', 'ebukadavis@gmail.com', '07054752356', 13, '2021-07-18 17:04:08', '2021-07-18 17:04:08', 1, 1),
(2, '2IznNBIz5lnURQpdGG3w', 'dAXn9RrXd61LYHNSgI2T', 'Kelvin Dubur', 'kelvindubur@gmail.com', '08095454548', 12, '2021-07-18 17:30:03', '2021-09-11 18:31:42', 1, 1),
(3, 'vaEd4ZvDguobtFSv6Woy', 'dAXn9RrXd61LYHNSgI2T', 'Kingsley Henry', 'kingsleyhenry@gmail.com', '08183848283', 12, '2021-09-11 09:35:10', '2021-09-11 23:43:29', 1, 1),
(4, 'iFPbKDm3YqoruhLWJWAv', 'dAXn9RrXd61LYHNSgI2T', 'Pepple Idu', 'peppleidu@gmail.com', '08183293823', 12, '2021-09-11 09:35:55', '2021-09-11 19:32:26', 1, 1),
(5, 'A21uaBJKTMFuPdHPgryV', 'dAXn9RrXd61LYHNSgI2T', 'Ayodele Aderibigbe', 'ayodeleaderibigbe2000@gmail.com', NULL, 12, '2021-09-11 23:04:47', '2021-09-11 23:43:46', 1, 1),
(6, 'gWzAWjgmcuFMLnqMn1GL', 'dAXn9RrXd61LYHNSgI2T', 'Emmanuel Nwoye', 'emmanuelnwoye5@gmail.com', '+2348093223317', 12, '2021-09-18 01:46:08', '2021-09-18 01:46:08', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `riders_addresses`
--

CREATE TABLE `riders_addresses` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `additional_information` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `riders_addresses`
--

INSERT INTO `riders_addresses` (`id`, `unique_id`, `user_unique_id`, `firstname`, `lastname`, `address`, `additional_information`, `city`, `state`, `country`, `added_date`, `last_modified`, `status`) VALUES
(1, 'v3SjUDX7yMlegcFbMiYF', 'EAqXSSPExWfYQUaDvBKF', 'Ebuka', 'Davis', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:20:40', '2021-07-24 11:20:40', 1),
(2, 'DJjw3ecnEPfBwEwVoKpl', 'EAqXSSPExWfYQUaDvBKF', 'Ebuka', 'Davis', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:20:44', '2021-07-24 11:20:44', 1),
(3, 'c8Rja9R3TQKwJLxbVZPN', 'EAqXSSPExWfYQUaDvBKF', 'Ebuka', 'Davis', 'No 4 Boulevard street, New York', 'Apartment 232', 'Brooklyn', 'New york', 'United States', '2021-07-24 11:20:46', '2021-07-24 11:20:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `riders_kyc`
--

CREATE TABLE `riders_kyc` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `front_image` varchar(50) NOT NULL,
  `back_image` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `approval` varchar(20) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) DEFAULT NULL,
  `search` varchar(300) NOT NULL,
  `type` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `search_history`
--

INSERT INTO `search_history` (`id`, `unique_id`, `user_unique_id`, `search`, `type`, `added_date`, `last_modified`, `status`) VALUES
(1, 'hM3LkAlwXzLEAHPfsSVz', 'bztRqJ7WTLOC32XmOwws', 'ciroc opener ', '', '2021-07-10 11:37:39', '2021-07-10 11:37:39', 1),
(2, 'TFTqUzQXwiCEZcDrpvVo', 'Anonymous', 'moet', '', '2021-07-10 11:38:41', '2021-07-10 11:38:41', 1),
(3, 'ac1hogFpdNlquk2mN9AW', 'Anonymous', 'Teremana', '', '2021-07-10 13:02:36', '2021-07-10 13:02:36', 1),
(4, 'bgxmLhYSZKLqkvSrBXtY', 'rzl5nk7rIHDpqMUbHuz9', 'red wine', '', '2021-07-10 13:02:53', '2021-07-10 13:02:53', 1),
(5, '2UFpCKgjtr7HlZmGU9C3', 'rzl5nk7rIHDpqMUbHuz9', 'single', '', '2021-07-10 13:03:06', '2021-07-10 13:03:06', 1),
(6, 'LlaKxMriJUX8SvHaatsr', 'bztRqJ7WTLOC32XmOwws', '50cl', '', '2021-07-10 13:03:23', '2021-07-10 13:03:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sharing_history`
--

CREATE TABLE `sharing_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sharing_unique_id` varchar(20) NOT NULL,
  `action` varchar(300) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sharing_history`
--

INSERT INTO `sharing_history` (`id`, `unique_id`, `user_unique_id`, `sharing_unique_id`, `action`, `added_date`, `last_modified`, `status`) VALUES
(1, 'FFv7USh4nhKeUtP2X9hA', 'oiH9fzKVpI8jLubuBqUK', 'VdyB8MWSZAwuvTDc7nkf', 'Subscribed to sharing - Cow medium size. Cost = 30000 naira, Shipping fee = 1200 naira, Shipping location - PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 31200.', '2021-10-05 20:05:06', '2021-10-05 20:05:06', 1),
(2, 'd1UYSc3AAjAQDZUsOWRt', 'rzl5nk7rIHDpqMUbHuz9', 'VdyB8MWSZAwuvTDc7nkf', 'Subscribed to sharing - Cow medium size. Cost = 30000 naira, Shipping fee = 0 naira, Shipping location - PORTHARCOURT-MILE 1, Rivers, Nigeria. Total amount = 30000.', '2021-10-05 20:05:16', '2021-10-05 20:05:16', 1),
(3, 'tOB6BUpHwCjfig8e7iXj', 'oiH9fzKVpI8jLubuBqUK', 'Pld2WCjJoNWfE6jtKwFj', 'Subscribed to sharing - Ram small size. Cost = 12500 naira, Shipping fee = 900 naira, Shipping location - PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 13400.', '2021-10-06 09:56:29', '2021-10-06 09:56:29', 1),
(4, 'bNvplB6dIQLWCubHNmfe', 'rzl5nk7rIHDpqMUbHuz9', 'Pld2WCjJoNWfE6jtKwFj', 'Subscribed to sharing - Ram small size. Cost = 12500 naira, Shipping fee = 900 naira, Shipping location - PORTHARCOURT-D/LINE, Rivers, Nigeria. Total amount = 13400.', '2021-10-06 09:56:46', '2021-10-06 09:56:46', 1),
(5, '520FvNlpV3Nb8OXE9USz', 'oiH9fzKVpI8jLubuBqUK', '7DveqPCRwLpaxpeucxfm', 'Subscribed to sharing - Ram big size. Cost = 22500 naira, Shipping fee = 1700 naira, Shipping location - PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 24200.', '2021-10-06 10:11:06', '2021-10-06 10:11:06', 1),
(6, 'MpoZ6DFVg9CkNDobwVdO', 'rzl5nk7rIHDpqMUbHuz9', '7DveqPCRwLpaxpeucxfm', 'Subscribed to sharing - Ram big size. Cost = 22500 naira, Shipping fee = 1700 naira, Shipping location - PORTHARCOURT-D/LINE, Rivers, Nigeria. Total amount = 24200.', '2021-10-06 10:16:59', '2021-10-06 10:16:59', 1),
(7, '14lLxI2YqZc9oE8zXM11', 'oiH9fzKVpI8jLubuBqUK', 'VdyB8MWSZAwuvTDc7nkf', 'Successfully paid for - Cow medium size sharing item. Amount = 31200 naira.', '2021-10-06 15:00:59', '2021-10-06 15:00:59', 1),
(8, 'Gc5xnHUjqvWxiCy3Ewtw', 'rzl5nk7rIHDpqMUbHuz9', 'VdyB8MWSZAwuvTDc7nkf', 'Successfully paid for - Cow medium size sharing item. Amount = 30000 naira.', '2021-10-06 15:40:02', '2021-10-06 15:40:02', 1),
(9, 'IcivBuAMWOUL96GXq89C', 'oiH9fzKVpI8jLubuBqUK', 'jRua3gvrhKfVsa0MY5Rj', 'Subscribed to sharing - Cow medium size. Cost = 200000 naira, Shipping fee = 4500 naira, Shipping location - PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 204500.', '2021-10-06 15:47:41', '2021-10-06 15:47:41', 1),
(10, '1FFnTe7ofxPbFLStiSqQ', 'rzl5nk7rIHDpqMUbHuz9', 'jRua3gvrhKfVsa0MY5Rj', 'Subscribed to sharing - Cow medium size. Cost = 200000 naira, Shipping fee = 3500 naira, Shipping location - PORTHARCOURT-D/LINE, Rivers, Nigeria. Total amount = 203500.', '2021-10-06 15:47:52', '2021-10-06 15:47:52', 1),
(11, 'hKasWN3qXM9MNDxtjRB8', 'oiH9fzKVpI8jLubuBqUK', '7DveqPCRwLpaxpeucxfm', 'Successfully paid for - Ram big size sharing item. Amount = 24200 naira.', '2021-10-06 15:48:29', '2021-10-06 15:48:29', 1),
(12, '2kRW7ySftJ4YeZt6bvsE', 'rzl5nk7rIHDpqMUbHuz9', '7DveqPCRwLpaxpeucxfm', 'Successfully paid for - Ram big size sharing item. Amount = 24200 naira.', '2021-10-06 15:48:40', '2021-10-06 15:48:40', 1),
(13, 'X2MLrySDK7GW2NRgsHWG', 'oiH9fzKVpI8jLubuBqUK', 'Pld2WCjJoNWfE6jtKwFj', 'Successfully paid for - Ram small size sharing item. Amount = 13400 naira.', '2021-10-06 15:48:51', '2021-10-06 15:48:51', 1),
(14, 'ySjUCnEzDFv8ZSJCvmZc', 'rzl5nk7rIHDpqMUbHuz9', 'Pld2WCjJoNWfE6jtKwFj', 'Successfully paid for - Ram small size sharing item. Amount = 13400 naira.', '2021-10-06 15:48:57', '2021-10-06 15:48:57', 1),
(15, 'wBjlwDBV6tiVJfp4zm6z', 'oiH9fzKVpI8jLubuBqUK', 'jRua3gvrhKfVsa0MY5Rj', 'Successfully paid for - Cow medium size sharing item. Amount = 204500 naira.', '2021-10-06 15:51:12', '2021-10-06 15:51:12', 1),
(17, 'bOiR6zN4kkqW9QpiNhKQ', 'rzl5nk7rIHDpqMUbHuz9', 'jRua3gvrhKfVsa0MY5Rj', 'Successfully paid for - Cow medium size sharing item. Amount = 203500 naira.', '2021-10-06 15:53:05', '2021-10-06 15:53:05', 1),
(18, 'qZqB5dXnBJWjlKcWvfyc', 'oiH9fzKVpI8jLubuBqUK', 'xVhH0hrgLrkEhUn53BY1', 'Subscribed to sharing - School provisions small size. Cost = 20000 naira, Shipping fee = 0 naira, Pickup location - Reestoc Mile 1, PORTHARCOURT-MILE 1, Rivers, Nigeria. Total amount = 20000.', '2021-10-10 23:52:58', '2021-10-10 23:52:58', 1),
(19, 'gKKsU7xlrP7KXaWquJQr', 'oiH9fzKVpI8jLubuBqUK', 'xVhH0hrgLrkEhUn53BY1', 'Successfully paid for - School provisions small size sharing item. Amount = 20000 naira.', '2021-10-11 00:18:52', '2021-10-11 00:18:52', 1),
(22, 'NpruJZDCoXNXzwpO1qCk', 'oiH9fzKVpI8jLubuBqUK', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 500 naira, Pickup location - Reestoc Diobu 2, PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 18500.', '2021-10-24 01:32:13', '2021-10-24 01:32:13', 1),
(23, '6kqV0abSvSMrbteCFfrD', 'bztRqJ7WTLOC32XmOwws', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 700 naira, Pickup location - Reestoc Agip, PORTHARCOURT-AGIP, Rivers, Nigeria. Total amount = 18700.', '2021-10-24 01:34:07', '2021-10-24 01:34:07', 1),
(24, 'Y945OCOW34ACBgdLjili', 'tPBIE40TyKjOu0A35Joe', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 700 naira, Pickup location - Reestoc Agip, PORTHARCOURT-AGIP, Rivers, Nigeria. Total amount = 18700.', '2021-10-24 01:34:23', '2021-10-24 01:34:23', 1),
(25, '9x7nvK0cYcuPbZZbZTYp', 'rzl5nk7rIHDpqMUbHuz9', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 500 naira, Pickup location - Reestoc Diobu 2, PORTHARCOURT-DIOBU, Rivers, Nigeria. Total amount = 18500.', '2021-10-24 01:34:38', '2021-10-24 01:34:38', 1),
(26, 'pGGFhQSnqIEIFBMhaUAD', 'FIx1NsUOzWnIeLp970CQ', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 500 naira, Pickup location - Reestoc Mile 1, PORTHARCOURT-MILE 1, Rivers, Nigeria. Total amount = 18500.', '2021-10-24 01:35:05', '2021-10-24 01:35:05', 1),
(27, 'qTZTD91sDPJqTaNbJLla', 'ejm525FluTiIUQkSTNqK', '2HEugCfvFeI7TelOGJGD', 'Subscribed to sharing - Full Goat. Cost = 18000 naira, Shipping fee = 500 naira, Pickup location - Reestoc Mile 1, PORTHARCOURT-MILE 1, Rivers, Nigeria. Total amount = 18500.', '2021-10-24 01:35:41', '2021-10-24 01:35:41', 1),
(28, 'glp1lLSNfoVQKnJlAMlA', 'ejm525FluTiIUQkSTNqK', '2HEugCfvFeI7TelOGJGD', 'Successfully paid for - Full Goat sharing item. Amount = 18500 naira.', '2021-10-24 01:37:51', '2021-10-24 01:37:51', 1),
(29, 'veHQxAHX2j81bRyIQ0O8', 'rzl5nk7rIHDpqMUbHuz9', '2HEugCfvFeI7TelOGJGD', 'Successfully paid for - Full Goat sharing item. Amount = 18500 naira.', '2021-10-24 01:38:02', '2021-10-24 01:38:02', 1),
(30, '89Ghze1LLErHepqwGj9P', 'bztRqJ7WTLOC32XmOwws', '2HEugCfvFeI7TelOGJGD', 'Successfully paid for - Full Goat sharing item. Amount = 18700 naira.', '2021-10-24 01:38:22', '2021-10-24 01:38:22', 1),
(31, 'hxoC3OsVnNdjvBfIfvZ0', 'tPBIE40TyKjOu0A35Joe', '2HEugCfvFeI7TelOGJGD', 'Successfully paid for - Full Goat sharing item. Amount = 18700 naira.', '2021-10-24 01:38:28', '2021-10-24 01:38:28', 1),
(32, 'YJq8TLLHyrlIMqKHR4fA', 'oiH9fzKVpI8jLubuBqUK', '2HEugCfvFeI7TelOGJGD', 'Successfully paid for - Full Goat sharing item. Amount = 18500 naira.', '2021-10-24 10:36:24', '2021-10-24 10:36:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sharing_images`
--

CREATE TABLE `sharing_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sharing_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sharing_images`
--

INSERT INTO `sharing_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sharing_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, '9vFm0EQBR3Bs5jrFC3Rl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'https://www.reestoc.com/images/sharing_images/1633119244.png', '1633119244.png', 459578, '2021-10-01 21:14:04', '2021-10-01 21:14:04', 1),
(2, 'uno9EAu7WfM76VpPQzLZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'https://www.reestoc.com/images/sharing_images/1633119426.png', '1633119426.png', 435148, '2021-10-01 21:14:04', '2021-10-01 21:17:06', 1),
(4, 'ne4RPVAaSqMkdmYEeqUe', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'https://www.reestoc.com/images/sharing_images/1633119247.webp', '1633119247.webp', 274048, '2021-10-01 21:14:04', '2021-10-01 21:14:04', 1),
(5, 'ruXyDBPpmracBQ6CHJv0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'https://www.reestoc.com/images/sharing_images/1633119608.webp', '1633119608.webp', 274048, '2021-10-01 21:20:08', '2021-10-01 21:20:08', 1),
(6, 'r7rSS6XXm0zapxToRSMH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'https://www.reestoc.com/images/sharing_images/1633119610.png', '1633119610.png', 459578, '2021-10-01 21:20:08', '2021-10-01 21:20:08', 1),
(7, 'kVxg325fiaAZrKc17ZHA', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'https://www.reestoc.com/images/sharing_images/1633119611.webp', '1633119611.webp', 267396, '2021-10-01 21:20:08', '2021-10-01 21:20:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sharing_items`
--

CREATE TABLE `sharing_items` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `stripped` varchar(250) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `total_price` double NOT NULL,
  `split_price` double NOT NULL,
  `total_no_of_persons` int(11) NOT NULL,
  `current_no_of_persons` int(11) NOT NULL,
  `expiration` int(2) NOT NULL,
  `starting_date` datetime NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `completion` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sharing_items`
--

INSERT INTO `sharing_items` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `name`, `stripped`, `description`, `total_price`, `split_price`, `total_no_of_persons`, `current_no_of_persons`, `expiration`, `starting_date`, `expiry_date`, `completion`, `added_date`, `last_modified`, `status`) VALUES
(1, 'VdyB8MWSZAwuvTDc7nkf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Cow medium size 1', 'cow-medium-size-1', '<p>This one we will do this and that<br></p>', 450000, 30000, 15, 2, 0, '2021-10-06 10:00:00', NULL, 'Started', '2021-10-01 17:19:16', '2021-10-06 15:40:02', 1),
(2, 'jRua3gvrhKfVsa0MY5Rj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Cow medium size', 'cow-medium-size', '<p>This one we will do this and that</p>', 400000, 200000, 2, 2, 1, '2021-10-08 08:00:00', '2021-10-16 18:00:00', 'Completed', '2021-10-01 17:29:58', '2021-10-06 15:53:05', 1),
(3, 'Pld2WCjJoNWfE6jtKwFj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ram small size', 'ram-small-size', '<p>This is the ram small size</p>', 125000, 12500, 10, 2, 1, '2021-10-07 09:30:00', '2021-10-15 16:30:00', 'Started', '2021-10-05 20:12:26', '2021-10-06 15:48:57', 1),
(4, '7DveqPCRwLpaxpeucxfm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ram big size', 'ram-big-size', '<p>This is ram big size&nbsp;</p>', 180000, 22500, 8, 2, 0, '2021-10-08 06:00:00', NULL, 'Started', '2021-10-05 03:56:32', '2021-10-06 15:48:40', 1),
(5, 'xVhH0hrgLrkEhUn53BY1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'School provisions small size', 'school-provisions-small-size', '<p>This consists of Milo, peak milk, cornflakes, golden morn and so much more</p>', 200000, 20000, 10, 1, 1, '2021-10-16 08:00:00', '2021-10-31 10:00:00', 'Started', '2021-10-10 12:59:54', '2021-10-11 00:18:52', 1),
(6, '2HEugCfvFeI7TelOGJGD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Full Goat', 'full-goat', '<p>the full goat comprises of what not</p>', 90000, 18000, 5, 5, 1, '2021-10-24 01:15:00', '2021-10-30 10:00:00', 'Completed', '2021-10-24 01:04:00', '2021-10-24 10:36:24', 1),
(7, 'rTubDticTvUZwxo1C74S', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Full Goat - 2', 'full-goat-2', '<p>this is this and that&nbsp;</p>', 80000, 16000, 5, 0, 0, '2021-10-24 13:02:00', NULL, 'Pending', '2021-10-24 13:02:49', '2021-10-24 13:02:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sharing_shipping_fees`
--

CREATE TABLE `sharing_shipping_fees` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sharing_unique_id` varchar(20) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sharing_shipping_fees`
--

INSERT INTO `sharing_shipping_fees` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sharing_unique_id`, `city`, `state`, `country`, `price`, `added_date`, `last_modified`, `status`) VALUES
(1, '17KrGiQRfqMuGa0EywWb', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AGIP', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(2, 'ngPx5AdG5QZjQAKe9yKI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ADA GEORGE', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(3, 'K2Lde6OUCDdjFc5GrFSj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 1200, '2021-10-05 05:28:37', '2021-10-05 05:58:28', 1),
(4, '1b5GSlxmSRmEU5D72lY7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-D/LINE', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(5, 'otRJlX33faW1i8vXRvVY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MILE 1', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(6, 'pwtDboakpBnU3CgKmsyq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MILE 2', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(7, 'jxqcnD3lUDpmcssRWXBi', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MILE 3', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(8, 'xGCKilYW5nat59vYJqoh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MGBUOSIMINI', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(9, 'ss5f9Y0ofUcSs4EmwHFk', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ORAZI', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(10, 'j13MOx2MzOnp9VU6nE8T', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUKPOKWU', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(11, 'fGO4MKYKFHdzioeQnQVu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUIGBO', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(12, 'zZPRdETsOO1j9izfDAA2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUODUMAYA', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(13, 'm5msckPfRPDcHK8ofE1p', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOKWUTA', 'Rivers', 'Nigeria', 1500, '2021-10-05 05:28:37', '2021-10-05 05:28:37', 1),
(14, 'TT7P2XOC9CtRehS5cS6S', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ABHONEMA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(15, 'UNXpSe6Fw2Lb6Rp79dVU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'AHOADA EAST', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(16, 'PchqLc0UZt2Lagu09ecV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'AHOADA WEST', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(17, 'bQIlFhrQGUXa8tOZD12t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'AIRPORT ROAD - PORT HARCOURT', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(18, 'xWQreXiqhChCfMev8VAJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'BONNY ISLAND', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(19, 'g0z3iEQGv2mNGBtcZxv5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELELE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(20, 'xZsbrkPrYzZnUMCraXud', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-AGBONCHIA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(21, 'RjE6F4P2FfOgrFmClSE9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-AKPAJO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(22, 'Cspz2OU4lzi2AzDCONy6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ALESA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(23, '2armQHLUjyddECwBSkjH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ALETO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(24, 'bN9rlwedRdQjRypPCoTW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ALODE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(25, '4qGkYzORqYNg62694WPa', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-EBUBU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(26, 'dvaD2XI4sXuXJC3f83Xh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-EKPORO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(27, 'galSgdqylnq3V0hwEReh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ETEO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(28, 'N1vZawMewwFoIVGN4fgT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-NCHIA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(29, 'oKh82xiQ8ZODPLBt2UtA', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ODIDO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(30, '8bbRvYRCeCCSWifSv1Et', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-OGALE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(31, 'oP6GxnQIUbjFejrWML5v', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ELEME-ONNE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(32, 'D9hYAPhx2Obzh4e2xqYZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'EMOUHA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(33, 'yEKr1kYhL04SVCVLWlU7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ETCHE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(34, 'SB8wXAXdFv70skrQIIwz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'ISIOKPO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(35, 'piNjYt7Ha7g16b79ujx7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OKRIKA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(36, 'MHWRy2oc7zauPfZeXZOA', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OKRIKA-OKRIKA TOWN(KIRIKE)', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(37, 'kPJ15FUNvp3w26oXtrMD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OMOKU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(38, 'EywtaJnvw5BxBMaJZqto', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-AFAM', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(39, 'Y3LKQPCcQHh23HsBpxIW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-EGBERU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(40, 'qrnEXkEXTM1ITcoJlPvm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-IZUOMA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(41, '8xa7F2ayMnegtt0uNfWz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-KOMKOM', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(42, 'M84x9rsFP4OddHpxiUE5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-MIRINWANYI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(43, '9VizkTxefGLMMJ3AgEs9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-NDOKI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(44, 'e5rK4sFaapx8nk4IFcsn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-OBEAMA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(45, 'kg2fwCrRMopYzmD572YB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'OYIGBO-UMUAGBAI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(46, 'DbhRrHNmXmObOmbA1yQZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PHC-NEW GRA -PHASE 1 2 3 4', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(47, 'euQLtfyIlkkCWsWbTpic', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PHC-RIVER STATE UNIVERSITY', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(48, 'M8aqhTXtuSyYC0vmnEtt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PHC-WOJI Central Location', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(49, 't86mSYYrdMWJ8za74aT4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ABONNEMA TOWN', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(50, 'MglZRpnWJiGFCE8oLVph', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ABONNEMA WHARF', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(51, 'HXuz1QUgv1rerKbnD0Su', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ABULOMA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(52, 'jvprDniV27pc6GmDG3jD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AIRFORCE BASE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(53, 's0QTGY9iayn57Ap174PB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AKPAJO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(54, 'JdcG2TIQs1SWL6leTsl2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ALAKAHIA-UPTH', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(55, 'tIha1V8YGM6tacAmeNrm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ALUU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(56, 'MgP0vWeiKsfugLgUIyEK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AMADI AMA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(57, 'jVOhfFBfAx4HqsKKrEY4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AYA-OGOLOGO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(58, 'H1Y6qGuAf2Q3lS5D8UQl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-AZUBIE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(59, 'LjYHyNTK2GpYecyvZkjK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-BORI CAMP', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(60, '22Ix0U3NG6kpEdtyFkWS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-BOROKIRI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(61, 'YmYpv5NAqUOQxRflLRNN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(62, 'efZbe02YTLVx7AV48RPn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-CHOBA-UNIPORT', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(63, 'eAgbJmWlJQ7hsyZ9SDme', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-CHURCHHILL', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(64, 'PeZRq2TvONVOZHpb8zMG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-DARICK POLO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(65, 'DF0jLI8JvPwU6vZeWbkX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(66, 'YZYvqGJoqOXFl6tyto51', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-EGBELU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(67, 'nlUGVS8sw1v7ZBGPWigt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELEKAHIA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(68, 'u325K8GVtawsRxaxTnMR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELELEWON', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(69, 'BhrpQD6rRkpLX8OlD4fW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELIBOLO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(70, 'J30EaA3nlzDUtiRJY3zH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELIGBAM', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(71, 'aKIkdu9DQah0fMGDrc6k', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELIMGBU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(72, 'QUFmIYRd6EFfzr1MnZbW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELINPARAWON', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(73, 'iZCQ36ekpYgGsQZELVVQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELIOHANI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(74, 'DDzXaOg8OWOKh3z7YX38', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ELIOZU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(75, 'bxAMYdrQqYfqrK2mTUWY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-ENEKA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(76, 'LH5uJwn80RaeRhXszDUh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-IGWURUTA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(77, 'd7LOUw0dEkoGmej9uM5M', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-INTELS KM 16', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(78, 'pAqnVMNhEvgkqqkdUmfy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MGBUOBA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(79, 'm7WBb889tPlmtpEYlXNE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MILE 4', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(80, 'lgXATjzhlUvLr6EVdEyq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-MILE 5', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(81, 'PMB16Rcw4WO8Bo2rSUTE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-NEW GRA-TOMBIA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(82, 'CoZRtbjbsImfhOBGy379', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-NKPOGU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(83, 'VaKKpZsrUTUh1dg3xPlo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-NKPOLU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(84, 'iU1gnGD8IEZuIsQrTECe', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OGBATAI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(85, 'ebyHyqd8x5B5moWhZUU1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OGBOGORO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(86, 'nlUjaPFGDNtajDoXIiO8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OGBUNABALI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(87, 'QJyb1CPfRMvTanmf42Ul', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OGINIGBA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(88, 'JBy4ashFgEVeXzV8qVEL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OKPORO ROAD', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(89, 'QJp0YduHWEInH0CU1xlt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OKURU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(90, 'PLC3CKXkuchR94Tuqd72', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OLD GRA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(91, 'X2pIqi3fUCzCYQnqOp4t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-OZUBOKO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(92, '7JQ73UmDtYCfjDVFUHrD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-PETER ODILLI ROAD', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(93, 'csTsav7SCWoBxgE2n15d', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RECLAMATION', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(94, 'QQAxnRWHV3QUiccamYmh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMEME', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(95, 'dMCfA8Mzaz6ep4jnigjs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUAGHAOLU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(96, 'kLaie5U0Pz4D58wIoCRS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUAKPAKOLOSI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(97, 'wMV2pK29HJv92Bx2CZFu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUEPIRIKOM', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(98, 'xCK2aEouCAjGHoFKiK5U', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUEVOLU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(99, '7qKr8jK8bx2cIaI6MGnc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUIBEKWE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(100, '5PFoou05GOigr5INhnN4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUKALAGBOR', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(101, 'GrvgEtyUdtDeluKUQLd5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUKRUSHI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(102, 'PlB1sjkwuoiTyUpI9Iks', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUMASI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(103, '0Y8EKhHYZN62xotwK1a1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOGBA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(104, 'vLX4bIP5GkNbSe5UTNrq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOKE', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(105, 'aCKECQe22rMMVTEGqzN3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOKORO', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(106, 'GbkZhyGszWajhz1CAAyL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOLA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(107, 'v6MhjorgXjcvdyL4rDgs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOLUMENI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(108, 'klglJySWcCAJffJgY7p6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOROSI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(109, '0X09g4PsxFBe3prkhOlv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-RUMUOWAH', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(110, 'JKppUiRtDFkfTiR09v3p', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-STADIUM ROAD', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(111, 'P6RLKJfiYlL9D7wS7Zci', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-TOWN', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(112, 'KztHMeaPpxyKTBjNhnK6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-TRANS AMADI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(113, 'aAfAvOjRiZ7RrLqrW3Dv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-WOJI ILLOM', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(114, 'NPXePPOPtBOCIgBNZRqQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-WOJI ROAD', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(115, 'fR52rCDw23U1EYYnwNCF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-WOJI YKC', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(116, 'b4lrzK1UjzTNnDCSrVfj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'PORTHARCOURT-WOJI-ELIJIJI', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(117, 'vzTolSm4mITES2tGkBYw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'RUMUAGHOLU', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(118, 'ExvkE19KF5hHip22wVN9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'RUMUODUOMAYA', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(119, '9abiMIIea0gxNgPlSBNd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Obio-Akpor', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(120, 'xnTLxFjbrTpHi54UvR2j', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Oguâ€“Bolo', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:56:59', 0),
(121, 'RalM62uMX86Gi8yP5TzD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Tai', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(122, 'xUzOowlEBMN4F3vIR5LU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Gokana', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(123, 'xHJd0csCfebFp9zgnydM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Khana', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(124, 'Rxh5BI3TRFSP4Uzi4IUL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Opoboâ€“Nkoro', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(125, 'AD2FolMxWYVwoigPrrwF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Andoni', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(126, 'wBREDAPh6XcjjXQoL5xE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Bonny', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(127, 'MtkrqmfVSMcSvbveizTg', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Degema', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(128, 'PBhsMLe6OpNkNh9wV0gA', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Asari-Toru', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(129, 'Y2m6CmeQGHHUGEI0lGu0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Akuku-Toru', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(130, 'TVPkVE06l4Km0Q4Ct9mH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Abuaâ€“Odual', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(131, 'iXtWNgwfwHYhXNDDU4A9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Ogbaâ€“Egbemaâ€“Ndoni', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(132, 'FsU16b1Y8rV3CLZe8kTm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Ikwerre', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(133, 'qg6tKbbiU9X3DjGKwsQG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Omuma', 'Rivers', 'Nigeria', 3000, '2021-10-05 05:30:06', '2021-10-05 05:30:06', 1),
(134, 'c7Dc4CIraSng5LnUuyaH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Uyo', 'Akwa Ibom', 'Nigeria', 3500, '2021-10-05 05:51:55', '2021-10-05 15:41:52', 1),
(135, 'Vg7amTj6MEV6AlkOP8Ne', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Ikot Ekpene', 'Akwa Ibom', 'Nigeria', 4000, '2021-10-05 05:51:55', '2021-10-05 05:51:55', 1),
(136, 's9AEfaDuTVkBwXbVHhmz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Oron', 'Akwa Ibom', 'Nigeria', 5000, '2021-10-05 05:51:55', '2021-10-05 05:56:28', 1),
(137, 'gvRwztuCvb4Ma64hUZYp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'VdyB8MWSZAwuvTDc7nkf', 'Abak', 'Akwa Ibom', 'Nigeria', 4000, '2021-10-05 05:51:55', '2021-10-05 05:51:55', 1),
(138, 'd8YFQAARtjrj95gH5dKB', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Pld2WCjJoNWfE6jtKwFj', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 900, '2021-10-06 09:56:01', '2021-10-06 09:56:01', 1),
(139, 'PhFaT7tY6uJH9SVxta3V', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Pld2WCjJoNWfE6jtKwFj', 'PORTHARCOURT-D/LINE', 'Rivers', 'Nigeria', 900, '2021-10-06 09:56:01', '2021-10-06 09:56:01', 1),
(140, '8rry5CRiCNlesONTKL3U', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Pld2WCjJoNWfE6jtKwFj', 'PORTHARCOURT-AGIP', 'Rivers', 'Nigeria', 900, '2021-10-06 09:56:01', '2021-10-06 09:56:01', 1),
(141, 'aJRmkFuOhK5p54w4uHxC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '7DveqPCRwLpaxpeucxfm', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 1700, '2021-10-06 10:10:38', '2021-10-06 10:10:38', 1),
(142, 'Z6tbw3U6ttqT6qUy3xoD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '7DveqPCRwLpaxpeucxfm', 'PORTHARCOURT-D/LINE', 'Rivers', 'Nigeria', 1700, '2021-10-06 10:10:38', '2021-10-06 10:10:38', 1),
(143, 'GbWNmmJdjAyZc2cVgDy1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '7DveqPCRwLpaxpeucxfm', 'PORTHARCOURT-MILE 1', 'Rivers', 'Nigeria', 1700, '2021-10-06 10:10:38', '2021-10-06 10:10:38', 1),
(144, 'YJZ6gtvFuNuIAMkJPb2r', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '7DveqPCRwLpaxpeucxfm', 'PHC-RIVER STATE UNIVERSITY', 'Rivers', 'Nigeria', 1700, '2021-10-06 10:10:38', '2021-10-06 10:10:38', 1),
(145, '67hULpzCVnZFdJXcSbnt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 4500, '2021-10-06 15:43:43', '2021-10-06 15:44:16', 1),
(146, 'mLwUcF1LQBq6bsppj2E8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'PORTHARCOURT-MILE 1', 'Rivers', 'Nigeria', 3800, '2021-10-06 15:43:43', '2021-10-06 15:44:39', 1),
(147, '79x0YhY3z5sM6KDUDgiv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'jRua3gvrhKfVsa0MY5Rj', 'PORTHARCOURT-D/LINE', 'Rivers', 'Nigeria', 3500, '2021-10-06 15:45:41', '2021-10-06 15:45:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sharing_users`
--

CREATE TABLE `sharing_users` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `sharing_unique_id` varchar(20) NOT NULL,
  `shipping_fee_unique_id` varchar(20) NOT NULL,
  `pickup_location` tinyint(1) NOT NULL,
  `amount` double NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `paid` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sharing_users`
--

INSERT INTO `sharing_users` (`id`, `unique_id`, `user_unique_id`, `sharing_unique_id`, `shipping_fee_unique_id`, `pickup_location`, `amount`, `payment_method`, `paid`, `added_date`, `last_modified`, `status`) VALUES
(1, 'fadeuBTxEvxeahX7zrzp', 'oiH9fzKVpI8jLubuBqUK', 'VdyB8MWSZAwuvTDc7nkf', 'K2Lde6OUCDdjFc5GrFSj', 0, 31200, 'Cash', 1, '2021-10-05 20:05:06', '2021-10-06 15:00:59', 1),
(2, 'fDOscJ2UPewGu6JIpKVK', 'rzl5nk7rIHDpqMUbHuz9', 'VdyB8MWSZAwuvTDc7nkf', 'c8Rja9R3TQKwJLxbVZPN', 1, 30000, 'Cash', 1, '2021-10-05 20:05:16', '2021-10-06 15:40:02', 1),
(3, 'xisnUYKRmIAiVHi1OwQ5', 'oiH9fzKVpI8jLubuBqUK', 'Pld2WCjJoNWfE6jtKwFj', 'd8YFQAARtjrj95gH5dKB', 0, 13400, 'Card', 1, '2021-10-06 09:56:29', '2021-10-06 15:48:51', 1),
(4, 'fmOi2fTLYJJvumkP4li2', 'rzl5nk7rIHDpqMUbHuz9', 'Pld2WCjJoNWfE6jtKwFj', 'PhFaT7tY6uJH9SVxta3V', 0, 13400, 'Card', 1, '2021-10-06 09:56:46', '2021-10-06 15:48:57', 1),
(5, 'WRpob3FJhTa7ditxU6WD', 'oiH9fzKVpI8jLubuBqUK', '7DveqPCRwLpaxpeucxfm', 'aJRmkFuOhK5p54w4uHxC', 0, 24200, 'Transfer', 1, '2021-10-06 10:11:06', '2021-10-06 15:48:29', 1),
(6, 'Ohe9p97IzczE6wgk87vE', 'rzl5nk7rIHDpqMUbHuz9', '7DveqPCRwLpaxpeucxfm', 'Z6tbw3U6ttqT6qUy3xoD', 0, 24200, 'Transfer', 1, '2021-10-06 10:16:59', '2021-10-06 15:48:40', 1),
(7, '5uVjvKFOumCcKR9tCKYs', 'oiH9fzKVpI8jLubuBqUK', 'jRua3gvrhKfVsa0MY5Rj', '67hULpzCVnZFdJXcSbnt', 0, 204500, 'Transfer', 1, '2021-10-06 15:47:41', '2021-10-06 15:51:12', 1),
(8, 'mCmwblOPS5emGvEW413H', 'rzl5nk7rIHDpqMUbHuz9', 'jRua3gvrhKfVsa0MY5Rj', '79x0YhY3z5sM6KDUDgiv', 0, 203500, 'Transfer', 1, '2021-10-06 15:47:52', '2021-10-06 15:53:05', 1),
(9, '9HF4W9PZSQFVbRrm2Zpu', 'oiH9fzKVpI8jLubuBqUK', 'xVhH0hrgLrkEhUn53BY1', 'fO92cGYP6jvNWnqINmEk', 1, 20000, 'POS', 0, '2021-10-10 23:52:58', '2021-10-11 00:18:52', 1),
(12, '6rAMagnUQtPvnXKdtEqS', 'oiH9fzKVpI8jLubuBqUK', '2HEugCfvFeI7TelOGJGD', 'uJ0ZOcR4xji4jnZqB7HY', 1, 18500, 'Card', 1, '2021-10-24 01:32:13', '2021-10-24 10:36:24', 1),
(13, '8BLOf38Yy8UPuRkF6HIv', 'bztRqJ7WTLOC32XmOwws', '2HEugCfvFeI7TelOGJGD', 'ObebRXLrvYH3wYNa5SMG', 1, 18700, 'Transfer', 1, '2021-10-24 01:34:07', '2021-10-24 01:38:22', 1),
(14, 'HoKovMZCyui77DFTQiit', 'tPBIE40TyKjOu0A35Joe', '2HEugCfvFeI7TelOGJGD', 'ObebRXLrvYH3wYNa5SMG', 1, 18700, 'Transfer', 1, '2021-10-24 01:34:23', '2021-10-24 01:38:28', 1),
(15, 'eZqGDLaZe3xZt8XyrcS5', 'rzl5nk7rIHDpqMUbHuz9', '2HEugCfvFeI7TelOGJGD', 'uJ0ZOcR4xji4jnZqB7HY', 1, 18500, 'Transfer', 1, '2021-10-24 01:34:38', '2021-10-24 01:38:02', 1),
(16, 'cTOB7rK1z4wwit4neRpG', 'FIx1NsUOzWnIeLp970CQ', '2HEugCfvFeI7TelOGJGD', 'HT4goQeRPOXHhuTxU8Ng', 1, 18500, 'Transfer', 0, '2021-10-24 01:35:05', '2021-10-24 01:35:05', 1),
(17, '3biUCB06DaAyJMS71nUK', 'ejm525FluTiIUQkSTNqK', '2HEugCfvFeI7TelOGJGD', 'HT4goQeRPOXHhuTxU8Ng', 1, 18500, 'Transfer', 1, '2021-10-24 01:35:41', '2021-10-24 01:37:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `shipment_unique_id` varchar(20) NOT NULL,
  `rider_unique_id` varchar(20) NOT NULL,
  `current_location` varchar(200) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `delivery_status` varchar(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `shipment_unique_id`, `rider_unique_id`, `current_location`, `longitude`, `latitude`, `delivery_status`, `added_date`, `last_modified`, `status`) VALUES
(1, 'fADJ84UbUamrqUdFxsRI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z5DTP6kXrEcu1wyeglmN', '2IznNBIz5lnURQpdGG3w', 'Diobu, Port-Harcourt, Nigeria', '7.0073301', '4.7999934', 'Completed', '2021-09-05 00:17:19', '2021-09-11 10:37:49', 1),
(2, 'NNcKAUuBonJTimmCCIIw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'xe9RjnjLOABza8GvLyM7', 'EAqXSSPExWfYQUaDvBKF', 'Port Harcourt Pleasure Park, park, Nigeria', '7.0115138751421195', '4.8375094999999995', 'Completed', '2021-09-08 22:41:33', '2021-09-11 10:39:50', 1),
(3, 's83U3QOZVcMbVcHd9kU6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '9TqA4ulhqmmy8lFknM6t', 'iFPbKDm3YqoruhLWJWAv', 'Rumuibekwe 500102, Port Harcourt, Nigeria', '7.057911', '4.843785', 'Cancelled', '2021-09-11 11:06:40', '2021-09-11 11:24:29', 0),
(4, 'JJekn5VfKtUCtRcm4x1I', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KHzhth7j6s4VBd9MNuvW', '2IznNBIz5lnURQpdGG3w', 'SPAR (PH mall, 1 Azikiwe Rd, next to Govt. House, Port Harcourt', '7.0133627', '4.775343', 'Completed', '2021-09-11 11:09:41', '2021-09-20 19:27:10', 1),
(5, 'XVZpRfDSsLs1bJwxACKp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CoCHQH7x61BuAwPimuCf', 'gWzAWjgmcuFMLnqMn1GL', 'CFC Roundabout Q2W3+MV7, Ogbunabali 500101, Port Harcourt', '7.0024703', '4.796654', 'Completed', '2021-09-20 19:27:43', '2021-09-21 16:25:30', 1),
(6, '0DMhQCTIxO2Z3uOthIva', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'lyRyue9ZCSOLR0gxlG0m', 'gWzAWjgmcuFMLnqMn1GL', 'Rumuibekwe 500102, Port Harcourt, Nigeria', '7.057911', '4.843785', 'Completed', '2021-09-21 16:25:59', '2021-09-22 07:37:54', 1),
(7, 'xUOVmRg7WDA3uzuB9ugm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vIZVEQbEGAZxHuDyGALL', 'gWzAWjgmcuFMLnqMn1GL', 'Rumuibekwe 500102, Port Harcourt, Nigeria', '7.057911', '4.843785', 'Processing', '2021-09-29 23:43:31', '2021-09-29 23:43:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_fees`
--

CREATE TABLE `shipping_fees` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipping_fees`
--

INSERT INTO `shipping_fees` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_product_unique_id`, `city`, `state`, `country`, `price`, `added_date`, `last_modified`, `status`) VALUES
(1, 'kKJikmrpFYJcoVZhf7im', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'AIRPORT ROAD - PORT HARCOURT', 'Rivers', 'Nigeria', 400, '2021-09-04 03:07:55', '2021-09-17 09:53:11', 1),
(2, 'mXGONTUow0WQVodYjUSs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'PORTHARCOURT-ADA GEORGE', 'Rivers', 'Nigeria', 100, '2021-09-04 03:08:42', '2021-09-17 09:52:12', 1),
(3, 'ZbTaCisQsYC5fZPR4I3l', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'PORTHARCOURT-BORI CAMP', 'Rivers', 'Nigeria', 150, '2021-09-04 03:08:58', '2021-09-17 09:51:20', 1),
(4, 'NfKYg3yUdKiKr3q8nqxY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'PHC-RIVER STATE UNIVERSITY', 'Rivers', 'Nigeria', 0, '2021-09-07 23:38:38', '2021-09-17 09:54:00', 1),
(5, 'OO8MThjTBNJJxTtC6e3m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'tNaI6YFU28TVzhpoRbOX', 'PORTHARCOURT-BOROKIRI', 'Rivers', 'Nigeria', 200, '2021-09-08 00:03:22', '2021-09-17 09:54:43', 1),
(6, 'Hh2AU93vhYyiWlATVoYg', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4xw7vU8hAWZmxkqRSpXp', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-16 22:11:55', 1),
(7, 'dHnPCd6pV53XT7AYsZxj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-16 22:11:55', 1),
(8, 'XNMRbKNdfZKUPebMajaQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-16 22:11:55', 1),
(9, 'VdpTLWRDJLlq15g4bu1T', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RfQh8qCsKx0iPjVBkDJz', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-16 22:11:55', 1),
(10, 'YwDN7PGbnxJiGhXhCIXP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-16 22:11:55', 1),
(11, 'VTCU8yng9w6LaA6SuvE6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BAkeyPT6bLp1Sa6ZRBwO', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 0, '2021-09-16 22:11:55', '2021-09-17 09:55:44', 0),
(12, '0m7hgCULtJHHx80pkkmM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4xw7vU8hAWZmxkqRSpXp', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(13, 'Gq7gEuBYOHWFV3aADwTo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RfQh8qCsKx0iPjVBkDJz', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(14, 'dgozQPFB6hJETx6JCWAW', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-17 09:47:22', 1),
(15, 'WQTNXHTC3RcMJon9Eghw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(16, 'lOhCNqPJXiLkJ0Hxcxma', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KYQfY8po1sJyAiIJzsmo', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(17, 'rY3wICvGpD7hr1xIHM2s', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oWVneQLUU10lOGZlzJNl', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(18, 'r8FQvFYUI2yfGw9Hv3OQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'w0dYyhZusHJQ79Nt3O32', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(19, '1AmvxoCq8z1IjsU0ZQEa', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 't0gqOhd5KvXkFBlPi9IE', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(20, 'lIDo8XU7tD1i5x9qQiD7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'K9U5133FieONokuFjx5j', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 100, '2021-09-16 22:13:41', '2021-09-16 22:13:41', 1),
(21, 'g2vaZF56xec58zMzzThI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'PORTHARCOURT-EAGLE ISLAND', 'Rivers', 'Nigeria', 50, '2021-09-16 22:13:41', '2021-09-17 09:50:19', 1),
(22, 'HxlHFJrlqVWVYQhiy6L3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(23, 'f5slFZfuHzyxqdg4m1cA', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(24, '1cv4RS5Ty0GqoT9DncmC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rM6UuKxkkzOKYiTGik9K', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(25, 'gUiEyksuNPzWpHwfa87X', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'K9U5133FieONokuFjx5j', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(26, '3XFeenvwmL8EUwBpV89r', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(27, 'UWSDPMmU0w8nXiDmK60d', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(28, 'zJJqWwuHleLaJziDvgBw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'StZD6JMMZcxgjjVZr9dD', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(29, 'c07I5NS7NwgtLcktg0so', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(30, 'f6uBzsMOwVunkHorQXVh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'buubKOU2qn5LEqwhrvon', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(31, 'KTpUwHWccP4SG4sWsvSj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TtJqtqBAHhBW7RAePZR3', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(32, 'wnDcCPheeb43CK0h2MKF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'IH8aF3hjtakJLQcWEkHV', 'PORTHARCOURT-CHOBA', 'Rivers', 'Nigeria', 100, '2021-09-17 16:37:09', '2021-09-17 16:37:09', 1),
(33, 'AZeQfaY0cg4PNl53RgNJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 100, '2021-09-18 12:14:50', '2021-09-18 12:14:50', 1),
(34, 'xJKsWG9DLv0Q2rMZhu1Q', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 20, '2021-09-19 17:14:18', '2021-09-20 19:13:59', 1),
(35, '2PR7vMUUFjzefNXgFriD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TO6o8Sx8qr1X0STjB0Cl', 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 50, '2021-09-21 15:20:02', '2021-09-21 15:20:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `stripped` varchar(50) NOT NULL,
  `details` varchar(500) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `unique_id`, `user_unique_id`, `name`, `stripped`, `details`, `added_date`, `last_modified`, `access`, `status`) VALUES
(1, 'i8CIcIggYLTD0CpVHUlh', 'BwmIbf8fq7Y6xShwvDS3', 'Jamals Provisions (JAP)', 'jamals-provisions-jap', 'Our store is located at 15 Evo Road Gra Port Harcourt ', '2021-08-27 23:38:27', '2021-08-28 13:56:03', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `store_images`
--

CREATE TABLE `store_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `store_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_users`
--

CREATE TABLE `store_users` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `store_unique_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` int(2) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_users`
--

INSERT INTO `store_users` (`id`, `unique_id`, `edit_user_unique_id`, `store_unique_id`, `fullname`, `email`, `phone_number`, `role`, `added_date`, `last_modified`, `access`, `status`) VALUES
(1, 'BwmIbf8fq7Y6xShwvDS3', 'BwmIbf8fq7Y6xShwvDS3', 'i8CIcIggYLTD0CpVHUlh', 'Jamal Lyon', 'jamallyon@gmail.com', '09128392473', 1, '2021-08-27 23:38:27', '2021-08-27 23:38:27', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `category_unique_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `stripped` varchar(100) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `category_unique_id`, `name`, `stripped`, `added_date`, `last_modified`, `status`) VALUES
(1, 'XbHIBZ6c7CreZvnUvH41', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Fruits & Vegetables', 'fruits-vegetables', '2021-08-22 13:14:49', '2021-08-30 14:31:46', 1),
(2, 'QrQp4BxphMlHGBXnZcvm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Pantry', 'pantry', '2021-08-25 00:04:12', '2021-08-25 00:04:12', 1),
(3, '9x1V9hsrIEYbtf3dBRfv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Eggs & Dairy', 'eggs-dairy', '2021-08-25 00:07:27', '2021-08-25 00:07:27', 1),
(4, 'tiSwMuRqIw1fl9FU349n', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Frozen', 'frozen', '2021-08-25 00:10:41', '2021-08-25 00:10:41', 1),
(5, 'qDKEDodAwF7kPVSzdlml', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Beverages', 'beverages', '2021-08-25 00:14:13', '2021-08-25 00:14:13', 1),
(6, 'OIJ4vZ9FCQ0nbnbPdTAa', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Snacks & Candy', 'snacks-candy', '2021-08-25 00:16:23', '2021-08-25 00:16:23', 1),
(7, '8fJh4qjZ8zoiPJr7us30', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Bread & Bakery', 'bread-bakery', '2021-08-25 00:18:03', '2021-08-25 00:18:03', 1),
(8, '92li75qyoAo8WUDqTfSK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1LMqS4xwFkhIqHw07uKu', 'Meat & Seafood', 'meat-seafood', '2021-08-25 00:20:48', '2021-08-25 00:20:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_category_images`
--

CREATE TABLE `sub_category_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_category_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_category_images`
--

INSERT INTO `sub_category_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_category_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(2, 'aFtcfi61T8UIIu8BN4FC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '92li75qyoAo8WUDqTfSK', 'https://www.reestoc.com/images/sub_category_images/1635105757.jpg', '1635105757.jpg', 962785, '2021-10-24 21:02:37', '2021-10-24 21:02:37', 1),
(3, '9v8HnQs7qRW9vrwysoeb', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XbHIBZ6c7CreZvnUvH41', 'https://www.reestoc.com/images/sub_category_images/1635105800.png', '1635105800.png', 250186, '2021-10-24 21:03:20', '2021-10-24 21:03:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_products`
--

CREATE TABLE `sub_products` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `product_unique_id` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `size` varchar(30) DEFAULT NULL,
  `stripped` varchar(250) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_remaining` int(11) NOT NULL,
  `price` double NOT NULL,
  `sales_price` double NOT NULL,
  `favorites` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_products`
--

INSERT INTO `sub_products` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `product_unique_id`, `name`, `size`, `stripped`, `description`, `stock`, `stock_remaining`, `price`, `sales_price`, `favorites`, `added_date`, `last_modified`, `status`) VALUES
(1, 'ZYmiB5A4ec2dpqRc9RjL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZYmiB5A4ec2dpqRc9RjL', 'Honeycrisp Apple', '1 pc', 'honeycrisp-apple', 'Honeycrisp Apple', 0, 0, 100, 0, 1, '2021-08-22 13:42:04', '2021-08-22 13:42:04', 1),
(2, '3Pwx8h1DGPuHGmtskkdN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '3Pwx8h1DGPuHGmtskkdN', 'Honeycrisp Apples (Bag)', '6 pcs', 'honeycrisp-apples-bag', 'Honeycrisp Apples (Bag)', 0, 0, 600, 0, 1, '2021-08-22 13:43:49', '2021-08-22 13:43:49', 1),
(3, 'CMfhOfeEBc7prqbh5S1h', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CMfhOfeEBc7prqbh5S1h', 'Fuji Apple', '1 pc', 'fuji-apple', 'Fuji Apple', 0, 0, 100, 0, 1, '2021-08-22 13:46:06', '2021-08-22 13:46:06', 1),
(4, '8n79czfWKvTK5mf9SmPP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '8n79czfWKvTK5mf9SmPP', 'Fuji Apples (half bag)', '3 pcs', 'fuji-apples-half-bag', 'Fuji Apples (half bag)', 0, 0, 300, 0, 1, '2021-08-22 13:46:35', '2021-08-22 13:46:35', 1),
(5, 'yMyB6vU6RhPWz62Txz4j', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'yMyB6vU6RhPWz62Txz4j', 'Fuji Apples (bag)', '6 pcs', 'fuji-apples-bag', 'Fuji Apples (bag)', 0, 0, 600, 0, 1, '2021-08-22 13:46:52', '2021-08-22 13:46:52', 1),
(6, 'pLmFhuwLB0qyx3Np2M1l', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'pLmFhuwLB0qyx3Np2M1l', 'Green Organic Apple', '1 pc', 'green-organic-apple', 'Green Organic Apple', 0, 0, 100, 0, 1, '2021-08-22 13:49:36', '2021-08-22 13:49:36', 1),
(7, 'YpFVXTZamfSmtjomZvqw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'YpFVXTZamfSmtjomZvqw', 'Green Organic Apple (half bag)', '3 pcs', 'green-organic-apple-half-bag', 'Green Organic Apple (half bag)', 0, 0, 300, 0, 1, '2021-08-22 13:50:00', '2021-08-22 13:50:00', 1),
(8, '6u5SAmZ6EB2cEJXWvhdl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6u5SAmZ6EB2cEJXWvhdl', 'Green Organic Apple (bag)', '6 pcs', 'green-organic-apple-bag', 'Green Organic Apple (bag)', 0, 0, 600, 0, 1, '2021-08-22 13:50:19', '2021-08-22 13:50:19', 1),
(9, 'TLgRemghfULk2YLIHiuj', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TLgRemghfULk2YLIHiuj', 'Pineapple', '1 pc', 'pineapple', 'Pineapple', 0, 0, 350, 0, 1, '2021-08-22 13:54:36', '2021-08-22 13:54:36', 1),
(10, 'Ed003fk4XIumWEXMM4Ll', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ed003fk4XIumWEXMM4Ll', 'Yellow Dragon Fruit', '1 pc', 'yellow-dragon-fruit', 'Yellow Dragon Fruit', 0, 0, 500, 0, 1, '2021-08-22 13:55:09', '2021-08-22 13:55:09', 1),
(11, 'F608qWR3vQUsQ9dBUASS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'F608qWR3vQUsQ9dBUASS', 'Raspberries ', '1 Mini Carton', 'raspberries-', 'Raspberries ', 0, 0, 1500, 0, 1, '2021-08-22 13:56:34', '2021-08-22 13:56:34', 1),
(12, 'bmXMoM9YcIVBxmyNwgj8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', 'Strawberries ', '1 Mini Carton', 'strawberries-', 'Strawberries ', 0, 0, 2000, 0, 1, '2021-08-22 13:57:29', '2021-08-22 13:57:29', 1),
(13, '1dSg8Z4r8qGkOgiq1GgU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1dSg8Z4r8qGkOgiq1GgU', 'Lime', '1 pc', 'lime', 'Lime', 0, 0, 100, 0, 1, '2021-08-22 13:59:41', '2021-08-22 13:59:41', 1),
(14, 'RDe5xNbjllZLDx3C06Bb', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RDe5xNbjllZLDx3C06Bb', 'Limes (half bag)', '4 pc', 'limes-half-bag', 'Limes (half bag)', 0, 0, 400, 0, 1, '2021-08-22 14:00:07', '2021-08-22 14:00:07', 1),
(15, 'PuRy7q00tSddczYjqlKs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'PuRy7q00tSddczYjqlKs', 'Pear ', '1 pc', 'pear-', 'Pear', 0, 0, 150, 0, 1, '2021-08-22 14:00:47', '2021-08-22 14:00:47', 1),
(16, 'roCiLQZYlSEDfynesYyd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'roCiLQZYlSEDfynesYyd', 'Pears (half bag)', '3 pcs', 'pears-half-bag', 'Pears (half bag)', 0, 0, 450, 0, 1, '2021-08-22 14:01:34', '2021-08-22 14:01:34', 1),
(17, 'o8BQRqrpzbJu5GnXNH40', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'Pawpaw (Papaya)', '1 pc', 'pawpaw-papaya', 'Pawpaw (Papaya)', 0, 0, 350, 0, 1, '2021-08-22 14:02:48', '2021-08-22 14:02:48', 1),
(18, 'rmATmc82fAsWTFNdUCne', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'Lemons', '3 pcs', 'lemons', 'Lemons', 0, 0, 400, 0, 1, '2021-08-22 14:03:42', '2021-08-22 14:03:42', 1),
(19, 'BIGfY7300msTzFiD1Mu3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'Bananas', '6 pcs', 'bananas', 'Bananas', 0, 0, 300, 0, 1, '2021-08-22 14:12:11', '2021-08-22 14:12:11', 1),
(20, 'GPb4qvv0cVkAmtrQYPJ7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GPb4qvv0cVkAmtrQYPJ7', 'Green seedless grapes', '1  pack', 'green-seedless-grapes', 'Green seedless grapes', 0, 0, 500, 0, 1, '2021-08-22 14:13:35', '2021-08-22 14:13:35', 1),
(21, 'viSSVJKUcdTxD8PiXMXQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'viSSVJKUcdTxD8PiXMXQ', 'Red seedless grapes', '1  pack', 'red-seedless-grapes', 'Red seedless grapes', 0, 0, 500, 0, 1, '2021-08-22 14:13:41', '2021-08-22 14:13:41', 1),
(22, 'K9U5133FieONokuFjx5j', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'K9U5133FieONokuFjx5j', 'Coconut', '1 pc', 'coconut', 'Coconut', 0, 0, 300, 0, 1, '2021-08-22 14:14:39', '2021-08-22 14:14:39', 1),
(23, 'fPo1wAt1cqNBacWWXWwh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'fPo1wAt1cqNBacWWXWwh', 'Mango', '1 pc', 'mango', 'Mango', 0, 0, 100, 0, 1, '2021-08-22 14:15:02', '2021-08-22 14:15:02', 1),
(24, 'RxKF7kjcwewxdxyfgpVG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RxKF7kjcwewxdxyfgpVG', 'Mangoes', '6 pcs', 'mangoes', 'Mangoes', 0, 0, 500, 0, 1, '2021-08-22 14:15:17', '2021-08-22 14:15:17', 1),
(25, '121T7LP8ddRjfQysdo4G', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '121T7LP8ddRjfQysdo4G', 'Yellow Peach', '1 pc', 'yellow-peach', 'Yellow Peach', 0, 0, 250, 0, 1, '2021-08-22 14:16:55', '2021-08-22 14:16:55', 1),
(26, 'NoCDRMowFlelV4crZLuL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'Watermelon', '1 pc', 'watermelon', 'Watermelon', 0, 0, 300, 0, 1, '2021-08-22 14:17:17', '2021-08-22 14:17:17', 1),
(27, '6kEPf5H8FFuIaOXGCqzx', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'Avocado', '1 pc', 'avocado', 'Avocado', 0, 0, 100, 0, 1, '2021-08-22 14:17:57', '2021-08-22 14:17:57', 1),
(28, 'CR2VgdgHnN510w7kPo6t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CR2VgdgHnN510w7kPo6t', 'Kiwi', '1 pc', 'kiwi', 'Kiwi', 0, 0, 150, 0, 1, '2021-08-22 14:18:40', '2021-08-22 14:18:40', 1),
(29, 'oWVneQLUU10lOGZlzJNl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oWVneQLUU10lOGZlzJNl', 'Blackberries', '1 mini carton', 'blackberries', 'Blackberries', 0, 0, 1500, 0, 1, '2021-08-22 14:21:25', '2021-08-22 14:21:25', 1),
(30, 'w0dYyhZusHJQ79Nt3O32', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'w0dYyhZusHJQ79Nt3O32', 'Blueberries', '1 mini carton', 'blueberries', 'Blueberries', 0, 0, 1500, 0, 1, '2021-08-22 14:21:41', '2021-08-22 14:21:41', 1),
(31, '2CgmFpBo8Cn8UMQW7tW8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '2CgmFpBo8Cn8UMQW7tW8', 'Jumbo Cantaloupe', '1 pc', 'jumbo-cantaloupe', 'Jumbo Cantaloupe', 0, 0, 250, 0, 1, '2021-08-22 14:22:33', '2021-08-22 14:22:33', 1),
(32, 'KjZEuKd57qFTIPhDOwLZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KjZEuKd57qFTIPhDOwLZ', 'Plantain', '3 pcs', 'plantain', 'Plantain', 0, 0, 1000, 0, 1, '2021-08-23 18:32:25', '2021-08-23 18:32:25', 1),
(33, 'zvlPfnTpos50OTYN1WBE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'zvlPfnTpos50OTYN1WBE', 'Red Dragon Fruit (Pitaya)', '1 pc', 'red-dragon-fruit-pitaya', 'Red Dragon Fruit (Pitaya)', 0, 0, 650, 0, 1, '2021-08-23 19:25:12', '2021-08-23 19:25:12', 1),
(34, 'BEVIo47cxgHInm44GhdN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BEVIo47cxgHInm44GhdN', 'Oranges (small)', '3 pcs', 'oranges-small-3-pcs', 'Oranges (small)', 0, 0, 100, 0, 1, '2021-08-23 19:35:23', '2021-08-23 19:35:23', 1),
(35, '4QTT7wp7v8RGSMLTGq2W', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'Oranges (small)', '6 pcs', 'oranges-small-6-pcs', 'Oranges (small)', 0, 0, 200, 0, 1, '2021-08-23 19:36:15', '2021-08-23 19:36:15', 1),
(36, 'Qm3SiE1uJZPiUZsMbdmt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qm3SiE1uJZPiUZsMbdmt', 'Oranges (big)', '3 pcs', 'oranges-small-3-pcs', 'Oranges (big)', 0, 0, 200, 0, 1, '2021-08-23 19:40:40', '2021-08-23 19:40:40', 1),
(37, 'R6PWOkPpmfHbg1oJTUnN', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R6PWOkPpmfHbg1oJTUnN', 'Oranges (big)', '6 pcs', 'oranges-big-6-pcs', 'Oranges (big)', 0, 0, 400, 0, 1, '2021-08-23 19:42:08', '2021-08-23 19:42:08', 1),
(38, '4xw7vU8hAWZmxkqRSpXp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4xw7vU8hAWZmxkqRSpXp', 'Apricot', '1 pc', 'apricot-1-pc', 'Apricot', 0, 0, 400, 0, 1, '2021-08-23 21:47:11', '2021-08-23 21:47:11', 1),
(39, 'z8SQw7F6HzBojlyKcrx7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z8SQw7F6HzBojlyKcrx7', 'Red Cabbage', '1 pc', 'red-cabbage-1-pc', 'Red Cabbage', 0, 0, 200, 0, 1, '2021-08-23 22:10:46', '2021-08-23 22:10:46', 1),
(40, 'oZobMY6DynqEc00brHuF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oZobMY6DynqEc00brHuF', 'Green Cabbage', '1 pc', 'green-cabbage-1-pc', 'Green Cabbage', 0, 0, 150, 0, 1, '2021-08-23 22:11:59', '2021-08-23 22:11:59', 1),
(41, 'njaQcTQGciA4tXDoDE8Z', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'njaQcTQGciA4tXDoDE8Z', 'Potatoes', '8 pc', 'potatoes-8-pc', 'Potatoes', 0, 0, 300, 0, 1, '2021-08-23 22:13:19', '2021-08-23 22:13:19', 1),
(42, 'XPBqbxn5edePf6UNQGhX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XPBqbxn5edePf6UNQGhX', 'Potatoes ', '1 custard rubber', 'potatoes-1-custard-rubber', 'Potatoes', 0, 0, 1400, 0, 1, '2021-08-23 22:14:02', '2021-08-23 22:14:02', 1),
(43, 'Qz6NgIp3lhzko1JXkWkr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qz6NgIp3lhzko1JXkWkr', 'Potatoes ', 'Half custard rubber', 'potatoes-half-custard-rubber', 'Potatoes', 0, 0, 700, 0, 1, '2021-08-23 22:15:17', '2021-08-23 22:15:17', 1),
(44, '4ue2Ou9euLUOkX1QNv3A', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4ue2Ou9euLUOkX1QNv3A', 'Sweet Potatoes ', '8 pc', 'sweet-potatoes-8-pc', 'Sweet Potatoes', 0, 0, 250, 0, 1, '2021-08-23 22:21:17', '2021-08-23 22:21:17', 1),
(45, 'ubqX3PhJZHAnd1owZwtF', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ubqX3PhJZHAnd1owZwtF', 'Sweet Potatoes ', '1 custard rubber', 'sweet-potatoes-1-custard-rubber', 'Sweet Potatoes', 0, 0, 1100, 0, 1, '2021-08-23 22:21:38', '2021-08-23 22:21:38', 1),
(46, 'V0r3ceyqIEbrYG9mAKXh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'V0r3ceyqIEbrYG9mAKXh', 'Sweet Potatoes ', 'Half custard rubber', 'sweet-potatoes-half-custard-rubber', 'Sweet Potatoes', 0, 0, 550, 0, 1, '2021-08-23 22:21:58', '2021-08-23 22:21:58', 1),
(47, 'GIrLFCYpNPdpg3NxrUbr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GIrLFCYpNPdpg3NxrUbr', 'Ginger', '5 pcs', 'ginger-5-pcs', 'Ginger', 0, 0, 150, 0, 1, '2021-08-23 22:32:30', '2021-08-23 22:32:30', 1),
(48, '4FbzqICLp5TN4J40kgSp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4FbzqICLp5TN4J40kgSp', 'Thai Chile Pepper', '6 pcs', 'thai-chile-pepper-6-pcs', 'Thai Chile Pepper', 0, 0, 100, 0, 1, '2021-08-23 22:33:16', '2021-08-23 22:33:16', 1),
(49, 'CPmEWz6ZyQZNUh4oKqNl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', 'Tomatoes', '1 small bowl', 'tomatoes-1-small-bowl', 'Tomatoes', 0, 0, 300, 0, 1, '2021-08-23 22:40:06', '2021-08-23 22:40:06', 1),
(50, 'Ly0q4AxQ3YLBeX73yuGG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ly0q4AxQ3YLBeX73yuGG', 'Tomatoes', '1 big bowl', 'tomatoes-1-big-bowl', 'Tomatoes', 0, 0, 650, 0, 1, '2021-08-23 22:40:25', '2021-08-23 22:40:25', 1),
(51, 'CsbrUs4NhzHMXJylSQE0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', 'Tomatoes', '1 custard rubber', 'tomatoes-1-custard-rubber', 'Tomatoes', 0, 0, 1300, 0, 1, '2021-08-23 22:41:31', '2021-08-23 22:41:31', 1),
(52, 'RiKvkhKmhzd4KYhxKViZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RiKvkhKmhzd4KYhxKViZ', 'Romaine Lettuce', '1 bundle', 'romaine-lettuce-1-bundle', 'Romaine Lettuce', 0, 0, 150, 0, 1, '2021-08-23 22:43:43', '2021-08-23 22:43:43', 1),
(53, 'Fk9scOOt8VNT1wJOSKPL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Fk9scOOt8VNT1wJOSKPL', 'Green Leaf Lettuce', '1 bundle', 'green-leaf-lettuce-1-bundle', 'Green Leaf Lettuce', 0, 0, 200, 0, 1, '2021-08-23 22:44:41', '2021-08-23 22:44:41', 1),
(54, 'B1LpuPX2quhHKstAsRn7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B1LpuPX2quhHKstAsRn7', 'Yellow Onions', 'Half bunch', 'yellow-onions-half-bunch', 'Yellow Onions', 0, 0, 150, 0, 1, '2021-08-23 22:47:01', '2021-08-23 22:47:01', 1),
(55, 'vXflwq2tex9cAUXYY4xq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vXflwq2tex9cAUXYY4xq', 'Red Onions', 'Half bunch', 'red-onions-half-bunch', 'Red Onions', 0, 0, 100, 0, 1, '2021-08-23 22:47:21', '2021-08-23 22:47:21', 1),
(56, 'AjeQr1WU0OgkuIuyGgk7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AjeQr1WU0OgkuIuyGgk7', 'Yellow Onions', '1 bunch', 'yellow-onions-1-bunch', 'Yellow Onions', 0, 0, 300, 0, 1, '2021-08-23 22:47:40', '2021-08-23 22:47:40', 1),
(57, 'v2aJaDOC8iHAHYx6Ms0E', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'v2aJaDOC8iHAHYx6Ms0E', 'Red Onions', '1 bunch', 'red-onions-1-bunch', 'Red Onions', 0, 0, 200, 0, 1, '2021-08-23 22:48:13', '2021-08-23 22:48:13', 1),
(58, '0Cb5pHO3ceHwxJ6lLFL2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '0Cb5pHO3ceHwxJ6lLFL2', 'White Onions', '1 bunch', 'white-onions-1-bunch', 'White Onions', 0, 0, 350, 0, 1, '2021-08-23 22:48:55', '2021-08-23 22:48:55', 1),
(59, 'n36Ane9qXyVSU0WUUlis', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'n36Ane9qXyVSU0WUUlis', 'White Onions', 'Half bunch', 'white-onions-half-bunch', 'White Onions', 0, 0, 250, 0, 1, '2021-08-23 22:49:05', '2021-08-23 22:49:05', 1),
(60, 'lf69BgYAqllUwawzMwdE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'lf69BgYAqllUwawzMwdE', 'Cucumber', '3 pcs', 'cucumber-3-pcs', 'Cucumber', 0, 0, 200, 0, 1, '2021-08-24 19:39:34', '2021-08-24 19:39:34', 1),
(61, 't0gqOhd5KvXkFBlPi9IE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 't0gqOhd5KvXkFBlPi9IE', 'Cucumber', '6 pcs', 'cucumber-6-pcs', 'Cucumber', 0, 0, 300, 0, 1, '2021-08-24 19:40:03', '2021-08-24 19:40:03', 1),
(62, 'MWBDhY9b6l2fsgk2RsxR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MWBDhY9b6l2fsgk2RsxR', 'Green Okra', '1 bunch', 'green-okra-1-bunch', 'Green Okra', 0, 0, 200, 0, 1, '2021-08-24 19:40:58', '2021-08-24 19:40:58', 1),
(63, 'caH7cuXhgZ1HHftXSQXI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'caH7cuXhgZ1HHftXSQXI', 'Jumbo Green Pepper', '1 pc', 'jumbo-green-pepper-1-pc', 'Jumbo Green Pepper', 0, 0, 100, 0, 1, '2021-08-24 19:41:37', '2021-08-24 19:41:37', 1),
(64, '14KmvpIZw2AHDaQdPkjq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '14KmvpIZw2AHDaQdPkjq', 'Jumbo Green Pepper', '3 pc', 'jumbo-green-pepper-3-pc', 'Jumbo Green Pepper', 0, 0, 250, 0, 1, '2021-08-24 19:41:48', '2021-08-24 19:41:48', 1),
(65, 'IH8aF3hjtakJLQcWEkHV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'IH8aF3hjtakJLQcWEkHV', 'Jumbo Green Pepper', '6 pc', 'jumbo-green-pepper-6-pc', 'Jumbo Green Pepper', 0, 0, 450, 0, 1, '2021-08-24 19:41:58', '2021-08-24 19:41:58', 1),
(66, 'BAkeyPT6bLp1Sa6ZRBwO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BAkeyPT6bLp1Sa6ZRBwO', 'Celery', '1 bundle', 'celery-1-bundle', 'Celery', 0, 0, 100, 0, 1, '2021-08-24 19:42:59', '2021-08-24 19:42:59', 1),
(67, 'RfQh8qCsKx0iPjVBkDJz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RfQh8qCsKx0iPjVBkDJz', 'Celery', '3 bundles', 'celery-3-bundles', 'Celery', 0, 0, 250, 0, 1, '2021-08-24 19:45:54', '2021-08-24 19:45:54', 1),
(68, 'hnorIWyffhmFwB5RAUVC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'Celery', '6 bundles', 'celery-6-bundles', 'Celery', 0, 0, 450, 0, 1, '2021-08-24 19:46:05', '2021-08-24 19:46:05', 1),
(69, 'FK9OMzBpP4UrxPpoq00I', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'FK9OMzBpP4UrxPpoq00I', 'Kale Bunch', '1 bunch', 'kale-bunch-1-bunch', 'Kale Bunch', 0, 0, 150, 0, 1, '2021-08-24 19:46:47', '2021-08-24 19:46:47', 1),
(70, 'buubKOU2qn5LEqwhrvon', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'buubKOU2qn5LEqwhrvon', 'Kale Bunch', '4 bunches', 'kale-bunch-4-bunches', 'Kale Bunch', 0, 0, 550, 0, 1, '2021-08-24 19:47:09', '2021-08-24 19:47:09', 1),
(71, 'rZUC7rAmM35lTZm6AUei', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rZUC7rAmM35lTZm6AUei', 'Zucchini Squash', '1 pc', 'zucchini-squash-1-pc', 'Zucchini Squash', 0, 0, 200, 0, 1, '2021-08-24 19:48:05', '2021-08-24 19:48:05', 1),
(72, 'KYQfY8po1sJyAiIJzsmo', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KYQfY8po1sJyAiIJzsmo', 'Carrots', '1 bundle', 'carrots-1-bundle', 'Carrots', 0, 0, 100, 0, 1, '2021-08-24 19:48:30', '2021-08-24 19:48:30', 1),
(73, 'rJ5pWCIox0hJsqqKmrN7', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rJ5pWCIox0hJsqqKmrN7', 'Carrots', '3 bundles', 'carrots-3-bundles', 'Carrots', 0, 0, 300, 0, 1, '2021-08-24 19:48:41', '2021-08-24 19:48:41', 1),
(74, 'rM6UuKxkkzOKYiTGik9K', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rM6UuKxkkzOKYiTGik9K', 'Carrots', '6 bundles', 'carrots-6-bundles', 'Carrots', 0, 0, 550, 0, 1, '2021-08-24 19:48:52', '2021-08-24 19:48:52', 1),
(75, '6gyD9GjRmk1JxwyfDBC0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6gyD9GjRmk1JxwyfDBC0', 'Egg Plant', '1 bunch', 'egg-plant-1-bunch', 'Egg Plant', 0, 0, 250, 0, 1, '2021-08-24 19:49:28', '2021-08-24 19:49:28', 1),
(76, 'MAI33CYT9cU4uQzhJw5t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MAI33CYT9cU4uQzhJw5t', 'Green Onions', '1 bundle', 'green-onions-1-bundle', 'Green Onions', 0, 0, 100, 0, 1, '2021-08-24 19:51:22', '2021-08-24 19:51:22', 1),
(77, 'R8EjUXBd4Ws8rSxqbvnU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R8EjUXBd4Ws8rSxqbvnU', 'Green Onions', '3 bundles', 'green-onions-3-bundles', 'Green Onions', 0, 0, 250, 0, 1, '2021-08-24 19:51:44', '2021-08-24 19:51:44', 1),
(78, 'TCV804vAclOg0ABHuDfQ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TCV804vAclOg0ABHuDfQ', 'Broccoli', '1 bundle', 'broccoli-1-bundle', 'Broccoli', 0, 0, 200, 0, 1, '2021-08-24 19:52:27', '2021-08-24 19:52:27', 1),
(79, '20EmDuRA4JkTQYtavkX5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '20EmDuRA4JkTQYtavkX5', 'Broccoli', '3 bundles', 'broccoli-3-bundles', 'Broccoli', 0, 0, 500, 0, 1, '2021-08-24 19:52:39', '2021-08-24 19:52:39', 1),
(80, '5t8EMBqYo2zP3Z8fTWQR', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '5t8EMBqYo2zP3Z8fTWQR', 'Yellow Squash', '1 pc', 'yellow-squash-1-pc', 'Yellow Squash', 0, 0, 200, 0, 1, '2021-08-24 19:54:13', '2021-08-24 19:54:13', 1),
(81, 'KEFFJDzBx3Ovn50qkx2u', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'Chayote', '1 pc', 'chayote-1-pc', 'Chayote', 0, 0, 100, 0, 1, '2021-08-24 19:54:37', '2021-08-24 19:54:37', 1),
(82, 'iY1QfTt2xGvFzDkdt9eO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'iY1QfTt2xGvFzDkdt9eO', 'Yellow Sweet Corn', '1 pc', 'yellow-sweet-corn-1-pc', 'Yellow Sweet Corn', 0, 0, 100, 0, 1, '2021-08-24 19:54:57', '2021-08-24 19:54:57', 1),
(83, 'XjxMjQ05J3qJgSazyrGn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'Jalapeno Pepper', '6 pcs', 'jalapeno-pepper-6-pcs', 'Jalapeno Pepper', 0, 0, 150, 0, 1, '2021-08-24 19:55:35', '2021-08-24 19:55:35', 1),
(84, 'StZD6JMMZcxgjjVZr9dD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'StZD6JMMZcxgjjVZr9dD', 'Malanga (Yautia)', '3 pcs', 'malanga-yautia-3-pcs', 'Malanga (Yautia)', 0, 0, 250, 0, 1, '2021-08-24 19:56:13', '2021-08-24 19:56:13', 1),
(85, 'GKqmoEXM88Tjj1JXk5UP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'Garlic', '8 pcs', 'garlic-8-pcs', 'Garlic', 0, 0, 100, 0, 1, '2021-08-24 19:57:20', '2021-08-24 19:57:20', 1),
(86, 'TtJqtqBAHhBW7RAePZR3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TtJqtqBAHhBW7RAePZR3', 'Jumbo Red Pepper', '1 pc', 'jumbo-red-pepper-1-pc', 'Jumbo Red Pepper', 0, 0, 100, 0, 1, '2021-08-24 20:04:34', '2021-09-03 16:11:47', 1),
(87, 'tNaI6YFU28TVzhpoRbOX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'tNaI6YFU28TVzhpoRbOX', 'Yucca', '3 pcs', 'yucca-3-pcs', 'Yucca 3 pcs', 0, 0, 200, 0, 1, '2021-08-24 20:05:07', '2021-09-03 16:49:34', 1),
(88, 'cgPcth9HTnqGjrCf6VWD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZuGUnC2vz8pFOOyxwVFj', 'Coca-Cola Low Sugar 35cl', '35cl', 'coca-cola-low-sugar-35cl-35cl', '<p>This is the&nbsp;Coca-Cola Low Sugar 35cl</p>', 0, 0, 250, 200, 1, '2021-09-03 16:15:56', '2021-09-03 16:20:53', 1),
(89, 'KZ7yG9bGYZVI1rjxoh40', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'Orlu bananas', '1 basket', 'orlu-bananas-1-basket', '<p>Orlu bananas 1 basket</p>', 0, 0, 4000, 0, 1, '2021-09-07 23:17:24', '2021-09-19 18:42:47', 1),
(90, 'TO6o8Sx8qr1X0STjB0Cl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZuGUnC2vz8pFOOyxwVFj', 'Coca-Cola Low Sugar 75cl', '75cl', 'coca-cola-low-sugar-75cl-75cl', '<p>Coca-Cola Low Sugar 75cl<br></p>', 0, 0, 400, 350, 1, '2021-09-21 04:29:27', '2021-09-21 04:29:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_product_images`
--

CREATE TABLE `sub_product_images` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `edit_user_unique_id` varchar(20) NOT NULL,
  `sub_product_unique_id` varchar(20) NOT NULL,
  `image` varchar(300) NOT NULL,
  `file` varchar(30) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_product_images`
--

INSERT INTO `sub_product_images` (`id`, `unique_id`, `user_unique_id`, `edit_user_unique_id`, `sub_product_unique_id`, `image`, `file`, `file_size`, `added_date`, `last_modified`, `status`) VALUES
(1, 'GGfbWE2sjCve70njSdPJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KjZEuKd57qFTIPhDOwLZ', 'https://www.reestoc.com/sub_product_images/1629742506.jpg', '1629742506.jpg', 4887, '2021-08-23 19:15:06', '2021-08-23 19:15:06', 1),
(2, '5q3jpy9tVvvyPVkl6JqU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KjZEuKd57qFTIPhDOwLZ', 'https://www.reestoc.com/sub_product_images/1629742507.jpeg', '1629742507.jpeg', 4998, '2021-08-23 19:15:06', '2021-08-23 19:15:06', 1),
(3, '3ugJ8X7WA0ncsSjA8jR4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'zvlPfnTpos50OTYN1WBE', 'https://www.reestoc.com/sub_product_images/1629743172.jpg', '1629743172.jpg', 13566, '2021-08-23 19:26:12', '2021-08-23 19:26:12', 1),
(4, 'hw4VGotP6GaGt7fQGrUY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '3Pwx8h1DGPuHGmtskkdN', 'https://www.reestoc.com/sub_product_images/1629743259.jpg', '1629743259.jpg', 7117, '2021-08-23 19:27:39', '2021-08-23 19:27:39', 1),
(5, '1j0SmS7ca2NPTMAzA5pU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'F608qWR3vQUsQ9dBUASS', 'https://www.reestoc.com/sub_product_images/1629743308.jpg', '1629743308.jpg', 10712, '2021-08-23 19:28:28', '2021-08-23 19:28:28', 1),
(6, 'sjbzC6AbvSVM9tTFIXtm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BEVIo47cxgHInm44GhdN', 'https://www.reestoc.com/sub_product_images/1629744216.jpg', '1629744216.jpg', 5749, '2021-08-23 19:43:36', '2021-08-23 19:43:36', 1),
(7, 'mQF2xbhVZB5LCuXuBHzs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BEVIo47cxgHInm44GhdN', 'https://www.reestoc.com/sub_product_images/1629744217.jpg', '1629744217.jpg', 6335, '2021-08-23 19:43:36', '2021-08-23 19:43:36', 1),
(8, 'yiyGpmsbGIAVxI0A4JyZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'https://www.reestoc.com/sub_product_images/1629744256.jpg', '1629744256.jpg', 5749, '2021-08-23 19:44:16', '2021-08-23 19:44:16', 1),
(9, 'AJSJcr6HlkAbCSEdAjVn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4QTT7wp7v8RGSMLTGq2W', 'https://www.reestoc.com/sub_product_images/1629744257.jpg', '1629744257.jpg', 6335, '2021-08-23 19:44:16', '2021-08-23 19:44:16', 1),
(10, 'fKh5Zw8NdzlHM80v4RU5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qm3SiE1uJZPiUZsMbdmt', 'https://www.reestoc.com/sub_product_images/1629744315.jpg', '1629744315.jpg', 5749, '2021-08-23 19:45:15', '2021-08-23 19:45:15', 1),
(11, 'KNyUXaQ0s3TzJ8pc6R3g', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qm3SiE1uJZPiUZsMbdmt', 'https://www.reestoc.com/sub_product_images/1629744316.jpg', '1629744316.jpg', 6335, '2021-08-23 19:45:15', '2021-08-23 19:45:15', 1),
(12, 'J4MJHKsLHoohYj1pkqyh', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R6PWOkPpmfHbg1oJTUnN', 'https://www.reestoc.com/sub_product_images/1629744355.jpg', '1629744355.jpg', 5749, '2021-08-23 19:45:55', '2021-08-23 19:45:55', 1),
(13, 'E50TaH4LTtf8t7xYJznH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R6PWOkPpmfHbg1oJTUnN', 'https://www.reestoc.com/sub_product_images/1629744356.jpg', '1629744356.jpg', 6335, '2021-08-23 19:45:55', '2021-08-23 19:45:55', 1),
(14, 'cw8gSjWj3v8Q0xqnjTn5', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '1dSg8Z4r8qGkOgiq1GgU', 'https://www.reestoc.com/sub_product_images/1629744707.jpeg', '1629744707.jpeg', 6594, '2021-08-23 19:51:47', '2021-08-23 19:51:47', 1),
(15, 'Shf8L9RUCNE6oYeBFy0P', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RDe5xNbjllZLDx3C06Bb', 'https://www.reestoc.com/sub_product_images/1629744820.jpg', '1629744820.jpg', 51384, '2021-08-23 19:53:40', '2021-08-23 19:53:40', 1),
(16, 'h2wjM47EU02lMqp3M7m6', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'https://www.reestoc.com/sub_product_images/1629749355.jpeg', '1629749355.jpeg', 5656, '2021-08-23 21:09:15', '2021-08-23 21:09:15', 1),
(17, 'KRR0MEmRsaHINXvkteCu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rmATmc82fAsWTFNdUCne', 'https://www.reestoc.com/sub_product_images/1629749356.jpg', '1629749356.jpg', 44550, '2021-08-23 21:09:15', '2021-08-23 21:09:15', 1),
(18, 'VDijzUuPRFGkt75njak1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', 'https://www.reestoc.com/sub_product_images/1629749468.jpg', '1629749468.jpg', 11495, '2021-08-23 21:11:08', '2021-08-23 21:11:08', 1),
(19, 'bp3ONGDDCRyDP44tYe5n', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'bmXMoM9YcIVBxmyNwgj8', 'https://www.reestoc.com/sub_product_images/1629749469.jpg', '1629749469.jpg', 8823, '2021-08-23 21:11:08', '2021-08-23 21:11:08', 1),
(20, 'Xn5Ri62rgQd65LH6ZEtG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'https://www.reestoc.com/sub_product_images/1629749567.jpg', '1629749567.jpg', 6181, '2021-08-23 21:12:47', '2021-08-23 21:12:47', 1),
(21, '6UB7OSJEBYzFcgYDj260', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'o8BQRqrpzbJu5GnXNH40', 'https://www.reestoc.com/sub_product_images/1629749569.jpg', '1629749569.jpg', 13309, '2021-08-23 21:12:47', '2021-08-23 21:12:47', 1),
(22, 'nACxwVmogRrbc1OtszdD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TLgRemghfULk2YLIHiuj', 'https://www.reestoc.com/sub_product_images/1629750063.jpg', '1629750063.jpg', 6687, '2021-08-23 21:21:03', '2021-08-23 21:21:03', 1),
(23, 'nan70v7waGmKECMMzr8U', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TLgRemghfULk2YLIHiuj', 'https://www.reestoc.com/sub_product_images/1629750064.jpeg', '1629750064.jpeg', 45751, '2021-08-23 21:21:03', '2021-08-23 21:21:03', 1),
(24, 'kpi4KdiJaQDtvh2zSsPq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ZYmiB5A4ec2dpqRc9RjL', 'https://www.reestoc.com/sub_product_images/1629750104.jpeg', '1629750104.jpeg', 56417, '2021-08-23 21:21:44', '2021-08-23 21:21:44', 1),
(25, '5fJXGx7XLYOuARiIBthu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ed003fk4XIumWEXMM4Ll', 'https://www.reestoc.com/sub_product_images/1629750138.jpg', '1629750138.jpg', 6311, '2021-08-23 21:22:18', '2021-08-23 21:22:18', 1),
(26, 'jwlJJCIQEZqDZxROCEcy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '121T7LP8ddRjfQysdo4G', 'https://www.reestoc.com/sub_product_images/1629750203.jpg', '1629750203.jpg', 6092, '2021-08-23 21:23:23', '2021-08-23 21:23:23', 1),
(27, '0sQyILT0qREEzzwla3i8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '121T7LP8ddRjfQysdo4G', 'https://www.reestoc.com/sub_product_images/1629750204.jpg', '1629750204.jpg', 8006, '2021-08-23 21:23:23', '2021-08-23 21:23:23', 1),
(28, 'vykw0yMGofM2HCgQn14J', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/sub_product_images/1629750273.jpeg', '1629750273.jpeg', 8238, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(29, 'mZInFJ6JmfkEGwA3avL8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/sub_product_images/1629750274.jpg', '1629750274.jpg', 6859, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(30, 'bL4UEnjM8cF6ZhvGHB19', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'NoCDRMowFlelV4crZLuL', 'https://www.reestoc.com/sub_product_images/1629750275.jpg', '1629750275.jpg', 7380, '2021-08-23 21:24:33', '2021-08-23 21:24:33', 1),
(31, 'ule2OjhJgfrlRuIm7nHV', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'K9U5133FieONokuFjx5j', 'https://www.reestoc.com/sub_product_images/1629750328.jpg', '1629750328.jpg', 6742, '2021-08-23 21:25:28', '2021-08-23 21:25:28', 1),
(32, 'dkqXuoQBa4TeoLrj6hqM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'fPo1wAt1cqNBacWWXWwh', 'https://www.reestoc.com/sub_product_images/1629750577.jpg', '1629750577.jpg', 6065, '2021-08-23 21:29:37', '2021-08-23 21:29:37', 1),
(33, 'SuQb9JL2xQBU0fdtW17s', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RxKF7kjcwewxdxyfgpVG', 'https://www.reestoc.com/sub_product_images/1629750603.jpg', '1629750603.jpg', 6065, '2021-08-23 21:30:03', '2021-08-23 21:30:03', 1),
(34, 'zKqp9aSgGgU44OSOl6q0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CMfhOfeEBc7prqbh5S1h', 'https://www.reestoc.com/sub_product_images/1629750704.jpeg', '1629750704.jpeg', 61814, '2021-08-23 21:31:44', '2021-08-23 21:31:44', 1),
(35, 'euZXlwIFlpY1JBkE9YtU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '8n79czfWKvTK5mf9SmPP', 'https://www.reestoc.com/sub_product_images/1629750788.jpg', '1629750788.jpg', 6923, '2021-08-23 21:33:08', '2021-08-23 21:33:08', 1),
(36, 'CkFEx6urJzytNmDkLwoE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'yMyB6vU6RhPWz62Txz4j', 'https://www.reestoc.com/sub_product_images/1629750829.jpg', '1629750829.jpg', 6580, '2021-08-23 21:33:49', '2021-08-23 21:33:49', 1),
(37, 't7dIgUR050XOma003Tnd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'https://www.reestoc.com/sub_product_images/1629750908.jpg', '1629750908.jpg', 6096, '2021-08-23 21:35:08', '2021-08-23 21:35:08', 1),
(38, 'nT1pVjnIEesIcVBc4320', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BIGfY7300msTzFiD1Mu3', 'https://www.reestoc.com/sub_product_images/1629750909.jpeg', '1629750909.jpeg', 3265, '2021-08-23 21:35:08', '2021-08-23 21:35:08', 1),
(39, 'ZNuO5QQt8D3xxlHYqytc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'w0dYyhZusHJQ79Nt3O32', 'https://www.reestoc.com/sub_product_images/1629751041.jpg', '1629751041.jpg', 10735, '2021-08-23 21:37:21', '2021-08-23 21:37:21', 1),
(40, 'uvRA8SEpcUIaoJvk8ryT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'PuRy7q00tSddczYjqlKs', 'https://www.reestoc.com/sub_product_images/1629751165.jpeg', '1629751165.jpeg', 5868, '2021-08-23 21:39:25', '2021-08-23 21:39:25', 1),
(41, '2k9JQeLgZyMBirfekW3e', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'roCiLQZYlSEDfynesYyd', 'https://www.reestoc.com/sub_product_images/1629751192.jpg', '1629751192.jpg', 6435, '2021-08-23 21:39:52', '2021-08-23 21:39:52', 1),
(42, '89GYLJP8suu4XZLWDYWi', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4xw7vU8hAWZmxkqRSpXp', 'https://www.reestoc.com/sub_product_images/1629751735.jpg', '1629751735.jpg', 7649, '2021-08-23 21:48:55', '2021-08-23 21:48:55', 1),
(43, '0qzPHdj1fSs2ifzF4MvI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'pLmFhuwLB0qyx3Np2M1l', 'https://www.reestoc.com/sub_product_images/1629751855.jpeg', '1629751855.jpeg', 73247, '2021-08-23 21:50:55', '2021-08-23 21:50:55', 1),
(44, '2twHlZ58SyrHr2MJdGZf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'YpFVXTZamfSmtjomZvqw', 'https://www.reestoc.com/sub_product_images/1629751884.jpg', '1629751884.jpg', 47277, '2021-08-23 21:51:24', '2021-08-23 21:51:24', 1),
(45, 'F78YOmbsVZD7SCHG41Xf', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6u5SAmZ6EB2cEJXWvhdl', 'https://www.reestoc.com/sub_product_images/1629751913.jpeg', '1629751913.jpeg', 12761, '2021-08-23 21:51:53', '2021-08-23 21:51:53', 1),
(46, 'J7wOosmIdrBpkeqvgTTs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'https://www.reestoc.com/sub_product_images/1629751974.jpg', '1629751974.jpg', 4362, '2021-08-23 21:52:54', '2021-08-23 21:52:54', 1),
(47, 'HS9Arif5cq86lhVCBgit', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6kEPf5H8FFuIaOXGCqzx', 'https://www.reestoc.com/sub_product_images/1629751975.jpeg', '1629751975.jpeg', 7779, '2021-08-23 21:52:54', '2021-08-23 21:52:54', 1),
(48, 'exF8UIKU9W3dsffo7Tt9', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GPb4qvv0cVkAmtrQYPJ7', 'https://www.reestoc.com/sub_product_images/1629752020.jpg', '1629752020.jpg', 6161, '2021-08-23 21:53:40', '2021-08-23 21:53:40', 1),
(49, 't9DW0LlCJRGSMctK6WdS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'viSSVJKUcdTxD8PiXMXQ', 'https://www.reestoc.com/sub_product_images/1629752097.jpg', '1629752097.jpg', 7042, '2021-08-23 21:54:57', '2021-08-23 21:54:57', 1),
(50, 'Vu5X1uI4PVW1GDpE0xcG', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oWVneQLUU10lOGZlzJNl', 'https://www.reestoc.com/sub_product_images/1629752138.jpg', '1629752138.jpg', 13124, '2021-08-23 21:55:38', '2021-08-23 21:55:38', 1),
(51, 'uOwitXQADn8d1VChMRCK', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '2CgmFpBo8Cn8UMQW7tW8', 'https://www.reestoc.com/sub_product_images/1629752174.jpeg', '1629752174.jpeg', 8438, '2021-08-23 21:56:14', '2021-08-23 21:56:14', 1),
(52, 'xH3IPFTY7r57hrieBtya', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CR2VgdgHnN510w7kPo6t', 'https://www.reestoc.com/sub_product_images/1629752206.jpeg', '1629752206.jpeg', 7997, '2021-08-23 21:56:46', '2021-08-23 21:56:46', 1),
(53, 'shrNFchJTn20moCKSsi3', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z8SQw7F6HzBojlyKcrx7', 'https://www.reestoc.com/sub_product_images/1629831998.jpeg', '1629831998.jpeg', 61731, '2021-08-24 20:06:38', '2021-08-24 20:06:38', 1),
(54, 'wU1JhdX6hBu9xdxJfw27', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'z8SQw7F6HzBojlyKcrx7', 'https://www.reestoc.com/sub_product_images/1629831999.jpg', '1629831999.jpg', 5376, '2021-08-24 20:06:38', '2021-08-24 20:06:38', 1),
(55, 'RrinGviVvweOZJF8kqiZ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oZobMY6DynqEc00brHuF', 'https://www.reestoc.com/sub_product_images/1629832136.jpg', '1629832136.jpg', 32926, '2021-08-24 20:08:56', '2021-08-24 20:08:56', 1),
(56, 'C3BqPm9XUUNnHDBbHgGU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'oZobMY6DynqEc00brHuF', 'https://www.reestoc.com/sub_product_images/1629832137.jpg', '1629832137.jpg', 7763, '2021-08-24 20:08:56', '2021-08-24 20:08:56', 1),
(57, 'agOcmUYXYKxZrD7xVIWr', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'njaQcTQGciA4tXDoDE8Z', 'https://www.reestoc.com/sub_product_images/1629832961.jpeg', '1629832961.jpeg', 24514, '2021-08-24 20:22:41', '2021-08-24 20:22:41', 1),
(58, 'nAIn6U6dBMWsFTz6jCvn', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XPBqbxn5edePf6UNQGhX', 'https://www.reestoc.com/sub_product_images/1629832981.jpeg', '1629832981.jpeg', 24514, '2021-08-24 20:23:01', '2021-08-24 20:23:01', 1),
(59, 'llqd80bYaMFohVgSx5M2', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Qz6NgIp3lhzko1JXkWkr', 'https://www.reestoc.com/sub_product_images/1629833012.jpeg', '1629833012.jpeg', 24514, '2021-08-24 20:23:32', '2021-08-24 20:23:32', 1),
(60, 'yNEyfRni9sUx9aKJDSTS', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4ue2Ou9euLUOkX1QNv3A', 'https://www.reestoc.com/sub_product_images/1629833049.jpg', '1629833049.jpg', 4960, '2021-08-24 20:24:09', '2021-08-24 20:24:09', 1),
(61, 'd2hQjbL7O1RRml9bSDBP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4ue2Ou9euLUOkX1QNv3A', 'https://www.reestoc.com/sub_product_images/1629833050.jpeg', '1629833050.jpeg', 21980, '2021-08-24 20:24:09', '2021-08-24 20:24:09', 1),
(62, '6k666j3N3ch8AJQProC0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ubqX3PhJZHAnd1owZwtF', 'https://www.reestoc.com/sub_product_images/1629833107.jpeg', '1629833107.jpeg', 21980, '2021-08-24 20:25:07', '2021-08-24 20:25:07', 1),
(63, 'ux2hTBCYbD97yVpZek9C', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'ubqX3PhJZHAnd1owZwtF', 'https://www.reestoc.com/sub_product_images/1629833108.jpg', '1629833108.jpg', 4960, '2021-08-24 20:25:07', '2021-08-24 20:25:07', 1),
(64, 'm8NGYL9t1ewIuBxR21bU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'V0r3ceyqIEbrYG9mAKXh', 'https://www.reestoc.com/sub_product_images/1629833149.jpeg', '1629833149.jpeg', 21980, '2021-08-24 20:25:49', '2021-08-24 20:25:49', 1),
(65, 'jnkQNLkxJXY0ZXr2kHAU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'V0r3ceyqIEbrYG9mAKXh', 'https://www.reestoc.com/sub_product_images/1629833150.jpg', '1629833150.jpg', 4960, '2021-08-24 20:25:49', '2021-08-24 20:25:49', 1),
(66, 'MyHVelqY49zLPu29n73x', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GIrLFCYpNPdpg3NxrUbr', 'https://www.reestoc.com/sub_product_images/1629833193.jpg', '1629833193.jpg', 5240, '2021-08-24 20:26:33', '2021-08-24 20:26:33', 1),
(67, 'Q2Qy6Yhb4pTQqdSEyeyH', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '4FbzqICLp5TN4J40kgSp', 'https://www.reestoc.com/sub_product_images/1629833225.jpeg', '1629833225.jpeg', 33104, '2021-08-24 20:27:05', '2021-08-24 20:27:05', 1),
(68, 'flWNQoAhJABPJZFW7qh4', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', 'https://www.reestoc.com/sub_product_images/1629833268.jpg', '1629833268.jpg', 50249, '2021-08-24 20:27:48', '2021-08-24 20:27:48', 1),
(69, 'NnBHKGa1HtJOs606hQ9t', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CPmEWz6ZyQZNUh4oKqNl', 'https://www.reestoc.com/sub_product_images/1629833269.jpeg', '1629833269.jpeg', 37273, '2021-08-24 20:27:48', '2021-08-24 20:27:48', 1),
(70, 'Xcw12rXrU0eJiC9hE2E8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ly0q4AxQ3YLBeX73yuGG', 'https://www.reestoc.com/sub_product_images/1629833287.jpg', '1629833287.jpg', 50249, '2021-08-24 20:28:07', '2021-08-24 20:28:07', 1),
(71, '2YeYYKABdFnSwozsT7ny', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Ly0q4AxQ3YLBeX73yuGG', 'https://www.reestoc.com/sub_product_images/1629833288.jpeg', '1629833288.jpeg', 37273, '2021-08-24 20:28:07', '2021-08-24 20:28:07', 1),
(72, 'S9yZAdnhyFq83FY4U9ec', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', 'https://www.reestoc.com/sub_product_images/1629833382.jpg', '1629833382.jpg', 50249, '2021-08-24 20:29:42', '2021-08-24 20:29:42', 1),
(73, 'rrgi87YgSJHszsFZ925q', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'CsbrUs4NhzHMXJylSQE0', 'https://www.reestoc.com/sub_product_images/1629833384.jpeg', '1629833384.jpeg', 37273, '2021-08-24 20:29:42', '2021-08-24 20:29:42', 1),
(74, 'YAfVGJN11Cc01KOlgtAs', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RiKvkhKmhzd4KYhxKViZ', 'https://www.reestoc.com/sub_product_images/1629834348.jpeg', '1629834348.jpeg', 56215, '2021-08-24 20:45:48', '2021-08-24 20:45:48', 1),
(75, 'v1CtrEYbrp88DtCHJz17', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RiKvkhKmhzd4KYhxKViZ', 'https://www.reestoc.com/sub_product_images/1629834349.jpg', '1629834349.jpg', 7298, '2021-08-24 20:45:48', '2021-08-24 20:45:48', 1),
(76, 'ACK1skVDR9TmJyR3xOw8', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'Fk9scOOt8VNT1wJOSKPL', 'https://www.reestoc.com/sub_product_images/1629834413.jpeg', '1629834413.jpeg', 63498, '2021-08-24 20:46:53', '2021-08-24 20:46:53', 1),
(77, 'mhrqrvYDb1jeJsPVVrFY', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B1LpuPX2quhHKstAsRn7', 'https://www.reestoc.com/sub_product_images/1629835194.jpeg', '1629835194.jpeg', 22823, '2021-08-24 20:59:54', '2021-08-24 20:59:54', 1),
(78, '8l6NPOGZGd1Di3ISyQiJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'B1LpuPX2quhHKstAsRn7', 'https://www.reestoc.com/sub_product_images/1629835195.jpeg', '1629835195.jpeg', 39981, '2021-08-24 20:59:54', '2021-08-24 20:59:54', 1),
(79, 'az5liHGXiiKYRRliuoXw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AjeQr1WU0OgkuIuyGgk7', 'https://www.reestoc.com/sub_product_images/1629835235.jpeg', '1629835235.jpeg', 22823, '2021-08-24 21:00:35', '2021-08-24 21:00:35', 1),
(80, 'L61NogK45E0i3pF4M1OX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'AjeQr1WU0OgkuIuyGgk7', 'https://www.reestoc.com/sub_product_images/1629835236.jpeg', '1629835236.jpeg', 39981, '2021-08-24 21:00:35', '2021-08-24 21:00:35', 1),
(81, 'WoTpf8MrfKaIySoAkVWy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vXflwq2tex9cAUXYY4xq', 'https://www.reestoc.com/sub_product_images/1629835271.jpg', '1629835271.jpg', 7603, '2021-08-24 21:01:11', '2021-08-24 21:01:11', 1),
(82, 'YIZDaBpRxzfrBaQVjTqC', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'vXflwq2tex9cAUXYY4xq', 'https://www.reestoc.com/sub_product_images/1629835272.jpeg', '1629835272.jpeg', 44119, '2021-08-24 21:01:11', '2021-08-24 21:01:11', 1),
(83, 'WXMMGdBhOo5GD7Ki4qfL', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'v2aJaDOC8iHAHYx6Ms0E', 'https://www.reestoc.com/sub_product_images/1629835307.jpg', '1629835307.jpg', 7603, '2021-08-24 21:01:47', '2021-08-24 21:01:47', 1),
(84, 'yLVfgivZcUIbdtj4wSAl', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'v2aJaDOC8iHAHYx6Ms0E', 'https://www.reestoc.com/sub_product_images/1629835308.jpeg', '1629835308.jpeg', 44119, '2021-08-24 21:01:47', '2021-08-24 21:01:47', 1),
(85, 'qXM4RMKrn1VuVf58yxSJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '0Cb5pHO3ceHwxJ6lLFL2', 'https://www.reestoc.com/sub_product_images/1629835555.jpeg', '1629835555.jpeg', 32465, '2021-08-24 21:05:55', '2021-08-24 21:05:55', 1),
(86, 'yKEYVZi96cVQnXqb8JsD', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'n36Ane9qXyVSU0WUUlis', 'https://www.reestoc.com/sub_product_images/1629835587.jpeg', '1629835587.jpeg', 32465, '2021-08-24 21:06:27', '2021-08-24 21:06:27', 1),
(87, 'Tj8q8BSdzyDrdQJgTJ59', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'lf69BgYAqllUwawzMwdE', 'https://www.reestoc.com/sub_product_images/1629835646.jpeg', '1629835646.jpeg', 20173, '2021-08-24 21:07:26', '2021-08-24 21:07:26', 1),
(88, 'g0zSZtvIUrnWXuWSE3kd', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 't0gqOhd5KvXkFBlPi9IE', 'https://www.reestoc.com/sub_product_images/1629835676.jpeg', '1629835676.jpeg', 20173, '2021-08-24 21:07:56', '2021-08-24 21:07:56', 1),
(89, 'a1UkYy83k1ugF0bheavp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MWBDhY9b6l2fsgk2RsxR', 'https://www.reestoc.com/sub_product_images/1629835712.jpg', '1629835712.jpg', 5512, '2021-08-24 21:08:32', '2021-08-24 21:08:32', 1),
(90, 'T1px2OZXDLbxiB63IUSU', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'caH7cuXhgZ1HHftXSQXI', 'https://www.reestoc.com/sub_product_images/1629835771.jpeg', '1629835771.jpeg', 36126, '2021-08-24 21:09:31', '2021-08-24 21:09:31', 1),
(91, 'BPaDrcyOX6eJ8lh0xxTy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '14KmvpIZw2AHDaQdPkjq', 'https://www.reestoc.com/sub_product_images/1629835786.jpeg', '1629835786.jpeg', 36126, '2021-08-24 21:09:46', '2021-08-24 21:09:46', 1),
(92, 'slsRkaSjvSC5eRYxIZmI', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'IH8aF3hjtakJLQcWEkHV', 'https://www.reestoc.com/sub_product_images/1629835822.jpeg', '1629835822.jpeg', 36126, '2021-08-24 21:10:22', '2021-08-24 21:10:22', 1),
(93, 'x1Q0bF85t3cb6wvVLMLw', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'BAkeyPT6bLp1Sa6ZRBwO', 'https://www.reestoc.com/sub_product_images/1629835854.jpg', '1629835854.jpg', 42632, '2021-08-24 21:10:54', '2021-08-24 21:10:54', 1),
(94, 'zD5wBl2B9hWymeVy18pT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'RfQh8qCsKx0iPjVBkDJz', 'https://www.reestoc.com/sub_product_images/1629835875.jpg', '1629835875.jpg', 42632, '2021-08-24 21:11:15', '2021-08-24 21:11:15', 1),
(95, 'frSBvznffkk8hDsuzuuy', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'hnorIWyffhmFwB5RAUVC', 'https://www.reestoc.com/sub_product_images/1629835899.jpg', '1629835899.jpg', 42632, '2021-08-24 21:11:39', '2021-08-24 21:11:39', 1),
(96, '8lpzS355jI5IneXlrlzM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'FK9OMzBpP4UrxPpoq00I', 'https://www.reestoc.com/sub_product_images/1629835940.jpeg', '1629835940.jpeg', 95499, '2021-08-24 21:12:20', '2021-08-24 21:12:20', 1),
(97, 'WNPwCY3Z3983vAX7iwkv', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'buubKOU2qn5LEqwhrvon', 'https://www.reestoc.com/sub_product_images/1629835972.jpeg', '1629835972.jpeg', 95499, '2021-08-24 21:12:52', '2021-08-24 21:12:52', 1),
(98, 'NYT2cmjArXG6LqIrGHK0', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rZUC7rAmM35lTZm6AUei', 'https://www.reestoc.com/sub_product_images/1629836009.jpeg', '1629836009.jpeg', 30144, '2021-08-24 21:13:29', '2021-08-24 21:13:29', 1),
(99, 'Z4S8MI2RD41WlEbCKnJX', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KYQfY8po1sJyAiIJzsmo', 'https://www.reestoc.com/sub_product_images/1629836091.jpeg', '1629836091.jpeg', 19142, '2021-08-24 21:14:51', '2021-08-24 21:14:51', 1),
(100, 'BfQIXzBRBwD5qraJxN8m', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rJ5pWCIox0hJsqqKmrN7', 'https://www.reestoc.com/sub_product_images/1629836132.jpeg', '1629836132.jpeg', 19142, '2021-08-24 21:15:32', '2021-08-24 21:15:32', 1),
(101, 'OdwUj7la6oIKNNxvAHMc', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'rM6UuKxkkzOKYiTGik9K', 'https://www.reestoc.com/sub_product_images/1629836171.jpeg', '1629836171.jpeg', 19142, '2021-08-24 21:16:11', '2021-08-24 21:16:11', 1),
(102, 'FIAxfhyF8msl97Co3YSq', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '6gyD9GjRmk1JxwyfDBC0', 'https://www.reestoc.com/sub_product_images/1629845293.jpg', '1629845293.jpg', 4915, '2021-08-24 23:48:13', '2021-08-24 23:48:13', 1),
(103, 'GWpvlbat3QPzO42abwcm', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'MAI33CYT9cU4uQzhJw5t', 'https://www.reestoc.com/sub_product_images/1629845331.jpeg', '1629845331.jpeg', 27258, '2021-08-24 23:48:51', '2021-08-24 23:48:51', 1),
(104, 'Ooe0m8sd3jiJZ6votkFO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'R8EjUXBd4Ws8rSxqbvnU', 'https://www.reestoc.com/sub_product_images/1629845355.jpeg', '1629845355.jpeg', 27258, '2021-08-24 23:49:15', '2021-08-24 23:49:15', 1),
(105, 'aekHQUMyvKDW1hKYrYDt', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TCV804vAclOg0ABHuDfQ', 'https://www.reestoc.com/sub_product_images/1629845398.jpeg', '1629845398.jpeg', 43316, '2021-08-24 23:49:58', '2021-08-24 23:49:58', 1),
(106, 'N9h5TA4O2k5e9WyULbLp', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TCV804vAclOg0ABHuDfQ', 'https://www.reestoc.com/sub_product_images/1629845399.jpg', '1629845399.jpg', 64362, '2021-08-24 23:49:58', '2021-08-24 23:49:58', 1),
(107, 'sCa5QagF5mWrk8JczoCM', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '20EmDuRA4JkTQYtavkX5', 'https://www.reestoc.com/sub_product_images/1629845419.jpeg', '1629845419.jpeg', 43316, '2021-08-24 23:50:19', '2021-08-24 23:50:19', 1),
(108, 'aw8MriBnjS5mqmLSrgrE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '20EmDuRA4JkTQYtavkX5', 'https://www.reestoc.com/sub_product_images/1629845420.jpg', '1629845420.jpg', 64362, '2021-08-24 23:50:19', '2021-08-24 23:50:19', 1),
(109, '1DSE8PrfsDNMhTHYcMc1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '5t8EMBqYo2zP3Z8fTWQR', 'https://www.reestoc.com/sub_product_images/1629845497.jpg', '1629845497.jpg', 3936, '2021-08-24 23:51:37', '2021-08-24 23:51:37', 1),
(110, 'b7UDi30s5jF2onGebU16', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', '5t8EMBqYo2zP3Z8fTWQR', 'https://www.reestoc.com/sub_product_images/1629845498.jpeg', '1629845498.jpeg', 17929, '2021-08-24 23:51:37', '2021-08-24 23:51:37', 1),
(111, '3SQwvmpwNNjsFgGwkIHz', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KEFFJDzBx3Ovn50qkx2u', 'https://www.reestoc.com/sub_product_images/1629845544.jpeg', '1629845544.jpeg', 27456, '2021-08-24 23:52:24', '2021-08-24 23:52:24', 1),
(112, 'l8r4nb0ztFCuAfOGSFTu', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'iY1QfTt2xGvFzDkdt9eO', 'https://www.reestoc.com/sub_product_images/1629845563.jpeg', '1629845563.jpeg', 24920, '2021-08-24 23:52:43', '2021-08-24 23:52:43', 1),
(113, 'pwGT7VrVezYu1ENySZkP', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'XjxMjQ05J3qJgSazyrGn', 'https://www.reestoc.com/sub_product_images/1629845599.jpeg', '1629845599.jpeg', 32503, '2021-08-24 23:53:19', '2021-08-24 23:53:19', 1),
(114, 'YldLdvC5ASNTDPuAmQId', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'StZD6JMMZcxgjjVZr9dD', 'https://www.reestoc.com/sub_product_images/1629845660.jpeg', '1629845660.jpeg', 43917, '2021-08-24 23:54:20', '2021-08-24 23:54:20', 1),
(115, 'lkXzF19AdBzrGrovskIJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'GKqmoEXM88Tjj1JXk5UP', 'https://www.reestoc.com/sub_product_images/1629845694.jpg', '1629845694.jpg', 27108, '2021-08-24 23:54:54', '2021-08-24 23:54:54', 1),
(116, '2iy4yStIjBcmGtew3Wj1', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'TtJqtqBAHhBW7RAePZR3', 'https://www.reestoc.com/sub_product_images/1629845725.jpeg', '1629845725.jpeg', 35351, '2021-08-24 23:55:25', '2021-08-24 23:55:25', 1),
(117, '9scJ0u1O4SMpAyL7cuyT', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'tNaI6YFU28TVzhpoRbOX', 'https://www.reestoc.com/sub_product_images/1629845763.jpeg', '1629845763.jpeg', 33502, '2021-08-24 23:56:03', '2021-08-24 23:56:03', 1),
(119, 'H3yVvyqrBnfNLliCvGmJ', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'https://www.reestoc.com/sub_product_images/1630684076.jpg', '1630684076.jpg', 36088, '2021-09-03 16:32:26', '2021-09-03 16:47:56', 1),
(120, 'AuoGupxiQqi5p1jzMkHO', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'cgPcth9HTnqGjrCf6VWD', 'https://www.reestoc.com/sub_product_images/1630684607.webp', '1630684607.webp', 155514, '2021-09-03 16:56:47', '2021-09-03 16:56:47', 1),
(121, 'aOcjz7Gxs91e1aiNU6hE', 'dAXn9RrXd61LYHNSgI2T', 'dAXn9RrXd61LYHNSgI2T', 'KZ7yG9bGYZVI1rjxoh40', 'https://www.reestoc.com/sub_product_images/1631053204.webp', '1631053204.webp', 15260, '2021-09-07 23:20:04', '2021-09-07 23:20:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `access` int(2) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `unique_id`, `fullname`, `email`, `phone_number`, `image`, `added_date`, `last_modified`, `access`, `status`) VALUES
(3, 'rzl5nk7rIHDpqMUbHuz9', 'Linda Henry', 'heliry@gmail.com', '08085245458', NULL, '2021-07-05 23:32:15', '2021-09-18 01:27:09', 1, 1),
(4, 'bztRqJ7WTLOC32XmOwws', 'Princess Latifah', 'princesslatifah@gmail.com', '08096545454', NULL, '2021-07-07 11:05:15', '2021-07-07 11:05:15', 1, 1),
(5, 'tPBIE40TyKjOu0A35Joe', 'Princess Latifah JR', 'princesslatifahjr@gmail.com', '08096556689', NULL, '2021-08-07 03:30:35', '2021-09-18 01:26:33', 1, 1),
(8, 'oiH9fzKVpI8jLubuBqUK', 'Emmanuel Nwoye', 'emmanuelnwoye5@gmail.com', '+2348093223317', NULL, '2021-09-18 03:08:44', '2021-09-18 03:08:44', 1, 1),
(9, 'FIx1NsUOzWnIeLp970CQ', 'Jaden Smith', 'jadensmith@gmail.com', '08125697513', NULL, '2021-10-06 16:53:55', '2021-10-06 16:53:55', 1, 1),
(10, 'ejm525FluTiIUQkSTNqK', 'Willow Smith', 'willowsmith@gmail.com', '07085663314', NULL, '2021-10-06 16:56:51', '2021-10-06 16:56:51', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_addresses`
--

CREATE TABLE `users_addresses` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `additional_information` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `default_status` varchar(10) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_addresses`
--

INSERT INTO `users_addresses` (`id`, `unique_id`, `user_unique_id`, `firstname`, `lastname`, `address`, `additional_information`, `city`, `state`, `country`, `default_status`, `added_date`, `last_modified`, `status`) VALUES
(1, 'tXimAs9kr2QQXiNSgQ0u', 'rzl5nk7rIHDpqMUbHuz9', 'Linda', 'Henry', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State', 'Apartment 23', 'PORTHARCOURT-D/LINE', 'Rivers', 'Nigeria', 'Yes', '2021-09-04 02:43:11', '2021-10-22 02:35:03', 1),
(2, 'P883s1MAimZ5AwGpcBBY', 'rzl5nk7rIHDpqMUbHuz9', 'Linda', 'Henry', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State', 'Apartment 23', '1245', '33', 'NG', 'No', '2021-09-04 02:43:17', '2021-10-22 02:35:03', 0),
(3, 'b6ANNe1xzxQiqq95dkti', 'rzl5nk7rIHDpqMUbHuz9', 'Linda', 'Henry', 'No 4 Okija Street, Diobu, Port Harcourt, RIvers State', 'Apartment 23', '1245', '33', 'NG', 'No', '2021-09-04 02:43:23', '2021-10-22 02:35:03', 0),
(4, 'aWQA5D0ZTmIjpZx2YaFX', 'oiH9fzKVpI8jLubuBqUK', 'Emmanuel', 'Nwoye', 'No 4 Okija Street, Diobu, Port Harcourt, Rivers State', NULL, 'PORTHARCOURT-DIOBU', 'Rivers', 'Nigeria', 'Yes', '2021-09-18 12:06:16', '2021-10-25 02:01:08', 1),
(5, 'uOv2B163479PUnY17790', 'oiH9fzKVpI8jLubuBqUK', 'Emmanuel', 'Nwoye', 'No 34 Emmanuel Drive, Fimena junction, Abuloma, Port Harcourt, Rivers State', 'Third apartment by your right', 'PORTHARCOURT-ABULOMA', 'Rivers', 'Nigeria', 'No', '2021-10-20 10:16:30', '2021-10-25 02:01:08', 1),
(6, 'PafUjs1NKiAf0WkZnhrV', 'oiH9fzKVpI8jLubuBqUK', 'Elvis', 'Puinaro', 'No 8 Mboushimini, Port Harcourt, Rivers State', NULL, 'PORTHARCOURT-MUGBUOSIMINI', 'Rivers', 'Nigeria', 'No', '2021-10-20 11:57:29', '2021-10-25 02:01:08', 0),
(7, 'LUqlkQTGC9Xy8QdPwZpR', 'rzl5nk7rIHDpqMUbHuz9', 'Linda', 'Henry', '56 agip road, port harcourt, rivers state', NULL, 'PORTHARCOURT-AGIP', 'Rivers', 'Nigeria', 'No', '2021-10-22 02:34:09', '2021-10-22 02:35:03', 1),
(8, '6J1sArWukCWiuNBNGNnp', 'oiH9fzKVpI8jLubuBqUK', 'Emmanuel', 'Aneku', 'No 8 Mboushimini, Port Harcourt, Rivers State.', NULL, 'PORTHARCOURT-MUGBUOSIMINI', 'Rivers', 'Nigeria', 'No', '2021-10-22 03:06:50', '2021-10-25 02:01:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_kyc`
--

CREATE TABLE `users_kyc` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) NOT NULL,
  `type` varchar(30) NOT NULL,
  `code` varchar(30) DEFAULT NULL,
  `front_image` varchar(50) NOT NULL,
  `back_image` varchar(50) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `approval` varchar(20) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `view_history`
--

CREATE TABLE `view_history` (
  `id` bigint(20) NOT NULL,
  `unique_id` varchar(20) NOT NULL,
  `user_unique_id` varchar(20) DEFAULT NULL,
  `product_unique_id` varchar(20) DEFAULT NULL,
  `added_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `view_history`
--

INSERT INTO `view_history` (`id`, `unique_id`, `user_unique_id`, `product_unique_id`, `added_date`, `last_modified`, `status`) VALUES
(1, 'FtJXKX6eB7RlAFrr9W92', 'bztRqJ7WTLOC32XmOwws', 'bQ1oAE3LFsPg3eOUG7yZ', '2021-07-06 00:00:00', '2021-07-10 20:22:31', 1),
(2, 'NF5KGIrAC41CRKwy0ZLM', 'rzl5nk7rIHDpqMUbHuz9', '4FbzqICLp5TN4J40kgSp', '2021-07-07 00:00:00', '2021-07-10 20:22:53', 1),
(5, 'ok2spFqP6k0dLhYBx8Oq', 'bztRqJ7WTLOC32XmOwws', 'BIGfY7300msTzFiD1Mu3', '2021-07-10 20:27:07', '2021-07-10 20:27:07', 1),
(6, 'QrEUjC0mPpTS4MY0Jlo5', 'Anonymous', 'mn7uFgo9HyoUi0G13mCs', '2021-07-10 20:38:36', '2021-07-10 20:38:36', 1),
(7, 'kJ6229hKo3rgH6cNR7bD', 'rzl5nk7rIHDpqMUbHuz9', 'K9U5133FieONokuFjx5j', '2021-07-10 20:40:06', '2021-07-10 20:40:06', 1),
(9, 'IgZS7U37lNPHOZVjTeRS', 'rzl5nk7rIHDpqMUbHuz9', '4xw7vU8hAWZmxkqRSpXp', '2021-07-10 21:35:12', '2021-07-10 21:35:12', 1),
(16, 'h7phUJ1GniRxMKRxK4Fi', 'oiH9fzKVpI8jLubuBqUK', 'BIGfY7300msTzFiD1Mu3', '2021-10-20 15:58:08', '2021-10-20 15:58:08', 1),
(17, 'DkaoXudUIYxdTlYsBFqs', 'oiH9fzKVpI8jLubuBqUK', 'NoCDRMowFlelV4crZLuL', '2021-10-20 15:58:19', '2021-10-20 15:58:19', 1),
(18, 'f9xOeXhRs3YdiIKNyKY3', 'oiH9fzKVpI8jLubuBqUK', 'bQ1oAE3LFsPg3eOUG7yZ', '2021-10-20 15:58:24', '2021-10-20 15:58:24', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `agents_addresses`
--
ALTER TABLE `agents_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `agents_kyc`
--
ALTER TABLE `agents_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `blog_images`
--
ALTER TABLE `blog_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `brand_images`
--
ALTER TABLE `brand_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `category_images`
--
ALTER TABLE `category_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `coupon_history`
--
ALTER TABLE `coupon_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `default_pickup_locations`
--
ALTER TABLE `default_pickup_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`unique_id`);

--
-- Indexes for table `flash_deals`
--
ALTER TABLE `flash_deals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `management`
--
ALTER TABLE `management`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `management_addresses`
--
ALTER TABLE `management_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `management_kyc`
--
ALTER TABLE `management_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `management_navigation`
--
ALTER TABLE `management_navigation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `mini_category`
--
ALTER TABLE `mini_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `mini_category_images`
--
ALTER TABLE `mini_category_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `offered_services`
--
ALTER TABLE `offered_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `offered_services_category`
--
ALTER TABLE `offered_services_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `orders_completed`
--
ALTER TABLE `orders_completed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `order_services`
--
ALTER TABLE `order_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `pickup_locations`
--
ALTER TABLE `pickup_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `pop_up_deals`
--
ALTER TABLE `pop_up_deals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `review_history`
--
ALTER TABLE `review_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `review_ratings`
--
ALTER TABLE `review_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `riders_addresses`
--
ALTER TABLE `riders_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `riders_kyc`
--
ALTER TABLE `riders_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sharing_history`
--
ALTER TABLE `sharing_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sharing_images`
--
ALTER TABLE `sharing_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sharing_items`
--
ALTER TABLE `sharing_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sharing_shipping_fees`
--
ALTER TABLE `sharing_shipping_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sharing_users`
--
ALTER TABLE `sharing_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD UNIQUE KEY `shipment_id` (`shipment_unique_id`);

--
-- Indexes for table `shipping_fees`
--
ALTER TABLE `shipping_fees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `store_images`
--
ALTER TABLE `store_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `store_users`
--
ALTER TABLE `store_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sub_category_images`
--
ALTER TABLE `sub_category_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sub_products`
--
ALTER TABLE `sub_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `sub_product_images`
--
ALTER TABLE `sub_product_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `users_addresses`
--
ALTER TABLE `users_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `users_kyc`
--
ALTER TABLE `users_kyc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `view_history`
--
ALTER TABLE `view_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `agents_addresses`
--
ALTER TABLE `agents_addresses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `agents_kyc`
--
ALTER TABLE `agents_kyc`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_images`
--
ALTER TABLE `blog_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brand_images`
--
ALTER TABLE `brand_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category_images`
--
ALTER TABLE `category_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `coupon_history`
--
ALTER TABLE `coupon_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `default_pickup_locations`
--
ALTER TABLE `default_pickup_locations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `flash_deals`
--
ALTER TABLE `flash_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `management`
--
ALTER TABLE `management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `management_addresses`
--
ALTER TABLE `management_addresses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `management_kyc`
--
ALTER TABLE `management_kyc`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `management_navigation`
--
ALTER TABLE `management_navigation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `mini_category`
--
ALTER TABLE `mini_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `mini_category_images`
--
ALTER TABLE `mini_category_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;

--
-- AUTO_INCREMENT for table `offered_services`
--
ALTER TABLE `offered_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `offered_services_category`
--
ALTER TABLE `offered_services_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders_completed`
--
ALTER TABLE `orders_completed`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_coupons`
--
ALTER TABLE `order_coupons`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `order_services`
--
ALTER TABLE `order_services`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pickup_locations`
--
ALTER TABLE `pickup_locations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pop_up_deals`
--
ALTER TABLE `pop_up_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `review_history`
--
ALTER TABLE `review_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review_ratings`
--
ALTER TABLE `review_ratings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `riders_addresses`
--
ALTER TABLE `riders_addresses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riders_kyc`
--
ALTER TABLE `riders_kyc`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sharing_history`
--
ALTER TABLE `sharing_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sharing_images`
--
ALTER TABLE `sharing_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sharing_items`
--
ALTER TABLE `sharing_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sharing_shipping_fees`
--
ALTER TABLE `sharing_shipping_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `sharing_users`
--
ALTER TABLE `sharing_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipping_fees`
--
ALTER TABLE `shipping_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store_images`
--
ALTER TABLE `store_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_users`
--
ALTER TABLE `store_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sub_category_images`
--
ALTER TABLE `sub_category_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sub_products`
--
ALTER TABLE `sub_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `sub_product_images`
--
ALTER TABLE `sub_product_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users_addresses`
--
ALTER TABLE `users_addresses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users_kyc`
--
ALTER TABLE `users_kyc`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `view_history`
--
ALTER TABLE `view_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
