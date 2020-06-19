DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` VARCHAR(36) NOT NULL,
  `title` VARCHAR(32) NOT NULL,
  `description` TEXT NOT NULL,
  `date_created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
