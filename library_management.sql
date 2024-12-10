-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 08:11 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitylog`
--

CREATE TABLE `activitylog` (
  `LogID` int(11) NOT NULL,
  `Action` varchar(255) DEFAULT NULL,
  `PerformedBy` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `Author` varchar(255) NOT NULL,
  `ISBN` varchar(13) DEFAULT NULL,
  `Publisher` varchar(255) DEFAULT NULL,
  `Edition` varchar(50) DEFAULT NULL,
  `PublicationDate` date DEFAULT NULL,
  `BookTitle` varchar(255) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `Genre` varchar(100) NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `ResourceID`, `Author`, `ISBN`, `Publisher`, `Edition`, `PublicationDate`, `BookTitle`, `Title`, `Genre`, `Status`) VALUES
(5, 7, 'abnoy', '234234', 'asdasda', '2nd', '2022-01-20', NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `borrowedbooks`
--

CREATE TABLE `borrowedbooks` (
  `BorrowID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  `BorrowedDate` datetime NOT NULL,
  `DueDate` datetime NOT NULL,
  `Status` enum('Borrowed','Returned','Overdue') NOT NULL DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `libraryresources`
--

CREATE TABLE `libraryresources` (
  `ResourceID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `AccessionNumber` varchar(20) NOT NULL,
  `Category` enum('Book','Periodical','Media') NOT NULL,
  `AddedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Author` varchar(255) DEFAULT NULL,
  `ISBN` varchar(20) DEFAULT NULL,
  `Publisher` varchar(255) DEFAULT NULL,
  `YearPublished` year(4) DEFAULT NULL,
  `Available` tinyint(1) DEFAULT 1,
  `Status` enum('available','borrowed') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `libraryresources`
--

INSERT INTO `libraryresources` (`ResourceID`, `Title`, `AccessionNumber`, `Category`, `AddedAt`, `Author`, `ISBN`, `Publisher`, `YearPublished`, `Available`, `Status`) VALUES
(1, 'Bossing?', 'B-2024-826', 'Book', '2024-12-09 15:41:37', 'malupiton', '14342342', 'kolokoys', 2021, 1, 'available'),
(5, 'asdasd', '', 'Book', '2024-12-09 16:55:33', 'asdasda', 'adasda', 'adad', 2021, 1, 'available'),
(7, 'haha', 'B-2024-267', 'Book', '2024-12-10 05:59:48', 'dsdfd', 'sdfsdf45423', '5efef', 2021, 1, 'available'),
(13, 'king', 'B-2024-7280', 'Book', '2024-12-10 06:07:14', 't', 'gfgf', 'f', 2011, 1, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `mediaresources`
--

CREATE TABLE `mediaresources` (
  `MediaResourceID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `Format` varchar(50) DEFAULT NULL,
  `Runtime` int(11) DEFAULT NULL,
  `MediaType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `periodicals`
--

CREATE TABLE `periodicals` (
  `PeriodicalID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `ISSN` varchar(8) DEFAULT NULL,
  `Volume` int(11) DEFAULT NULL,
  `Issue` int(11) DEFAULT NULL,
  `PublicationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `BorrowedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `DueDate` date NOT NULL,
  `ReturnedAt` timestamp NULL DEFAULT NULL,
  `Fine` decimal(10,2) DEFAULT 0.00,
  `Status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TransactionID`, `UserID`, `ResourceID`, `BorrowedAt`, `DueDate`, `ReturnedAt`, `Fine`, `Status`) VALUES
(1, 2, 1, '2024-12-08 16:00:00', '2024-12-23', '2024-12-08 16:00:00', '0.00', ''),
(2, 2, 1, '2024-12-09 16:00:00', '2024-12-24', '2024-12-09 16:00:00', '0.00', ''),
(3, 2, 5, '2024-12-09 16:00:00', '2024-12-24', '2024-12-09 16:00:00', '0.00', ''),
(4, 2, 1, '2024-12-09 16:00:00', '2024-12-24', '2024-12-09 16:00:00', '0.00', ''),
(5, 2, 1, '2024-12-09 16:00:00', '2024-12-24', '2024-12-09 16:00:00', '0.00', ''),
(6, 2, 5, '2024-12-09 16:00:00', '2024-12-24', '2024-12-09 16:00:00', '0.00', ''),
(7, 2, 13, '2024-12-09 23:35:26', '2024-12-17', '2024-12-10 06:48:05', '0.00', ''),
(8, 2, 7, '2024-12-09 23:49:55', '2024-12-17', '2024-12-10 06:50:05', '0.00', ''),
(9, 2, 1, '2024-12-09 23:53:44', '2024-12-17', NULL, '0.00', ''),
(10, 2, 5, '2024-12-10 00:04:45', '2024-12-17', NULL, '0.00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `MembershipType` enum('Admin','Student','Faculty','Staff') NOT NULL,
  `MaxBooks` int(11) NOT NULL DEFAULT 0,
  `RegisteredAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Status` enum('Active','Suspended') NOT NULL DEFAULT 'Active',
  `LastLogin` datetime DEFAULT NULL,
  `Active` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `Role` varchar(50) DEFAULT NULL,
  `Username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Name`, `Email`, `Password`, `MembershipType`, `MaxBooks`, `RegisteredAt`, `Status`, `LastLogin`, `Active`, `Role`, `Username`) VALUES
