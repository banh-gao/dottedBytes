SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

--
-- Dump dei dati per la tabella `configurations`
--

INSERT INTO `configurations` (`id`, `parentId`, `name`, `value`, `type`, `params`, `comment`) VALUES
(1, NULL, 'system', '', '', '', ''),
(2, 1, 'email', '', '', '', ''),
(3, 2, 'smtp', '', '', '', ''),
(4, 3, 'host', '', 'string', '', 'The hostname of the smtp mail server'),
(5, 3, 'port', '', 'integer', '', 'The port number of the smtp mail server'),
(6, 3, 'enable', 'true', 'boolean', '', 'Use smtp for sending email'),
(7, 3, 'useAuth', 'true', 'boolean', '', 'Use authentication for smtp server'),
(8, 3, 'username', '', 'string', '', 'The username for the smtp authentication'),
(9, 3, 'password', '', 'password', '', 'The password for the smtp authentication'),
(10, 2, 'fromMail', '', 'email', '', 'The email to display in the from field of emails'),
(11, 2, 'replyMail', '', 'email', '', 'The email to display in the reply-to field of emails'),
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
(69, 68, 'publicKey', '', '', '', ''),
(70, 68, 'privateKey', '', '', '', ''),
(71, 47, 'thumbHeight', '200', '', '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentID` bigint(20) DEFAULT NULL,
  `type` text NOT NULL,
  `title` text NOT NULL,
  `subtitle` text NOT NULL,
  `text` longtext NOT NULL,
  `authorID` bigint(20) DEFAULT NULL,
  `creation_time` datetime NOT NULL,
  `editorID` bigint(20) DEFAULT NULL,
  `editor_time` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL,
  `isNews` tinyint(1) NOT NULL DEFAULT '0',
  `useComments` tinyint(1) NOT NULL DEFAULT '0',
  `readed` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `authorID` (`authorID`),
  KEY `editorID` (`editorID`),
  KEY `parentID` (`parentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

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

--
-- Dump dei dati per la tabella `contents_comments`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `content_tags`
--

CREATE TABLE IF NOT EXISTS `content_tags` (
  `contentID` bigint(20) NOT NULL,
  `tagID` bigint(20) NOT NULL,
  PRIMARY KEY (`contentID`,`tagID`),
  KEY `tagID` (`tagID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `content_tags`
--

INSERT INTO `content_tags` (`contentID`, `tagID`) VALUES
(7, 8);

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

--
-- Dump dei dati per la tabella `gallery`
--


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
-- Struttura della tabella `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `name` text NOT NULL,
  `perm` int(11) DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `frontOptions` text NOT NULL,
  `adminOptions` text NOT NULL,
  `version` text NOT NULL,
  `author` text NOT NULL,
  `email` text NOT NULL,
  `site` text NOT NULL,
  `licence` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`(255)),
  KEY `compname` (`name`(255)),
  KEY `perm` (`perm`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `modules`
--

INSERT INTO `modules` (`id`, `title`, `name`, `perm`, `is_core`, `active`, `frontOptions`, `adminOptions`, `version`, `author`, `email`, `site`, `licence`) VALUES
(1, 'Contenuti', 'contentMgr', 1, 0, 1, '|default|', '|popular|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(2, 'Utenti', 'userManager', 1, 1, 1, '|online|userMenu|login|', '|online|userMenu|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(3, 'Galleria fotografica', 'gallery', 1, 0, 1, '|random|', '|random|', '1.0', 'Daniel Zozin', 'daniel.zozin@gmail.com', '', 'GNU/GPL'),
(4, 'Configurazione', 'siteConfig', 4, 1, 1, '', '', '1.0', '', '', '', '');

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
(1, 1, 'Menu principale', '1', 1, 1, 0, '0', 1, 'default'),
(2, 2, 'Utenti online', '', 1, 1, 0, '3', 4, 'online'),
(3, 2, 'Menu utente', '0', 9, 1, 0, '0', 2, 'userMenu'),
(8, 2, 'Login', '', 2, 1, 0, '0', 3, 'login'),
(10, 1, 'Articoli piu letti', '0', 1, 1, 0, '3', 0, 'popular'),
(11, 1, '', '', 1, 1, 0, '0', 3, 'search'),
(12, 1, 'Tag cloud', '', 1, 1, 0, '3', 1, 'tagCloud'),
(13, 3, '', '', 1, 1, 0, '3', 2, 'random');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `session`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `tags`
--

INSERT INTO `tags` (`id`, `tag`, `creation`) VALUES
(1, 'key1', '2011-02-05 18:12:28'),
(2, 'articolo 1', '2011-02-05 18:12:28'),
(7, 'tag1', '2011-02-05 18:43:50'),
(8, 'key', '2011-02-12 05:14:50');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

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

--
-- Dump dei dati per la tabella `users_login`
--


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

--
-- Dump dei dati per la tabella `users_perms`
--

INSERT INTO `users_perms` (`uid`, `permID`) VALUES
(4, 9);

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
-- Dump dei dati per la tabella `users_pm`
--


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
  ADD CONSTRAINT `contents_ibfk_3` FOREIGN KEY (`parentID`) REFERENCES `contents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`editorID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_ibfk_2` FOREIGN KEY (`authorID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `contents_comments`
--
ALTER TABLE `contents_comments`
  ADD CONSTRAINT `contents_comments_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contents_comments_ibfk_1` FOREIGN KEY (`contentID`) REFERENCES `contents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
  ADD CONSTRAINT `groups_perms_ibfk_2` FOREIGN KEY (`permID`) REFERENCES `perms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_perms_ibfk_1` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `users_perms_ibfk_2` FOREIGN KEY (`permID`) REFERENCES `perms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_perms_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `users_pm`
--
ALTER TABLE `users_pm`
  ADD CONSTRAINT `users_pm_ibfk_2` FOREIGN KEY (`toID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_pm_ibfk_1` FOREIGN KEY (`fromID`) REFERENCES `users` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE;
