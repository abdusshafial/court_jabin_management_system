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
  `qualification` VARCHAR(255) NOT NULL DEFAULT '',
  `bar_council_registration` VARCHAR(100) NOT NULL DEFAULT '',
  `bar_association_member_id` VARCHAR(100) NOT NULL DEFAULT '',
  `senior_advocate_name` VARCHAR(255) NOT NULL DEFAULT '',
  `mobile_number` VARCHAR(50) NOT NULL DEFAULT '',
  `alternate_contact_number` VARCHAR(50) NOT NULL DEFAULT '',
  `email` VARCHAR(255) NOT NULL DEFAULT '',
  `chamber_address` TEXT NULL,
  `residential_address` TEXT NULL,
  `vakalatnama_status` VARCHAR(30) NOT NULL DEFAULT 'Not submitted',
  `roll_serial_number` VARCHAR(100) NOT NULL DEFAULT '',
  `court_room_number` VARCHAR(100) NOT NULL DEFAULT '',
  `specialization` VARCHAR(255) NOT NULL DEFAULT '',
  `fee_basis` VARCHAR(30) NOT NULL DEFAULT 'Per case',
  `fee_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `paid_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `due_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
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
