/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.5-10.4.16-MariaDB-log : Database - attm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`attm` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `attm`;

/*Table structure for table `att_logs` */

DROP TABLE IF EXISTS `att_logs`;

CREATE TABLE `att_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `USERID` int(11) NOT NULL,
  `checklog_time` date NOT NULL,
  `check_log_in` datetime DEFAULT NULL,
  `check_log_out` datetime DEFAULT NULL,
  `shift_in` datetime NOT NULL,
  `shift_out` datetime NOT NULL,
  `checkin_time1` datetime NOT NULL,
  `checkin_time2` datetime NOT NULL,
  `checkout_time1` datetime NOT NULL,
  `checkout_time2` datetime NOT NULL,
  `check_type` int(11) NOT NULL,
  `late_tolerance` int(11) NOT NULL,
  `early_tolerance` int(11) NOT NULL,
  `SDAYS` int(11) NOT NULL,
  `late` int(11) NOT NULL,
  `early_checkin` int(11) NOT NULL,
  `overtime` int(11) NOT NULL,
  `early_checkout` int(11) NOT NULL,
  `check_log_status` int(11) NOT NULL,
  `departement_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17971 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `attendances` */

DROP TABLE IF EXISTS `attendances`;

CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stamp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `status1` tinyint(1) DEFAULT NULL,
  `status2` tinyint(1) DEFAULT NULL,
  `status3` tinyint(1) DEFAULT NULL,
  `status4` tinyint(1) DEFAULT NULL,
  `status5` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `device_log` */

DROP TABLE IF EXISTS `device_log`;

CREATE TABLE `device_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl` date DEFAULT NULL,
  `sn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_sn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `online` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `devices_no_sn_unique` (`no_sn`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `employee_check_log_statuses` */

DROP TABLE IF EXISTS `employee_check_log_statuses`;

CREATE TABLE `employee_check_log_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `checklog_date` datetime NOT NULL,
  `employee_USERID` bigint(20) unsigned NOT NULL,
  `checklog_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=523 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `employee_permit` */

DROP TABLE IF EXISTS `employee_permit`;

CREATE TABLE `employee_permit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_permit` date DEFAULT NULL,
  `USERID` int(11) NOT NULL,
  `permit_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `error_log` */

DROP TABLE IF EXISTS `error_log`;

CREATE TABLE `error_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `finger_log` */

DROP TABLE IF EXISTS `finger_log`;

CREATE TABLE `finger_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1757 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mcu` */

DROP TABLE IF EXISTS `mcu`;

CREATE TABLE `mcu` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `USERID` int(11) DEFAULT NULL,
  `mcu_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_modify` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_role_name_unique` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `userinfo` */

DROP TABLE IF EXISTS `userinfo`;

CREATE TABLE `userinfo` (
  `USERID` int(11) DEFAULT NULL,
  `Badgenumber` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SSN` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Gender` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TITLE` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PAGER` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BIRTHDAY` datetime DEFAULT NULL,
  `HIREDDAY` datetime DEFAULT NULL,
  `street` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CITY` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `STATE` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ZIP` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OPHONE` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FPHONE` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VERIFICATIONMETHOD` smallint(6) DEFAULT NULL,
  `DEFAULTDEPTID` smallint(6) DEFAULT NULL,
  `SECURITYFLAGS` smallint(6) DEFAULT NULL,
  `ATT` smallint(6) DEFAULT NULL,
  `INLATE` smallint(6) DEFAULT NULL,
  `OUTEARLY` smallint(6) DEFAULT NULL,
  `OVERTIME` smallint(6) DEFAULT NULL,
  `SEP` smallint(6) DEFAULT NULL,
  `HOLIDAY` smallint(6) DEFAULT NULL,
  `MINZU` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PASSWORD` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LUNCHDURATION` smallint(6) DEFAULT NULL,
  `PHOTO` blob DEFAULT NULL,
  `mverifypass` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Notes` blob DEFAULT NULL,
  `privilege` int(11) DEFAULT NULL,
  `InheritDeptSch` smallint(6) DEFAULT NULL,
  `InheritDeptSchClass` smallint(6) DEFAULT NULL,
  `AutoSchPlan` smallint(6) DEFAULT NULL,
  `MinAutoSchInterval` int(11) DEFAULT NULL,
  `RegisterOT` smallint(6) DEFAULT NULL,
  `InheritDeptRule` smallint(6) DEFAULT NULL,
  `EMPRIVILEGE` smallint(6) DEFAULT NULL,
  `CardNo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FaceGroup` int(11) DEFAULT NULL,
  `AccGroup` int(11) DEFAULT NULL,
  `UseAccGroupTZ` int(11) DEFAULT NULL,
  `VerifyCode` int(11) DEFAULT NULL,
  `Expires` int(11) DEFAULT NULL,
  `ValidCount` int(11) DEFAULT NULL,
  `ValidTimeBegin` datetime DEFAULT NULL,
  `ValidTimeEnd` datetime DEFAULT NULL,
  `TimeZone1` int(11) DEFAULT NULL,
  `TimeZone2` int(11) DEFAULT NULL,
  `TimeZone3` int(11) DEFAULT NULL,
  `IDCardNo` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardValidTime` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EMail` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardName` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardBirth` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardSN` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardDN` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardAddr` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardNewAddr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardISSUER` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardGender` int(11) DEFAULT NULL,
  `IDCardNation` int(11) DEFAULT NULL,
  `IDCardReserve` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCardNotice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCard_MainCard` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IDCard_ViceCard` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FSelected` tinyint(1) DEFAULT NULL,
  `Pin1` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_telpon` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `work_schedules` */

DROP TABLE IF EXISTS `work_schedules`;

CREATE TABLE `work_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