(1, 'aljuninato', 'inatoaljun@gmail.com', '$2y$10$TM2t8hj7hfCJ/HDuZa/MOOfsq8C2o4WsTfZWmm0AoyXbA8GMVUVhG', 'Admin', 0, '2024-12-10 05:23:52', 'Active', NULL, 'Active', 'Admin', 'aljuninato'),
(2, 'kyami', 'kyami@gmail.com', '$2y$10$oo6CrF26KlC7IdMDf4GcmeURk1OSCUYdD5QTWtBOOSi7itml50xbO', 'Student', 3, '2024-12-10 05:25:18', 'Active', NULL, 'Active', 'Student', 'kyami30'),
(3, 'aljun', 'aljun@gmail.com', '$2y$10$..eHb8TtpKK4hfA2tssvQ.XH00Ly6u6lmlurJgw2D7CIjHDQNdF8G', 'Faculty', 5, '2024-12-10 05:25:26', 'Active', NULL, 'Active', 'Faculty', 'aljun');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitylog`
--
ALTER TABLE `activitylog`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `PerformedBy` (`PerformedBy`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`),
  ADD UNIQUE KEY `ISBN` (`ISBN`),
  ADD KEY `ResourceID` (`ResourceID`);

--
-- Indexes for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  ADD PRIMARY KEY (`BorrowID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BookID` (`BookID`);

--
-- Indexes for table `libraryresources`
--
ALTER TABLE `libraryresources`
  ADD PRIMARY KEY (`ResourceID`),
  ADD UNIQUE KEY `AccessionNumber` (`AccessionNumber`),
  ADD UNIQUE KEY `AccessionNumber_2` (`AccessionNumber`),
  ADD UNIQUE KEY `AccessionNumber_3` (`AccessionNumber`);

--
-- Indexes for table `mediaresources`
--
ALTER TABLE `mediaresources`
  ADD PRIMARY KEY (`MediaResourceID`),
  ADD KEY `ResourceID` (`ResourceID`);

--
-- Indexes for table `periodicals`
--
ALTER TABLE `periodicals`
  ADD PRIMARY KEY (`PeriodicalID`),
  ADD UNIQUE KEY `ISSN` (`ISSN`),
  ADD KEY `ResourceID` (`ResourceID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ResourceID` (`ResourceID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activitylog`
--
ALTER TABLE `activitylog`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  MODIFY `BorrowID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `libraryresources`
--
ALTER TABLE `libraryresources`
  MODIFY `ResourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `mediaresources`
--
ALTER TABLE `mediaresources`
  MODIFY `MediaResourceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periodicals`
--
ALTER TABLE `periodicals`
  MODIFY `PeriodicalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitylog`
--
ALTER TABLE `activitylog`
  ADD CONSTRAINT `activitylog_ibfk_1` FOREIGN KEY (`PerformedBy`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`ResourceID`) REFERENCES `libraryresources` (`ResourceID`) ON DELETE CASCADE;

--
-- Constraints for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  ADD CONSTRAINT `borrowedbooks_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowedbooks_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `books` (`BookID`) ON DELETE CASCADE;

--
-- Constraints for table `mediaresources`
--
ALTER TABLE `mediaresources`
  ADD CONSTRAINT `mediaresources_ibfk_1` FOREIGN KEY (`ResourceID`) REFERENCES `libraryresources` (`ResourceID`) ON DELETE CASCADE;

--
-- Constraints for table `periodicals`
--
ALTER TABLE `periodicals`
  ADD CONSTRAINT `periodicals_ibfk_1` FOREIGN KEY (`ResourceID`) REFERENCES `libraryresources` (`ResourceID`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`ResourceID`) REFERENCES `libraryresources` (`ResourceID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
