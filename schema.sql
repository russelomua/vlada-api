--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 8.0.108.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 23.05.2019 22:49:06
-- Версия сервера: 5.6.35
-- Версия клиента: 4.1
--

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Удалить таблицу `drones`
--
DROP TABLE IF EXISTS drones;

--
-- Удалить таблицу `drones_queue`
--
DROP TABLE IF EXISTS drones_queue;

--
-- Удалить таблицу `files`
--
DROP TABLE IF EXISTS files;

--
-- Удалить таблицу `orders`
--
DROP TABLE IF EXISTS orders;

--
-- Удалить таблицу `printers`
--
DROP TABLE IF EXISTS printers;

--
-- Удалить таблицу `printers_queue`
--
DROP TABLE IF EXISTS printers_queue;

--
-- Удалить таблицу `users`
--
DROP TABLE IF EXISTS users;

--
-- Установка базы данных по умолчанию
--
USE Vlada;

--
-- Создать таблицу `users`
--
CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  name text NOT NULL,
  surname text DEFAULT NULL,
  adress text DEFAULT NULL,
  password varchar(55) NOT NULL,
  login varchar(55) NOT NULL,
  avatar varchar(255) DEFAULT NULL,
  rule varchar(55) DEFAULT NULL,
  email varchar(255) NOT NULL,
  secret varchar(64) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `printers_queue`
--
CREATE TABLE printers_queue (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  printer_id int(11) NOT NULL,
  file_id int(11) NOT NULL,
  start datetime DEFAULT NULL,
  finish datetime NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 6,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `printers`
--
CREATE TABLE printers (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  lat float NOT NULL,
  lon float NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `orders`
--
CREATE TABLE orders (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id int(10) UNSIGNED NOT NULL,
  price float DEFAULT NULL,
  comment int(11) DEFAULT NULL,
  date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  payment_status tinyint(1) DEFAULT NULL,
  lat float DEFAULT NULL,
  lon float DEFAULT NULL,
  status enum ('new', 'pending', 'payment', 'payed', 'printing', 'enter_location', 'pending_delivery', 'delivering', 'done') NOT NULL DEFAULT 'new',
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 11,
AVG_ROW_LENGTH = 4096,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `files`
--
CREATE TABLE files (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id int(11) UNSIGNED NOT NULL,
  filename varchar(255) NOT NULL,
  hash varchar(64) NOT NULL,
  route text NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 28,
AVG_ROW_LENGTH = 2730,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `drones_queue`
--
CREATE TABLE drones_queue (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  dron_id int(10) UNSIGNED NOT NULL,
  order_id int(10) UNSIGNED NOT NULL,
  start datetime NOT NULL,
  finish datetime NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 18,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

--
-- Создать таблицу `drones`
--
CREATE TABLE drones (
  id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  lat float NOT NULL,
  lon float NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;