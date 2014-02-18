-- $Id: install.mysql.utf8.sql 24 2009-11-09 11:56:31Z chdemko $

DROP TABLE IF EXISTS `#__etr_tree`;

CREATE TABLE `#__etr_tree` (
  `id` int(11) NOT NULL auto_increment,
  `gid` int(11) NOT NULL,
  `uid` int(11),
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `parentesco` varchar(255) NOT NULL,
  `padre` varchar(255),
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- INSERT INTO `#__etr_tree` (`greeting`) VALUES
--	('Hello World!'),
--	('Good bye World!');

