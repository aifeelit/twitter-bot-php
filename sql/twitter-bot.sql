SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=529 ;

CREATE TABLE IF NOT EXISTS `follower` (
  `id` int(11) NOT NULL,
  `url` char(140) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL,
  `userImage` binary(1) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `userScreenName` varchar(30) NOT NULL,
  `followers_count` int(10) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tweet` char(140) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tweet` (`tweet`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1345 ;

