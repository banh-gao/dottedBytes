-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Nov 06, 2011 alle 10:28
-- Versione del server: 5.1.54
-- Versione PHP: 5.3.5-1ubuntu7.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dottedBytes`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentId` bigint(20) DEFAULT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `type` text NOT NULL,
  `params` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`parentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Dump dei dati per la tabella `configurations`
--

INSERT INTO `configurations` (`id`, `parentId`, `name`, `value`, `type`, `params`, `comment`) VALUES
(1, NULL, 'system', '', '', '', ''),
(2, 1, 'email', '', '', '', ''),
(3, 2, 'smtp', '', '', '', ''),
(4, 3, 'host', 'smtp.gmail.com', 'string', '', 'The hostname of the smtp mail server'),
(5, 3, 'port', '465', 'integer', '', 'The port number of the smtp mail server'),
(6, 3, 'enable', 'true', 'boolean', '', 'Use smtp for sending email'),
(7, 3, 'useAuth', 'true', 'boolean', '', 'Use authentication for smtp server'),
(8, 3, 'username', 'dottedBytes@gmail.com', 'string', '', 'The username for the smtp authentication'),
(9, 3, 'password', 'caccamo123', 'password', '', 'The password for the smtp authentication'),
(10, 2, 'fromMail', 'dottedBytes@gmail.com', 'email', '', 'The email to display in the from field of emails'),
(11, 2, 'replyMail', 'dottedBytes@gmail.com', 'email', '', 'The email to display in the reply-to field of emails'),
(12, 1, 'site', '', '', '', ''),
(13, 12, 'name', 'DottedBytes', 'string', '', 'The name of the website'),
(14, 12, 'description', '', 'string', '', 'A generic description of the website'),
(15, 12, 'keywords', '', 'string', '', 'The keywords to associate to the website'),
(16, 12, 'template', 'basic', 'string', '', 'The name of the template to use'),
(17, 12, 'dateFormat', '%A %e %B %Y', 'string', '', 'The format of the date, according to the php date function.'),
(18, 12, 'timeFormat', '%H:%M:%S', 'string', '', 'The format of the date, according to the php date function.'),
(19, 12, 'languageCode', 'it', 'string', '', 'The 2 chars language code of the site, this is the main language.'),
(20, 1, 'module', '', '', '', ''),
(21, 20, 'default', 'contentMgr', '', '', ''),
(22, 1, 'users', '', '', '', ''),
(23, 22, 'atteptControl', '', '', '', ''),
(24, 23, 'enable', 'false', '', '', ''),
(25, 23, 'maxAttepts', '3', '', '', ''),
(26, 23, 'retryingDelay', '60', '', '', ''),
(27, 22, 'sessionExpire', '800', '', '', ''),
(28, NULL, 'module', '', '', '', ''),
(29, 28, 'contentMgr', '', '', '', ''),
(30, 29, 'comments', '', '', '', ''),
(31, 30, 'enable', 'true', '', '', ''),
(32, 30, 'enableGuestPost', 'true', '', '', ''),
(33, 29, 'category', '', '', '', ''),
(34, 33, 'previewLength', '100', 'integer', '', ''),
(35, 33, 'columns', '2', 'integer', '', ''),
(36, 33, 'pageElements', '5', '', '', ''),
(37, 33, 'popularArticles', '10', '', '', ''),
(38, 29, 'news', '', '', '', ''),
(39, 38, 'columns', '2', '', '', ''),
(40, 29, 'search', '', '', '', ''),
(41, 40, 'previewLength', '100', '', '', ''),
(42, 28, 'siteConfig', '', '', '', ''),
(43, 42, 'gAnalytics', '', '', '', ''),
(44, 43, 'enable', 'true', '', '', ''),
(45, 43, 'account', '', '', '', ''),
(46, 28, 'gallery', '', '', '', ''),
(47, 46, 'images', '', '', '', ''),
(48, 47, 'baseDir', 'images', '', '', ''),
(49, 47, 'allowedTypes', 'image/jpg,image/gif,image/jpeg', '', '', ''),
(50, 47, 'thumbWidth', '200', '', '', ''),
(51, 47, 'maxWidth', '800', '', '', ''),
(52, 46, 'pageElements', '10', '', '', ''),
(53, 28, 'userManager', '', '', '', ''),
(54, 53, 'account', '', '', '', ''),
(55, 54, 'enableOpenID', 'false', '', '', ''),
(56, 53, 'admin', '', '', '', ''),
(57, 56, 'googleKey', '', '', '', ''),
(58, 53, 'registration', '', '', '', ''),
(59, 58, 'enable', 'true', '', '', ''),
(60, 58, 'enableRecovery', 'true', '', '', ''),
(61, 58, 'uniqueMail', 'false', '', '', ''),
(62, 53, 'pm', '', '', '', ''),
(63, 62, 'enable', 'true', '', '', ''),
(64, 62, 'multipleLimit', '5', '', '', ''),
(65, 53, 'showUsersInfo', 'true', '', '', ''),
(66, 53, 'showConnectedUsers', 'true', '', '', ''),
(67, 1, 'common', '', '', '', ''),
(68, 67, 'recaptcha', '', '', '', ''),
(69, 68, 'publicKey', '6Le9xMASAAAAAD72b8Dfu57ShgDOQYsNdk3tiVBg', '', '', ''),
(70, 68, 'privateKey', '6Le9xMASAAAAAMJsvYkTGwzgBJJq5OH1ITP-CPc3', '', '', ''),
(71, 47, 'thumbHeight', '200', '', '', ''),
(72, 29, 'social', '', '', '', ''),
(73, 72, 'buttonScript', '<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:''0eece04e-a45f-40e4-9e61-387456e9a8e5''});</script>', '', '', ''),
(74, 72, 'buttonHTML', '<span  class=''st_email_vcount'' displayText=''Email''></span><span  class=''st_twitter_vcount'' displayText=''Tweet''></span><span  class=''st_facebook_vcount'' displayText=''Facebook''></span><span  class=''st_plusone_vcount'' ></span><span  class=''st_fblike_vcount'' ></span>', '', '', ''),
(75, 72, 'useButton', '1', '', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `text` longtext NOT NULL,
  `authorID` bigint(20) DEFAULT NULL,
  `creation_time` datetime NOT NULL,
  `editorID` bigint(20) DEFAULT NULL,
  `editor_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `useComments` tinyint(1) NOT NULL DEFAULT '0',
  `readed` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `authorID` (`authorID`),
  KEY `editorID` (`editorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `contents`
--

INSERT INTO `contents` (`id`, `title`, `subtitle`, `text`, `authorID`, `creation_time`, `editorID`, `editor_time`, `published`, `useComments`, `readed`) VALUES
(10, 'test123', '', '<p>\r\n	dfsdfs</p>\r\n', 5, '2011-11-06 09:06:45', NULL, '0000-00-00 00:00:00', 1, 1, 28);

-- --------------------------------------------------------

--
-- Struttura della tabella `contents_comments`
--

CREATE TABLE IF NOT EXISTS `contents_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contentID` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `comment` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `addr` text NOT NULL,
  `name` text,
  `email` text,
  PRIMARY KEY (`id`),
  KEY `contentID` (`contentID`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `content_tags`
--

CREATE TABLE IF NOT EXISTS `content_tags` (
  `contentID` bigint(20) NOT NULL,
  `tagID` bigint(20) NOT NULL,
  `options` varchar(255) NOT NULL,
  PRIMARY KEY (`contentID`,`tagID`),
  KEY `tagID` (`tagID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) DEFAULT NULL,
  `type` text NOT NULL,
  `title` text NOT NULL,
  `fileName` text NOT NULL,
  `ext` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `authorID` bigint(20) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentID` (`parentID`),
  KEY `authorID` (`authorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `gid` int(6) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`gid`, `name`, `creation`) VALUES
