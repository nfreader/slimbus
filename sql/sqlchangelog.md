## 4.0 - 18-09-2020

Adds a table for tracking ahelp tickets marked as public.

Adds a table for tracking votes on paintings in the library.

```
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
```

```
CREATE TABLE `public_tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` int(11) unsigned NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1 COMMENT 'Not public, Public, Forced Private',
  `identifier` varchar(16) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
```

```
UPDATE `version` SET `major` = '4' WHERE `major` = '3' AND `minor` = '0' AND `patch` = '0' AND `updated` = '2019-11-10 20:36:06' LIMIT 1;
```


## 3.0 - 12-06-2019

Removes unused tables and updates the DB version: 

```
DROP TABLE `tracked_rounds`;
DROP TABLE `tracked_round_details`;
DROP TABLE `session`;
DROP TABLE `explosions`;
DROP TABLE `audit`;
```

```
UPDATE `version` SET `major` = '3', `minor` = '0' WHERE `major` = '1' AND `minor` = '6' AND `patch` = '0' AND `updated` = '2018-11-17 20:57:01' LIMIT 1;
```


## 2.0 - 17-12-2018
Adds a new table to track voting on character names.

```
CREATE TABLE `name_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `ckey` varchar(32) NOT NULL DEFAULT '',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `good` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`ckey`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
```


```
UPDATE `version` SET `major` = '2', `minor` = '0' WHERE `major` = '1' AND `minor` = '6' AND `patch` = '0' AND `updated` = '2018-11-16 16:13:55' LIMIT 1;
```


## 1.6 - 16-11-2018
The following updates to the `manifest` table should be applied. These changes will allow character names, jobs, and special roles that are longer than 32 characters.

```
ALTER TABLE `manifest` CHANGE `name` `name` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL
```

```
ALTER TABLE `manifest` CHANGE `job` `job` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;
```

```
ALTER TABLE `manifest` CHANGE `role` `role` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL
```


```
UPDATE `version` SET `minor` = '6' WHERE `major` = '1' AND `minor` = '5' AND `patch` = '0' AND `updated` = '2018-10-16 20:44:53' LIMIT 1;
```


## 1.5 - 16-10-2018

```
ALTER TABLE `round_logs` CHANGE `area` `area` VARCHAR(64)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NULL  DEFAULT NULL;
```


```
UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '5' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;

```


## 1.4 - 16-10-2018

```
ALTER TABLE `round_logs` ADD `area` VARCHAR(32)  NULL  DEFAULT NULL  AFTER `added`;
```


```
UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '4' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;

```


## 1.3 - 16-10-2018
```
DROP TABLE `round_logs`;
```

```
CREATE TABLE `round_logs` (   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   `round` int(11) NOT NULL,   `timestamp` DATETIME(3) DEFAULT NULL,   `type` varchar(16) DEFAULT NULL,   `text` mediumtext,   `x` int(11) DEFAULT NULL,   `y` int(11) DEFAULT NULL,   `z` int(11) DEFAULT NULL,   `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```



```
UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '3' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;
```


## 1.2 - 11-5-2018

```
ALTER TABLE `version` CHANGE `updated` `updated` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP;
```


```
TRUNCATE `round_logs`;
```
  

```
ALTER TABLE `round_logs` AUTO_INCREMENT = 1;
```



```
ALTER TABLE `round_logs` ADD `date` INT  NULL  DEFAULT NULL  AFTER `added`;
```
  

```
ALTER TABLE `round_logs` CHANGE `date` `date` DATE  NULL;
```
  

```
ALTER TABLE `round_logs` MODIFY COLUMN `date` DATE DEFAULT NULL AFTER `round`;
```
  

```
ALTER TABLE `round_logs` CHANGE `timestamp` `time` TIME  NULL  DEFAULT NULL;
```
  

```
ALTER TABLE `round_logs` CHANGE `time` `time` TIME(3)  NULL;
```
  

```
ALTER TABLE `round_logs` AUTO_INCREMENT = 1;
```
  

```
ALTER TABLE `round_logs` DROP `map`;
```


```
UPDATE `version` SET `updated` = NOW() WHERE `major` = '1' AND `minor` = '2' AND `patch` = '0' AND `updated` = '0000-00-00 00:00:00' LIMIT 1;
```



## 1.1 - 19-1-2018
```
ALTER TABLE `tracked_rounds` CHANGE `added` `added` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP;
```



```
ALTER TABLE `tracked_rounds` CHANGE `added` `added` TIMESTAMP  NOT NULL  DEFAULT CURRENT_TIMESTAMP;
```



```
ALTER TABLE `tracked_rounds` ADD `regenerated` TIMESTAMP  NULL  AFTER `added`;
```



```
ALTER TABLE `tracked_rounds` CHANGE `regenerated` `regenerated` TIMESTAMP  NULL  DEFAULT NULL  ON UPDATE CURRENT_TIMESTAMP;
```



```
ALTER TABLE `tracked_rounds` CHANGE `regenerated` `regenerated` TIMESTAMP  NOT NULL  ON UPDATE CURRENT_TIMESTAMP;
```



```
UPDATE `version` SET `minor` = '1' WHERE `major` = '1' AND `minor` = '0' AND `patch` = '0' AND `updated` = '2017-12-14 20:53:02' LIMIT 1;
```

