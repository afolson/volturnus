-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2009 at 11:17 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `config`
--

-- --------------------------------------------------------

--
-- Table structure for table `badwords`
--

CREATE TABLE IF NOT EXISTS `badwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `types` text,
  `word` varchar(255) DEFAULT NULL,
  `replace` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=21 ;

--
-- Dumping data for table `badwords`
--

INSERT INTO `badwords` (`id`, `types`, `word`, `replace`, `action`) VALUES
(1, 'a:1:{i:0;s:3:"all";}', 'pussy', '', 'block'),
(2, 'a:1:{i:0;s:3:"all";}', 'fuck', '', 'block'),
(3, 'a:1:{i:0;s:3:"all";}', 'whore', '', 'block'),
(4, 'a:1:{i:0;s:3:"all";}', 'slut', '', 'block'),
(5, 'a:1:{i:0;s:3:"all";}', 'shit', '', 'block'),
(6, 'a:1:{i:0;s:3:"all";}', 'asshole', '', 'block'),
(7, 'a:1:{i:0;s:3:"all";}', 'bitch', '', 'block'),
(8, 'a:1:{i:0;s:3:"all";}', 'cunt', '', 'block'),
(9, 'a:1:{i:0;s:3:"all";}', 'vagina', '', 'block'),
(10, 'a:1:{i:0;s:3:"all";}', 'penis', '', 'block'),
(11, 'a:1:{i:0;s:3:"all";}', 'jackass', '', 'block'),
(12, 'a:1:{i:0;s:3:"all";}', '*fucker*', '', 'block'),
(13, 'a:1:{i:0;s:3:"all";}', 'faggot', '', 'block'),
(14, 'a:1:{i:0;s:3:"all";}', 'fag', '', 'block'),
(15, 'a:1:{i:0;s:3:"all";}', 'horny', '', 'block'),
(16, 'a:1:{i:0;s:3:"all";}', 'gay', '', 'block'),
(17, 'a:1:{i:0;s:3:"all";}', 'dickhead', '', 'block'),
(18, 'a:1:{i:0;s:3:"all";}', 'sonuvabitch', '', 'block'),
(19, 'a:1:{i:0;s:3:"all";}', '*fuck*', '', 'block'),
(20, 'a:1:{i:0;s:3:"all";}', 'tits', '', 'block');

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `mask` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=7 ;

--
-- Dumping data for table `bans`
--

INSERT INTO `bans` (`id`, `type`, `mask`, `reason`, `action`) VALUES
(1, 'nick', '*C*h*a*n*S*e*r*v*', 'Reserved for Services', 'kill'),
(2, 'ip', '195.86.232.81', 'Delinked server', 'kill'),
(3, 'server', 'eris.berkeley.edu', 'Get out of here.', 'kill'),
(4, 'user', '*tirc@*.saturn.bbn.com', 'Idiot', 'gline'),
(5, 'realname', 'Swat Team', 'mIRKFORCE', 'gline'),
(6, 'realname', 'sub7server', 'sub7', 'kill');

-- --------------------------------------------------------

--
-- Table structure for table `cgiirc`
--

CREATE TABLE IF NOT EXISTS `cgiirc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'webirc',
  `username` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cgiirc`
--


-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE IF NOT EXISTS `channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `mask` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `warn` int(11) NOT NULL DEFAULT '0',
  `redirect` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`id`, `type`, `mask`, `reason`, `warn`, `redirect`) VALUES
