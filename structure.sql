-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 22, 2013 at 04:17 PM
-- Server version: 5.1.67
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yasyf_gradclothes`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `article` varchar(100) NOT NULL,
  `cost` varchar(100) NOT NULL,
  `sizes` varchar(100) NOT NULL,
  `colours` varchar(100) NOT NULL,
  `img` varchar(100) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `article` (`article`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `userid` varchar(100) NOT NULL,
  `article` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `colour` varchar(100) NOT NULL,
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `text` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=180 ;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `song` varchar(100) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `video` varchar(200) DEFAULT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'fast',
  PRIMARY KEY (`id`),
  UNIQUE KEY `song` (`song`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Table structure for table `speakers`
--

CREATE TABLE IF NOT EXISTS `speakers` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `userid` int(100) NOT NULL,
  `itemid` int(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=373 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
