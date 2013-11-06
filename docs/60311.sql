-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2013 at 12:41 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `60311`
--
CREATE DATABASE IF NOT EXISTS `60311` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `60311`;

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE IF NOT EXISTS `authentication` (
  `Name` varchar(40) COLLATE ascii_bin NOT NULL,
  `Token` char(40) COLLATE ascii_bin NOT NULL,
  KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COLLATE=ascii_bin;

--
-- Dumping data for table `authentication`
--

INSERT INTO `authentication` (`Name`, `Token`) VALUES
('khoda', '0a12fb50b010b3d199d4441d44a711732f3a52c0'),
('taphim', '3a59281052497e44e72990e5728c4e8f239ae032');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(64) COLLATE ascii_bin NOT NULL,
  `SellerID` int(11) NOT NULL,
  `Author` varchar(40) COLLATE ascii_bin NOT NULL,
  `Price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Subject` varchar(40) COLLATE ascii_bin NOT NULL DEFAULT '',
  `Description` varchar(640) COLLATE ascii_bin NOT NULL,
  `isbn` varchar(15) COLLATE ascii_bin DEFAULT NULL,
  `image_url` varchar(150) COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Secondary` (`Timestamp`,`Title`,`SellerID`),
  KEY `SellerID` (`SellerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii COLLATE=ascii_bin AUTO_INCREMENT=7 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`ID`, `Timestamp`, `Title`, `SellerID`, `Author`, `Price`, `Subject`, `Description`, `isbn`, `image_url`) VALUES
(1, '2013-11-03 17:47:21', 'PHP Advanced for the World Wide Web', 8, 'Larry Ullman', '0.00', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', NULL, 'http://d2o0t5hpnwv4c1.cloudfront.net/116_50books/16.jpg'),
(2, '2013-11-03 08:16:21', 'Java: How to Program', 4, 'Harvey M. Deitel, Paul J. Deitel', '20.00', 'Computer Science', '', NULL, 'http://www.informit.com/ShowCover.aspx?isbn=0131486608'),
(3, '2013-11-03 08:17:28', 'Java: How to Program', 1, 'Harvey M. Deitel, Paul J. Deitel', '15.00', 'Computer Science', 'Praesent at molestie lectus. Pellentesque facilisis auctor consequat. Vestibulum eget odio quis magna placerat tempus. Mauris sed eros sollicitudin tellus bibendum lacinia facilisis id erat.', NULL, NULL),
(4, '2013-11-03 08:18:37', 'Java: How to Program', 2, 'Harvey M. Deitel, Paul J. Deitel', '22.00', 'Computer Science', '', NULL, NULL),
(5, '2013-11-04 12:18:37', 'Java: How to Program', 8, 'Harvey M. Deitel, Paul J. Deitel', '22.00', 'Computer Science', 'Praesent at molestie lectus. Pellentesque facilisis auctor consequat. Vestibulum eget odio quis magna placerat tempus. Mauris sed eros sollicitudin tellus bibendum lacinia facilisis id erat. Nulla commodo mi ipsum, a vehicula libero suscipit posuere. Duis sit amet massa dolor. Integer laoreet tempus aliquam. Nullam in accumsan nisl. Maecenas auctor mattis consequat. Integer ut ante vehicula, gravida dui at, iaculis purus. Donec ornare lectus vitae nunc molestie aliquet.', '1234567891234', 'http://www.computersciencelab.com/Images/cppHTP4_large.jpg'),
(6, '2013-11-04 13:18:37', 'Digital Logic Design', 8, 'Brian Holdsworth', '45.00', 'Computer Science', 'Nulla commodo dignissim justo, et convallis magna aliquet eu. Pellentesque sollicitudin cursus turpis non viverra.', '9780750605014', 'http://www.ebook3000.com/upimg/201103/17/203332189.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Level` int(1) NOT NULL DEFAULT '0',
  `Name` varchar(40) COLLATE ascii_bin NOT NULL,
  `Password` char(64) COLLATE ascii_bin NOT NULL,
  `Email` varchar(40) COLLATE ascii_bin NOT NULL,
  `PostCode` char(6) COLLATE ascii_bin NOT NULL,
  `Verified` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii COLLATE=ascii_bin AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Level`, `Name`, `Password`, `Email`, `PostCode`, `Verified`) VALUES
(1, 1, 'admin', 'cb4522fe1cbc18c4e7973cfa12cbb4eb0a6158b37e824d2492d3518cafcdcf67', 'admin@email.ca', 'N9B3P4', 1),
(2, 0, 'verified', 'cb4522fe1cbc18c4e7973cfa12cbb4eb0a6158b37e824d2492d3518cafcdcf67', 'verified@email.ca', 'N9B3P4', 1),
(3, 0, 'unverified', 'cb4522fe1cbc18c4e7973cfa12cbb4eb0a6158b37e824d2492d3518cafcdcf67', 'unverified@email.ca', 'N9B3P4', 0),
(4, 0, 'parekh', 'kishan123', 'parekh@uwindsor.ca', 'N9C1A5', 1),
(8, 0, 'taphim', '5ba865a81da464fc117519b19a6c2ada3f83656e847aa9c510bea9d936408141', 'taphim@hotmail.ca', 'N9C1G8', 1),
(9, 0, 'khoda', '5bc26fca465aad64fda17c0c68fdf01c8b575db9fc3448d9167a2e764d4ace68', 'khoda4@hotmail.com', 'N9C3H4', 1),
(10, 0, 'taphim3', 'db67a247809f9b986158f0d17aa5aafe6eeb67a74426946def1a5f99a3d65308', 'taphim@gmail.com', 'g4gg4g', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authentication`
--
ALTER TABLE `authentication`
  ADD CONSTRAINT `authentication_ibfk_2` FOREIGN KEY (`Name`) REFERENCES `users` (`Name`) ON UPDATE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `users` (`ID`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
