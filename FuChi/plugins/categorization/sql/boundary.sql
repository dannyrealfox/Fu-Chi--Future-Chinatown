-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 14, 2010 at 07:21 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `plugins`
--

-- --------------------------------------------------------

--
-- Table structure for table `boundary`
--

CREATE TABLE `boundary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `boundary_name` varchar(100) NOT NULL COMMENT 'name of the boundary',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `boundary`
--

INSERT INTO `boundary` VALUES(2, 'Westlands');
INSERT INTO `boundary` VALUES(3, 'Kasarani');
INSERT INTO `boundary` VALUES(4, 'Embakasi');
