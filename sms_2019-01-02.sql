# ************************************************************
# Sequel Pro SQL dump
# Version 5120
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22)
# Database: sms
# Generation Time: 2019-01-02 15:42:00 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_menu`;

CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`)
VALUES
	(1,0,1,'首页','fa-bar-chart','/',NULL,NULL,'2018-10-06 03:44:17'),
	(2,0,10,'系统管理','fa-tasks',NULL,NULL,NULL,'2018-11-19 21:02:02'),
	(3,2,11,'管理员','fa-users','auth/users',NULL,NULL,'2018-11-19 21:02:02'),
	(4,2,12,'角色','fa-user','auth/roles',NULL,NULL,'2018-11-19 21:02:02'),
	(5,2,13,'权限','fa-ban','auth/permissions',NULL,NULL,'2018-11-19 21:02:02'),
	(6,2,14,'菜单','fa-bars','auth/menu',NULL,NULL,'2018-11-19 21:02:02'),
	(7,2,16,'操作日志','fa-history','auth/logs',NULL,NULL,'2018-11-19 21:02:02'),
	(8,0,0,'卡类管理','fa-bars','card',NULL,'2019-01-02 21:15:29','2019-01-02 21:15:29');

/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_operation_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_operation_log`;

CREATE TABLE `admin_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_operation_log_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_operation_log` WRITE;
/*!40000 ALTER TABLE `admin_operation_log` DISABLE KEYS */;

INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`)
VALUES
	(18,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:15:07','2019-01-02 21:15:07'),
	(19,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5361\\u7c7b\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"card\",\"roles\":[null],\"permission\":null,\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\"}','2019-01-02 21:15:29','2019-01-02 21:15:29'),
	(20,1,'admin/auth/menu','GET','127.0.0.1','[]','2019-01-02 21:15:29','2019-01-02 21:15:29'),
	(21,1,'admin/auth/menu/8/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:15:33','2019-01-02 21:15:33'),
	(22,1,'admin/auth/menu/8/edit','GET','127.0.0.1','[]','2019-01-02 21:15:55','2019-01-02 21:15:55'),
	(23,1,'admin/auth/menu/8/edit','GET','127.0.0.1','[]','2019-01-02 21:20:42','2019-01-02 21:20:42'),
	(24,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:20:43','2019-01-02 21:20:43'),
	(25,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:40','2019-01-02 21:21:40'),
	(26,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:41','2019-01-02 21:21:41'),
	(27,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:42','2019-01-02 21:21:42'),
	(28,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:42','2019-01-02 21:21:42'),
	(29,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:42','2019-01-02 21:21:42'),
	(30,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:42','2019-01-02 21:21:42'),
	(31,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:53','2019-01-02 21:21:53'),
	(32,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:21:58','2019-01-02 21:21:58'),
	(33,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:22:51','2019-01-02 21:22:51'),
	(34,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:23:17','2019-01-02 21:23:17'),
	(35,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:23:43','2019-01-02 21:23:43'),
	(36,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:23:44','2019-01-02 21:23:44'),
	(37,1,'admin/card','POST','127.0.0.1','{\"name\":\"12312312312312312\",\"amount\":\"8\",\"password\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 21:23:55','2019-01-02 21:23:55'),
	(38,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:23:55','2019-01-02 21:23:55'),
	(39,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:28:01','2019-01-02 21:28:01'),
	(40,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:28:04','2019-01-02 21:28:04'),
	(41,1,'admin/card','POST','127.0.0.1','{\"name\":\"213123123\",\"amount\":\"10\",\"password\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 21:28:17','2019-01-02 21:28:17'),
	(42,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:28:17','2019-01-02 21:28:17'),
	(43,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:28:41','2019-01-02 21:28:41'),
	(44,1,'admin/card','POST','127.0.0.1','{\"name\":\"123123123\",\"amount\":\"500.12345\",\"password\":\"123123123213\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 21:29:01','2019-01-02 21:29:01'),
	(45,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:29:01','2019-01-02 21:29:01'),
	(46,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:30:34','2019-01-02 21:30:34'),
	(47,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:31:49','2019-01-02 21:31:49'),
	(48,1,'admin/card','POST','127.0.0.1','{\"name\":\"12312312\",\"amount\":\"12310\",\"password\":\"123123\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 21:31:54','2019-01-02 21:31:54'),
	(49,1,'admin/card/create','GET','127.0.0.1','[]','2019-01-02 21:31:54','2019-01-02 21:31:54'),
	(50,1,'admin/card/create','GET','127.0.0.1','[]','2019-01-02 21:32:23','2019-01-02 21:32:23'),
	(51,1,'admin/card','POST','127.0.0.1','{\"name\":\"123123\",\"amount\":\"1230\",\"password\":\"123123\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\"}','2019-01-02 21:32:28','2019-01-02 21:32:28'),
	(52,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:32:28','2019-01-02 21:32:28'),
	(53,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:35:41','2019-01-02 21:35:41'),
	(54,1,'admin/card','GET','127.0.0.1','{\"name\":\"12\",\"_pjax\":\"#pjax-container\"}','2019-01-02 21:35:44','2019-01-02 21:35:44'),
	(55,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"name\":\"2222\"}','2019-01-02 21:35:47','2019-01-02 21:35:47'),
	(56,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:35:50','2019-01-02 21:35:50'),
	(57,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:40:21','2019-01-02 21:40:21'),
	(58,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:40:23','2019-01-02 21:40:23'),
	(59,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:40:50','2019-01-02 21:40:50'),
	(60,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:40:52','2019-01-02 21:40:52'),
	(61,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 21:41:01','2019-01-02 21:41:01'),
	(62,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 21:45:32','2019-01-02 21:45:32'),
	(63,1,'admin/card','GET','127.0.0.1','{\"_export_\":\"all\"}','2019-01-02 21:45:35','2019-01-02 21:45:35'),
	(64,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:05:09','2019-01-02 22:05:09'),
	(65,1,'admin/card','GET','127.0.0.1','{\"_export_\":\"all\"}','2019-01-02 22:05:18','2019-01-02 22:05:18'),
	(66,1,'admin/card','GET','127.0.0.1','{\"_export_\":\"all\"}','2019-01-02 22:06:30','2019-01-02 22:06:30'),
	(67,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:06:39','2019-01-02 22:06:39'),
	(68,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:07:01','2019-01-02 22:07:01'),
	(69,1,'admin/card','POST','127.0.0.1','{\"name\":\"maintenance\",\"amount\":\"0\",\"password\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:07:07','2019-01-02 22:07:07'),
	(70,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:07:07','2019-01-02 22:07:07'),
	(71,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:10:33','2019-01-02 22:10:33'),
	(72,1,'admin/card','GET','127.0.0.1','{\"_scope_\":\"name\",\"_pjax\":\"#pjax-container\"}','2019-01-02 22:10:39','2019-01-02 22:10:39'),
	(73,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:10:41','2019-01-02 22:10:41'),
	(74,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"_scope_\":\"name\"}','2019-01-02 22:10:45','2019-01-02 22:10:45'),
	(75,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:10:47','2019-01-02 22:10:47'),
	(76,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:10:50','2019-01-02 22:10:50'),
	(77,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"_scope_\":\"name\"}','2019-01-02 22:10:54','2019-01-02 22:10:54'),
	(78,1,'admin/card','GET','127.0.0.1','{\"_scope_\":\"name\"}','2019-01-02 22:11:07','2019-01-02 22:11:07'),
	(79,1,'admin/card','GET','127.0.0.1','{\"_scope_\":\"name\",\"_pjax\":\"#pjax-container\"}','2019-01-02 22:11:10','2019-01-02 22:11:10'),
	(80,1,'admin/card','GET','127.0.0.1','{\"_scope_\":\"name\",\"_pjax\":\"#pjax-container\"}','2019-01-02 22:11:12','2019-01-02 22:11:12'),
	(81,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:11:15','2019-01-02 22:11:15'),
	(82,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:12:17','2019-01-02 22:12:17'),
	(83,1,'admin/card','GET','127.0.0.1','{\"name\":{\"start\":\"1\",\"end\":\"2\"},\"_pjax\":\"#pjax-container\"}','2019-01-02 22:12:24','2019-01-02 22:12:24'),
	(84,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:12:31','2019-01-02 22:12:31'),
	(85,1,'admin/card','POST','127.0.0.1','{\"name\":\"10000\",\"amount\":\"0\",\"password\":\"10000\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card?name%5Bstart%5D=1&name%5Bend%5D=2\"}','2019-01-02 22:12:43','2019-01-02 22:12:43'),
	(86,1,'admin/card','GET','127.0.0.1','{\"name\":{\"start\":\"1\",\"end\":\"2\"}}','2019-01-02 22:12:43','2019-01-02 22:12:43'),
	(87,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:12:48','2019-01-02 22:12:48'),
	(88,1,'admin/card','POST','127.0.0.1','{\"name\":\"10001\",\"amount\":\"0\",\"password\":\"10000\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card?name%5Bstart%5D=1&name%5Bend%5D=2\"}','2019-01-02 22:12:55','2019-01-02 22:12:55'),
	(89,1,'admin/card','GET','127.0.0.1','{\"name\":{\"start\":\"1\",\"end\":\"2\"}}','2019-01-02 22:12:55','2019-01-02 22:12:55'),
	(90,1,'admin/card','GET','127.0.0.1','{\"name\":{\"start\":\"1\",\"end\":\"2\"}}','2019-01-02 22:13:55','2019-01-02 22:13:55'),
	(91,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:13:58','2019-01-02 22:13:58'),
	(92,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:13:59','2019-01-02 22:13:59'),
	(93,1,'admin/card/create','GET','127.0.0.1','[]','2019-01-02 22:14:14','2019-01-02 22:14:14'),
	(94,1,'admin/card','POST','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"0\",\"password\":null,\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\"}','2019-01-02 22:14:24','2019-01-02 22:14:24'),
	(95,1,'admin/card/create','GET','127.0.0.1','[]','2019-01-02 22:14:24','2019-01-02 22:14:24'),
	(96,1,'admin/card','POST','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"0\",\"password\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\"}','2019-01-02 22:14:29','2019-01-02 22:14:29'),
	(97,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:14:30','2019-01-02 22:14:30'),
	(98,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:14:32','2019-01-02 22:14:32'),
	(99,1,'admin/card','POST','127.0.0.1','{\"name\":\"1000000001\",\"amount\":\"0\",\"password\":\"1000000002\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:14:42','2019-01-02 22:14:42'),
	(100,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:14:43','2019-01-02 22:14:43'),
	(101,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:14:45','2019-01-02 22:14:45'),
	(102,1,'admin/card','POST','127.0.0.1','{\"name\":\"1000000000\",\"amount\":\"1\",\"password\":\"1000000002\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:14:56','2019-01-02 22:14:56'),
	(103,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:14:56','2019-01-02 22:14:56'),
	(104,1,'admin/card','GET','127.0.0.1','{\"name\":{\"start\":\"1000000002\",\"end\":\"1000000001\"},\"_pjax\":\"#pjax-container\"}','2019-01-02 22:15:05','2019-01-02 22:15:05'),
	(105,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"name\":{\"start\":\"1000000001\",\"end\":\"1000000002\"}}','2019-01-02 22:15:10','2019-01-02 22:15:10'),
	(106,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"name\":{\"start\":\"100000000\",\"end\":\"1000000002\"}}','2019-01-02 22:15:17','2019-01-02 22:15:17'),
	(107,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"name\":{\"start\":\"100000000\",\"end\":\"1000000002\"},\"_export_\":\"all\"}','2019-01-02 22:15:44','2019-01-02 22:15:44'),
	(108,1,'admin/card/1','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:16:35','2019-01-02 22:16:35'),
	(109,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:16:50','2019-01-02 22:16:50'),
	(110,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:16:56','2019-01-02 22:16:56'),
	(111,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"$2y$10$SJrBaGL7CmHRN5lnyVmSDO5XVpAFd178DMEKN2\\/N7OKdNzQcKcOiW\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\\/1\"}','2019-01-02 22:16:59','2019-01-02 22:16:59'),
	(112,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:16:59','2019-01-02 22:16:59'),
	(113,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:19:26','2019-01-02 22:19:26'),
	(114,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:19:29','2019-01-02 22:19:29'),
	(115,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:19:31','2019-01-02 22:19:31'),
	(116,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"$2y$10$IhdNSXYUgKNrUyq8psSqi.g9QvxKIrXxffDCyPNTs1ZOsZH1cgS7i\",\"password_confirmation\":\"$2y$10$IhdNSXYUgKNrUyq8psSqi.g9QvxKIrXxffDCyPNTs1ZOsZH1cgS7i\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\"}','2019-01-02 22:19:33','2019-01-02 22:19:33'),
	(117,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:19:33','2019-01-02 22:19:33'),
	(118,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:20:00','2019-01-02 22:20:00'),
	(119,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"$2y$10$IhdNSXYUgKNrUyq8psSqi.g9QvxKIrXxffDCyPNTs1ZOsZH1cgS7i\",\"password_confirmation\":\"$2y$10$IhdNSXYUgKNrUyq8psSqi.g9QvxKIrXxffDCyPNTs1ZOsZH1cgS7i\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\"}','2019-01-02 22:20:02','2019-01-02 22:20:02'),
	(120,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:20:02','2019-01-02 22:20:02'),
	(121,1,'admin/card/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:20:05','2019-01-02 22:20:05'),
	(122,1,'admin/card/2','PUT','127.0.0.1','{\"name\":\"1000000001\",\"amount\":\"0\",\"password\":\"$2y$10$dh1ADC2uw1TEbFILLwd5SufrB.WK2Ta0I.6IFCnMaSGeLN3sA5GtW\",\"password_confirmation\":\"$2y$10$dh1ADC2uw1TEbFILLwd5SufrB.WK2Ta0I.6IFCnMaSGeLN3sA5GtW\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:20:07','2019-01-02 22:20:07'),
	(123,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:20:07','2019-01-02 22:20:07'),
	(124,1,'admin/card/3/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:20:08','2019-01-02 22:20:08'),
	(125,1,'admin/card/3','PUT','127.0.0.1','{\"name\":\"1000000000\",\"amount\":\"1\",\"password\":\"$2y$10$Jp47t1MgetgxP.JdN.BBeOPXYpLBlXwE7LntHMJAu0Kz0Bm.j68NG\",\"password_confirmation\":\"$2y$10$Jp47t1MgetgxP.JdN.BBeOPXYpLBlXwE7LntHMJAu0Kz0Bm.j68NG\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:20:09','2019-01-02 22:20:09'),
	(126,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:20:09','2019-01-02 22:20:09'),
	(127,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:20:50','2019-01-02 22:20:50'),
	(128,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123456\",\"password_confirmation\":\"$2y$10$Ev0qeJ5bAaS2P4gDXnjQKO1Pv2vc35Alsvr9vSa3CcdfKGy.Ci7fG\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:20:55','2019-01-02 22:20:55'),
	(129,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:20:55','2019-01-02 22:20:55'),
	(130,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123456\",\"password_confirmation\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\"}','2019-01-02 22:20:58','2019-01-02 22:20:58'),
	(131,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:20:58','2019-01-02 22:20:58'),
	(132,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:22:45','2019-01-02 22:22:45'),
	(133,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123456\",\"password_confirmation\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:22:50','2019-01-02 22:22:50'),
	(134,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:22:51','2019-01-02 22:22:51'),
	(135,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:24:26','2019-01-02 22:24:26'),
	(136,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:24:29','2019-01-02 22:24:29'),
	(137,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:24:30','2019-01-02 22:24:30'),
	(138,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123456\",\"password_confirmation\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:24:35','2019-01-02 22:24:35'),
	(139,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:24:43','2019-01-02 22:24:43'),
	(140,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:24:47','2019-01-02 22:24:47'),
	(141,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123456\",\"password_confirmation\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\\/1\"}','2019-01-02 22:24:54','2019-01-02 22:24:54'),
	(142,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:25:13','2019-01-02 22:25:13'),
	(143,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:25:14','2019-01-02 22:25:14'),
	(144,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"123123\",\"password_confirmation\":\"123123\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\\/1\"}','2019-01-02 22:25:18','2019-01-02 22:25:18'),
	(145,1,'admin/card/1','GET','127.0.0.1','[]','2019-01-02 22:25:18','2019-01-02 22:25:18'),
	(146,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:25:35','2019-01-02 22:25:35'),
	(147,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:25:36','2019-01-02 22:25:36'),
	(148,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:25:37','2019-01-02 22:25:37'),
	(149,1,'admin/card','POST','127.0.0.1','{\"name\":\"222\",\"amount\":\"0\",\"password\":\"123456\",\"password_confirmation\":\"123456\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:25:45','2019-01-02 22:25:45'),
	(150,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:25:45','2019-01-02 22:25:45'),
	(151,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:25:56','2019-01-02 22:25:56'),
	(152,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22222\",\"password\":\"654321\",\"password_confirmation\":\"654321\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:26:05','2019-01-02 22:26:05'),
	(153,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:26:05','2019-01-02 22:26:05'),
	(154,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:26:36','2019-01-02 22:26:36'),
	(155,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:28:21','2019-01-02 22:28:21'),
	(156,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:29:13','2019-01-02 22:29:13'),
	(157,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22218\",\"password\":\"$2y$10$ozdaeEHiNqDpEy1rF51N1.ydoADxSZYWnViIXoSdOKIVu1Zm4tj5S\",\"password_confirmation\":\"$2y$10$ozdaeEHiNqDpEy1rF51N1.ydoADxSZYWnViIXoSdOKIVu1Zm4tj5S\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\"}','2019-01-02 22:29:17','2019-01-02 22:29:17'),
	(158,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:29:17','2019-01-02 22:29:17'),
	(159,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:29:20','2019-01-02 22:29:20'),
	(160,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"22224\",\"password\":\"$2y$10$19HuUnLktrRDSYe3ggUd3exFytkWCTWQR1\\/djhs4W4FPmrJck0piW\",\"password_confirmation\":\"$2y$10$19HuUnLktrRDSYe3ggUd3exFytkWCTWQR1\\/djhs4W4FPmrJck0piW\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/sms.test\\/admin\\/card\"}','2019-01-02 22:29:23','2019-01-02 22:29:23'),
	(161,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:29:23','2019-01-02 22:29:23'),
	(162,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:29:25','2019-01-02 22:29:25'),
	(163,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:29:39','2019-01-02 22:29:39'),
	(164,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:29:41','2019-01-02 22:29:41'),
	(165,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:29:58','2019-01-02 22:29:58'),
	(166,1,'admin/card/1','PUT','127.0.0.1','{\"name\":\"1000000002\",\"amount\":\"2224\",\"password\":\"$2y$10$bwlqBvdkUPUpmwRKFCa3hOywRe1MnNi4WHLEgoVmRz1JcwWMM9tbK\",\"password_confirmation\":\"$2y$10$bwlqBvdkUPUpmwRKFCa3hOywRe1MnNi4WHLEgoVmRz1JcwWMM9tbK\",\"_token\":\"sIPqUBeVgnQpq5uB4j3t43mNwE3hWO5jcAAl5t7m\",\"_method\":\"PUT\"}','2019-01-02 22:30:12','2019-01-02 22:30:12'),
	(167,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:30:13','2019-01-02 22:30:13'),
	(168,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:30:28','2019-01-02 22:30:28'),
	(169,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:30:29','2019-01-02 22:30:29'),
	(170,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:30:31','2019-01-02 22:30:31'),
	(171,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:30:40','2019-01-02 22:30:40'),
	(172,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:30:42','2019-01-02 22:30:42'),
	(173,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:31:06','2019-01-02 22:31:06'),
	(174,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:31:09','2019-01-02 22:31:09'),
	(175,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:31:09','2019-01-02 22:31:09'),
	(176,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:31:16','2019-01-02 22:31:16'),
	(177,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:35:12','2019-01-02 22:35:12'),
	(178,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:35:13','2019-01-02 22:35:13'),
	(179,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:35:15','2019-01-02 22:35:15'),
	(180,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:35:16','2019-01-02 22:35:16'),
	(181,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:35:18','2019-01-02 22:35:18'),
	(182,1,'admin/card','GET','127.0.0.1','[]','2019-01-02 22:35:50','2019-01-02 22:35:50'),
	(183,1,'admin/card/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:35:52','2019-01-02 22:35:52'),
	(184,1,'admin/card/1/edit','GET','127.0.0.1','[]','2019-01-02 22:36:03','2019-01-02 22:36:03'),
	(185,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:36:08','2019-01-02 22:36:08'),
	(186,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:36:10','2019-01-02 22:36:10'),
	(187,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:36:11','2019-01-02 22:36:11'),
	(188,1,'admin/card/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:36:13','2019-01-02 22:36:13'),
	(189,1,'admin/card','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2019-01-02 22:36:18','2019-01-02 22:36:18');

/*!40000 ALTER TABLE `admin_operation_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_permissions`;

CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`)
VALUES
	(1,'All permission','*','','*',NULL,NULL),
	(2,'Dashboard','dashboard','GET','/',NULL,NULL),
	(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),
	(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),
	(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL);