(1, 'Guest', '2011-01-30 22:46:02'),
(2, 'Super Administrator', '2011-01-30 22:46:02'),
(3, 'Editor', '2011-01-30 22:46:02'),
(4, 'Registered', '2011-01-30 22:46:02');

-- --------------------------------------------------------

--
-- Struttura della tabella `groups_perms`
--

CREATE TABLE IF NOT EXISTS `groups_perms` (
  `gid` int(11) NOT NULL DEFAULT '0',
  `permID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`,`permID`),
  KEY `permID` (`permID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `groups_perms`
--

INSERT INTO `groups_perms` (`gid`, `permID`) VALUES
(2, 1),
(3, 1),
(4, 1),
(1, 2),
(2, 3),
(2, 4),
(2, 6),
(3, 6),
(2, 9),
(3, 9),
(4, 9),
(2, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `listeners`
--

CREATE TABLE IF NOT EXISTS `listeners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleID` int(11) NOT NULL,
  `name` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `pattern` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`moduleID`),
  KEY `moduleID` (`moduleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `listeners`
--

INSERT INTO `listeners` (`id`, `moduleID`, `name`, `active`, `type`, `pattern`, `ordering`) VALUES
(1, 1, 'UrlRewrite', 1, 'module', '.*', 0),
(2, 1, 'EmailProtect', 1, 'module', '.*', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menuID` int(11) NOT NULL,
  `moduleID` int(6) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL,
  UNIQUE KEY `position` (`menuID`,`moduleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  `version` text NOT NULL,
  `author` text NOT NULL,
  `email` text NOT NULL,
  `site` text NOT NULL,
  `licence` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `compname` (`name`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `modules`
--

INSERT INTO `modules` (`id`, `name`, `is_core`, `active`, `options`, `version`, `author`, `email`, `site`, `licence`) VALUES
(1, 'contentMgr', 0, 1, '|default|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(2, 'userManager', 1, 1, '|online|userMenu|login|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(3, 'gallery', 0, 1, '|random|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(4, 'siteConfig', 1, 1, '', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(5, 'menu', 1, 1, '', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL');

-- --------------------------------------------------------

--
-- Struttura della tabella `panels_loaded`
--

CREATE TABLE IF NOT EXISTS `panels_loaded` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `moduleID` int(6) NOT NULL,
  `title` text NOT NULL,
  `params` text NOT NULL,
  `perm` int(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `block` tinyint(1) NOT NULL DEFAULT '0',
  `position` text NOT NULL,
  `ordering` int(6) NOT NULL DEFAULT '0',
  `modOption` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `moduleID` (`moduleID`),
  KEY `perm` (`perm`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dump dei dati per la tabella `panels_loaded`
--

INSERT INTO `panels_loaded` (`id`, `moduleID`, `title`, `params`, `perm`, `published`, `block`, `position`, `ordering`, `modOption`) VALUES
(1, 5, 'Menu principale', '1', 1, 1, 0, '1', 1, 'default'),
(2, 2, 'Utenti online', '', 1, 1, 0, '4', 4, 'online'),
(3, 2, 'Menu utente', '0', 9, 1, 0, '1', 2, 'userMenu'),
(8, 2, 'Login', '', 2, 1, 0, '1', 3, 'login'),
(10, 1, 'Articoli piu letti', '0', 1, 1, 0, '4', 0, 'popular'),
(11, 1, '', '', 1, 1, 0, '1', 3, 'search'),
(12, 1, 'Tag cloud', '', 1, 1, 0, '4', 1, 'tagCloud'),
(13, 3, '', '', 1, 1, 0, '4', 2, 'random');

-- --------------------------------------------------------

--
-- Struttura della tabella `perms`
--

CREATE TABLE IF NOT EXISTS `perms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `perms`
--

INSERT INTO `perms` (`id`, `name`, `desc`) VALUES
(1, 'default', 'Default permission'),
(2, 'guestAccess', 'Object visible only to guests'),
(3, 'userAdmin', 'Users administration'),
(4, 'groupAdmin', 'Groups administration'),
(6, 'editor', 'Write and manage articles'),
(9, 'access', 'Allow user to login'),
(10, 'siteConfig', 'Allow site and modules configuration');

-- --------------------------------------------------------

--
-- Struttura della tabella `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL,
  `sessionID` tinytext NOT NULL,
  `addr` tinytext NOT NULL,
  `msg` text NOT NULL,
  `expire` int(11) NOT NULL,
  `url` text NOT NULL,
  `referer` text NOT NULL,
  `client` text NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Dump dei dati per la tabella `session`
--

INSERT INTO `session` (`id`, `uid`, `sessionID`, `addr`, `msg`, `expire`, `url`, `referer`, `client`, `hidden`) VALUES
(55, 5, '8g3b4nikitu1m5fp2r56krjri5', '127.0.0.1', '', 1320572515, 'http://localhost/DottedBytes/index.php?section=contentMgr&itemid=10', 'http://localhost/DottedBytes/index.php?section=contentMgr&itemid=10', 'Mozilla/5.0 (X11; Linux i686; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`parent`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `tags`
--

INSERT INTO `tags` (`id`, `parent`, `name`) VALUES
(2, NULL, 'articolo 1'),
(8, NULL, 'key'),
(1, NULL, 'key1'),
(7, NULL, 'tag1');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `sendEmail` tinyint(1) NOT NULL DEFAULT '0',
  `gid` int(6) DEFAULT NULL,
  `regDate` datetime NOT NULL,
  `visitDate` datetime NOT NULL,
  `activation` text NOT NULL,
  `language` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`uid`, `name`, `username`, `email`, `password`, `sendEmail`, `gid`, `regDate`, `visitDate`, `activation`, `language`, `params`) VALUES
(5, 'Admin', 'admin', 'admin@fgdf.com', 'JSyKPGwE:c3f32d11be6bf704a212ba1177591c4ace81b06a', 0, 2, '2011-11-04 06:52:15', '2011-11-06 08:39:57', '', 'ita', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `users_login`
--

CREATE TABLE IF NOT EXISTS `users_login` (
  `uid` bigint(20) NOT NULL,
  `loginAttepts` int(6) NOT NULL,
  `lastAttept` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users_openID`
--

CREATE TABLE IF NOT EXISTS `users_openID` (
  `uid` bigint(20) NOT NULL,
  `identifier` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `users_openID`
--

INSERT INTO `users_openID` (`uid`, `identifier`, `active`) VALUES
(5, '', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users_perms`
--

CREATE TABLE IF NOT EXISTS `users_perms` (
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `permID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`permID`),
  KEY `permID` (`permID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users_pm`
--

CREATE TABLE IF NOT EXISTS `users_pm` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `fromID` bigint(20) DEFAULT NULL,
  `toID` bigint(20) DEFAULT NULL,
  `date` datetime NOT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fromID` (`fromID`),
  KEY `toID` (`toID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `configurations`
--
ALTER TABLE `configurations`
  ADD CONSTRAINT `configurations_ibfk_1` FOREIGN KEY (`parentId`) REFERENCES `configurations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`editorID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_2` FOREIGN KEY (`authorID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `contents_comments`
--
ALTER TABLE `contents_comments`
  ADD CONSTRAINT `contents_comments_ibfk_1` FOREIGN KEY (`contentID`) REFERENCES `contents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_comments_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `content_tags`
--
ALTER TABLE `content_tags`
  ADD CONSTRAINT `content_tags_ibfk_1` FOREIGN KEY (`contentID`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `content_tags_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `groups_perms`
--
ALTER TABLE `groups_perms`
  ADD CONSTRAINT `groups_perms_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_perms_ibfk_2` FOREIGN KEY (`permID`) REFERENCES `perms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `listeners`
--
ALTER TABLE `listeners`
  ADD CONSTRAINT `listeners_ibfk_1` FOREIGN KEY (`moduleID`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `panels_loaded`
--
ALTER TABLE `panels_loaded`
  ADD CONSTRAINT `panels_loaded_ibfk_1` FOREIGN KEY (`moduleID`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `users_login`
--
ALTER TABLE `users_login`
  ADD CONSTRAINT `users_login_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `users_openID`
--
ALTER TABLE `users_openID`
  ADD CONSTRAINT `users_openID_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `users_perms`
--
ALTER TABLE `users_perms`
  ADD CONSTRAINT `users_perms_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_perms_ibfk_2` FOREIGN KEY (`permID`) REFERENCES `perms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `users_pm`
--
ALTER TABLE `users_pm`
  ADD CONSTRAINT `users_pm_ibfk_1` FOREIGN KEY (`fromID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_pm_ibfk_2` FOREIGN KEY (`toID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
