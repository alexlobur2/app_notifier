DROP TABLE IF EXISTS `announces`, `devices`, `apps`;

CREATE TABLE `apps` (
  `app_id` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `enabled` tinyint NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `uq_apps_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `announces` (
  `uuid` char(16) NOT NULL,
  `app_id` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `status` enum('draft','live','archived') NOT NULL DEFAULT 'draft',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uuid`),
  KEY `idx_announces_app_status` (`app_id`, `status`),
  CONSTRAINT `fk_announces_app`
      FOREIGN KEY (`app_id`)
      REFERENCES `apps` (`app_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `chk_announces_period`
      CHECK (`start_at` IS NULL OR `end_at` IS NULL OR `end_at` >= `start_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `devices` (
  `app_id` varchar(32) NOT NULL,
  `device_fpt` varchar(64) NOT NULL,
  `first_seen_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_seen_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_count` int UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`app_id`, `device_fpt`),
  CONSTRAINT `fk_devices_app`
      FOREIGN KEY (`app_id`)
      REFERENCES `apps` (`app_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `apps`
    SET app_id="admin",
        token="TEST_ADMIN",
        enabled=1;

INSERT INTO `apps`
    SET app_id="test.app",
        token="TEST_APP",
        enabled=1;
