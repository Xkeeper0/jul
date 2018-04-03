-- MySQL dump 10.13  Distrib 5.6.35, for osx10.9 (x86_64)
--
-- Host: localhost    Database: jultest
-- ------------------------------------------------------
-- Server version	5.6.35-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `jultest`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `jultest` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `jultest`;

--
-- Table structure for table `actionlog`
--

DROP TABLE IF EXISTS `actionlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actionlog` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `atime` varchar(15) NOT NULL DEFAULT '',
  `adesc` mediumtext NOT NULL,
  `aip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actionlog`
--

LOCK TABLES `actionlog` WRITE;
/*!40000 ALTER TABLE `actionlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `actionlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `text` text,
  `forum` tinyint(3) NOT NULL DEFAULT '0',
  `headtext` text,
  `signtext` text,
  `edited` text,
  `headid` mediumint(6) NOT NULL DEFAULT '0',
  `signid` mediumint(6) NOT NULL DEFAULT '0',
  `tagval` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum` (`forum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockedlayouts`
--

DROP TABLE IF EXISTS `blockedlayouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blockedlayouts` (
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `blockee` smallint(5) unsigned NOT NULL DEFAULT '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockedlayouts`
--

LOCK TABLES `blockedlayouts` WRITE;
/*!40000 ALTER TABLE `blockedlayouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockedlayouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `minpower` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Default Category',0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dailystats`
--

DROP TABLE IF EXISTS `dailystats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dailystats` (
  `date` varchar(8) NOT NULL DEFAULT '',
  `users` int(11) NOT NULL DEFAULT '0',
  `threads` int(11) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dailystats`
--

LOCK TABLES `dailystats` WRITE;
/*!40000 ALTER TABLE `dailystats` DISABLE KEYS */;
INSERT INTO `dailystats` VALUES ('04-03-18',1,0,0,60),('04-04-18',1,0,0,185);
/*!40000 ALTER TABLE `dailystats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defines`
--

DROP TABLE IF EXISTS `defines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defines` (
  `name` varchar(255) NOT NULL,
  `definition` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defines`
--

LOCK TABLES `defines` WRITE;
/*!40000 ALTER TABLE `defines` DISABLE KEYS */;
/*!40000 ALTER TABLE `defines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `d` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `m` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `y` smallint(4) unsigned NOT NULL DEFAULT '0',
  `user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failedlogins`
--

DROP TABLE IF EXISTS `failedlogins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failedlogins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`,`username`,`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failedlogins`
--

LOCK TABLES `failedlogins` WRITE;
/*!40000 ALTER TABLE `failedlogins` DISABLE KEYS */;
/*!40000 ALTER TABLE `failedlogins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failsupress`
--

DROP TABLE IF EXISTS `failsupress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failsupress` (
  `ip` varchar(15) NOT NULL,
  `cnt` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failsupress`
--

LOCK TABLES `failsupress` WRITE;
/*!40000 ALTER TABLE `failsupress` DISABLE KEYS */;
/*!40000 ALTER TABLE `failsupress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `user` bigint(6) NOT NULL DEFAULT '0',
  `thread` bigint(9) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forummods`
--

DROP TABLE IF EXISTS `forummods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forummods` (
  `forum` smallint(5) NOT NULL DEFAULT '0',
  `user` mediumint(8) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forummods`
--

LOCK TABLES `forummods` WRITE;
/*!40000 ALTER TABLE `forummods` DISABLE KEYS */;
/*!40000 ALTER TABLE `forummods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumread`
--

DROP TABLE IF EXISTS `forumread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumread` (
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `forum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `readdate` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `userforum` (`user`,`forum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumread`
--

LOCK TABLES `forumread` WRITE;
/*!40000 ALTER TABLE `forumread` DISABLE KEYS */;
/*!40000 ALTER TABLE `forumread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forums`
--

DROP TABLE IF EXISTS `forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forums` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `olddesc` text NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `minpower` tinyint(2) NOT NULL DEFAULT '0',
  `minpowerthread` tinyint(2) NOT NULL DEFAULT '0',
  `minpowerreply` tinyint(2) NOT NULL DEFAULT '0',
  `numthreads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `numposts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastpostdate` int(11) NOT NULL DEFAULT '0',
  `lastpostuser` int(11) unsigned NOT NULL DEFAULT '0',
  `lastpostid` int(11) NOT NULL,
  `forder` smallint(5) NOT NULL DEFAULT '0',
  `specialscheme` varchar(32) NOT NULL DEFAULT '',
  `pollstyle` int(11) NOT NULL DEFAULT '0',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `minpower` (`minpower`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forums`
--

LOCK TABLES `forums` WRITE;
/*!40000 ALTER TABLE `forums` DISABLE KEYS */;
INSERT INTO `forums` VALUES (1,'Restricted Forum','A restricted forum to staff and above.','',1,1,1,1,1,0,0,0,0,0,'',0,0),(2,'Default Forum','The default forum...','',1,0,0,0,0,0,0,0,0,10,'',0,0);
/*!40000 ALTER TABLE `forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guests` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL,
  `date` int(11) NOT NULL DEFAULT '0',
  `lasturl` varchar(100) NOT NULL DEFAULT '',
  `lastforum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=656 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guests`
--

LOCK TABLES `guests` WRITE;
/*!40000 ALTER TABLE `guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hits`
--

DROP TABLE IF EXISTS `hits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hits` (
  `num` int(11) NOT NULL DEFAULT '0',
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT '0',
  KEY `num` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hits`
--

LOCK TABLES `hits` WRITE;
/*!40000 ALTER TABLE `hits` DISABLE KEYS */;
INSERT INTO `hits` VALUES (1,0,'127.0.0.1',1522774970),(2,0,'127.0.0.1',1522774974),(3,0,'127.0.0.1',1522775065),(4,0,'127.0.0.1',1522775691),(5,0,'127.0.0.1',1522776013),(6,0,'127.0.0.1',1522776075),(7,0,'127.0.0.1',1522776083),(8,0,'127.0.0.1',1522780849),(9,0,'127.0.0.1',1522780854),(10,0,'127.0.0.1',1522780889),(11,0,'127.0.0.1',1522780893),(12,0,'127.0.0.1',1522780903),(13,0,'127.0.0.1',1522788240),(14,0,'127.0.0.1',1522788242),(15,0,'127.0.0.1',1522788243),(16,0,'127.0.0.1',1522788243),(17,0,'127.0.0.1',1522788243),(18,0,'127.0.0.1',1522788245),(19,0,'127.0.0.1',1522788329),(20,0,'127.0.0.1',1522788329),(21,0,'127.0.0.1',1522788330),(22,0,'127.0.0.1',1522788334),(23,0,'127.0.0.1',1522788335),(24,0,'127.0.0.1',1522788335),(25,0,'127.0.0.1',1522788335),(26,0,'127.0.0.1',1522788362),(27,0,'127.0.0.1',1522788715),(28,0,'127.0.0.1',1522788718),(29,0,'127.0.0.1',1522788727),(30,0,'127.0.0.1',1522788730),(31,0,'127.0.0.1',1522790000),(32,0,'127.0.0.1',1522790072),(33,0,'127.0.0.1',1522790207),(34,0,'127.0.0.1',1522790222),(35,0,'127.0.0.1',1522791428),(36,0,'127.0.0.1',1522791433),(37,0,'127.0.0.1',1522791435),(38,0,'127.0.0.1',1522791437),(39,0,'127.0.0.1',1522791439),(40,0,'127.0.0.1',1522791440),(41,0,'127.0.0.1',1522791443),(42,0,'127.0.0.1',1522791446),(43,0,'127.0.0.1',1522791468),(44,0,'127.0.0.1',1522791487),(45,0,'127.0.0.1',1522791490),(46,0,'127.0.0.1',1522791629),(47,0,'127.0.0.1',1522791648),(48,0,'127.0.0.1',1522791671),(49,0,'127.0.0.1',1522791708),(50,0,'127.0.0.1',1522791824),(51,0,'127.0.0.1',1522791825),(52,0,'127.0.0.1',1522791829),(53,0,'127.0.0.1',1522791836),(54,0,'127.0.0.1',1522791837),(55,0,'127.0.0.1',1522791845),(56,0,'127.0.0.1',1522791846),(57,0,'127.0.0.1',1522791846),(58,0,'127.0.0.1',1522791851),(59,0,'127.0.0.1',1522791855),(60,0,'127.0.0.1',1522791856),(61,0,'127.0.0.1',1522795683),(62,0,'127.0.0.1',1522795728),(63,0,'127.0.0.1',1522795733),(64,0,'127.0.0.1',1522796216),(65,0,'127.0.0.1',1522796220),(66,0,'127.0.0.1',1522796717),(67,0,'127.0.0.1',1522796723),(68,0,'127.0.0.1',1522796725),(69,0,'127.0.0.1',1522796730),(70,0,'127.0.0.1',1522796796),(71,0,'127.0.0.1',1522796798),(72,0,'127.0.0.1',1522796812),(73,0,'127.0.0.1',1522796813),(74,0,'127.0.0.1',1522796818),(75,0,'127.0.0.1',1522796820),(76,0,'127.0.0.1',1522796950),(77,0,'127.0.0.1',1522796954),(78,0,'127.0.0.1',1522796956),(79,0,'127.0.0.1',1522796960),(80,0,'127.0.0.1',1522796965),(81,0,'127.0.0.1',1522797268),(82,0,'127.0.0.1',1522797270),(83,0,'127.0.0.1',1522797271),(84,0,'127.0.0.1',1522797276),(85,2,'127.0.0.1',1522797277),(86,2,'127.0.0.1',1522797297),(87,2,'127.0.0.1',1522797300),(88,2,'127.0.0.1',1522797347),(89,2,'127.0.0.1',1522797354),(90,2,'127.0.0.1',1522797382),(91,2,'127.0.0.1',1522797429),(92,2,'127.0.0.1',1522797437),(93,2,'127.0.0.1',1522797475),(94,2,'127.0.0.1',1522797520),(95,2,'127.0.0.1',1522797714),(96,2,'127.0.0.1',1522798176),(97,2,'127.0.0.1',1522798399),(98,2,'127.0.0.1',1522798417),(99,2,'127.0.0.1',1522798421),(100,2,'127.0.0.1',1522798907),(101,2,'127.0.0.1',1522798910),(102,2,'127.0.0.1',1522798911),(103,2,'127.0.0.1',1522798911),(104,2,'127.0.0.1',1522798913),(105,2,'127.0.0.1',1522798914),(106,2,'127.0.0.1',1522798915),(107,2,'127.0.0.1',1522798916),(108,2,'127.0.0.1',1522798918),(109,2,'127.0.0.1',1522798951),(110,2,'127.0.0.1',1522798952),(111,2,'127.0.0.1',1522798955),(112,2,'127.0.0.1',1522798957),(113,2,'127.0.0.1',1522798960),(114,2,'127.0.0.1',1522798961),(115,2,'127.0.0.1',1522798963),(116,2,'127.0.0.1',1522798964),(117,2,'127.0.0.1',1522798965),(118,2,'127.0.0.1',1522798966),(119,2,'127.0.0.1',1522798967),(120,2,'127.0.0.1',1522798968),(121,2,'127.0.0.1',1522798969),(122,2,'127.0.0.1',1522798970),(123,2,'127.0.0.1',1522798971),(124,2,'127.0.0.1',1522798971),(125,2,'127.0.0.1',1522798978),(126,2,'127.0.0.1',1522798981),(127,2,'127.0.0.1',1522798982),(128,2,'127.0.0.1',1522798983),(129,2,'127.0.0.1',1522798984),(130,2,'127.0.0.1',1522798990),(131,2,'127.0.0.1',1522798991),(132,2,'127.0.0.1',1522799013),(133,2,'127.0.0.1',1522799027),(134,2,'127.0.0.1',1522799106),(135,2,'127.0.0.1',1522799173),(136,2,'127.0.0.1',1522799176),(137,2,'127.0.0.1',1522799185),(138,2,'127.0.0.1',1522799196),(139,2,'127.0.0.1',1522799196),(140,2,'127.0.0.1',1522799197),(141,2,'127.0.0.1',1522799199),(142,2,'127.0.0.1',1522799200),(143,2,'127.0.0.1',1522799200),(144,2,'127.0.0.1',1522799201),(145,2,'127.0.0.1',1522799202),(146,2,'127.0.0.1',1522799204),(147,2,'127.0.0.1',1522799209),(148,2,'127.0.0.1',1522799234),(149,2,'127.0.0.1',1522799277),(150,2,'127.0.0.1',1522799282),(151,2,'127.0.0.1',1522799284),(152,2,'127.0.0.1',1522799469),(153,2,'127.0.0.1',1522799539),(154,2,'127.0.0.1',1522799567),(155,2,'127.0.0.1',1522799575),(156,2,'127.0.0.1',1522799577),(157,2,'127.0.0.1',1522799579),(158,2,'127.0.0.1',1522799581),(159,2,'127.0.0.1',1522799604),(160,2,'127.0.0.1',1522799606),(161,2,'127.0.0.1',1522799607),(162,2,'127.0.0.1',1522799608),(163,2,'127.0.0.1',1522799614),(164,2,'127.0.0.1',1522799622),(165,2,'127.0.0.1',1522799624),(166,2,'127.0.0.1',1522799625),(167,2,'127.0.0.1',1522799629),(168,2,'127.0.0.1',1522799633),(169,2,'127.0.0.1',1522799639),(170,2,'127.0.0.1',1522799642),(171,2,'127.0.0.1',1522799646),(172,2,'127.0.0.1',1522799647),(173,2,'127.0.0.1',1522799648),(174,2,'127.0.0.1',1522799650),(175,2,'127.0.0.1',1522799652),(176,2,'127.0.0.1',1522799654),(177,2,'127.0.0.1',1522799683),(178,2,'127.0.0.1',1522799688),(179,2,'127.0.0.1',1522799689),(180,0,'127.0.0.1',1522799690),(181,0,'127.0.0.1',1522799692),(182,0,'127.0.0.1',1522799693),(183,0,'127.0.0.1',1522799694),(184,0,'127.0.0.1',1522799698),(185,2,'127.0.0.1',1522799699);
/*!40000 ALTER TABLE `hits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipbans`
--

DROP TABLE IF EXISTS `ipbans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipbans` (
  `ip` varchar(15) NOT NULL DEFAULT '',
  `reason` varchar(100) NOT NULL DEFAULT '',
  `perm` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `banner` smallint(5) unsigned NOT NULL DEFAULT '1',
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipbans`
--

LOCK TABLES `ipbans` WRITE;
/*!40000 ALTER TABLE `ipbans` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipbans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemcateg`
--

DROP TABLE IF EXISTS `itemcateg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemcateg` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `corder` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemcateg`
--

LOCK TABLES `itemcateg` WRITE;
/*!40000 ALTER TABLE `itemcateg` DISABLE KEYS */;
/*!40000 ALTER TABLE `itemcateg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `stype` varchar(9) NOT NULL DEFAULT '',
  `sHP` smallint(5) NOT NULL DEFAULT '100',
  `sMP` smallint(5) NOT NULL DEFAULT '100',
  `sAtk` smallint(5) NOT NULL DEFAULT '100',
  `sDef` smallint(5) NOT NULL DEFAULT '100',
  `sInt` smallint(5) NOT NULL DEFAULT '100',
  `sMDf` smallint(5) NOT NULL DEFAULT '100',
  `sDex` smallint(5) NOT NULL DEFAULT '100',
  `sLck` smallint(5) NOT NULL DEFAULT '100',
  `sSpd` smallint(5) NOT NULL DEFAULT '100',
  `effect` tinyint(4) NOT NULL,
  `coins` mediumint(8) NOT NULL DEFAULT '100',
  `gcoins` int(11) NOT NULL,
  `desc` text NOT NULL,
  `user` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat` (`cat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemtypes`
--

DROP TABLE IF EXISTS `itemtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemtypes` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ord` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemtypes`
--

LOCK TABLES `itemtypes` WRITE;
/*!40000 ALTER TABLE `itemtypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `itemtypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jstrap`
--

DROP TABLE IF EXISTS `jstrap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jstrap` (
  `loguser` smallint(6) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `text` text NOT NULL,
  `filtered` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jstrap`
--

LOCK TABLES `jstrap` WRITE;
/*!40000 ALTER TABLE `jstrap` DISABLE KEYS */;
/*!40000 ALTER TABLE `jstrap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `misc`
--

DROP TABLE IF EXISTS `misc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `misc` (
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `hotcount` smallint(5) unsigned DEFAULT '30',
  `maxpostsday` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `maxpostshour` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `maxpostsdaydate` int(10) unsigned NOT NULL DEFAULT '0',
  `maxpostshourdate` int(10) unsigned NOT NULL DEFAULT '0',
  `maxusers` smallint(5) unsigned NOT NULL DEFAULT '0',
  `maxusersdate` int(10) unsigned NOT NULL DEFAULT '0',
  `maxuserstext` text,
  `disable` tinyint(4) NOT NULL,
  `donations` float NOT NULL,
  `ads` float NOT NULL,
  `valkyrie` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `misc`
--

LOCK TABLES `misc` WRITE;
/*!40000 ALTER TABLE `misc` DISABLE KEYS */;
INSERT INTO `misc` VALUES (0,30,0,0,0,0,1,1522780893,': <a style=\'color:#7C60B0;\' href=\'profile.php?id=2\'>admin</a>',0,0,0,0);
/*!40000 ALTER TABLE `misc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendingusers`
--

DROP TABLE IF EXISTS `pendingusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pendingusers` (
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendingusers`
--

LOCK TABLES `pendingusers` WRITE;
/*!40000 ALTER TABLE `pendingusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `pendingusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pmsgs`
--

DROP TABLE IF EXISTS `pmsgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pmsgs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `userto` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userfrom` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `msgread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `headid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `signid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `folderto` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `folderfrom` tinyint(3) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `userto` (`userto`),
  KEY `userfrom` (`userfrom`),
  KEY `msgread` (`msgread`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pmsgs`
--

LOCK TABLES `pmsgs` WRITE;
/*!40000 ALTER TABLE `pmsgs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pmsgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pmsgs_text`
--

DROP TABLE IF EXISTS `pmsgs_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pmsgs_text` (
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `headtext` text NOT NULL,
  `text` mediumtext NOT NULL,
  `signtext` text NOT NULL,
  `tagval` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pmsgs_text`
--

LOCK TABLES `pmsgs_text` WRITE;
/*!40000 ALTER TABLE `pmsgs_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `pmsgs_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `briefing` text NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `doublevote` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll`
--

LOCK TABLES `poll` WRITE;
/*!40000 ALTER TABLE `poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_choices`
--

DROP TABLE IF EXISTS `poll_choices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_choices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll` int(11) NOT NULL DEFAULT '0',
  `choice` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_choices`
--

LOCK TABLES `poll_choices` WRITE;
/*!40000 ALTER TABLE `poll_choices` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_choices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pollvotes`
--

DROP TABLE IF EXISTS `pollvotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollvotes` (
  `poll` int(11) NOT NULL DEFAULT '0',
  `choice` int(11) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `choice` (`choice`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollvotes`
--

LOCK TABLES `pollvotes` WRITE;
/*!40000 ALTER TABLE `pollvotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `pollvotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postlayouts`
--

DROP TABLE IF EXISTS `postlayouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postlayouts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postlayouts`
--

LOCK TABLES `postlayouts` WRITE;
/*!40000 ALTER TABLE `postlayouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `postlayouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postradar`
--

DROP TABLE IF EXISTS `postradar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postradar` (
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comp` smallint(5) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `user` (`user`,`comp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postradar`
--

LOCK TABLES `postradar` WRITE;
/*!40000 ALTER TABLE `postradar` DISABLE KEYS */;
/*!40000 ALTER TABLE `postradar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `thread` int(10) unsigned NOT NULL DEFAULT '0',
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '0.0.0.0',
  `num` mediumint(8) NOT NULL DEFAULT '0',
  `noob` tinyint(4) NOT NULL,
  `moodid` tinyint(4) NOT NULL DEFAULT '0',
  `headid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `signid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `thread` (`thread`),
  KEY `date` (`date`),
  KEY `user` (`user`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_text`
--

DROP TABLE IF EXISTS `posts_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_text` (
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `headtext` text,
  `text` mediumtext,
  `signtext` text,
  `tagval` text,
  `options` char(3) NOT NULL DEFAULT '0|0',
  `edited` text,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_text`
--

LOCK TABLES `posts_text` WRITE;
/*!40000 ALTER TABLE `posts_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postsday`
--

DROP TABLE IF EXISTS `postsday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postsday` (
  `time` int(11) NOT NULL DEFAULT '0',
  `acmlm2` int(11) NOT NULL DEFAULT '0',
  `justus` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postsday`
--

LOCK TABLES `postsday` WRITE;
/*!40000 ALTER TABLE `postsday` DISABLE KEYS */;
/*!40000 ALTER TABLE `postsday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ranks`
--

DROP TABLE IF EXISTS `ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ranks` (
  `rset` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `num` mediumint(8) NOT NULL DEFAULT '0',
  `text` varchar(255) NOT NULL DEFAULT '',
  KEY `count` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ranks`
--

LOCK TABLES `ranks` WRITE;
/*!40000 ALTER TABLE `ranks` DISABLE KEYS */;
/*!40000 ALTER TABLE `ranks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ranksets`
--

DROP TABLE IF EXISTS `ranksets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ranksets` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ranksets`
--

LOCK TABLES `ranksets` WRITE;
/*!40000 ALTER TABLE `ranksets` DISABLE KEYS */;
/*!40000 ALTER TABLE `ranksets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referer`
--

DROP TABLE IF EXISTS `referer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referer` (
  `time` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referer`
--

LOCK TABLES `referer` WRITE;
/*!40000 ALTER TABLE `referer` DISABLE KEYS */;
INSERT INTO `referer` VALUES (1522776075,'/jul/memberlist.php','http://michiel/jul/','127.0.0.1'),(1522780849,'/jul/index.php','http://michiel/','127.0.0.1'),(1522780854,'/jul/register.php','http://michiel/jul/','127.0.0.1'),(1522780889,'/jul/register.php','http://michiel/jul/register.php','127.0.0.1'),(1522780893,'/jul/index.php','http://michiel/jul/register.php','127.0.0.1'),(1522791433,'/jul/smilies.php','http://michiel/jul/','127.0.0.1'),(1522791437,'/jul/hex.php','http://michiel/jul/','127.0.0.1'),(1522791440,'/jul/stats.php','http://michiel/jul/','127.0.0.1'),(1522791490,'/jul/ranks.php','http://michiel/jul/faq.php','127.0.0.1'),(1522791825,'/jul/activeusers.php','http://michiel/jul/calendar.php','127.0.0.1'),(1522791829,'/jul/memberlist.php','http://michiel/jul/activeusers.php','127.0.0.1'),(1522791836,'/jul/index.php','http://michiel/jul/memberlist.php','127.0.0.1'),(1522791837,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791845,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791846,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791846,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791851,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791855,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522791856,'/jul/irc.php','http://michiel/jul/','127.0.0.1'),(1522795683,'/jul/index.php','http://michiel/jul/irc.php','127.0.0.1'),(1522795728,'/jul/login.php','http://michiel/jul/','127.0.0.1'),(1522795733,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796220,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796723,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796725,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522796730,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522796796,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796798,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522796813,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522796818,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796820,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522796950,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522796954,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796956,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522796960,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522796965,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522796967,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522797066,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522797067,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522797072,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522797136,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522797138,'/jul/index.php','http://michiel/jul/','127.0.0.1'),(1522797150,'/jul/index.php','http://michiel/jul/','127.0.0.1'),(1522797219,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522797226,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522797268,'/jul/index.php','http://michiel/jul/index.php','127.0.0.1'),(1522797270,'/jul/index.php','http://michiel/jul/','127.0.0.1'),(1522797271,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522797276,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522797277,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522797297,'/jul/online.php','http://michiel/jul/index.php','127.0.0.1'),(1522797300,'/jul/profile.php?id=2','http://michiel/jul/online.php','127.0.0.1'),(1522798417,'/jul/index.php','http://michiel/jul/profile.php?id=2','127.0.0.1'),(1522798421,'/jul/shop.php','http://michiel/jul/','127.0.0.1'),(1522798910,'/jul/forum.php?fav=1','http://michiel/jul/forum.php?fav=1','127.0.0.1'),(1522798911,'/jul/activeusers.php','http://michiel/jul/forum.php?fav=1','127.0.0.1'),(1522798911,'/jul/memberlist.php','http://michiel/jul/activeusers.php','127.0.0.1'),(1522798913,'/jul/index.php','http://michiel/jul/memberlist.php','127.0.0.1'),(1522798914,'/jul/ranks.php','http://michiel/jul/index.php','127.0.0.1'),(1522798915,'/jul/faq.php','http://michiel/jul/ranks.php','127.0.0.1'),(1522798916,'/jul/stats.php','http://michiel/jul/faq.php','127.0.0.1'),(1522798918,'/jul/faq.php','http://michiel/jul/stats.php','127.0.0.1'),(1522798951,'/jul/faq.php','http://michiel/jul/stats.php','127.0.0.1'),(1522798952,'/jul/online.php','http://michiel/jul/faq.php','127.0.0.1'),(1522798955,'/jul/smilies.php','http://michiel/jul/online.php','127.0.0.1'),(1522798957,'/jul/online.php','http://michiel/jul/faq.php','127.0.0.1'),(1522798960,'/jul/online.php?time=60','http://michiel/jul/online.php','127.0.0.1'),(1522798961,'/jul/online.php?time=300','http://michiel/jul/online.php?time=60','127.0.0.1'),(1522798963,'/jul/smilies.php','http://michiel/jul/online.php?time=300','127.0.0.1'),(1522798964,'/jul/online.php?time=300','http://michiel/jul/online.php?time=60','127.0.0.1'),(1522798965,'/jul/latestposts.php','http://michiel/jul/online.php?time=300','127.0.0.1'),(1522798966,'/jul/stats.php','http://michiel/jul/latestposts.php','127.0.0.1'),(1522798967,'/jul/faq.php','http://michiel/jul/stats.php','127.0.0.1'),(1522798968,'/jul/ranks.php','http://michiel/jul/faq.php','127.0.0.1'),(1522798969,'/jul/index.php','http://michiel/jul/ranks.php','127.0.0.1'),(1522798970,'/jul/memberlist.php','http://michiel/jul/index.php','127.0.0.1'),(1522798971,'/jul/activeusers.php','http://michiel/jul/memberlist.php','127.0.0.1'),(1522798971,'/jul/calendar.php','http://michiel/jul/activeusers.php','127.0.0.1'),(1522798978,'/jul/irc.php','http://michiel/jul/calendar.php','127.0.0.1'),(1522798981,'/jul/online.php','http://michiel/jul/irc.php','127.0.0.1'),(1522798982,'/jul/forum.php?fav=1','http://michiel/jul/online.php','127.0.0.1'),(1522798983,'/jul/shop.php','http://michiel/jul/forum.php?fav=1','127.0.0.1'),(1522798984,'/jul/postradar.php','http://michiel/jul/shop.php','127.0.0.1'),(1522798990,'/jul/postradar.php','http://michiel/jul/postradar.php','127.0.0.1'),(1522798991,'/jul/editprofile.php','http://michiel/jul/postradar.php','127.0.0.1'),(1522799013,'/jul/index.php','http://michiel/jul/editprofile.php','127.0.0.1'),(1522799176,'/jul/index.php','http://michiel/jul/announcement.php','127.0.0.1'),(1522799185,'/jul/shoped.php','http://michiel/jul/index.php','127.0.0.1'),(1522799196,'/jul/shoped.php?cat=1','http://michiel/jul/shoped.php','127.0.0.1'),(1522799196,'/jul/shoped.php?cat=2','http://michiel/jul/shoped.php?cat=1','127.0.0.1'),(1522799197,'/jul/shoped.php?cat=3','http://michiel/jul/shoped.php?cat=2','127.0.0.1'),(1522799199,'/jul/shoped.php?cat=4','http://michiel/jul/shoped.php?cat=3','127.0.0.1'),(1522799200,'/jul/shoped.php?cat=5','http://michiel/jul/shoped.php?cat=4','127.0.0.1'),(1522799200,'/jul/shoped.php?cat=6','http://michiel/jul/shoped.php?cat=5','127.0.0.1'),(1522799201,'/jul/shoped.php?cat=7','http://michiel/jul/shoped.php?cat=6','127.0.0.1'),(1522799202,'/jul/shoped.php?cat=99','http://michiel/jul/shoped.php?cat=7','127.0.0.1'),(1522799204,'/jul/shoped.php?cat=99&id=-1','http://michiel/jul/shoped.php?cat=99','127.0.0.1'),(1522799209,'/jul/admin.php','http://michiel/jul/shoped.php?cat=99&id=-1','127.0.0.1'),(1522799234,'/jul/admin-editforums.php','http://michiel/jul/admin.php','127.0.0.1'),(1522799277,'/jul/index.php','http://michiel/jul/admin-editforums.php','127.0.0.1'),(1522799282,'/jul/admin.php','http://michiel/jul/','127.0.0.1'),(1522799284,'/jul/admin-editforums.php','http://michiel/jul/admin.php','127.0.0.1'),(1522799567,'/jul/index.php','http://michiel/jul/forum.php?id=1','127.0.0.1'),(1522799575,'/jul/forum.php?id=2','http://michiel/jul/index.php','127.0.0.1'),(1522799577,'/jul/index.php','http://michiel/jul/forum.php?id=1','127.0.0.1'),(1522799579,'/jul/forum.php?id=1','http://michiel/jul/index.php','127.0.0.1'),(1522799581,'/jul/index.php','http://michiel/jul/forum.php?id=1','127.0.0.1'),(1522799606,'/jul/private.php?view=sent','http://michiel/jul/private.php','127.0.0.1'),(1522799607,'/jul/private.php','http://michiel/jul/private.php?view=sent','127.0.0.1'),(1522799608,'/jul/sendprivate.php','http://michiel/jul/private.php?','127.0.0.1'),(1522799614,'/jul/index.php','http://michiel/jul/sendprivate.php','127.0.0.1'),(1522799622,'/jul/index.php','http://michiel/jul/sendprivate.php','127.0.0.1'),(1522799624,'/jul/shoped.php','http://michiel/jul/','127.0.0.1'),(1522799625,'/jul/admin.php','http://michiel/jul/shoped.php','127.0.0.1'),(1522799629,'/jul/admin-editmods.php','http://michiel/jul/admin.php','127.0.0.1'),(1522799633,'/jul/ipsearch.php','http://michiel/jul/admin-editmods.php','127.0.0.1'),(1522799639,'/jul/ipsearch.php','http://michiel/jul/ipsearch.php','127.0.0.1'),(1522799642,'/jul/profile.php?id=2','http://michiel/jul/ipsearch.php','127.0.0.1'),(1522799647,'/jul/admin-threads.php','http://michiel/jul/ipsearch.php','127.0.0.1'),(1522799648,'/jul/admin-threads.php','http://michiel/jul/admin-threads.php','127.0.0.1'),(1522799650,'/jul/admin-threads.php','http://michiel/jul/admin-threads.php','127.0.0.1'),(1522799652,'/jul/admin-threads2.php','http://michiel/jul/admin-threads.php','127.0.0.1'),(1522799654,'/jul/admin-threads2.php','http://michiel/jul/admin-threads2.php','127.0.0.1'),(1522799683,'/jul/del.php','http://michiel/jul/admin-threads2.php','127.0.0.1'),(1522799688,'/jul/index.php','http://michiel/jul/del.php','127.0.0.1'),(1522799689,'/jul/login.php','http://michiel/jul/','127.0.0.1'),(1522799690,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522799692,'/jul/forum.php?id=2','http://michiel/jul/index.php','127.0.0.1'),(1522799693,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1'),(1522799694,'/jul/login.php','http://michiel/jul/index.php','127.0.0.1'),(1522799698,'/jul/login.php','http://michiel/jul/login.php','127.0.0.1'),(1522799699,'/jul/index.php','http://michiel/jul/login.php','127.0.0.1');
/*!40000 ALTER TABLE `referer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendertimes`
--

DROP TABLE IF EXISTS `rendertimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendertimes` (
  `page` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `rendertime` double NOT NULL,
  KEY `page` (`page`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendertimes`
--

LOCK TABLES `rendertimes` WRITE;
/*!40000 ALTER TABLE `rendertimes` DISABLE KEYS */;
/*!40000 ALTER TABLE `rendertimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rpg_classes`
--

DROP TABLE IF EXISTS `rpg_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpg_classes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `sex` tinyint(4) unsigned DEFAULT NULL,
  `minpowerselect` tinyint(4) DEFAULT NULL,
  `HP` float unsigned NOT NULL DEFAULT '1',
  `MP` float unsigned NOT NULL DEFAULT '1',
  `Atk` float unsigned NOT NULL DEFAULT '1',
  `Def` float unsigned NOT NULL DEFAULT '1',
  `Int` float unsigned NOT NULL DEFAULT '1',
  `MDf` float unsigned NOT NULL DEFAULT '1',
  `Dex` float unsigned NOT NULL DEFAULT '1',
  `Lck` float unsigned NOT NULL DEFAULT '1',
  `Spd` float unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rpg_classes`
--

LOCK TABLES `rpg_classes` WRITE;
/*!40000 ALTER TABLE `rpg_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpg_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rpg_inventory`
--

DROP TABLE IF EXISTS `rpg_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpg_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` mediumint(9) NOT NULL,
  `itemid` int(11) NOT NULL,
  `equippedto` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rpg_inventory`
--

LOCK TABLES `rpg_inventory` WRITE;
/*!40000 ALTER TABLE `rpg_inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpg_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schemes`
--

DROP TABLE IF EXISTS `schemes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schemes` (
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ord` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `file` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schemes`
--

LOCK TABLES `schemes` WRITE;
/*!40000 ALTER TABLE `schemes` DISABLE KEYS */;
INSERT INTO `schemes` VALUES (0,1,'Night','night.php');
/*!40000 ALTER TABLE `schemes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` int(5) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `replies` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lastpostdate` int(10) NOT NULL DEFAULT '0',
  `lastposter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `poll` smallint(5) unsigned NOT NULL DEFAULT '0',
  `locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forum` (`forum`),
  KEY `user` (`user`),
  KEY `sticky` (`sticky`),
  KEY `pollid` (`poll`),
  KEY `lastpostdate` (`lastpostdate`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `threads`
--

LOCK TABLES `threads` WRITE;
/*!40000 ALTER TABLE `threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `threadsread`
--

DROP TABLE IF EXISTS `threadsread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `threadsread` (
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `read` tinyint(4) NOT NULL,
  UNIQUE KEY `combo` (`uid`,`tid`),
  KEY `read` (`read`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `threadsread`
--

LOCK TABLES `threadsread` WRITE;
/*!40000 ALTER TABLE `threadsread` DISABLE KEYS */;
/*!40000 ALTER TABLE `threadsread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tinapoints`
--

DROP TABLE IF EXISTS `tinapoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tinapoints` (
  `name` varchar(32) NOT NULL,
  `points` int(11) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tinapoints`
--

LOCK TABLES `tinapoints` WRITE;
/*!40000 ALTER TABLE `tinapoints` DISABLE KEYS */;
/*!40000 ALTER TABLE `tinapoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tlayouts`
--

DROP TABLE IF EXISTS `tlayouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tlayouts` (
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ord` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `file` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tlayouts`
--

LOCK TABLES `tlayouts` WRITE;
/*!40000 ALTER TABLE `tlayouts` DISABLE KEYS */;
INSERT INTO `tlayouts` VALUES (1,0,'Regular','regular'),(2,2,'Compact','compact'),(3,99,'Hydra\'s Layout&trade;','hydra');
/*!40000 ALTER TABLE `tlayouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tor`
--

DROP TABLE IF EXISTS `tor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tor` (
  `ip` varchar(15) NOT NULL,
  `allowed` tinyint(4) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tor`
--

LOCK TABLES `tor` WRITE;
/*!40000 ALTER TABLE `tor` DISABLE KEYS */;
/*!40000 ALTER TABLE `tor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournamentplayers`
--

DROP TABLE IF EXISTS `tournamentplayers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournamentplayers` (
  `tid` mediumint(9) NOT NULL,
  `pid` mediumint(9) NOT NULL,
  `cmt` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournamentplayers`
--

LOCK TABLES `tournamentplayers` WRITE;
/*!40000 ALTER TABLE `tournamentplayers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournamentplayers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tournaments` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `description` text NOT NULL,
  `scorehide` tinyint(4) NOT NULL,
  `scoretype` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `organizer` mediumint(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournaments`
--

LOCK TABLES `tournaments` WRITE;
/*!40000 ALTER TABLE `tournaments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tournaments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userpic`
--

DROP TABLE IF EXISTS `userpic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userpic` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `categ` smallint(5) unsigned NOT NULL DEFAULT '0',
  `url` varchar(250) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `categ` (`categ`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userpic`
--

LOCK TABLES `userpic` WRITE;
/*!40000 ALTER TABLE `userpic` DISABLE KEYS */;
/*!40000 ALTER TABLE `userpic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userpiccateg`
--

DROP TABLE IF EXISTS `userpiccateg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userpiccateg` (
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `page` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userpiccateg`
--

LOCK TABLES `userpiccateg` WRITE;
/*!40000 ALTER TABLE `userpiccateg` DISABLE KEYS */;
/*!40000 ALTER TABLE `userpiccateg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userratings`
--

DROP TABLE IF EXISTS `userratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userratings` (
  `userfrom` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userrated` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rating` smallint(5) NOT NULL DEFAULT '0',
  KEY `userrated` (`userrated`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userratings`
--

LOCK TABLES `userratings` WRITE;
/*!40000 ALTER TABLE `userratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `userratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `posts` mediumint(9) NOT NULL DEFAULT '0',
  `regdate` int(11) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL DEFAULT '',
  `loginname` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `minipic` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(100) NOT NULL DEFAULT '',
  `moodurl` varchar(255) NOT NULL DEFAULT '',
  `postbg` varchar(250) NOT NULL DEFAULT '',
  `postheader` text,
  `signature` text,
  `bio` text,
  `powerlevel` tinyint(2) NOT NULL DEFAULT '0',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `oldsex` tinyint(4) NOT NULL DEFAULT '-1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `useranks` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `titleoption` tinyint(1) NOT NULL DEFAULT '1',
  `realname` varchar(60) NOT NULL DEFAULT '',
  `location` varchar(200) NOT NULL DEFAULT '',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `aim` varchar(30) NOT NULL DEFAULT '',
  `icq` int(10) unsigned NOT NULL DEFAULT '0',
  `imood` varchar(60) NOT NULL DEFAULT '',
  `homepageurl` varchar(80) NOT NULL DEFAULT '',
  `homepagename` varchar(100) NOT NULL DEFAULT '',
  `lastposttime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `lasturl` varchar(100) NOT NULL DEFAULT '',
  `lastforum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `postsperpage` smallint(4) unsigned NOT NULL DEFAULT '20',
  `threadsperpage` smallint(4) unsigned NOT NULL DEFAULT '50',
  `timezone` float NOT NULL DEFAULT '0',
  `scheme` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `layout` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `viewsig` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `posttool` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `signsep` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pagestyle` tinyint(4) NOT NULL,
  `pollstyle` tinyint(4) NOT NULL,
  `profile_locked` tinyint(1) NOT NULL DEFAULT '0',
  `editing_locked` tinyint(1) NOT NULL DEFAULT '0',
  `influence` int(10) unsigned NOT NULL DEFAULT '1',
  `lastexp` bigint(20) NOT NULL,
  `lastannouncement` int(11) NOT NULL,
  `fancy_js` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateformat` varchar(32) NOT NULL,
  `dateshort` varchar(32) NOT NULL,
  `aka` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `posts` (`posts`),
  KEY `name` (`name`),
  KEY `lastforum` (`lastforum`),
  KEY `lastposttime` (`lastposttime`),
  KEY `lastactivity` (`lastactivity`),
  KEY `powerlevel` (`powerlevel`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,0,1522780889,'admin','','$2y$10$YqfABVCBBo5xpiiNIhUpfORGOPLyWuboFU/PFymnQJwAERKQj5VDy','','','','',NULL,NULL,NULL,3,2,-1,'',1,1,'','',0,'','',0,'','','',0,1522799699,'127.0.0.1','/jul/index.php',0,20,50,0,0,1,1,1,0,0,0,0,0,1,0,0,0,'','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users2`
--

DROP TABLE IF EXISTS `users2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users2` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `posts` mediumint(9) NOT NULL DEFAULT '0',
  `regdate` int(11) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL DEFAULT '',
  `loginname` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '',
  `minipic` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(100) NOT NULL DEFAULT '',
  `moodurl` varchar(255) NOT NULL DEFAULT '',
  `postbg` varchar(250) NOT NULL DEFAULT '',
  `postheader` text,
  `signature` text,
  `bio` text,
  `powerlevel` tinyint(2) NOT NULL DEFAULT '0',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `oldsex` tinyint(4) NOT NULL DEFAULT '-1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `useranks` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `titleoption` tinyint(1) NOT NULL DEFAULT '1',
  `realname` varchar(60) NOT NULL DEFAULT '',
  `location` varchar(200) NOT NULL DEFAULT '',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL DEFAULT '',
  `aim` varchar(30) NOT NULL DEFAULT '',
  `icq` int(10) unsigned NOT NULL DEFAULT '0',
  `imood` varchar(60) NOT NULL DEFAULT '',
  `homepageurl` varchar(80) NOT NULL DEFAULT '',
  `homepagename` varchar(100) NOT NULL DEFAULT '',
  `lastposttime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `lasturl` varchar(100) NOT NULL DEFAULT '',
  `lastforum` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `postsperpage` smallint(4) unsigned NOT NULL DEFAULT '20',
  `threadsperpage` smallint(4) unsigned NOT NULL DEFAULT '50',
  `timezone` float NOT NULL DEFAULT '0',
  `scheme` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `layout` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `viewsig` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `posttool` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `signsep` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pagestyle` tinyint(4) NOT NULL,
  `pollstyle` tinyint(4) NOT NULL,
  `profile_locked` tinyint(1) NOT NULL DEFAULT '0',
  `editing_locked` tinyint(1) NOT NULL DEFAULT '0',
  `influence` int(10) unsigned NOT NULL DEFAULT '1',
  `lastexp` bigint(20) NOT NULL,
  `lastannouncement` int(11) NOT NULL,
  `fancy_js` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `posts` (`posts`),
  KEY `name` (`name`),
  KEY `lastforum` (`lastforum`),
  KEY `lastposttime` (`lastposttime`),
  KEY `lastactivity` (`lastactivity`),
  KEY `powerlevel` (`powerlevel`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users2`
--

LOCK TABLES `users2` WRITE;
/*!40000 ALTER TABLE `users2` DISABLE KEYS */;
/*!40000 ALTER TABLE `users2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_rpg`
--

DROP TABLE IF EXISTS `users_rpg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_rpg` (
  `uid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `class` int(11) NOT NULL,
  `damage` bigint(20) NOT NULL,
  `spent` int(11) NOT NULL DEFAULT '0',
  `gcoins` int(11) NOT NULL,
  `eq1` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq2` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq3` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq4` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq5` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq6` smallint(5) unsigned NOT NULL DEFAULT '0',
  `eq7` smallint(6) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_rpg`
--

LOCK TABLES `users_rpg` WRITE;
/*!40000 ALTER TABLE `users_rpg` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_rpg` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-03 22:58:27
