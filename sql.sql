SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `helper_customs` (
  `type` varchar(50) NOT NULL,
  `label` varchar(60) NOT NULL,
  `values` tinytext NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `helper_files` (
  `id` int(100) NOT NULL,
  `thread_num` int(5) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `type` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `helper_threads` (
  `ticket_id` int(255) NOT NULL,
  `number` int(5) NOT NULL,
  `message` tinytext NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  `time` int(25) NOT NULL,
  `last_change` int(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `helper_ticket` (
  `id` int(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `urgency` int(1) NOT NULL DEFAULT '3',
  `name` varchar(155) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(155) NOT NULL,
  `custom` tinytext NOT NULL,
  `time` int(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=latin1;


ALTER TABLE `helper_customs`
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `helper_files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `helper_threads`
  ADD PRIMARY KEY (`number`);

ALTER TABLE `helper_ticket`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `helper_files`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `helper_threads`
  MODIFY `number` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE `helper_ticket`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10000;