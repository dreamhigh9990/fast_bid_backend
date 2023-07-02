/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.19-MariaDB : Database - olining
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`olining` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `olining`;

/*Table structure for table `answers` */

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `answers_id` int(11) NOT NULL AUTO_INCREMENT,
  `answerer_id` int(11) DEFAULT NULL,
  `liked_count` int(11) DEFAULT NULL,
  `is_correct_answer` tinyint(1) DEFAULT NULL,
  `answer` text,
  `questions_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`answers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `answers` */

insert  into `answers`(`answers_id`,`answerer_id`,`liked_count`,`is_correct_answer`,`answer`,`questions_id`,`date`) values (1,1,0,1,'0',1,'2016-12-20 12:52:09');

/*Table structure for table `books` */

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `books_id` int(11) NOT NULL AUTO_INCREMENT,
  `courses_id` int(11) DEFAULT NULL,
  `name` varchar(240) DEFAULT NULL,
  PRIMARY KEY (`books_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `books` */

insert  into `books`(`books_id`,`courses_id`,`name`) values (3,0,'Family Album'),(4,0,'New English 900'),(5,1,'Voice Tube'),(6,1,'AJ Hoge Book');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(240) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `icon` varchar(240) DEFAULT NULL,
  PRIMARY KEY (`categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`categories_id`,`name`,`parent`,`icon`) values (1,'Shacharit',0,'wb_sunny'),(4,'Arvit',0,'wb_sunny'),(6,'Mincha',0,'wb_sunny'),(7,'Prayers',0,'format_quote'),(8,'Bedtime Shema',0,'hotel'),(9,'Food Brachot',0,'restaurant'),(10,'Before a Meal',9,NULL),(11,'After a Meal',9,NULL),(13,'Test1',9,NULL),(14,'Test2',11,NULL),(15,'Test3',10,NULL),(16,'Test4',11,NULL);

/*Table structure for table `chapters` */

DROP TABLE IF EXISTS `chapters`;

