-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2011 at 01:01 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `jul`
--

-- --------------------------------------------------------

--
-- Table structure for table `actionlog`
--

CREATE TABLE IF NOT EXISTS `actionlog` (
  `id` mediumint(9) NOT NULL auto_increment,
  `atime` varchar(15) NOT NULL default '',
  `adesc` mediumtext NOT NULL,
  `aip` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `user` smallint(5) unsigned NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `ip` varchar(32) NOT NULL default '',
  `title` varchar(250) NOT NULL default '',
  `text` text,
  `forum` tinyint(3) NOT NULL default '0',
  `headtext` text,
  `signtext` text,
  `edited` text,
  `headid` mediumint(6) NOT NULL default '0',
  `signid` mediumint(6) NOT NULL default '0',
  `tagval` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `forum` (`forum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blockedlayouts`
--

CREATE TABLE IF NOT EXISTS `blockedlayouts` (
  `user` smallint(5) unsigned NOT NULL default '0',
  `blockee` smallint(5) unsigned NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `minpower` tinyint(4) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dailystats`
--

CREATE TABLE IF NOT EXISTS `dailystats` (
  `date` varchar(8) NOT NULL default '',
  `users` int(11) NOT NULL default '0',
  `threads` int(11) NOT NULL default '0',
  `posts` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  PRIMARY KEY  (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `defines`
--

CREATE TABLE IF NOT EXISTS `defines` (
  `name` varchar(255) NOT NULL,
  `definition` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `d` tinyint(2) unsigned NOT NULL default '0',
  `m` tinyint(2) unsigned NOT NULL default '0',
  `y` smallint(4) unsigned NOT NULL default '0',
  `user` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `failedlogins`
--

CREATE TABLE IF NOT EXISTS `failedlogins` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time` (`time`,`username`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `failsupress`
--

CREATE TABLE IF NOT EXISTS `failsupress` (
  `ip` varchar(15) NOT NULL,
  `cnt` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `user` bigint(6) NOT NULL default '0',
  `thread` bigint(9) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forummods`
--

CREATE TABLE IF NOT EXISTS `forummods` (
  `forum` smallint(5) NOT NULL default '0',
  `user` mediumint(8) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forumread`
--

CREATE TABLE IF NOT EXISTS `forumread` (
  `user` smallint(5) unsigned NOT NULL default '0',
  `forum` tinyint(3) unsigned NOT NULL default '0',
  `readdate` int(11) NOT NULL default '0',
  UNIQUE KEY `userforum` (`user`,`forum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(250) default NULL,
  `description` text,
  `olddesc` text NOT NULL,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `minpower` tinyint(2) NOT NULL default '0',
  `minpowerthread` tinyint(2) NOT NULL default '0',
  `minpowerreply` tinyint(2) NOT NULL default '0',
  `numthreads` mediumint(8) unsigned NOT NULL default '0',
  `numposts` mediumint(8) unsigned NOT NULL default '0',
  `lastpostdate` int(11) NOT NULL default '0',
  `lastpostuser` int(11) unsigned NOT NULL default '0',
  `lastpostid` int(11) NOT NULL,
  `forder` smallint(5) NOT NULL default '0',
  `specialscheme` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`),
  KEY `minpower` (`minpower`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE IF NOT EXISTS `guests` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `ip` varchar(32) NOT NULL default '',
  `useragent` varchar(255) NOT NULL,
  `date` int(11) NOT NULL default '0',
  `lasturl` varchar(100) NOT NULL default '',
  `lastforum` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=558 ;

-- --------------------------------------------------------

--
-- Table structure for table `hits`
--

CREATE TABLE IF NOT EXISTS `hits` (
  `num` int(11) NOT NULL default '0',
  `user` mediumint(8) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  KEY `num` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ipbans`
--

CREATE TABLE IF NOT EXISTS `ipbans` (
  `ip` varchar(15) NOT NULL default '',
  `reason` varchar(100) NOT NULL default '',
  `perm` tinyint(2) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `banner` smallint(5) unsigned NOT NULL default '1',
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemcateg`
--

CREATE TABLE IF NOT EXISTS `itemcateg` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `corder` tinyint(4) NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `cat` tinyint(3) unsigned NOT NULL default '0',
  `type` tinyint(4) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `stype` varchar(9) NOT NULL default '',
  `sHP` smallint(5) NOT NULL default '100',
  `sMP` smallint(5) NOT NULL default '100',
  `sAtk` smallint(5) NOT NULL default '100',
  `sDef` smallint(5) NOT NULL default '100',
  `sInt` smallint(5) NOT NULL default '100',
  `sMDf` smallint(5) NOT NULL default '100',
  `sDex` smallint(5) NOT NULL default '100',
  `sLck` smallint(5) NOT NULL default '100',
  `sSpd` smallint(5) NOT NULL default '100',
  `effect` tinyint(4) NOT NULL,
  `coins` mediumint(8) NOT NULL default '100',
  `gcoins` int(11) NOT NULL,
  `desc` text NOT NULL,
  `user` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cat` (`cat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `itemtypes`
--

CREATE TABLE IF NOT EXISTS `itemtypes` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `ord` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jstrap`
--

CREATE TABLE IF NOT EXISTS `jstrap` (
  `loguser` smallint(6) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `text` text NOT NULL,
  `filtered` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `misc`
--

CREATE TABLE IF NOT EXISTS `misc` (
  `views` int(11) unsigned NOT NULL default '0',
  `hotcount` smallint(5) unsigned default '30',
  `maxpostsday` mediumint(7) unsigned NOT NULL default '0',
  `maxpostshour` mediumint(6) unsigned NOT NULL default '0',
  `maxpostsdaydate` int(10) unsigned NOT NULL default '0',
  `maxpostshourdate` int(10) unsigned NOT NULL default '0',
  `maxusers` smallint(5) unsigned NOT NULL default '0',
  `maxusersdate` int(10) unsigned NOT NULL default '0',
  `maxuserstext` text,
  `disable` tinyint(4) NOT NULL,
  `donations` float NOT NULL,
  `ads` float NOT NULL,
  `valkyrie` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pendingusers`
--

CREATE TABLE IF NOT EXISTS `pendingusers` (
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pmsgs`
--

CREATE TABLE IF NOT EXISTS `pmsgs` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `userto` smallint(5) unsigned NOT NULL default '0',
  `userfrom` smallint(5) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `msgread` tinyint(3) unsigned NOT NULL default '0',
  `headid` smallint(5) unsigned NOT NULL default '0',
  `signid` smallint(5) unsigned NOT NULL default '0',
  `folderto` tinyint(3) unsigned NOT NULL default '1',
  `folderfrom` tinyint(3) unsigned NOT NULL default '2',
  PRIMARY KEY  (`id`),
  KEY `userto` (`userto`),
  KEY `userfrom` (`userfrom`),
  KEY `msgread` (`msgread`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pmsgs_text`
--

CREATE TABLE IF NOT EXISTS `pmsgs_text` (
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `headtext` text NOT NULL,
  `text` mediumtext NOT NULL,
  `signtext` text NOT NULL,
  `tagval` text NOT NULL,
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL default '',
  `briefing` text NOT NULL,
  `closed` tinyint(1) NOT NULL default '0',
  `doublevote` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pollvotes`
--

CREATE TABLE IF NOT EXISTS `pollvotes` (
  `poll` int(11) NOT NULL default '0',
  `choice` int(11) NOT NULL default '0',
  `user` int(11) NOT NULL default '0',
  UNIQUE KEY `choice` (`choice`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `poll_choices`
--

CREATE TABLE IF NOT EXISTS `poll_choices` (
  `id` int(11) NOT NULL auto_increment,
  `poll` int(11) NOT NULL default '0',
  `choice` varchar(255) NOT NULL default '',
  `color` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `postlayouts`
--

CREATE TABLE IF NOT EXISTS `postlayouts` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `postradar`
--

CREATE TABLE IF NOT EXISTS `postradar` (
  `user` smallint(5) unsigned NOT NULL default '0',
  `comp` smallint(5) unsigned NOT NULL default '0',
  UNIQUE KEY `user` (`user`,`comp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` mediumint(8) NOT NULL auto_increment,
  `thread` int(10) unsigned NOT NULL default '0',
  `user` smallint(5) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '0.0.0.0',
  `num` mediumint(8) NOT NULL default '0',
  `noob` tinyint(4) NOT NULL,
  `moodid` tinyint(4) NOT NULL default '0',
  `headid` mediumint(8) unsigned NOT NULL default '0',
  `signid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `thread` (`thread`),
  KEY `date` (`date`),
  KEY `user` (`user`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `postsday`
--

CREATE TABLE IF NOT EXISTS `postsday` (
  `time` int(11) NOT NULL default '0',
  `acmlm2` int(11) NOT NULL default '0',
  `justus` int(11) NOT NULL default '0',
  UNIQUE KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts_text`
--

CREATE TABLE IF NOT EXISTS `posts_text` (
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `headtext` text,
  `text` mediumtext,
  `signtext` text,
  `tagval` text,
  `options` char(3) NOT NULL default '0|0',
  `edited` text,
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `rset` tinyint(3) unsigned NOT NULL default '1',
  `num` mediumint(8) NOT NULL default '0',
  `text` varchar(255) NOT NULL default '',
  KEY `count` (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ranksets`
--

CREATE TABLE IF NOT EXISTS `ranksets` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `referer`
--

CREATE TABLE IF NOT EXISTS `referer` (
  `time` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ref` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rendertimes`
--

CREATE TABLE IF NOT EXISTS `rendertimes` (
  `page` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `rendertime` double NOT NULL,
  KEY `page` (`page`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rpg_classes`
--

CREATE TABLE IF NOT EXISTS `rpg_classes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `sex` tinyint(4) unsigned default NULL,
  `minpowerselect` tinyint(4) default NULL,
  `HP` float unsigned NOT NULL default '1',
  `MP` float unsigned NOT NULL default '1',
  `Atk` float unsigned NOT NULL default '1',
  `Def` float unsigned NOT NULL default '1',
  `Int` float unsigned NOT NULL default '1',
  `MDf` float unsigned NOT NULL default '1',
  `Dex` float unsigned NOT NULL default '1',
  `Lck` float unsigned NOT NULL default '1',
  `Spd` float unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rpg_inventory`
--

CREATE TABLE IF NOT EXISTS `rpg_inventory` (
  `id` int(11) NOT NULL auto_increment,
  `user` mediumint(9) NOT NULL,
  `itemid` int(11) NOT NULL,
  `equippedto` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schemes`
--

CREATE TABLE IF NOT EXISTS `schemes` (
  `id` smallint(5) unsigned NOT NULL default '0',
  `ord` smallint(5) NOT NULL default '0',
  `name` varchar(50) default NULL,
  `file` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE IF NOT EXISTS `threads` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `forum` tinyint(3) unsigned NOT NULL default '0',
  `user` smallint(5) unsigned NOT NULL default '0',
  `views` int(5) unsigned NOT NULL default '0',
  `closed` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `icon` varchar(200) NOT NULL default '',
  `replies` smallint(5) unsigned NOT NULL default '0',
  `lastpostdate` int(10) NOT NULL default '0',
  `lastposter` smallint(5) unsigned NOT NULL default '0',
  `sticky` tinyint(1) unsigned NOT NULL default '0',
  `poll` smallint(5) unsigned NOT NULL default '0',
  `locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `forum` (`forum`),
  KEY `user` (`user`),
  KEY `sticky` (`sticky`),
  KEY `pollid` (`poll`),
  KEY `lastpostdate` (`lastpostdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `threadsread`
--

CREATE TABLE IF NOT EXISTS `threadsread` (
  `uid` smallint(5) unsigned NOT NULL default '0',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `time` int(11) NOT NULL,
  `read` tinyint(4) NOT NULL,
  UNIQUE KEY `combo` (`uid`,`tid`),
  KEY `read` (`read`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tinapoints`
--

CREATE TABLE IF NOT EXISTS `tinapoints` (
  `name` varchar(32) NOT NULL,
  `points` int(11) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tlayouts`
--

CREATE TABLE IF NOT EXISTS `tlayouts` (
  `id` smallint(5) unsigned NOT NULL default '0',
  `ord` smallint(5) NOT NULL default '0',
  `name` varchar(50) default NULL,
  `file` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tor`
--

CREATE TABLE IF NOT EXISTS `tor` (
  `ip` varchar(15) NOT NULL,
  `allowed` tinyint(4) NOT NULL default '0',
  `hits` int(11) NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tournamentplayers`
--

CREATE TABLE IF NOT EXISTS `tournamentplayers` (
  `tid` mediumint(9) NOT NULL,
  `pid` mediumint(9) NOT NULL,
  `cmt` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE IF NOT EXISTS `tournaments` (
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

-- --------------------------------------------------------

--
-- Table structure for table `userpic`
--

CREATE TABLE IF NOT EXISTS `userpic` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `categ` smallint(5) unsigned NOT NULL default '0',
  `url` varchar(250) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `categ` (`categ`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userpiccateg`
--

CREATE TABLE IF NOT EXISTS `userpiccateg` (
  `id` smallint(5) unsigned NOT NULL default '0',
  `page` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `userratings`
--

CREATE TABLE IF NOT EXISTS `userratings` (
  `userfrom` smallint(5) unsigned NOT NULL default '0',
  `userrated` smallint(5) unsigned NOT NULL default '0',
  `rating` smallint(5) NOT NULL default '0',
  KEY `userrated` (`userrated`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `posts` mediumint(9) NOT NULL default '0',
  `regdate` int(11) NOT NULL default '0',
  `name` varchar(25) NOT NULL default '',
  `loginname` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL default '',
  `minipic` varchar(100) NOT NULL default '',
  `picture` varchar(100) NOT NULL default '',
  `moodurl` varchar(255) NOT NULL default '',
  `postbg` varchar(250) NOT NULL default '',
  `postheader` text,
  `signature` text,
  `bio` text,
  `powerlevel` tinyint(2) NOT NULL default '0',
  `sex` tinyint(1) unsigned NOT NULL default '2',
  `oldsex` tinyint(4) NOT NULL default '-1',
  `title` varchar(255) NOT NULL default '',
  `useranks` tinyint(1) unsigned NOT NULL default '1',
  `titleoption` tinyint(1) NOT NULL default '1',
  `realname` varchar(60) NOT NULL default '',
  `location` varchar(200) NOT NULL default '',
  `birthday` int(11) NOT NULL default '0',
  `email` varchar(60) NOT NULL default '',
  `aim` varchar(30) NOT NULL default '',
  `icq` int(10) unsigned NOT NULL default '0',
  `imood` varchar(60) NOT NULL default '',
  `homepageurl` varchar(80) NOT NULL default '',
  `homepagename` varchar(100) NOT NULL default '',
  `lastposttime` int(10) unsigned NOT NULL default '0',
  `lastactivity` int(10) unsigned NOT NULL default '0',
  `lastip` varchar(15) NOT NULL default '',
  `lasturl` varchar(100) NOT NULL default '',
  `lastforum` tinyint(3) unsigned NOT NULL default '0',
  `postsperpage` smallint(4) unsigned NOT NULL default '20',
  `threadsperpage` smallint(4) unsigned NOT NULL default '50',
  `timezone` float NOT NULL default '0',
  `scheme` tinyint(2) unsigned NOT NULL default '0',
  `layout` tinyint(2) unsigned NOT NULL default '1',
  `viewsig` tinyint(1) unsigned NOT NULL default '1',
  `posttool` tinyint(1) unsigned NOT NULL default '1',
  `signsep` tinyint(3) unsigned NOT NULL default '0',
  `pagestyle` tinyint(4) NOT NULL,
  `pollstyle` tinyint(4) NOT NULL,
  `profile_locked` tinyint(1) NOT NULL default '0',
  `editing_locked` tinyint(1) NOT NULL default '0',
  `influence` int(10) unsigned NOT NULL default '1',
  `lastexp` bigint(20) NOT NULL,
  `lastannouncement` int(11) NOT NULL,
  `fancy_js` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `posts` (`posts`),
  KEY `name` (`name`),
  KEY `lastforum` (`lastforum`),
  KEY `lastposttime` (`lastposttime`),
  KEY `lastactivity` (`lastactivity`),
  KEY `powerlevel` (`powerlevel`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `users2`
--

CREATE TABLE IF NOT EXISTS `users2` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `posts` mediumint(9) NOT NULL default '0',
  `regdate` int(11) NOT NULL default '0',
  `name` varchar(25) NOT NULL default '',
  `loginname` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL default '',
  `minipic` varchar(100) NOT NULL default '',
  `picture` varchar(100) NOT NULL default '',
  `moodurl` varchar(255) NOT NULL default '',
  `postbg` varchar(250) NOT NULL default '',
  `postheader` text,
  `signature` text,
  `bio` text,
  `powerlevel` tinyint(2) NOT NULL default '0',
  `sex` tinyint(1) unsigned NOT NULL default '2',
  `oldsex` tinyint(4) NOT NULL default '-1',
  `title` varchar(255) NOT NULL default '',
  `useranks` tinyint(1) unsigned NOT NULL default '1',
  `titleoption` tinyint(1) NOT NULL default '1',
  `realname` varchar(60) NOT NULL default '',
  `location` varchar(200) NOT NULL default '',
  `birthday` int(11) NOT NULL default '0',
  `email` varchar(60) NOT NULL default '',
  `aim` varchar(30) NOT NULL default '',
  `icq` int(10) unsigned NOT NULL default '0',
  `imood` varchar(60) NOT NULL default '',
  `homepageurl` varchar(80) NOT NULL default '',
  `homepagename` varchar(100) NOT NULL default '',
  `lastposttime` int(10) unsigned NOT NULL default '0',
  `lastactivity` int(10) unsigned NOT NULL default '0',
  `lastip` varchar(15) NOT NULL default '',
  `lasturl` varchar(100) NOT NULL default '',
  `lastforum` tinyint(3) unsigned NOT NULL default '0',
  `postsperpage` smallint(4) unsigned NOT NULL default '20',
  `threadsperpage` smallint(4) unsigned NOT NULL default '50',
  `timezone` float NOT NULL default '0',
  `scheme` tinyint(2) unsigned NOT NULL default '0',
  `layout` tinyint(2) unsigned NOT NULL default '1',
  `viewsig` tinyint(1) unsigned NOT NULL default '1',
  `posttool` tinyint(1) unsigned NOT NULL default '1',
  `signsep` tinyint(3) unsigned NOT NULL default '0',
  `pagestyle` tinyint(4) NOT NULL,
  `pollstyle` tinyint(4) NOT NULL,
  `profile_locked` tinyint(1) NOT NULL default '0',
  `editing_locked` tinyint(1) NOT NULL default '0',
  `influence` int(10) unsigned NOT NULL default '1',
  `lastexp` bigint(20) NOT NULL,
  `lastannouncement` int(11) NOT NULL,
  `fancy_js` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `posts` (`posts`),
  KEY `name` (`name`),
  KEY `lastforum` (`lastforum`),
  KEY `lastposttime` (`lastposttime`),
  KEY `lastactivity` (`lastactivity`),
  KEY `powerlevel` (`powerlevel`),
  KEY `sex` (`sex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_rpg`
--

CREATE TABLE IF NOT EXISTS `users_rpg` (
  `uid` smallint(5) unsigned NOT NULL default '0',
  `class` int(11) NOT NULL,
  `damage` bigint(20) NOT NULL,
  `spent` int(11) NOT NULL default '0',
  `gcoins` int(11) NOT NULL,
  `eq1` smallint(5) unsigned NOT NULL default '0',
  `eq2` smallint(5) unsigned NOT NULL default '0',
  `eq3` smallint(5) unsigned NOT NULL default '0',
  `eq4` smallint(5) unsigned NOT NULL default '0',
  `eq5` smallint(5) unsigned NOT NULL default '0',
  `eq6` smallint(5) unsigned NOT NULL default '0',
  `eq7` smallint(6) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;











-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 10, 2011 at 01:02 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `jul`
--

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `minpower`) VALUES
(1, 'Default Category', 0);

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `title`, `description`, `olddesc`, `catid`, `minpower`, `minpowerthread`, `minpowerreply`, `numthreads`, `numposts`, `lastpostdate`, `lastpostuser`, `lastpostid`, `forder`, `specialscheme`) VALUES
(1, 'Restricted Forum', 'A restricted forum to staff and above.', '', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, ''),
(2, 'Default Forum', 'The default forum...', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 10, '');

--
-- Dumping data for table `misc`
--

INSERT INTO `misc` (`views`, `hotcount`, `maxpostsday`, `maxpostshour`, `maxpostsdaydate`, `maxpostshourdate`, `maxusers`, `maxusersdate`, `maxuserstext`, `disable`, `donations`, `ads`, `valkyrie`) VALUES
(0, 30, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0);

--
-- Dumping data for table `schemes`
--

INSERT INTO `schemes` (`id`, `ord`, `name`, `file`) VALUES
(0, 1, 'Night', 'night.php');

--
-- Dumping data for table `tlayouts`
--

INSERT INTO `tlayouts` (`id`, `ord`, `name`, `file`) VALUES
(1, 0, 'Regular', 'regular'),
(2, 2, 'Compact', 'compact'),
(3, 99, 'Hydra''s Layout&trade;', 'hydra');
