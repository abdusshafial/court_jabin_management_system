-- Rangpur Court Database Setup
CREATE DATABASE IF NOT EXISTS `rangpur_court` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rangpur_court`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `clients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `village` VARCHAR(255) NOT NULL DEFAULT '',
  `postcode` VARCHAR(20) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_clients_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `jabin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `village` VARCHAR(255) NOT NULL DEFAULT '',
  `postcode` VARCHAR(20) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_jabin_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `final_list` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Active',
  `village` VARCHAR(255) NOT NULL DEFAULT '',
  `postcode` VARCHAR(20) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_final_list_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `users` (`username`, `password`) VALUES
  ('admin', 'admin123');

INSERT IGNORE INTO `clients` (`name`, `status`, `village`, `postcode`) VALUES
  ('Client A', 'Active', 'Pirgonj Village', '5400'),
  ('Client B', 'Pending', 'Pirgonj Village', '5400'),
  ('Client C', 'Complete', 'Pirgonj Village', '5400');

INSERT IGNORE INTO `jabin` (`name`, `status`, `village`, `postcode`) VALUES
  ('Jabin 101', 'Active', 'Pirgonj Village', '5400'),
  ('Jabin 102', 'Pending', 'Pirgonj Village', '5400'),
  ('Jabin 103', 'Complete', 'Pirgonj Village', '5400');

INSERT IGNORE INTO `final_list` (`name`, `status`, `village`, `postcode`) VALUES
  ('Final Case 1', 'Active', 'Pirgonj Village', '5400'),
  ('Final Case 2', 'Pending', 'Pirgonj Village', '5400'),
  ('Final Case 3', 'Complete', 'Pirgonj Village', '5400');
