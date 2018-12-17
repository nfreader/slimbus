##2.0 - 17-12-2018
Adds a new table to track voting on character names.

```CREATE TABLE `name_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `ckey` varchar(32) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `good` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`ckey`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;```

```UPDATE `version` SET `major` = '2', `minor` = '0' WHERE `major` = '1' AND `minor` = '6' AND `patch` = '0' AND `updated` = '2018-11-16 16:13:55' LIMIT 1;```

##1.6 - 16-11-2018
The following updates to the `manifest` table should be applied. These changes will allow character names, jobs, and special roles that are longer than 32 characters.

```ALTER TABLE `manifest` CHANGE `name` `name` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;```

```ALTER TABLE `manifest` CHANGE `job` `job` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;```

```ALTER TABLE `manifest` CHANGE `role` `role` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;```

```UPDATE `version` SET `minor` = '6' WHERE `major` = '1' AND `minor` = '5' AND `patch` = '0' AND `updated` = '2018-10-16 20:44:53' LIMIT 1;
```

##1.5 - 16-10-2018

```ALTER TABLE `round_logs` CHANGE `area` `area` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;```

```UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '5' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;```

##1.4 - 16-10-2018

```ALTER TABLE `round_logs` ADD `area` VARCHAR(32)  NULL  DEFAULT NULL  AFTER `added`;```

```UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '4' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;```

##1.3 - 16-10-2018
```DROP TABLE `round_logs`;```

```CREATE TABLE `round_logs` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `round` int(11) NOT NULL,   `timestamp` DATETIME(3) DEFAULT NULL,   `type` varchar(16) DEFAULT NULL,   `text` mediumtext,   `x` int(11) DEFAULT NULL,   `y` int(11) DEFAULT NULL,   `z` int(11) DEFAULT NULL,   `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;```

```UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '3' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;```


##1.2 - 11-5-2018

```ALTER TABLE `version` CHANGE `updated` `updated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP;```

```TRUNCATE `round_logs`;```  

```ALTER TABLE `round_logs` AUTO_INCREMENT = 1;```


```ALTER TABLE `round_logs` ADD `date` INT  NULL  DEFAULT NULL  AFTER `added`;```  

```ALTER TABLE `round_logs` CHANGE `date` `date` DATE  NULL;```  

```ALTER TABLE `round_logs` MODIFY COLUMN `date` DATE DEFAULT NULL AFTER `round`;```  

```ALTER TABLE `round_logs` CHANGE `timestamp` `time` TIME  NULL  DEFAULT NULL;```  

```ALTER TABLE `round_logs` CHANGE `time` `time` TIME(3)  NULL;```  

```ALTER TABLE `round_logs` AUTO_INCREMENT = 1;```  

```ALTER TABLE `round_logs` DROP `map`;
```

```UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '2' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;```


##1.1 - 19-1-2018
```/* 1:17:55 PM Vagrant statbus */ ALTER TABLE `tracked_rounds` CHANGE `added` `added` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP;```


```/* 1:18:02 PM Vagrant statbus */ ALTER TABLE `tracked_rounds` CHANGE `added` `added` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP;```


```/* 1:18:17 PM Vagrant statbus */ ALTER TABLE `tracked_rounds` ADD `regenerated` TIMESTAMP  NULL  AFTER `added`;```


```/* 1:18:20 PM Vagrant statbus */ ALTER TABLE `tracked_rounds` CHANGE `regenerated` `regenerated` TIMESTAMP  NULL  DEFAULT NULL  ON UPDATE CURRENT_TIMESTAMP;```


```/* 1:22:48 PM Vagrant statbus */ ALTER TABLE `tracked_rounds` CHANGE `regenerated` `regenerated` TIMESTAMP  NOT NULL  ON UPDATE CURRENT_TIMESTAMP;```


```/* 1:33:42 PM Vagrant statbus */ UPDATE `version` SET `minor` = '1' WHERE `major` = '1' AND `minor` = '0' AND `patch` = '0' AND `updated` = '2017-12-14 20:53:02' LIMIT 1;```
