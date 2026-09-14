
--
-- Структура таблицы `app_notifier_announces`
--
CREATE TABLE `app_notifier_announces` (
  `uuid` char(16) NOT NULL,
  `app_id` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `status` enum('draft','live','archived') NOT NULL DEFAULT 'draft',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `app_notifier_announces`
    ADD PRIMARY KEY (`uuid`),
    ADD KEY `idx_app_status` (`app_id`,`status`);

ALTER TABLE `app_notifier_announces`
    ADD CONSTRAINT `fk_app_notifier_announcements_app`
        FOREIGN KEY (`app_id`)
            REFERENCES `app_notifier_apps` (`app_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE;


-- --------------------------------------------------------
--
-- Структура таблицы `app_notifier_apps`
--
CREATE TABLE `app_notifier_apps` (
  `app_id` varchar(32) NOT NULL,
  `token` varchar(32) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `app_notifier_apps`
    ADD PRIMARY KEY (`app_id`),
  ADD UNIQUE KEY `token` (`token`);


-- --------------------------------------------------------
--
-- Структура таблицы `app_notifier_devices`
--

CREATE TABLE `app_notifier_devices` (
  `app_id` varchar(32) NOT NULL,
  `device_fpt` varchar(64) NOT NULL,
  `first_seen_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_seen_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_count` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `app_notifier_devices`
  ADD PRIMARY KEY (`app_id`,`device_fpt`);

ALTER TABLE `app_notifier_devices`
  ADD CONSTRAINT `fk_app_notifier_devices_app`
      FOREIGN KEY (`app_id`)
          REFERENCES `app_notifier_apps` (`app_id`)
          ON DELETE CASCADE
          ON UPDATE CASCADE;

