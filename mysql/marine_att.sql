/*
SQLyog Enterprise - MySQL GUI v8.05 
MySQL - 5.5.5-10.4.16-MariaDB-log : Database - marine_att
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`marine_att` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `marine_att`;

/*Table structure for table `acgroup` */

DROP TABLE IF EXISTS `acgroup`;

CREATE TABLE `acgroup` (
  `GroupID` smallint(6) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `TimeZone1` smallint(6) DEFAULT NULL,
  `TimeZone2` smallint(6) DEFAULT NULL,
  `TimeZone3` smallint(6) DEFAULT NULL,
  `holidayvaild` bit(1) DEFAULT NULL,
  `verifystyle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `acholiday` */

DROP TABLE IF EXISTS `acholiday`;

CREATE TABLE `acholiday` (
  `primaryid` int(11) DEFAULT NULL,
  `holidayid` int(11) DEFAULT NULL,
  `begindate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `timezone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `actimezones` */

DROP TABLE IF EXISTS `actimezones`;

CREATE TABLE `actimezones` (
  `TimeZoneID` smallint(6) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `SunStart` datetime DEFAULT NULL,
  `SunEnd` datetime DEFAULT NULL,
  `MonStart` datetime DEFAULT NULL,
  `MonEnd` datetime DEFAULT NULL,
  `TuesStart` datetime DEFAULT NULL,
  `TuesEnd` datetime DEFAULT NULL,
  `WedStart` datetime DEFAULT NULL,
  `WedEnd` datetime DEFAULT NULL,
  `ThursStart` datetime DEFAULT NULL,
  `ThursEnd` datetime DEFAULT NULL,
  `FriStart` datetime DEFAULT NULL,
  `FriEnd` datetime DEFAULT NULL,
  `SatStart` datetime DEFAULT NULL,
  `SatEnd` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `acunlockcomb` */

DROP TABLE IF EXISTS `acunlockcomb`;

CREATE TABLE `acunlockcomb` (
  `UnlockCombID` smallint(6) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Group01` smallint(6) DEFAULT NULL,
  `Group02` smallint(6) DEFAULT NULL,
  `Group03` smallint(6) DEFAULT NULL,
  `Group04` smallint(6) DEFAULT NULL,
  `Group05` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `alarmlog` */

DROP TABLE IF EXISTS `alarmlog`;

CREATE TABLE `alarmlog` (
  `ID` int(11) DEFAULT NULL,
  `Operator` varchar(20) DEFAULT NULL,
  `EnrollNumber` varchar(30) DEFAULT NULL,
  `LogTime` datetime DEFAULT NULL,
  `MachineAlias` varchar(20) DEFAULT NULL,
  `AlarmType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `attparam` */

DROP TABLE IF EXISTS `attparam`;

CREATE TABLE `attparam` (
  `PARANAME` varchar(20) DEFAULT NULL,
  `PARATYPE` varchar(2) DEFAULT NULL,
  `PARAVALUE` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `auditedexc` */

DROP TABLE IF EXISTS `auditedexc`;

CREATE TABLE `auditedexc` (
  `AEID` int(11) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `CheckTime` datetime DEFAULT NULL,
  `NewExcID` int(11) DEFAULT NULL,
  `IsLeave` smallint(6) DEFAULT NULL,
  `UName` varchar(20) DEFAULT NULL,
  `UTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `authdevice` */

DROP TABLE IF EXISTS `authdevice`;

CREATE TABLE `authdevice` (
  `ID` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `MachineID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `biotemplate` */

DROP TABLE IF EXISTS `biotemplate`;

CREATE TABLE `biotemplate` (
  `ID` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_pin` varchar(64) DEFAULT NULL,
  `create_operator` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `valid_flag` int(11) DEFAULT NULL,
  `is_duress` int(11) DEFAULT NULL,
  `bio_type` int(11) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `data_format` int(11) DEFAULT NULL,
  `template_no` int(11) DEFAULT NULL,
  `template_no_index` int(11) DEFAULT NULL,
  `Template_Data` mediumtext DEFAULT NULL,
  `nOldType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `checkexact` */

DROP TABLE IF EXISTS `checkexact`;

CREATE TABLE `checkexact` (
  `EXACTID` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `CHECKTIME` datetime DEFAULT NULL,
  `CHECKTYPE` varchar(2) DEFAULT NULL,
  `ISADD` smallint(6) DEFAULT NULL,
  `YUYIN` varchar(25) DEFAULT NULL,
  `ISMODIFY` smallint(6) DEFAULT NULL,
  `ISDELETE` smallint(6) DEFAULT NULL,
  `INCOUNT` smallint(6) DEFAULT NULL,
  `ISCOUNT` smallint(6) DEFAULT NULL,
  `MODIFYBY` varchar(20) DEFAULT NULL,
  `DATE` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `checkinout` */

DROP TABLE IF EXISTS `checkinout`;

CREATE TABLE `checkinout` (
  `USERID` int(11) DEFAULT NULL,
  `CHECKTIME` datetime DEFAULT NULL,
  `CHECKTYPE` varchar(1) DEFAULT NULL,
  `VERIFYCODE` int(11) DEFAULT NULL,
  `SENSORID` varchar(5) DEFAULT NULL,
  `Memoinfo` varchar(30) DEFAULT NULL,
  `WorkCode` varchar(24) DEFAULT NULL,
  `sn` varchar(20) DEFAULT NULL,
  `UserExtFmt` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `company3` */

DROP TABLE IF EXISTS `company3`;

CREATE TABLE `company3` (
  `Name` varchar(100) DEFAULT NULL,
  `Logo` mediumblob DEFAULT NULL,
  `IsAutoBackup` bit(1) DEFAULT NULL,
  `BackupDataBaseDays` int(11) DEFAULT NULL,
  `BackupDataBaseTime` datetime DEFAULT NULL,
  `ClearDataLogDays` int(11) DEFAULT NULL,
  `ClearDataLogTime` datetime DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PassWord` varchar(100) DEFAULT NULL,
  `Display` varchar(100) DEFAULT NULL,
  `SMTP` varchar(100) DEFAULT NULL,
  `Port` int(11) DEFAULT NULL,
  `Credentials` bit(1) DEFAULT NULL,
  `SSL` bit(1) DEFAULT NULL,
  `Early` bit(1) DEFAULT NULL,
  `Late` bit(1) DEFAULT NULL,
  `Absent` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `DEPTID` int(11) DEFAULT NULL,
  `DEPTNAME` varchar(30) DEFAULT NULL,
  `SUPDEPTID` int(11) DEFAULT NULL,
  `InheritParentSch` smallint(6) DEFAULT NULL,
  `InheritDeptSch` smallint(6) DEFAULT NULL,
  `InheritDeptSchClass` smallint(6) DEFAULT NULL,
  `AutoSchPlan` smallint(6) DEFAULT NULL,
  `InLate` smallint(6) DEFAULT NULL,
  `OutEarly` smallint(6) DEFAULT NULL,
  `InheritDeptRule` smallint(6) DEFAULT NULL,
  `MinAutoSchInterval` int(11) DEFAULT NULL,
  `RegisterOT` smallint(6) DEFAULT NULL,
  `DefaultSchId` int(11) DEFAULT NULL,
  `ATT` smallint(6) DEFAULT NULL,
  `Holiday` smallint(6) DEFAULT NULL,
  `OverTime` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `deptusedschs` */

DROP TABLE IF EXISTS `deptusedschs`;

CREATE TABLE `deptusedschs` (
  `DeptId` int(11) DEFAULT NULL,
  `SchId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `emoplog` */

DROP TABLE IF EXISTS `emoplog`;

CREATE TABLE `emoplog` (
  `ID` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `OperateTime` datetime DEFAULT NULL,
  `manipulationID` int(11) DEFAULT NULL,
  `Params1` int(11) DEFAULT NULL,
  `Params2` int(11) DEFAULT NULL,
  `Params3` int(11) DEFAULT NULL,
  `Params4` int(11) DEFAULT NULL,
  `SensorId` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `excnotes` */

DROP TABLE IF EXISTS `excnotes`;

CREATE TABLE `excnotes` (
  `USERID` int(11) DEFAULT NULL,
  `ATTDATE` datetime DEFAULT NULL,
  `NOTES` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `facetemp` */

DROP TABLE IF EXISTS `facetemp`;

CREATE TABLE `facetemp` (
  `TEMPLATEID` int(11) DEFAULT NULL,
  `USERNO` varchar(24) DEFAULT NULL,
  `SIZE` int(11) DEFAULT NULL,
  `pin` int(11) DEFAULT NULL,
  `FACEID` int(11) DEFAULT NULL,
  `VALID` int(11) DEFAULT NULL,
  `RESERVE` int(11) DEFAULT NULL,
  `ACTIVETIME` int(11) DEFAULT NULL,
  `VFCOUNT` int(11) DEFAULT NULL,
  `TEMPLATE` mediumblob DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `holidays` */

DROP TABLE IF EXISTS `holidays`;

CREATE TABLE `holidays` (
  `HOLIDAYID` int(11) DEFAULT NULL,
  `HOLIDAYNAME` varchar(20) DEFAULT NULL,
  `HOLIDAYYEAR` smallint(6) DEFAULT NULL,
  `HOLIDAYMONTH` smallint(6) DEFAULT NULL,
  `HOLIDAYDAY` smallint(6) DEFAULT NULL,
  `STARTTIME` datetime DEFAULT NULL,
  `DURATION` smallint(6) DEFAULT NULL,
  `HOLIDAYTYPE` smallint(6) DEFAULT NULL,
  `XINBIE` varchar(4) DEFAULT NULL,
  `MINZU` varchar(50) DEFAULT NULL,
  `DeptID` smallint(6) DEFAULT NULL,
  `timezone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `importoptions` */

DROP TABLE IF EXISTS `importoptions`;

CREATE TABLE `importoptions` (
  `TabStyle` int(11) DEFAULT NULL,
  `FileType` int(11) DEFAULT NULL,
  `FileName` varchar(255) DEFAULT NULL,
  `Separator` varchar(20) DEFAULT NULL,
  `Options` mediumtext DEFAULT NULL,
  `ImportTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `leaveclass` */

DROP TABLE IF EXISTS `leaveclass`;

CREATE TABLE `leaveclass` (
  `LeaveId` int(11) DEFAULT NULL,
  `LeaveName` varchar(20) DEFAULT NULL,
  `MinUnit` double DEFAULT NULL,
  `Unit` smallint(6) DEFAULT NULL,
  `RemaindProc` smallint(6) DEFAULT NULL,
  `RemaindCount` smallint(6) DEFAULT NULL,
  `ReportSymbol` varchar(4) DEFAULT NULL,
  `Deduct` double DEFAULT NULL,
  `Color` int(11) DEFAULT NULL,
  `Classify` smallint(6) DEFAULT NULL,
  `Code` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `leaveclass1` */

DROP TABLE IF EXISTS `leaveclass1`;

CREATE TABLE `leaveclass1` (
  `LeaveId` int(11) DEFAULT NULL,
  `LeaveName` varchar(20) DEFAULT NULL,
  `MinUnit` double DEFAULT NULL,
  `Unit` smallint(6) DEFAULT NULL,
  `RemaindProc` smallint(6) DEFAULT NULL,
  `RemaindCount` smallint(6) DEFAULT NULL,
  `ReportSymbol` varchar(4) DEFAULT NULL,
  `Deduct` double DEFAULT NULL,
  `LeaveType` smallint(6) DEFAULT NULL,
  `Color` int(11) DEFAULT NULL,
  `Classify` smallint(6) DEFAULT NULL,
  `Calc` mediumtext DEFAULT NULL,
  `Code` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `machines` */

DROP TABLE IF EXISTS `machines`;

CREATE TABLE `machines` (
  `ID` int(11) DEFAULT NULL,
  `MachineAlias` varchar(20) DEFAULT NULL,
  `ConnectType` int(11) DEFAULT NULL,
  `IP` varchar(20) DEFAULT NULL,
  `SerialPort` int(11) DEFAULT NULL,
  `Port` int(11) DEFAULT NULL,
  `Baudrate` int(11) DEFAULT NULL,
  `MachineNumber` int(11) DEFAULT NULL,
  `IsHost` bit(1) DEFAULT NULL,
  `Enabled` bit(1) DEFAULT NULL,
  `CommPassword` varchar(12) DEFAULT NULL,
  `UILanguage` smallint(6) DEFAULT NULL,
  `DateFormat` smallint(6) DEFAULT NULL,
  `InOutRecordWarn` smallint(6) DEFAULT NULL,
  `Idle` smallint(6) DEFAULT NULL,
  `Voice` smallint(6) DEFAULT NULL,
  `managercount` smallint(6) DEFAULT NULL,
  `usercount` smallint(6) DEFAULT NULL,
  `fingercount` smallint(6) DEFAULT NULL,
  `SecretCount` smallint(6) DEFAULT NULL,
  `FirmwareVersion` varchar(20) DEFAULT NULL,
  `ProductType` varchar(20) DEFAULT NULL,
  `LockControl` smallint(6) DEFAULT NULL,
  `Purpose` smallint(6) DEFAULT NULL,
  `ProduceKind` int(11) DEFAULT NULL,
  `sn` varchar(20) DEFAULT NULL,
  `PhotoStamp` varchar(20) DEFAULT NULL,
  `IsIfChangeConfigServer2` int(11) DEFAULT NULL,
  `pushver` int(11) DEFAULT NULL,
  `IsAndroid` varchar(1) DEFAULT NULL,
  `P2PUid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `num_run` */

DROP TABLE IF EXISTS `num_run`;

CREATE TABLE `num_run` (
  `NUM_RUNID` int(11) DEFAULT NULL,
  `OLDID` int(11) DEFAULT NULL,
  `NAME` varchar(30) DEFAULT NULL,
  `STARTDATE` datetime DEFAULT NULL,
  `ENDDATE` datetime DEFAULT NULL,
  `CYLE` smallint(6) DEFAULT NULL,
  `UNITS` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `num_run_deil` */

DROP TABLE IF EXISTS `num_run_deil`;

CREATE TABLE `num_run_deil` (
  `NUM_RUNID` smallint(6) DEFAULT NULL,
  `STARTTIME` datetime DEFAULT NULL,
  `ENDTIME` datetime DEFAULT NULL,
  `SDAYS` smallint(6) DEFAULT NULL,
  `EDAYS` smallint(6) DEFAULT NULL,
  `SCHCLASSID` int(11) DEFAULT NULL,
  `OverTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `reportitem` */

DROP TABLE IF EXISTS `reportitem`;

CREATE TABLE `reportitem` (
  `RIID` int(11) DEFAULT NULL,
  `RIIndex` int(11) DEFAULT NULL,
  `ShowIt` smallint(6) DEFAULT NULL,
  `RIName` varchar(12) DEFAULT NULL,
  `UnitName` varchar(6) DEFAULT NULL,
  `Formula` mediumblob DEFAULT NULL,
  `CalcBySchClass` smallint(6) DEFAULT NULL,
  `StatisticMethod` smallint(6) DEFAULT NULL,
  `CalcLast` smallint(6) DEFAULT NULL,
  `Notes` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `schclass` */

DROP TABLE IF EXISTS `schclass`;

CREATE TABLE `schclass` (
  `schClassid` int(11) DEFAULT NULL,
  `schName` varchar(20) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `LateMinutes` int(11) DEFAULT NULL,
  `EarlyMinutes` int(11) DEFAULT NULL,
  `CheckIn` int(11) DEFAULT NULL,
  `CheckOut` int(11) DEFAULT NULL,
  `Color` int(11) DEFAULT NULL,
  `AutoBind` smallint(6) DEFAULT NULL,
  `CheckInTime1` datetime DEFAULT NULL,
  `CheckInTime2` datetime DEFAULT NULL,
  `CheckOutTime1` datetime DEFAULT NULL,
  `CheckOutTime2` datetime DEFAULT NULL,
  `WorkDay` double DEFAULT NULL,
  `SensorID` varchar(5) DEFAULT NULL,
  `WorkMins` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `securitydetails` */

DROP TABLE IF EXISTS `securitydetails`;

CREATE TABLE `securitydetails` (
  `SECURITYDETAILID` int(11) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `DEPTID` smallint(6) DEFAULT NULL,
  `SCHEDULE` smallint(6) DEFAULT NULL,
  `USERINFO` smallint(6) DEFAULT NULL,
  `ENROLLFINGERS` smallint(6) DEFAULT NULL,
  `REPORTVIEW` smallint(6) DEFAULT NULL,
  `REPORT` varchar(10) DEFAULT NULL,
  `ReadOnly` bit(1) DEFAULT NULL,
  `FullControl` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `serverlog` */

DROP TABLE IF EXISTS `serverlog`;

CREATE TABLE `serverlog` (
  `ID` int(11) DEFAULT NULL,
  `EVENT` varchar(30) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `EnrollNumber` varchar(30) DEFAULT NULL,
  `parameter` smallint(6) DEFAULT NULL,
  `EVENTTIME` datetime DEFAULT NULL,
  `SENSORID` varchar(5) DEFAULT NULL,
  `OPERATOR` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `shift` */

DROP TABLE IF EXISTS `shift`;

CREATE TABLE `shift` (
  `SHIFTID` int(11) DEFAULT NULL,
  `NAME` varchar(20) DEFAULT NULL,
  `USHIFTID` int(11) DEFAULT NULL,
  `STARTDATE` datetime DEFAULT NULL,
  `ENDDATE` datetime DEFAULT NULL,
  `RUNNUM` smallint(6) DEFAULT NULL,
  `SCH1` int(11) DEFAULT NULL,
  `SCH2` int(11) DEFAULT NULL,
  `SCH3` int(11) DEFAULT NULL,
  `SCH4` int(11) DEFAULT NULL,
  `SCH5` int(11) DEFAULT NULL,
  `SCH6` int(11) DEFAULT NULL,
  `SCH7` int(11) DEFAULT NULL,
  `SCH8` int(11) DEFAULT NULL,
  `SCH9` int(11) DEFAULT NULL,
  `SCH10` int(11) DEFAULT NULL,
  `SCH11` int(11) DEFAULT NULL,
  `SCH12` int(11) DEFAULT NULL,
  `CYCLE` smallint(6) DEFAULT NULL,
  `UNITS` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `systemlog` */

DROP TABLE IF EXISTS `systemlog`;

CREATE TABLE `systemlog` (
  `ID` int(11) DEFAULT NULL,
  `Operator` varchar(20) DEFAULT NULL,
  `LogTime` datetime DEFAULT NULL,
  `MachineAlias` varchar(20) DEFAULT NULL,
  `LogTag` smallint(6) DEFAULT NULL,
  `LogDescr` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tbkey` */

DROP TABLE IF EXISTS `tbkey`;

CREATE TABLE `tbkey` (
  `PreName` varchar(12) DEFAULT NULL,
  `ONEKEY` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tbsmsallot` */

DROP TABLE IF EXISTS `tbsmsallot`;

CREATE TABLE `tbsmsallot` (
  `REFERENCE` int(11) DEFAULT NULL,
  `SMSREF` int(11) DEFAULT NULL,
  `USERREF` int(11) DEFAULT NULL,
  `GENTM` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tbsmsinfo` */

DROP TABLE IF EXISTS `tbsmsinfo`;

CREATE TABLE `tbsmsinfo` (
  `REFERENCE` int(11) DEFAULT NULL,
  `SMSID` varchar(16) DEFAULT NULL,
  `SMSINDEX` int(11) DEFAULT NULL,
  `SMSTYPE` int(11) DEFAULT NULL,
  `SMSCONTENT` mediumtext DEFAULT NULL,
  `SMSSTARTTM` varchar(32) DEFAULT NULL,
  `SMSTMLENG` int(11) DEFAULT NULL,
  `GENTM` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `template` */

DROP TABLE IF EXISTS `template`;

CREATE TABLE `template` (
  `TEMPLATEID` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `FINGERID` int(11) DEFAULT NULL,
  `TEMPLATE` mediumblob DEFAULT NULL,
  `TEMPLATE2` mediumblob DEFAULT NULL,
  `BITMAPPICTURE` mediumblob DEFAULT NULL,
  `BITMAPPICTURE2` mediumblob DEFAULT NULL,
  `BITMAPPICTURE3` mediumblob DEFAULT NULL,
  `BITMAPPICTURE4` mediumblob DEFAULT NULL,
  `USETYPE` smallint(6) DEFAULT NULL,
  `EMACHINENUM` varchar(3) DEFAULT NULL,
  `TEMPLATE1` mediumblob DEFAULT NULL,
  `Flag` smallint(6) DEFAULT NULL,
  `DivisionFP` smallint(6) DEFAULT NULL,
  `TEMPLATE4` mediumblob DEFAULT NULL,
  `TEMPLATE3` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_of_run` */

DROP TABLE IF EXISTS `user_of_run`;

CREATE TABLE `user_of_run` (
  `USERID` int(11) DEFAULT NULL,
  `NUM_OF_RUN_ID` int(11) DEFAULT NULL,
  `STARTDATE` datetime DEFAULT NULL,
  `ENDDATE` datetime DEFAULT NULL,
  `ISNOTOF_RUN` int(11) DEFAULT NULL,
  `ORDER_RUN` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_speday` */

DROP TABLE IF EXISTS `user_speday`;

CREATE TABLE `user_speday` (
  `USERID` int(11) DEFAULT NULL,
  `STARTSPECDAY` datetime DEFAULT NULL,
  `ENDSPECDAY` datetime DEFAULT NULL,
  `DATEID` smallint(6) DEFAULT NULL,
  `YUANYING` varchar(200) DEFAULT NULL,
  `DATE` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_temp_sch` */

DROP TABLE IF EXISTS `user_temp_sch`;

CREATE TABLE `user_temp_sch` (
  `USERID` int(11) DEFAULT NULL,
  `COMETIME` datetime DEFAULT NULL,
  `LEAVETIME` datetime DEFAULT NULL,
  `TYPE` smallint(6) DEFAULT NULL,
  `FLAG` smallint(6) DEFAULT NULL,
  `SCHCLASSID` int(11) DEFAULT NULL,
  `OVERTIME` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `useracmachines` */

DROP TABLE IF EXISTS `useracmachines`;

CREATE TABLE `useracmachines` (
  `UserID` int(11) DEFAULT NULL,
  `Deviceid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `useracprivilege` */

DROP TABLE IF EXISTS `useracprivilege`;

CREATE TABLE `useracprivilege` (
  `UserID` int(11) DEFAULT NULL,
  `DeviceID` int(11) DEFAULT NULL,
  `ACGroupID` int(11) DEFAULT NULL,
  `IsUseGroup` bit(1) DEFAULT NULL,
  `TimeZone1` int(11) DEFAULT NULL,
  `TimeZone2` int(11) DEFAULT NULL,
  `TimeZone3` int(11) DEFAULT NULL,
  `verifystyle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `userinfo` */

DROP TABLE IF EXISTS `userinfo`;

CREATE TABLE `userinfo` (
  `USERID` int(11) DEFAULT NULL,
  `Badgenumber` varchar(24) DEFAULT NULL,
  `SSN` varchar(20) DEFAULT NULL,
  `Name` varchar(40) DEFAULT NULL,
  `Gender` varchar(8) DEFAULT NULL,
  `TITLE` varchar(20) DEFAULT NULL,
  `PAGER` varchar(20) DEFAULT NULL,
  `BIRTHDAY` datetime DEFAULT NULL,
  `HIREDDAY` datetime DEFAULT NULL,
  `street` varchar(80) DEFAULT NULL,
  `CITY` varchar(2) DEFAULT NULL,
  `STATE` varchar(2) DEFAULT NULL,
  `ZIP` varchar(12) DEFAULT NULL,
  `OPHONE` varchar(20) DEFAULT NULL,
  `FPHONE` varchar(20) DEFAULT NULL,
  `VERIFICATIONMETHOD` smallint(6) DEFAULT NULL,
  `DEFAULTDEPTID` smallint(6) DEFAULT NULL,
  `SECURITYFLAGS` smallint(6) DEFAULT NULL,
  `ATT` smallint(6) DEFAULT NULL,
  `INLATE` smallint(6) DEFAULT NULL,
  `OUTEARLY` smallint(6) DEFAULT NULL,
  `OVERTIME` smallint(6) DEFAULT NULL,
  `SEP` smallint(6) DEFAULT NULL,
  `HOLIDAY` smallint(6) DEFAULT NULL,
  `MINZU` varchar(8) DEFAULT NULL,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `LUNCHDURATION` smallint(6) DEFAULT NULL,
  `PHOTO` mediumblob DEFAULT NULL,
  `mverifypass` varchar(10) DEFAULT NULL,
  `Notes` mediumblob DEFAULT NULL,
  `privilege` int(11) DEFAULT NULL,
  `InheritDeptSch` smallint(6) DEFAULT NULL,
  `InheritDeptSchClass` smallint(6) DEFAULT NULL,
  `AutoSchPlan` smallint(6) DEFAULT NULL,
  `MinAutoSchInterval` int(11) DEFAULT NULL,
  `RegisterOT` smallint(6) DEFAULT NULL,
  `InheritDeptRule` smallint(6) DEFAULT NULL,
  `EMPRIVILEGE` smallint(6) DEFAULT NULL,
  `CardNo` varchar(20) DEFAULT NULL,
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
  `IDCardNo` varchar(18) DEFAULT NULL,
  `IDCardValidTime` varchar(32) DEFAULT NULL,
  `EMail` varchar(100) DEFAULT NULL,
  `IDCardName` varchar(30) DEFAULT NULL,
  `IDCardBirth` varchar(16) DEFAULT NULL,
  `IDCardSN` varchar(24) DEFAULT NULL,
  `IDCardDN` varchar(24) DEFAULT NULL,
  `IDCardAddr` varchar(70) DEFAULT NULL,
  `IDCardNewAddr` varchar(255) DEFAULT NULL,
  `IDCardISSUER` varchar(32) DEFAULT NULL,
  `IDCardGender` int(11) DEFAULT NULL,
  `IDCardNation` int(11) DEFAULT NULL,
  `IDCardReserve` varchar(36) DEFAULT NULL,
  `IDCardNotice` varchar(255) DEFAULT NULL,
  `IDCard_MainCard` varchar(24) DEFAULT NULL,
  `IDCard_ViceCard` varchar(24) DEFAULT NULL,
  `FSelected` bit(1) DEFAULT NULL,
  `Pin1` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `usersmachines` */

DROP TABLE IF EXISTS `usersmachines`;

CREATE TABLE `usersmachines` (
  `ID` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `DEVICEID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `userupdates` */

DROP TABLE IF EXISTS `userupdates`;

CREATE TABLE `userupdates` (
  `UpdateId` int(11) DEFAULT NULL,
  `BadgeNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `userusedsclasses` */

DROP TABLE IF EXISTS `userusedsclasses`;

CREATE TABLE `userusedsclasses` (
  `UserId` int(11) DEFAULT NULL,
  `SchId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `zkattendancemonthstatistics` */

DROP TABLE IF EXISTS `zkattendancemonthstatistics`;

CREATE TABLE `zkattendancemonthstatistics` (
  `Id` int(11) DEFAULT NULL,
  `USERID` int(11) DEFAULT NULL,
  `PortalSite` int(11) DEFAULT NULL,
  `Department` int(11) DEFAULT NULL,
  `Employee` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL,
  `Month` int(11) DEFAULT NULL,
  `YingDao` decimal(9,1) DEFAULT NULL,
  `ShiDao` decimal(9,1) DEFAULT NULL,
  `ChiDao` decimal(9,1) DEFAULT NULL,
  `ZaoTui` decimal(9,1) DEFAULT NULL,
  `KuangGong` decimal(9,1) DEFAULT NULL,
  `JiaBan` decimal(9,1) DEFAULT NULL,
  `WaiChu` decimal(9,1) DEFAULT NULL,
  `YinGongWaiChu` decimal(9,1) DEFAULT NULL,
  `GongZuoShiJian` decimal(9,1) DEFAULT NULL,
  `YingQian` decimal(9,1) DEFAULT NULL,
  `QianDao` decimal(9,1) DEFAULT NULL,
  `QianTui` decimal(9,1) DEFAULT NULL,
  `WeiQianDao` decimal(9,1) DEFAULT NULL,
  `WeiQianTui` decimal(9,1) DEFAULT NULL,
  `QingJia` decimal(9,1) DEFAULT NULL,
  `PingRi` decimal(9,1) DEFAULT NULL,
  `ZhouMo` decimal(9,1) DEFAULT NULL,
  `JieJiaRi` decimal(9,1) DEFAULT NULL,
  `ChuQinShiJian` decimal(9,1) DEFAULT NULL,
  `PingRiJiaBan` decimal(9,1) DEFAULT NULL,
  `ZhouMoJiaBan` decimal(9,1) DEFAULT NULL,
  `JieJiaRiJiaBan` decimal(9,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
