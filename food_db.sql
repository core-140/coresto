-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 04:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartid` int(11) NOT NULL,
  `foodid` int(100) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `session_id` varchar(255) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customerid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerid`, `name`, `phone`) VALUES
(1, 'joshua', '1234567890'),
(2, 'joshua', '1234567899'),
(3, 'joh', 'hjhjhjjojpok'),
(4, 'hgkgak', '1234567890'),
(5, 'rgdhfghkn', '1234567890'),
(6, 'josh', '1234567890'),
(7, 'joh', '1234567899'),
(8, 'fufu', '1234567890'),
(9, 'joshs', '1234567898'),
(10, 'joel', '2345678911'),
(11, 'josh', '1234567890'),
(12, 'joel', '1123456789'),
(13, 'ggggg', '1234567890'),
(14, 'joel', '1234567890'),
(15, 'iugfiugwef', '1234567890'),
(16, 'joshss', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackid` int(11) NOT NULL,
  `foodid` int(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `review` int(11) DEFAULT NULL,
  `review_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackid`, `foodid`, `email`, `review`, `review_text`) VALUES
(1, 1, 'absdf@gmail.com', NULL, 'good'),
(2, 1, 'asscv@gmail.com', NULL, 'very good recommemnded starter');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodid` int(100) NOT NULL,
  `foodname` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodid`, `foodname`, `price`, `category`, `description`, `image`, `rating`) VALUES
(1, 'Garlic Bread', 120, 'starter', 'Toasted bread with garlic butter', 'garlic_bread.png', 4.5),
(2, 'Chicken Soup', 150, 'starter', 'Warm chicken broth with herbs', 'chicken_soup.png', 4.3),
(3, 'French Fries', 100, 'starter', 'Crispy golden potato fries', 'fries.png', 4.6),
(4, 'Margherita Pizza', 350, 'main course', 'Classic cheese and tomato pizza', 'margherita_pizza.png', 4.8),
(5, 'Veg Biryani', 250, 'main course', 'Aromatic rice with vegetables', 'veg_biryani.png', 4.4),
(6, 'Grilled Chicken', 400, 'main course', 'Juicy grilled chicken with spices', 'grilled_chicken.png', 4.7),
(7, 'Chocolate Cake', 180, 'dessert', 'Moist chocolate sponge with frosting', 'chocolate_cake.png', 4.9),
(8, 'Vanilla Ice Cream', 100, 'dessert', 'Creamy vanilla ice cream scoop', 'vanilla_icecream.png', 4.5),
(9, 'Gulab Jamun', 120, 'dessert', 'Soft fried balls soaked in sugar syrup', 'gulab_jamun.png', 4.8),
(10, 'Cold Coffee', 150, 'drinks', 'Chilled coffee with cream', 'cold_coffee.png', 4.6),
(11, 'Fresh Lime Juice', 90, 'drinks', 'Refreshing lime juice with mint', 'lime_juice.png\r\n', 4.4),
(12, 'Mango Smoothie', 170, 'drinks', 'Thick mango blend with milk', 'mango_smoothie.png', 4.7),
(13, 'Bubble Tea', 140, 'drinks', 'ice filled with coco nuts', 'bubble_tea.png', 4.5);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`) VALUES
(1, 'core', 'iqoo10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(11) NOT NULL,
  `foodid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `tableno` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `foodid`, `quantity`, `tableno`, `email`, `status`) VALUES
(35, 1, 1, 333, '', 'Pending'),
(36, 4, 1, 333, '', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentid`, `orderid`, `amount`) VALUES
(1, 1, 1070.00),
(2, 6, 270.00),
(3, 8, 740.00),
(4, 12, 810.00),
(5, 17, 120.00),
(6, 18, 120.00),
(7, 19, 120.00),
(8, 20, 120.00),
(9, 21, 270.00),
(10, 23, 100.00),
(11, 24, 270.00),
(12, 26, 600.00),
(13, 28, 120.00),
(14, 29, 950.00),
(15, 34, 120.00),
(16, 35, 470.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartid`),
  ADD KEY `foodid` (`foodid`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customerid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackid`),
  ADD KEY `foodid` (`foodid`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodid`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`foodid`) REFERENCES `food` (`foodid`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`foodid`) REFERENCES `food` (`foodid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
