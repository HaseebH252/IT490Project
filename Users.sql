-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 27, 2019 at 03:41 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Users`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--
CREATE TABLE `accounts` (
  `Fname` varchar(32) NOT NULL,
  `Lname` varchar(32) NOT NULL,
  `Email` varchar(320) NOT NULL,
  `Pass` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`Fname`, `Lname`, `Email`, `Pass`) VALUES
('Haseeb', 'Hussain', 'hh262@njit.edu', '$2y$10$/LxI87KLNjxT5ieVZKAxXuytu0Kq1zfGepzgNfaIpes1FPh/Vlr4K'),
('Test', 'Account', 'test@test.com', '$2y$10$cwo4Z3QjfzFDvyDFGkvk0epU044mt11OrhPZAoitkoO6E3l0PGpgW');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `date` datetime NOT NULL,
  `type` varchar(32) NOT NULL,
  `message` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`date`, `type`, `message`) VALUES
('2019-10-25 16:11:45', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n'),
('2019-10-25 16:16:07', 'authenticate', 'Account with email: test@test.com logged in.\n'),
('2019-10-25 16:29:46', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n'),
('2019-10-25 16:32:22', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n'),
('2019-10-25 16:56:14', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n'),
('2019-10-25 18:27:26', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n'),
('2019-10-25 18:33:05', 'authenticate', 'Account with email: hh262@njit.edu logged in.\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`date`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
