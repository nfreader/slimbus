-- Create syntax for TABLE 'art_vote'
CREATE TABLE `art_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `artwork` varchar(32) NOT NULL DEFAULT '',
  `ckey` varchar(32) NOT NULL DEFAULT '',
  `rating` int(1) unsigned NOT NULL DEFAULT 1,
  `server` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `artwork` (`artwork`,`ckey`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3070 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'manifest'
CREATE TABLE `manifest` (
  `round_id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `ckey` varchar(32) DEFAULT NULL,
  `job` varchar(64) DEFAULT NULL,
  `role` varchar(64) DEFAULT NULL,
  `roundstart` tinyint(1) NOT NULL DEFAULT '0',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'name_vote'
CREATE TABLE `name_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `ckey` varchar(32) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `good` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`ckey`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'public_tickets'
CREATE TABLE `public_tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` int(11) unsigned NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT 'Not public, Public, Forced Private',
  `identifier` varchar(16) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'round_logs'
CREATE TABLE `round_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `round` int(11) NOT NULL,
  `timestamp` datetime(3) DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  `text` mediumtext,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `z` int(11) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create syntax for TABLE 'version'
CREATE TABLE `version` (
  `major` int(11) NOT NULL,
  `minor` int(11) NOT NULL,
  `patch` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `version` (`major`, `minor`, `patch`, `updated`) VALUES 	(4, 0, 0, CURRENT_TIMESTAMP);