(1, 'deny', '*warez*', 'Warez is illegal', 0, ''),
(2, 'allow', '#WarezSucks', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `dcc`
--

CREATE TABLE IF NOT EXISTS `dcc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `soft` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `dcc`
--

INSERT INTO `dcc` (`id`, `type`, `filename`, `reason`, `soft`) VALUES
(1, 'deny', '*sub7*', 'Possible Sub7 Virus', 0);

-- --------------------------------------------------------

--
-- Table structure for table `exceptions`
--

CREATE TABLE IF NOT EXISTS `exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `mask` varchar(255) NOT NULL,
  `types` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `exceptions`
--

INSERT INTO `exceptions` (`id`, `type`, `mask`, `types`) VALUES
(1, 'ban', '*stskeeps@212.*', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `opers`
--

CREATE TABLE IF NOT EXISTS `opers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `passtype` varchar(15) DEFAULT NULL,
  `hosts` text NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `flags` text,
  `swhois` varchar(255) DEFAULT NULL,
  `modes` varchar(255) DEFAULT NULL,
  `snomask` varchar(255) DEFAULT NULL,
  `maxlogins` int(11) NOT NULL DEFAULT '0',
  `admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Dumping data for table `opers`
--

INSERT INTO `opers` (`id`, `username`, `password`, `passtype`, `hosts`, `class`, `flags`, `swhois`, `modes`, `snomask`, `maxlogins`, `admin`) VALUES
(1, 'config', 'config', '', 'a:1:{i:0;s:3:"*@*";}', 'clients', 'a:29:{i:0;s:5:"local";i:1;s:6:"global";i:2;s:7:"coadmin";i:3;s:5:"admin";i:4;s:14:"services-admin";i:5;s:8:"netadmin";i:6;s:6:"helpop";i:7;s:10:"can_rehash";i:8;s:7:"can_die";i:9;s:11:"can_restart";i:10;s:11:"can_wallops";i:11;s:11:"can_globops";i:12;s:14:"can_localroute";i:13;s:15:"can_globalroute";i:14;s:13:"can_localkill";i:15;s:14:"can_globalkill";i:16;s:9:"can_kline";i:17;s:11:"can_unkline";i:18;s:15:"can_localnotice";i:19;s:16:"can_globalnotice";i:20;s:9:"can_zline";i:21;s:10:"can_gkline";i:22;s:10:"can_gzline";i:23;s:10:"get_umodew";i:24;s:8:"get_host";i:25;s:12:"can_override";i:26;s:8:"can_setq";i:27;s:11:"can_addline";i:28;s:11:"can_dccdeny";}', '', '', '', 0, 1),
(2, 'rehashbot', 'rehashbot', '', 'a:1:{i:0;s:3:"*@*";}', 'clients', 'a:20:{i:0;s:6:"global";i:1;s:8:"netadmin";i:2;s:10:"can_rehash";i:3;s:14:"can_localroute";i:4;s:15:"can_localnotice";i:5;s:10:"get_umodew";i:6;s:8:"get_host";i:7;s:11:"can_dccdeny";i:8;s:6:"helpop";i:9;s:11:"can_wallops";i:10;s:11:"can_globops";i:11;s:15:"can_globalroute";i:12;s:13:"can_localkill";i:13;s:14:"can_globalkill";i:14;s:9:"can_kline";i:15;s:11:"can_unkline";i:16;s:16:"can_globalnotice";i:17;s:5:"admin";i:18;s:14:"services-admin";i:19;s:8:"can_setq";}', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `other`
--

CREATE TABLE IF NOT EXISTS `other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=4 ;

--
-- Dumping data for table `other`
--

INSERT INTO `other` (`id`, `name`, `config`) VALUES
(1, 'Aliases', '// This points the command /nickserv to the user NickServ who is connected to the set::services-server server\r\n/*alias NickServ {\r\n	target "NickServ";\r\n	type services;\r\n};*/\r\n\r\n// If you want the command to point to the same nick as the command, you can leave the nick entry out\r\n//alias ChanServ { type services; };\r\n\r\n// Points the /statserv command to the user StatServ on the set::stats-server server\r\n//alias StatServ { type stats; };\r\n\r\n// Points the /superbot command to the user SuperBot\r\n//alias SuperBot { type normal; };\r\n\r\n\r\n/* Standard aliases */\r\nalias NickServ { type services; };\r\nalias ChanServ { type services; };\r\nalias OperServ { type services; };\r\nalias HelpServ { type services; };\r\nalias StatServ { type stats; };\r\n\r\nalias "identify" {\r\n	format "^#" {\r\n		target "chanserv";\r\n		type services;\r\n		parameters "IDENTIFY %1-";\r\n	};\r\n	format "^[^#]" {\r\n		target "nickserv";\r\n		type services;\r\n		parameters "IDENTIFY %1-";\r\n	};\r\n	type command;\r\n};\r\n*/\r\n\r\n/* Standard aliases */\r\nalias "services" {\r\n	format "^#" {\r\n		target "chanserv";\r\n		type services;\r\n		parameters "%1-";\r\n	};\r\n	format "^[^#]" {\r\n		target "nickserv";\r\n		type services;\r\n		parameters "%1-";\r\n	};\r\n	type command;\r\n};\r\n\r\nalias "identify" {\r\n	format "^#" {\r\n		target "chanserv";\r\n		type services;\r\n		parameters "IDENTIFY %1-";\r\n	};\r\n	format "^[^#]" {\r\n		target "nickserv";\r\n		type services;\r\n		parameters "IDENTIFY %1-";\r\n	};\r\n	type command;\r\n};\r\n\r\n/* This is an example of a real command alias */\r\n/* This maps /GLINEBOT to /GLINE <parameter> 2d etc... */\r\nalias "glinebot" {\r\n	format ".+" {\r\n		command "gline";\r\n		type real;\r\n		parameters "%1 2d Bots are not allowed on this server, please read the faq at http://www.example.com/faq/123";\r\n	};\r\n	type command;\r\n};'),
(2, 'Network Set block', 'set {\r\n	network-name 		"ROXnet";\r\n	default-server 		"irc.roxnet.org";\r\n	services-server 	"services.roxnet.org";\r\n	stats-server 		"stats.roxnet.org";\r\n	help-channel 		"#ROXnet";\r\n	hiddenhost-prefix	"rox";\r\n	/* prefix-quit 		"no"; */\r\n	/* Cloak keys should be the same at all servers on the network.\r\n	 * They are used for generating masked hosts and should be kept secret.\r\n	 * The keys should be 3 random strings of 5-100 characters\r\n	 * (10-20 chars is just fine) and must consist of lowcase (a-z),\r\n	 * upcase (A-Z) and digits (0-9) [see first key example].\r\n	 * HINT: On *NIX, you can run ''./unreal gencloak'' in your shell to let\r\n	 *       Unreal generate 3 random strings for you.\r\n	 */\r\n	cloak-keys {\r\n		"aoAr1HnR6gl3sJ7hVz4Zb7x4YwpW";\r\n		"sdfnw8r3894HUDSGN89435";\r\n		"sdkjf3444458234jIUDSFUIIUWOER";\r\n	};\r\n	/* on-oper host */\r\n	hosts {\r\n		local		"locop.roxnet.org";\r\n		global		"ircop.roxnet.org";\r\n		coadmin		"coadmin.roxnet.org";\r\n		admin		"admin.roxnet.org";\r\n		servicesadmin 	"csops.roxnet.org";\r\n		netadmin 	"netadmin.roxnet.org";\r\n		host-on-oper-up "no";\r\n	};\r\n};'),
(3, 'Server Set Block', 'set {\r\n	kline-address "test@testemail.net";\r\n	modes-on-connect "+ixw";\r\n	modes-on-oper	 "+xwgs";\r\n	oper-auto-join "#opers";\r\n	options {\r\n		hide-ulines;\r\n		/* You can enable ident checking here if you want */\r\n		/* identd-check; */\r\n		show-connect-info;\r\n	};\r\n\r\n	maxchannelsperuser 10;\r\n	/* The minimum time a user must be connected before being allowed to use a QUIT message,\r\n	 * This will hopefully help stop spam */\r\n	anti-spam-quit-message-time 10s;\r\n	/* Make the message in static-quit show in all quits - meaning no\r\n           custom quits are allowed on local server */\r\n	/* static-quit "Client quit";	*/\r\n\r\n	/* You can also block all part reasons by uncommenting this and say ''yes'',\r\n	 * or specify some other text (eg: "Bye bye!") to always use as a comment.. */\r\n	/* static-part yes; */\r\n\r\n	/* This allows you to make certain stats oper only, use * for all stats,\r\n	 * leave it out to allow users to see all stats. Type ''/stats'' for a full list.\r\n	 * Some admins might want to remove the ''kGs'' to allow normal users to list\r\n	 * klines, glines and shuns.\r\n	 */\r\n	oper-only-stats "okfGsMRUEelLCXzdD";\r\n\r\n	/* Throttling: this example sets a limit of 3 connection attempts per 60s (per host). */\r\n	throttle {\r\n		connections 3;\r\n		period 60s;\r\n	};\r\n\r\n	/* Anti flood protection */\r\n	anti-flood {\r\n		nick-flood 3:60;	/* 3 nickchanges per 60 seconds (the default) */\r\n	};\r\n\r\n	/* Spam filter */\r\n	spamfilter {\r\n		ban-time 1d; /* default duration of a *line ban set by spamfilter */\r\n		ban-reason "Spam/Advertising"; /* default reason */\r\n		virus-help-channel "#help"; /* channel to use for ''viruschan'' action */\r\n		/* except "#help"; channel to exempt from filtering */\r\n	};\r\n};');

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `name`, `ip`, `password`) VALUES
(1, 'server1', '127.0.0.1', 'password'),
(2, 'server2', '255.255.255.255', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `spamfilters`
--

CREATE TABLE IF NOT EXISTS `spamfilters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regex` text NOT NULL,
  `targets` text NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=28 ;

--
-- Dumping data for table `spamfilters`
--

INSERT INTO `spamfilters` (`id`, `regex`, `targets`, `action`, `reason`, `time`) VALUES
(1, '\\x01DCC (SEND|RESUME)[ ]+\\"(.+ ){20}', 'a:2:{i:0;s:7:"channel";i:1;s:7:"private";}', 'kill', 'mIRC 6.0-6.11 exploit attempt', '0'),
(2, '\\x01DCC (SEND|RESUME).{225}', 'a:2:{i:0;s:7:"channel";i:1;s:7:"private";}', 'kill', 'Possible mIRC 6.12 exploit attempt', '0'),
(3, 'Come watch me on my webcam and chat /w me :-\\) http://.+:\\d+/me\\.mpg', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by fyle trojan: see http://www.sophos.com/virusinfo/analyses/trojfylexa.html', '0'),
(4, 'Speed up your mIRC DCC Transfer by up to 75%.*www\\.freewebs\\.com/mircupdate/mircspeedup\\.exe', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by mirseed trojan: see http://www.sophos.com/virusinfo/analyses/trojmirseeda.html', '0'),
(5, '^http://www\\.angelfire\\.com/[a-z0-9]+/[a-z0-9]+/[a-z_]+\\.jpg <- .*!', 'a:1:{i:0;s:7:"private";}', 'block', 'Infected by fagot worm: see http://www.f-secure.com/v-descs/fagot.shtml', '0'),
(6, '^FREE PORN: http://free:porn@([0-9]{1,3}\\.){3}[0-9]{1,3}:8180$', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by aplore worm: see http://www.f-secure.com/v-descs/aplore.shtml', '0'),
(7, '^!login Wasszup!$', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to login to a GTBot', '0'),
(8, '^!login grrrr yeah baby!$', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to login to a GTBot', '0'),
(9, '^!packet ([0-9]{1,3}\\.){3}[0-9]{1,3} [0-9]{1,15}', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to use a GTBot', '0'),
(10, '^!icqpagebomb ([0-9]{1,15} ){2}.+', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to use a GTBot', '0'),
(11, '^!portscan ([0-9]{1,3}\\.){3}[0-9]{1,3} [0-9]{1,5} [0-9]{1,5}$', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to use a GTBot', '0'),
(12, '^.u(dp)? ([0-9]{1,3}\\.){3}[0-9]{1,3} [0-9]{1,15} [0-9]{1,15} [0-9]{1,15}( [0-9])*$', 'a:1:{i:0;s:7:"channel";}', 'gline', 'Attempting to use an SDBot', '0'),
(13, '^.syn ((([0-9]{1,3}\\.){3}[0-9]{1,3})|([a-zA-Z0-9_-]+\\.[a-zA-Z0-9_-]+\\.[a-zA-Z0-9_.-]+)) [0-9]{1,5} [0-9]{1,15} [0-9]{1,15}', 'a:2:{i:0;s:7:"channel";i:1;s:7:"private";}', 'gline', 'Attempting to use a SpyBot', '0'),
(14, '^porn! porno! http://.+\\/sexo\\.exe', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by soex trojan: see http://www.trendmicro.com/vinfo/virusencyclo/default5.asp?VName=TROJ%5FSOEX.A', '0'),
(15, '(^wait a minute plz\\. i am updating my site|.*my erotic video).*http://.+/erotic(a)?/myvideo\\.exe$', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by some trojan (erotica?)', '0'),
(16, '^STOP SPAM, USE THIS COMMAND: //write nospam \\$decode\\(.+\\) \\| \\.load -rs nospam \\| //mode \\$me \\+R$', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by nkie worm: see http://www.trojaninfo.com/nkie/nkie.htm', '0'),
(17, 'FOR MATRIX 2 DOWNLOAD, USE THIS COMMAND: //write Matrix2 \\$decode\\(.+=,m\\) \\| \\.load -rs Matrix2 \\| //mode \\$me \\+R$', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by nkie worm: see http://www.trojaninfo.com/nkie/nkie.htm', '0'),
(18, '^hey .* to get OPs use this hack in the chan but SHH! //\\$decode\\(.*,m\\) \\| \\$decode\\(.*,m\\)$', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by nkie worm: see http://www.trojaninfo.com/nkie/nkie.htm', '0'),
(19, '.*(http://jokes\\.clubdepeche\\.com|http://horny\\.69sexy\\.net|http://private\\.a123sdsdssddddgfg\\.com).*', 'a:1:{i:0;s:7:"private";}', 'gline', 'Infected by LOI trojan', '0'),
(20, 'C:\\\\\\\\WINNT\\\\\\\\system32\\\\\\\\[][0-9a-z_-{|}`]+\\.zip', 'a:1:{i:0;s:3:"dcc";}', 'block', 'Infected by Gaggle worm?', '0'),
(21, 'C:\\\\\\\\WINNT\\\\\\\\system32\\\\\\\\(notes|videos|xxx|ManualSeduccion|postal|hechizos|images|sex|avril)\\.zip', 'a:1:{i:0;s:3:"dcc";}', 'dccblock', 'Infected by Gaggle worm', '0'),
(22, 'http://.+\\.lycos\\..+/[iy]server[0-9]/[a-z]{4,11}\\.(gif|jpg|avi|txt)', 'a:2:{i:0;s:7:"private";i:1;s:4:"quit";}', 'block', 'Infected by Gaggle worm', '0'),
(23, '^Free porn pic.? and movies (www\\.sexymovies\\.da\\.ru|www\\.girlporn\\.org)', 'a:1:{i:0;s:7:"private";}', 'block', 'Unknown virus. Site causes Backdoor.Delf.lq infection', '0'),
(24, '^LOL! //echo -a \\$\\(\\$decode\\(.+,m\\),[0-9]\\)$', 'a:1:{i:0;s:7:"channel";}', 'block', '$decode exploit', '0'),
(25, '//write \\$decode\\(.+\\|.+load -rs', 'a:2:{i:0;s:7:"channel";i:1;s:7:"private";}', 'block', 'Generic $decode exploit', '0'),
(26, '^Want To Be An IRCOp\\? Try This New Bug Type: //write \\$decode\\(.+=.?,m\\) \\| \\.load -rs \\$decode\\(.+=.?,m\\)$', 'a:1:{i:0;s:7:"private";}', 'block', 'Spamming users with an mIRC trojan. Type ''/unload -rs newb'' to remove the trojan.', '0'),
(27, 'DCC SEND (\\"?[^\\"]+\\"?)? 0 0 0', 'a:8:{i:0;s:7:"channel";i:1;s:7:"private";i:2;s:14:"private-notice";i:3;s:14:"channel-notice";i:4;s:4:"part";i:5;s:4:"quit";i:6;s:4:"away";i:7;s:5:"topic";}', 'block', 'Router exploit', '-');

-- --------------------------------------------------------

--
-- Table structure for table `vhosts`
--

CREATE TABLE IF NOT EXISTS `vhosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `passtype` varchar(15) DEFAULT NULL,
  `hosts` text NOT NULL,
  `vhost` varchar(255) DEFAULT NULL,
  `swhois` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `vhosts`
--

INSERT INTO `vhosts` (`id`, `username`, `password`, `passtype`, `hosts`, `vhost`, `swhois`) VALUES
(1, 'stskeeps', 'moocowsrulemyworld', '', 'a:1:{i:0;s:12:"*@*.image.dk";}', 'i.hate.microsefrs.com', '');