CREATE TABLE `chapters` (
  `chapters_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(240) DEFAULT NULL,
  `books_id` int(11) DEFAULT NULL,
  `words_count` int(11) DEFAULT NULL,
  `sentences_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`chapters_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `chapters` */

insert  into `chapters`(`chapters_id`,`name`,`books_id`,`words_count`,`sentences_count`) values (1,'EPISODE I  \"46 Linden Street” -> Act 1',0,NULL,NULL),(2,'EPISODE II  \"AA” -> Act 1',3,NULL,NULL),(3,'EPISODE I  \"46 Linden Street” -> Act 1',3,NULL,NULL),(4,'A clever way to estimate enormous',5,NULL,NULL),(5,'AJ Hoge Book Chapter 1',6,NULL,NULL);

/*Table structure for table `ci_cookies` */

DROP TABLE IF EXISTS `ci_cookies`;

CREATE TABLE `ci_cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie_id` varchar(255) DEFAULT NULL,
  `netid` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `orig_page_requested` varchar(120) DEFAULT NULL,
  `php_session_id` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ci_cookies` */

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('30704934b42ec92075a75bce9002f5f0','192.168.0.121','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482205295,'a:7:{s:9:\"user_data\";s:0:\"\";s:9:\"user_name\";s:5:\"admin\";s:12:\"is_logged_in\";b:1;s:15:\"videos_selected\";N;s:22:\"search_string_selected\";N;s:5:\"order\";N;s:10:\"order_type\";N;}'),('b81d35cefef3a9aba2a48c45371cffa0','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482205474,''),('267b65aec5cd75cb77cdadf19c882e98','192.168.0.121','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482209529,'a:3:{s:9:\"user_data\";s:0:\"\";s:9:\"user_name\";s:5:\"admin\";s:12:\"is_logged_in\";b:1;}'),('00eb8901a4b7e4008f6821bb99f5c56c','192.168.0.121','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482236043,'a:3:{s:9:\"user_data\";s:0:\"\";s:9:\"user_name\";s:5:\"admin\";s:12:\"is_logged_in\";b:1;}'),('23e1826e5d31cac1bf261812a3b5a6d1','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482352164,''),('42b654c537af40d928eb1ac10d9d2cf7','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482200265,''),('edee05663f499e3404027bf8ac452eee','192.168.0.121','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1482200230,'a:7:{s:9:\"user_data\";s:0:\"\";s:9:\"user_name\";s:5:\"admin\";s:12:\"is_logged_in\";b:1;s:15:\"videos_selected\";N;s:22:\"search_string_selected\";N;s:5:\"order\";N;s:10:\"order_type\";N;}');

/*Table structure for table `courses` */

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `courses_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(240) DEFAULT NULL,
  PRIMARY KEY (`courses_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `courses` */

insert  into `courses`(`courses_id`,`name`) values (1,'AJ Hoge'),(2,'Crazy English');

/*Table structure for table `delivery` */

DROP TABLE IF EXISTS `delivery`;

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gname` varchar(240) NOT NULL,
  `price` float NOT NULL,
  `photo` varchar(240) NOT NULL,
  `flong` double NOT NULL,
  `flat` double NOT NULL,
  `fphone` varchar(240) NOT NULL,
  `faddress` varchar(240) NOT NULL,
  `fapt` varchar(240) NOT NULL,
  `fcity` varchar(240) NOT NULL,
  `fcountry` int(11) NOT NULL,
  `fzip` varchar(50) NOT NULL,
  `tlong` double NOT NULL,
  `tlat` double NOT NULL,
  `tphone` varchar(240) NOT NULL,
  `taddress` varchar(240) NOT NULL,
  `tapt` varchar(240) NOT NULL,
  `tcity` varchar(50) NOT NULL,
  `tcountry` int(11) NOT NULL,
  `tzip` int(11) NOT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `delivery` */

/*Table structure for table `dictionarys` */

DROP TABLE IF EXISTS `dictionarys`;

CREATE TABLE `dictionarys` (
  `dictionarys_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(240) DEFAULT NULL,
  PRIMARY KEY (`dictionarys_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `dictionarys` */

/*Table structure for table `feedback` */

DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromUser` int(11) NOT NULL,
  `toUser` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `content` text NOT NULL,
  `fromType` int(11) NOT NULL,
  `createdAt` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `feedback` */

/*Table structure for table `mail_contents` */

DROP TABLE IF EXISTS `mail_contents`;

CREATE TABLE `mail_contents` (
  `mail_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `mail_contents` */

insert  into `mail_contents`(`mail_id`,`title`,`content`) values (1,'asdfaa','abc');

/*Table structure for table `membership` */

DROP TABLE IF EXISTS `membership`;

CREATE TABLE `membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_addres` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `pass_word` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `membership` */

insert  into `membership`(`id`,`first_name`,`last_name`,`email_addres`,`user_name`,`pass_word`) values (1,'admin','admin','admin@admin.com','admin','21232f297a57a5a743894a0e4a801fc3'),(2,'abcdasdf','bcdddasdf','a@a.com','abcd','e2fc714c4727ee9395f324cd2e7f331f');

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `news_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `img_url` varchar(255) NOT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='news table';

/*Data for the table `news` */

/*Table structure for table `point_contents` */

DROP TABLE IF EXISTS `point_contents`;

CREATE TABLE `point_contents` (
  `point_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`point_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `point_contents` */

insert  into `point_contents`(`point_id`,`content`) values (1,'abcd');

/*Table structure for table `prayers` */

DROP TABLE IF EXISTS `prayers`;

CREATE TABLE `prayers` (
  `prayers_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `content_english` text,
  `content_hebrew` text,
  `content_transliterated` text,
  `published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`prayers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `prayers` */

insert  into `prayers`(`prayers_id`,`category`,`name`,`tag`,`content_english`,`content_hebrew`,`content_transliterated`,`published`) values (8,'4','Test Arvit',NULL,'asdofij','asdf\r\nas\r\ndf\r\nasdf\r\na\r\nsdf\r\nasd\r\nf','asoidjfoiwjaefoijwqoiejf\r\nasdf\r\nasd\r\nfa\r\nsdf\r\nasd\r\nf',1),(7,'1','Test Prayer1',NULL,'On becoming conscious, say Modeh Ani, as follows:\r\n\r\nI give thanks before You,\r\nliving and eternal King,\r\nwho has returned my soul into me\r\nin compassion; great is Your faithfulness. ','ז. אחר כך ישב ויכניס התפילין של יד, וקודם לכן יבדוק שאין שום דבר תוצץ הן במקום ההנחה והן במקום הרצועות (בא\"ח חיי שרה ה). ואז יסדר התפילין בידו החלשה יותר (שם ז), במקום השריר. ויכוין את התפילין לכיוון הלב, כך שהיו\"ד [הוא פיסת רצועה הנמצאת בצד התפילין של יד שנקשר כעין אות יו\"ד] יהיה לצד אחור במעט. ואז יכסה כל היד בטלית שעליו, כמ\"ש \"והיה לך לאות על ידך\", לך ולא לאחרים. ואפילו יושב לבדו יעשה כן (בא\"ח וירא טו).\r\nח. ואז אחר שהניח התפילין של יד על הקיבורת, ולפני הקשירה וההידוק, יברך \"להניח תפילין\" ביושב (שם ח):\r\n','On becoming conscious, say Modeh Ani, as follows:\r\n\r\nMo-deh   a-ni   l\'fa-ne-cha\r\nme-lech   chai   v\'ka-yam\r\nshe-he-che-zar-ta   bi   nish-ma-ti\r\nb\'chem-la   ra-bah   e-mu-na-te-cha.\r\n\r\n\r\nAfter washing the hands, say:\r\n\r\nBa-ruch   a-tah   A-do-nai,\r\nE-lo-hei-nu,   Me-lech   Ha-o-lam,\r\na-sher   ki-d\'sha-nu   b\'mits-vo-tav,\r\nv\'tsi-va-nu,\r\nal   n\'ti-lat   ya-da-yim.\r\n\r\n\r\nAfter going to the bathroom, and then washing hands as above, say Asher Yatsar, as follows:\r\n\r\nBa-ruch  a-tah  A-do-nai,\r\nElo-hei-nu,  me-lech  l\'o-lam,\r\nA-sher  ya-tsar\r\net  ha-a-dam  b\'choch-mah,\r\nu-va-ra  vo\r\nn\'ka-vim  n\'ka-vim,\r\ncha-lu-lim  cha-lu-lim,\r\nga-lu  v\'ya-du-a\r\nlif-nei  chi-sei  ch\'vo-de-cha\r\nshe-im  yi-pa-tei-ach  e-chad  mei-hem\r\no  yi-sa-teim  e-chad  mei-hem,\r\ni  ef-shar  l\'hit-ka-yeim\r\nv\'la-a-mod  l\'fa-ne-cha\r\n[a-fi-lu  sha-ah  e-chat].\r\nBa-ruch  a-tah  A-do-nai,\r\nro-fei  chawl  ba-sar\r\nu-maf-li  la-a-sot.\r\n\r\n\r\nBefore donning the small tallis, while inspecting the fringes, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher   ki-d\'sha-nu  b\'mits-vo-tav,\r\nv\'tsi-va-nu,  \r\nal  mits-vat  tsi-tsit.\r\n\r\n\r\nBefore donning the large tallis, while inspecting the fringes, recite the first two verses of Psalm 104:\r\n\r\nBa-r\'chi  naf-shi  et  A-do-nai,\r\nA-do-nai   E-lo-hai  ga-dal-ta  m\'od,\r\nhod   v\'ha-dar  la-vash-ta.\r\nO-te   or  ka-sal-mah,\r\nno-teh   sha-ma-yim  kai-ri-ah.\r\n\r\n\r\nWhen ready to don it, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher   ki-d\'sha-nu  b\'mits-vo-tav,\r\nv\'tsi-va-nu,  l\'-hit-a-teif  ba-tsi-tsit. \r\n\r\n\r\nWhile the head and body are enwrapped, recite verses 8 through 11 of Psalm 36:\r\n\r\nMa ya-kar   chas-d\'cha  E-lo-him,\r\nuv-nei   a-dam  b\'tseil  k\'na-fe-cha  ye-che-sa-yun.\r\nYir-v\'yun   mi-de-shen  bei-te-cha,\r\nv\'na-chal   a-da-ne-cha  tash-keim.\r\nKi   i-m\'cha  m\'kor  cha-yim,\r\nb\'o-r\'cha   nir-eh  or.\r\nM\'shoch   chas-d\'cha  l\'yo-d\'e-cha,\r\nv\'tsid-ka-t\'cha   l\'yish-rei leiv.\r\n\r\n\r\nBefore tightening the arm t\'fillin, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher  ki-d\'sha-nu  b\'mits-vo-tav,\r\nv\'tsi-va-nu,  l\'-ha-ni-ach  t\'fi-lin. \r\n\r\n\r\nBefore donning the head t\'fillin, men say:\r\n\r\nBa-ruch  a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher  ki-d\'sha-nu  b\'mits-vo-tav,\r\nv\'tsi-va-nu,\r\nal  mits-vat  t\'fi-lin. ',1),(9,'8','Test Bedtime Shema',NULL,'asdf','asdfbhsdfbdfb','asdf',1),(10,'1','Test Prayer12',NULL,'asdf','asdfasdf','asdfasdf',0),(11,'6','Test Mincha',NULL,'foaisjdfoijopwiejfgopi','q908jerfiajsgvoi','fqopiwjefpq1jf890234',0),(12,'6','asdf',NULL,'asdf','asdf','asdf',1),(13,'10','asdf',NULL,'asdf','asdf','asdf',1),(14,'1','Test Shacharit',NULL,'a','a','a',1);

/*Table structure for table `questions` */

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `questions_id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) NOT NULL,
  `question` text,
  `target` varchar(30) DEFAULT NULL,
  `description` text,
  `images` text,
  `asker_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`questions_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `questions` */

insert  into `questions`(`questions_id`,`lang`,`question`,`target`,`description`,`images`,`asker_id`,`date`) values (1,'0','0','0','0','0',1,'2016-12-20 12:52:09');

/*Table structure for table `reviewed` */

DROP TABLE IF EXISTS `reviewed`;

CREATE TABLE `reviewed` (
  `reviewed_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `successed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`reviewed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `reviewed` */

/*Table structure for table `sentences` */

DROP TABLE IF EXISTS `sentences`;

CREATE TABLE `sentences` (
  `sentences_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_en` text,
  `s_zh-cn` text,
  `s_ko` text,
  `s_de` text,
  `s_language` varchar(5) DEFAULT NULL,
  `audio` text,
  `image` text,
  `editor_id` int(11) DEFAULT NULL,
  `searched_count` int(11) DEFAULT NULL,
  `reviewed_count` int(11) DEFAULT NULL,
  `viewed_count` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `chapters_id` int(11) DEFAULT NULL,
  `d_zh-CN` text,
  `d_en` text,
  `d_ko` text,
  `d_de` text,
  `d_language` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`sentences_id`)
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

/*Data for the table `sentences` */

insert  into `sentences`(`sentences_id`,`s_en`,`s_zh-cn`,`s_ko`,`s_de`,`s_language`,`audio`,`image`,`editor_id`,`searched_count`,`reviewed_count`,`viewed_count`,`date`,`chapters_id`,`d_zh-CN`,`d_en`,`d_ko`,`d_de`,`d_language`) values (162,'adsfafdads',NULL,NULL,NULL,'EN',NULL,NULL,NULL,NULL,NULL,NULL,'2016-12-20 13:15:54',4,'asdfafasdf',NULL,NULL,NULL,'CN');

/*Table structure for table `tested` */

DROP TABLE IF EXISTS `tested`;

CREATE TABLE `tested` (
  `tested_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `is_global` tinyint(1) DEFAULT NULL,
  `score` float DEFAULT NULL,
  PRIMARY KEY (`tested_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `tested` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `photo` varchar(240) CHARACTER SET utf8 NOT NULL,
  `name` varchar(240) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `other_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` int(11) NOT NULL,
  `addressbookTBD` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` int(11) NOT NULL,
  `verify_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feedback_deliverer` float NOT NULL,
  `feedback_customer` float NOT NULL,
  `is_deliverer` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

/*Table structure for table `verification` */

DROP TABLE IF EXISTS `verification`;

CREATE TABLE `verification` (
  `phone` varchar(50) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `verification` */

/*Table structure for table `videos` */

DROP TABLE IF EXISTS `videos`;

CREATE TABLE `videos` (
  `videos_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text,
  `title` varchar(255) DEFAULT NULL,
  `reviewed_count` int(11) DEFAULT NULL,
  `image` text,
  PRIMARY KEY (`videos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `videos` */

/*Table structure for table `viewed` */

DROP TABLE IF EXISTS `viewed`;

CREATE TABLE `viewed` (
  `viewed_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`viewed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `viewed` */

/*Table structure for table `vsentences` */

DROP TABLE IF EXISTS `vsentences`;

CREATE TABLE `vsentences` (
  `vsentences_id` int(11) NOT NULL AUTO_INCREMENT,
  `main` varchar(30) DEFAULT NULL,
  `videos_id` int(11) DEFAULT NULL,
  `start_pos` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `editor_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `en` text,
  `ar` text,
  `bg` text,
  `da` text,
  `de` text,
  `el` text,
  `es` text,
  `et` text,
  `fa` text,
  `fi` text,
  `fr` text,
  `hr` text,
  `hu` text,
  `it` text,
  `iw` text,
  `ja` text,
  `ko` text,
  `lt` text,
  `lv` text,
  `mr` text,
  `pt-BR` text,
  `pt` text,
  `ru` text,
  `sr` text,
  `sv` text,
  `tr` text,
  `uk` text,
  `vi` text,
  `zh-CN` text,
  `zh-TW` text,
  PRIMARY KEY (`vsentences_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9086 DEFAULT CHARSET=utf8;

/*Data for the table `vsentences` */

/*Table structure for table `words` */

DROP TABLE IF EXISTS `words`;

CREATE TABLE `words` (
  `words_id` int(11) NOT NULL AUTO_INCREMENT,
  `english` varchar(240) DEFAULT NULL,
  `chinese` varchar(240) DEFAULT NULL,
  `korean` varchar(240) DEFAULT NULL,
  `german` varchar(240) DEFAULT NULL,
  `direction` varchar(30) DEFAULT NULL,
  `pronunciation` varchar(240) DEFAULT NULL,
  `audio` text,
  `image` text,
  `dictionary_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`words_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `words` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
