-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 15, 2012 at 06:22 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `baseEngine`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `surname` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL,
  `sex` smallint(1) NOT NULL,
  `bd` date NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `image` int(11) NOT NULL,
  `passwd` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `pass_key` text NOT NULL,
  `subscribe` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `accounts`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '0',
  `content` text,
  `blocked` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups_members`
--

CREATE TABLE IF NOT EXISTS `groups_members` (
  `id_group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `id_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_group`,`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `htdocs`
--

CREATE TABLE IF NOT EXISTS `htdocs` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_node` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `id_maket` mediumint(8) unsigned DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `pos` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `notice` text NOT NULL,
  `content` mediumtext NOT NULL,
  `eval` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `seo1` text NOT NULL,
  `seo2` text NOT NULL,
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `published` int(11) DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`id_node`,`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `htdocs`
--

INSERT INTO `htdocs` (`id`, `id_node`, `id_maket`, `path`, `pos`, `title`, `notice`, `content`, `eval`, `keywords`, `description`, `seo1`, `seo2`, `hidden`, `published`, `created`, `updated`) VALUES
(1, 1, 1, 'index', 0, 'Главная', '', '', 0, NULL, NULL, '', '', 1, NULL, 1331801623, 1331801864);

-- --------------------------------------------------------

--
-- Table structure for table `makets`
--

CREATE TABLE IF NOT EXISTS `makets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `makets`
--

INSERT INTO `makets` (`id`, `title`, `content`, `created`, `updated`, `file`) VALUES
(1, 'Основной макет сайта', '', 1331801508, 1331818215, 'main.phpt');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(4) unsigned DEFAULT NULL,
  `section` varchar(20) NOT NULL DEFAULT '',
  `position` int(8) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `descr` text,
  UNIQUE KEY `name` (`name`),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`,`name`, `hidden`, `section`, `position`, `title`, `descr`) VALUES
(1,'htdocs', 0, 'struct', 1, 'Структура сайта', ''),
(2,'users', 0, 'access', 0, 'Аккаунты', 'Пользователи системы'),
(3,'perm', 0, 'access', 0, 'Права доступа', 'Редактирование прав доступа пользователей'),
(4,'settings', 0, 'modules', 99, 'Настройки', NULL),
(5,'makets', 0, 'struct', 2, 'Макеты', ''),
(6,'sql', 0, 'tools', 2, 'SQL', ''),
(7,'groups', 0, 'access', 0, 'Группы ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `modules_config`
--

CREATE TABLE IF NOT EXISTS `modules_config` (
  `module` varchar(64) NOT NULL DEFAULT '',
  `var` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`module`,`var`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules_config`
--

INSERT INTO `modules_config` (`module`, `var`, `title`, `value`) VALUES
('main', 'sitename_ru', 'Название сайта', 'Заголовок сайта'),
('contact', 'mail', 'Email', ''),
('page', 'whatit', 'Что это?(главная)', ''),
('page', 'how', 'Описание шагов', ''),
('page', 'copyright', 'Копирайт', '');

-- --------------------------------------------------------

--
-- Table structure for table `modules_files`
--

CREATE TABLE IF NOT EXISTS `modules_files` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `ext` varchar(255) NOT NULL DEFAULT '',
  `mime` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL DEFAULT '0',
  `info` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modules_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `modules_help`
--

CREATE TABLE IF NOT EXISTS `modules_help` (
  `var` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`var`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `modules_help`
--

INSERT INTO `modules_help` (`var`, `title`, `value`) VALUES
('helper', 'Включить подсказки', '0');

-- --------------------------------------------------------

--
-- Table structure for table `modules_images`
--

CREATE TABLE IF NOT EXISTS `modules_images` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `section` varchar(255) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `path` varchar(64) DEFAULT NULL,
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `modules_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `modules_perm`
--

CREATE TABLE IF NOT EXISTS `modules_perm` (
  `module` varchar(255) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`module`,`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules_perm`
--


-- --------------------------------------------------------

--
-- Table structure for table `modules_perm_groups`
--

CREATE TABLE IF NOT EXISTS `modules_perm_groups` (
  `module` varchar(255) NOT NULL DEFAULT '0',
  `id_group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`module`,`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules_perm_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `ptree`
--

CREATE TABLE IF NOT EXISTS `ptree` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id_maket` mediumint(8) unsigned DEFAULT NULL,
  `id_parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pos` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` mediumint(8) unsigned DEFAULT NULL,
  `isparent` tinyint(1) DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `fullpath` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`),
  KEY `level` (`level`),
  KEY `pos` (`pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ptree`
--

INSERT INTO `ptree` (`id`, `id_maket`, `id_parent`, `pos`, `level`, `isparent`, `title`, `path`, `fullpath`, `keywords`, `description`, `hidden`, `created`, `updated`) VALUES
(1, 1, 0, 0, 0, 1, 'Главная страница', '/', '/', '', '', 0, 1203401215, 1203401215);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(32) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `id_account` int(11) NOT NULL,
  `cap` varchar(32) NOT NULL,
  `remote_addr` varchar(24) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '0',
  `storage` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `accounts_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `id_user`, `id_account`, `cap`, `remote_addr`, `user_agent`, `storage`, `city`, `created`, `updated`) VALUES
('b9e9c341ac3ca08edbbc5ecc89f40c53', 1, 0, '', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:8.0.1) Gecko/20100101 Firefox/8.0.1', 'REQUEST_URI|s:7:"/admin/";makets|a:2:{s:5:"order";s:2:"id";s:4:"desc";b:1;}htdocsorder|a:1:{i:1;s:2:"id";}htdocsdesc|a:1:{i:1;b:1;}users|a:2:{s:5:"order";s:2:"id";s:4:"desc";b:1;}', '', 1331801376, 1331824407),
('60f518e0765b5f2dfb3f11efa8ee96d9', 1, 0, '', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:8.0.1) Gecko/20100101 Firefox/8.0.1', 'groups|a:2:{s:5:"order";s:2:"id";s:4:"desc";b:1;}users|a:2:{s:5:"order";s:2:"id";s:4:"desc";b:1;}', '', 1331824440, 1331824859);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(128) NOT NULL DEFAULT '',
  `passwd` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `blocked` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `passwd`, `email`, `name`, `blocked`, `created`, `updated`) VALUES
(1, 'root', '63a9f0ea7bb98050796b649e85481845', 'admin@admin.ru', 'Administrator', 0, 0, 1331824407);

-- --------------------------------------------------------

--
-- Table structure for table `users_activate`
--

CREATE TABLE IF NOT EXISTS `users_activate` (
  `id` varchar(32) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_activate`
--


-- --------------------------------------------------------

--
-- Table structure for table `users_forget`
--

CREATE TABLE IF NOT EXISTS `users_forget` (
  `id` varchar(32) NOT NULL DEFAULT '0',
  `id_user` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_forget`
--