/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_menu`;

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`)
VALUES
	(1,2,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_permissions`;

CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_users`;

CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,1,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,'Administrator','administrator','2019-01-01 09:13:40','2019-01-01 09:13:40');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_user_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user_permissions`;

CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'admin','$2y$10$azvsIHoObSbTlYeUAU0TUeZJSxWpS7szqO.HdYALT/9SODqi6tgtG','Administrator',NULL,'x93NESPafbAqmUawY9q7xnYDY2ZLPBCgdVurgpZCgjfa0SsAGTo8WSJtiI9i','2019-01-01 09:13:40','2019-01-01 09:13:40');

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cards`;

CREATE TABLE `cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` bigint(20) NOT NULL COMMENT '卡号',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `real_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '真实密码',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;

INSERT INTO `cards` (`id`, `name`, `amount`, `password`, `real_password`, `created_at`, `updated_at`)
VALUES
	(1,1000000002,22240000,'$2y$10$zzZyfW/8rzwGPN4JD3eGa.njj.GgGxAcDVg6fqCSu7MoqMN62bZIC','$2y$10$bwlqBvdkUPUpmwRKFCa3hOywRe1MnNi4WHLEgoVmRz1JcwWMM9tbK','2019-01-02 22:14:30','2019-01-02 22:30:13'),
	(2,1000000001,0,'$2y$10$feifS5POXjSbB725DqtLNOfAkTVdark03eHLUlsfFnEt4MBqP96hG','123456','2019-01-02 22:14:43','2019-01-02 22:20:07'),
	(3,1000000000,10000,'$2y$10$cAEVEEici1tOzQduODZTuuggWJzrjEzmzZzARtY16GTOZVhu01Ava','123456','2019-01-02 22:14:56','2019-01-02 22:20:09'),
	(4,222,0,'$2y$10$c16MCRhpfwLdzGjF8jcuwOifltHcL1iDqOKRSVR5KukwldQ7vftPq','123456','2019-01-02 22:25:45','2019-01-02 22:25:45');

/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_resets_table',1),
	(3,'2016_01_04_173148_create_admin_tables',1),
	(6,'2019_01_02_211716_create_cards_table',2);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
