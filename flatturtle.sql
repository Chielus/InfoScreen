-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 19, 2011 at 05:07 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flatturtle`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `username`, `password`) VALUES
(1, 'John', 'password'),
(2, 'Jane', 'password'),
(3, 'Jessy', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `infoscreens`
--

CREATE TABLE IF NOT EXISTS `infoscreens` (
  `id` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `title` text NOT NULL,
  `motd` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `infoscreens`
--

INSERT INTO `infoscreens` (`id`, `customerid`, `title`, `motd`) VALUES
(1, 1, 'The Amadeus Square', 'The Amadeus Square'),
(2, 1, 'Screen B', 'Yeah!'),
(3, 1, 'Screen C', 'Oh man!?'),
(4, 1, 'Screen D', 'Hellooooo!');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `infoscreenid` int(11) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`infoscreenid`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`infoscreenid`, `key`, `value`) VALUES
(1, 'cycleinterval', '60'),
(1, 'rowstoshow', '10'),
(4, 'rowstoshow', '6'),
(1, 'lang', 'EN'),
(4, 'cycleinterval', '35'),
(4, 'lang', 'DE'),
(1, 'color', '#3366FF'),
(1, 'logo', 'templates/FlatTurtle/img/amadeussquare.png');

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
  `infoscreenid` int(11) NOT NULL,
  `stationid` varchar(25) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`infoscreenid`,`stationid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`infoscreenid`, `stationid`, `type`) VALUES
(1, 'BE.NMBS.008814001', 'NMBS'),
(1, 'BE.NMBS.008814118', 'NMBS'),
(1, 'BE.NMBS.008814373', 'NMBS');
