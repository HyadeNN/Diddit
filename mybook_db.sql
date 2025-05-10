-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2021 at 08:14 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linkedin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `content_i_follow`
--

CREATE TABLE `content_i_follow` (
                                    `id` bigint(20) NOT NULL,
                                    `userid` bigint(20) NOT NULL,
                                    `contentid` bigint(20) NOT NULL,
                                    `content_type` varchar(10) NOT NULL,
                                    `disabled` tinyint(1) NOT NULL DEFAULT '0',
                                    `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `endorsements`
--

CREATE TABLE `endorsements` (
                                `id` bigint(20) NOT NULL,
                                `type` varchar(10) NOT NULL,
                                `endorsements` text NOT NULL,
                                `contentid` bigint(20) NOT NULL,
                                `connections` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
                                 `id` bigint(20) NOT NULL,
                                 `userid` bigint(20) NOT NULL,
                                 `activity` varchar(20) NOT NULL,
                                 `contentid` bigint(20) NOT NULL,
                                 `content_owner` bigint(20) NOT NULL,
                                 `content_type` varchar(10) NOT NULL,
                                 `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `notification_seen`
--

CREATE TABLE `notification_seen` (
                                     `id` bigint(20) NOT NULL,
                                     `userid` bigint(20) NOT NULL,
                                     `notification_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
                         `id` bigint(20) NOT NULL,
                         `postid` bigint(20) NOT NULL,
                         `post` text NOT NULL,
                         `image` varchar(500) NOT NULL,
                         `has_image` tinyint(1) NOT NULL,
                         `is_profile_image` tinyint(1) NOT NULL,
                         `is_cover_image` tinyint(1) NOT NULL,
                         `parent` bigint(20) NOT NULL,
                         `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `userid` bigint(20) NOT NULL,
                         `endorsements` int(11) NOT NULL,
                         `comments` int(11) NOT NULL,
                         `tags` varchar(2048) NOT NULL,
                         `post_type` varchar(20) NOT NULL DEFAULT 'standard'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                         `id` bigint(20) NOT NULL,
                         `userid` bigint(20) NOT NULL,
                         `first_name` varchar(50) NOT NULL,
                         `last_name` varchar(50) NOT NULL,
                         `gender` varchar(6) NOT NULL,
                         `profile_image` varchar(500) NOT NULL,
                         `cover_image` varchar(500) NOT NULL,
                         `date` year(4) NOT NULL,
                         `online` int(11) NOT NULL,
                         `email` varchar(100) NOT NULL,
                         `password` varchar(64) NOT NULL,
                         `url_address` varchar(100) NOT NULL,
                         `endorsements` int(11) NOT NULL,
                         `about` text NOT NULL,
                         `tag_name` varchar(20) NOT NULL,
                         `headline` varchar(100) NOT NULL,
                         `company` varchar(100) NOT NULL,
                         `job_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
                               `id` bigint(20) NOT NULL,
                               `userid` bigint(20) NOT NULL,
                               `company` varchar(100) NOT NULL,
                               `position` varchar(100) NOT NULL,
                               `description` text NOT NULL,
                               `from_date` date NOT NULL,
                               `to_date` date DEFAULT NULL,
                               `current_job` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
                             `id` bigint(20) NOT NULL,
                             `userid` bigint(20) NOT NULL,
                             `school` varchar(100) NOT NULL,
                             `degree` varchar(100) NOT NULL,
                             `field` varchar(100) NOT NULL,
                             `from_date` date NOT NULL,
                             `to_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
                          `id` bigint(20) NOT NULL,
                          `userid` bigint(20) NOT NULL,
                          `skill` varchar(100) NOT NULL,
                          `endorsements` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `likes` text NOT NULL,
  `contentid` bigint(20) NOT NULL,
  `following` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `content_i_follow`
--
ALTER TABLE `content_i_follow`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `contentid` (`contentid`),
  ADD KEY `disabled` (`disabled`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `endorsements`
--
ALTER TABLE `endorsements`
    ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `contentid` (`contentid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `contentid` (`contentid`),
  ADD KEY `content_owner` (`content_owner`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `notification_seen`
--
ALTER TABLE `notification_seen`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `notification_id` (`notification_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
    ADD PRIMARY KEY (`id`),
  ADD KEY `postid` (`postid`),
  ADD KEY `date` (`date`),
  ADD KEY `parent` (`parent`),
  ADD KEY `userid` (`userid`),
  ADD KEY `endorsements` (`endorsements`),
  ADD KEY `comments` (`comments`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`),
  ADD KEY `date` (`date`),
  ADD KEY `online` (`online`),
  ADD KEY `email` (`email`),
  ADD KEY `url_address` (`url_address`),
  ADD KEY `endorsements` (`endorsements`),
  ADD KEY `tag_name` (`tag_name`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
    ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content_i_follow`
--
ALTER TABLE `content_i_follow`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `endorsements`
--
ALTER TABLE `endorsements`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_seen`
--
ALTER TABLE `notification_seen`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;