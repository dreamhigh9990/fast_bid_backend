/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.13-MariaDB : Database - olining
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `answers` */

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `answers_id` int(11) NOT NULL AUTO_INCREMENT,
  `answerer_id` int(11) DEFAULT NULL,
  `questions_id` int(11) DEFAULT NULL,
  `answer` text,
  `date` datetime DEFAULT NULL,
  `lang` varchar(30) DEFAULT NULL,
  `question_lang` varchar(30) DEFAULT NULL,
  `is_correct_answer` tinyint(1) DEFAULT '0',
  `is_asker_answer` tinyint(1) DEFAULT '0',
  `searched_count` int(11) DEFAULT '0',
  `viewed_count` int(11) DEFAULT '0',
  `reviewed_count` int(11) DEFAULT '0',
  `liked_count` int(11) DEFAULT '0',
  PRIMARY KEY (`answers_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `answers` */

insert  into `answers`(`answers_id`,`answerer_id`,`questions_id`,`answer`,`date`,`lang`,`question_lang`,`is_correct_answer`,`is_asker_answer`,`searched_count`,`viewed_count`,`reviewed_count`,`liked_count`) values (1,28,1,'我们什么时候可以再见面?','2016-12-20 12:52:09','zh-CN','en',1,0,0,0,0,0),(2,29,1,'什么时候可以再见？','2017-01-19 01:22:38','zh-CN','en',0,0,0,0,0,0),(10,1,22,'rtukiryuk','2017-01-30 19:27:25',NULL,NULL,1,0,0,0,0,0),(9,1,9,'sp[ofghk','2017-01-29 22:13:28',NULL,NULL,1,0,0,0,0,0);

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

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('67b28f06a0f328079090c833835e32d9','192.168.0.77','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',1485815208,'a:7:{s:9:\"user_data\";s:0:\"\";s:9:\"user_name\";s:5:\"admin\";s:12:\"is_logged_in\";b:1;s:15:\"videos_selected\";N;s:22:\"search_string_selected\";N;s:5:\"order\";N;s:10:\"order_type\";N;}');

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
  `answer_lang` varchar(30) DEFAULT NULL,
  `description` text,
  `images` text,
  `asker_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `searched_count` int(11) DEFAULT '0',
  `viewed_count` int(11) DEFAULT '0',
  `answered_count` int(11) DEFAULT '0',
  PRIMARY KEY (`questions_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `questions` */

insert  into `questions`(`questions_id`,`lang`,`question`,`answer_lang`,`description`,`images`,`asker_id`,`date`,`searched_count`,`viewed_count`,`answered_count`) values (1,'en','When can we meet again later?','zh-CN','I am going to use this sentence for my business in China.','',1,'2017-01-30 19:36:39',0,0,0),(2,'en','Why don\'t you go to the school?','zh-CN','My father said like that in this morning.',NULL,27,'2017-01-23 23:58:00',0,0,0),(22,'zh-CN','asg','en','srftj','1153d2f4dbc23dd227b8e1419ceb0ebc.png',1,'2017-01-30 19:47:09',0,0,0),(9,'zh-CN','sdfoisdfopi','en','sohii','7c77e9aa7948151ae9226daf4b4fecf6.png,19682627914827a4ffab7b312afa6b32.jpg',1,'2017-01-30 19:42:54',0,0,0),(15,'zh-CN','2','en','3','019ec0d2e2675a0bac945a4b29596b43.png,81629ec0616a754e0346b37f189eea06.jpg',1,'2017-01-30 18:32:26',0,0,0),(16,'zh-CN','32','en','234','0746a33b1bc8cf8b0032d48f4399b2a8.png',1,'2017-01-30 18:34:46',0,0,0),(17,'zh-CN','sdfh','en','sfgh','f1392fc2d7ec087a4d993c8ebb7feb98.png,735333ba302d143dd007da713cfe4fb9.png',1,'2017-01-30 18:37:55',0,0,0),(18,'zh-CN','strrhtgjk','en','ghj','e524c432409ace1b6986aa02c6c52959.png,d478be12d1bcb4d1389630f38d6056cb.png',1,'2017-01-30 18:38:53',0,0,0),(19,'zh-CN','adf','en','dsfgj','5d464d6744887bc3dc440c63d5cfc5c2.png,58d32cfb27c28ab1273ae528e9c81390.jpg',1,'2017-01-30 18:40:14',0,0,0),(20,'zh-CN','sdghfj','en','fgjkyhuk','5be76a065f1f929543ebcbc4537ae1b7.jpg,5ccab9ca110d6111ab8b0cb7fc99e80f.png',1,'2017-01-30 18:41:01',0,0,0),(21,'zh-CN','sfgh','en','dfj','7073f20c308c031b6df7e00ceada4ca0.png,cb92e943d50b9dc37fcfcbaad51f8363.png,d2991b044ccc385a89ae0fd1667e075f.jpg',1,'2017-01-30 21:27:55',0,0,0);

/*Table structure for table `review` */

DROP TABLE IF EXISTS `review`;

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `questions_id` int(11) DEFAULT NULL,
  `answers_id` int(11) DEFAULT NULL,
  `vsentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `successed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`review_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `review` */

insert  into `review`(`review_id`,`users_id`,`sentences_id`,`questions_id`,`answers_id`,`vsentences_id`,`date`,`successed`) values (1,27,163,0,0,0,'2017-01-24 00:26:59',0),(2,27,0,1,2,0,'2017-01-25 00:26:59',0),(3,27,0,1,1,0,'2017-01-16 23:56:42',0),(4,27,0,0,0,9108,'2017-01-20 23:57:30',0),(5,27,167,0,0,0,'2017-01-21 23:58:25',0);

/*Table structure for table `reviewed` */

DROP TABLE IF EXISTS `reviewed`;

CREATE TABLE `reviewed` (
  `reviewed_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `sentences_count` int(11) DEFAULT NULL,
  `words_count` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `successed` tinyint(1) DEFAULT NULL,
  `review_from_date` datetime DEFAULT NULL,
  `review_to_date` datetime DEFAULT NULL,
  PRIMARY KEY (`reviewed_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `reviewed` */

insert  into `reviewed`(`reviewed_id`,`users_id`,`sentences_count`,`words_count`,`date`,`successed`,`review_from_date`,`review_to_date`) values (1,27,2,6,'2017-01-25 01:34:24',1,'2017-01-01 01:34:31','2017-01-14 01:34:39'),(3,27,3,8,'2017-01-27 00:00:00',0,'2017-01-01 00:00:00','2017-01-14 00:00:00');

/*Table structure for table `sentences` */

DROP TABLE IF EXISTS `sentences`;

CREATE TABLE `sentences` (
  `sentences_id` int(11) NOT NULL AUTO_INCREMENT,
  `chapters_id` int(11) DEFAULT NULL,
  `editor_id` int(11) DEFAULT NULL,
  `s_en` text,
  `s_zh-CN` text,
  `s_ko` text,
  `s_de` text,
  `s_language` varchar(5) DEFAULT NULL,
  `d_en` text,
  `d_zh-CN` text,
  `d_ko` text,
  `d_de` text,
  `d_language` varchar(5) DEFAULT NULL,
  `audio` text,
  `image` text,
  `searched_count` int(11) DEFAULT '0',
  `reviewed_count` int(11) DEFAULT '0',
  `viewed_count` int(11) DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`sentences_id`),
  FULLTEXT KEY `src_english` (`s_en`)
) ENGINE=MyISAM AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;

/*Data for the table `sentences` */

insert  into `sentences`(`sentences_id`,`chapters_id`,`editor_id`,`s_en`,`s_zh-CN`,`s_ko`,`s_de`,`s_language`,`d_en`,`d_zh-CN`,`d_ko`,`d_de`,`d_language`,`audio`,`image`,`searched_count`,`reviewed_count`,`viewed_count`,`date`) values (162,4,NULL,'','abc 廣義線上英文字典提供了','','','zh-CN','asdfafasdf','','','','en',NULL,NULL,NULL,NULL,NULL,'2017-01-30 23:27:00'),(163,4,NULL,'That\'s a good idea','','','','en','','좋은 생각입니다','','','zh-CN',NULL,'b3f91e3485985105a31c55dd9021ea6c.png',NULL,NULL,NULL,'2017-01-03 17:16:22'),(164,2,NULL,'test',NULL,NULL,NULL,'en',NULL,'test',NULL,NULL,'zh-CN',NULL,NULL,NULL,NULL,NULL,'2017-01-04 07:46:41'),(165,2,NULL,'That\'s a nice idea',NULL,NULL,NULL,'en',NULL,'这是好意思',NULL,NULL,'zh-CN',NULL,NULL,NULL,NULL,NULL,'2017-01-18 19:59:47'),(166,2,NULL,'Excuse me. My name is Richard Stewart',NULL,NULL,NULL,'en',NULL,'对不起，我叫Richard Stewart',NULL,NULL,'zh-CN',NULL,NULL,NULL,NULL,NULL,'2017-01-18 20:00:13'),(167,2,NULL,'Whether you like it or not, we use numbers every day.',NULL,NULL,NULL,'en',NULL,'你喜歡不喜歡這個， 每天我們用',NULL,NULL,'zh-CN',NULL,NULL,NULL,NULL,NULL,'2017-01-18 20:00:25');

/*Table structure for table `tested` */

DROP TABLE IF EXISTS `tested`;

CREATE TABLE `tested` (
  `tested_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `is_global` tinyint(1) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `sentences_count` int(11) DEFAULT NULL,
  `review_from_date` datetime DEFAULT NULL,
  `review_to_date` datetime DEFAULT NULL,
  PRIMARY KEY (`tested_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `tested` */

insert  into `tested`(`tested_id`,`users_id`,`date`,`is_global`,`score`,`sentences_count`,`review_from_date`,`review_to_date`) values (3,27,'2017-01-29 03:00:30',1,45,NULL,NULL,NULL),(2,27,'2017-01-28 09:30:31',0,90.9,5,'2017-01-01 00:00:00','2017-01-30 00:00:00');

/*Table structure for table `user_ability` */

DROP TABLE IF EXISTS `user_ability`;

CREATE TABLE `user_ability` (
  `user_ability_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `language` varchar(30) NOT NULL,
  `level` float DEFAULT '0',
  `searched_count` int(11) DEFAULT '0',
  `asked_count` int(11) DEFAULT '0',
  `mastered_sentences_count` int(11) DEFAULT '0',
  `mastered_words_count` int(11) DEFAULT '0',
  `listened_count` int(11) DEFAULT '0',
  `listened_minutes` int(11) DEFAULT '0',
  `tested_count` int(11) DEFAULT '0',
  `last_global_score` float DEFAULT '0',
  `rua` int(11) DEFAULT '0',
  PRIMARY KEY (`user_ability_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user_ability` */

insert  into `user_ability`(`user_ability_id`,`users_id`,`language`,`level`,`searched_count`,`asked_count`,`mastered_sentences_count`,`mastered_words_count`,`listened_count`,`listened_minutes`,`tested_count`,`last_global_score`,`rua`) values (2,27,'zh-CN',48.891,897,25,2117,3196,51,684,84,2.15,85);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `users_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(240) CHARACTER SET utf8 NOT NULL,
  `full_name` varchar(240) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(240) CHARACTER SET utf8 DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `verified` int(11) DEFAULT NULL,
  `verify_code` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `user_status` int(11) DEFAULT NULL,
  `session` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_date` date DEFAULT NULL,
  `udid` varchar(255) CHARACTER SET utf8 NOT NULL,
  `source_language` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_language` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`users_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`users_id`,`user_name`,`full_name`,`email`,`other_email`,`password`,`phone`,`photo`,`zip`,`verified`,`verify_code`,`latitude`,`longitude`,`user_status`,`session`,`session_date`,`udid`,`source_language`,`target_language`) values (27,'v_ilinsky707','Valera Ilinsky','valera.ilinsky@hotmail.com','','a','41215441654','',0,0,'',0,0,0,'cf53c2eed1c4d20b965a34ec4342951a','2017-01-18','abc123','zh-CN','en'),(28,'will8','William Han','qinghan910@hotmail.com','','b','8618841561531','',0,0,'',0,0,0,NULL,NULL,'abc124','en','zh-CN'),(29,'vk707','Vladimir Kovalev','vakov707@outlook.com','','c','','',0,0,'',0,0,0,NULL,NULL,'abc125','en','zh-CN');

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
  `viewed_count` int(11) DEFAULT '0',
  `image` text,
  PRIMARY KEY (`videos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

/*Data for the table `videos` */

insert  into `videos`(`videos_id`,`link`,`title`,`viewed_count`,`image`) values (51,'8QVTOTtXb1c','Improve your English the CRAZY way!!!',0,'https://i.ytimg.com/vi/8QVTOTtXb1c/default.jpg');

/*Table structure for table `viewed` */

DROP TABLE IF EXISTS `viewed`;

CREATE TABLE `viewed` (
  `viewed_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `questions_id` int(11) DEFAULT NULL,
  `answers_id` int(11) DEFAULT NULL,
  `vsentences_id` int(11) DEFAULT NULL,
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
  `searched_count` int(11) DEFAULT '0',
  `viewed_count` int(11) DEFAULT '0',
  `reviewed_count` int(11) DEFAULT '0',
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
) ENGINE=MyISAM AUTO_INCREMENT=9245 DEFAULT CHARSET=utf8;

/*Data for the table `vsentences` */

insert  into `vsentences`(`vsentences_id`,`main`,`videos_id`,`start_pos`,`duration`,`order`,`editor_id`,`date`,`searched_count`,`viewed_count`,`reviewed_count`,`en`,`ar`,`bg`,`da`,`de`,`el`,`es`,`et`,`fa`,`fi`,`fr`,`hr`,`hu`,`it`,`iw`,`ja`,`ko`,`lt`,`lv`,`mr`,`pt-BR`,`pt`,`ru`,`sr`,`sv`,`tr`,`uk`,`vi`,`zh-CN`,`zh-TW`) values (9086,'en',51,969,8543,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'Hi, there. Are you having problems or difficulties,\nor do you find it difficult to practice speaking',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9087,'en',51,9538,7411,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'English? Maybe you live in a country where\nnobody around you speaks English, or you&#39;re',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9088,'en',51,16949,6160,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'the only person you know that speaks English.\nI&#39;ve got some advice for you. So, how to help',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9089,'en',51,23109,5827,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'you improve your speaking\nor your talking in English.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9090,'en',51,29377,10160,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'Goin&#39; crazy. Usually in English, we never\nsay: &quot;going&quot; or &quot;trying&quot;. We say: &quot;goin&#39;&quot;.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9091,'en',51,39563,8437,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'So, any time in English you see this, we&#39;re\nactually missing the &quot;g&quot;. So, probably you',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9092,'en',51,48000,9770,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'should say: &quot;Going crazy trying to speak or\npractice English&quot;. But, in slang when regular',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9093,'en',51,57770,7319,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'speech, we say: &quot;Goin&#39; crazy tryin&#39;\nto speak or practice English&quot;.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9094,'en',51,65115,6566,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'So, I want you to think about one thing. Crazy\npeople, there&#39;s one right here. I&#39;m crazy,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9095,'en',51,71707,7313,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'little bit. But when I say &quot;crazy people&quot;, I\nmean people who are mentally disturbed or',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9096,'en',51,79020,5820,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'have something really wrong with their brain.\nAnd we like to categorize people as being',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9097,'en',51,84840,10075,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'crazy, but they really are not insane. They just\nmake crazy noises. So if someone is considered',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9098,'en',51,94941,10341,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'crazy, what do they do? Crazy people usually\ntalk to themselves, they hear voices, especially',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9099,'en',51,105308,7614,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'if they&#39;re psychotic, and they will take to\nanyone or everyone that will listen to them.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9100,'en',51,112948,9722,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'So, my advice to you, secret number 42 of how\nto speak English, is act like you&#39;re crazy,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9101,'en',51,122670,2199,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'or just go crazy\nlearning English.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9102,'en',51,125126,10074,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'The first one: crazy people talk to themselves.\nYou are going to talk to yourself. If you',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9103,'en',51,135200,6585,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'want to really put... Bring this off and do\nit well, you could go on the bus [giggles]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9104,'en',51,141811,7029,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'or on any kind of trans... Public transportation,\ngo on the street in your city and just talk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9105,'en',51,148840,5560,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'to yourself on the street. I don&#39;t really\nrecommend that. If you want to do that, you',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9106,'en',51,154400,9470,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'can. But talk to yourself, but record it. So\nwhen you do this, you&#39;re actually listening',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9107,'en',51,163870,6346,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'to your English so you can catch your mistakes\nand you can listen to your pronunciation.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9108,'en',51,170349,7681,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'And, really, what do you sound like in English?\nSo, rule number one: you&#39;re going to talk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9109,'en',51,178030,9520,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'to yourself, but you&#39;re going to record it\nso you can check your mistakes and you can',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9110,'en',51,187550,6329,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'see just how well you do speak. Because I\nbet you, you speak better than you think.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9111,'en',51,193879,8811,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'Next one: crazy people talk about hearing\nvoices. Now, I know you inside have a voice.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9112,'en',51,202768,9262,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'You, like I, have an inner being, a voice\ninside your head. Crazy people are known to',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9113,'en',51,212030,8620,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'have more than one voice. If you have this,\nyou might want to seek some help. But when',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9114,'en',51,220650,9180,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'you hear voices, I want you to talk to yourself\ninside your head in English. When I lived',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9115,'en',51,229804,5540,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'in Japan, I learned to speak Japanese. I didn&#39;t\ntake a course. I don&#39;t like studying. But',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9116,'en',51,235344,9190,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'my inner voice spoke to me in Japanese. So\nI would come back to Canada or I would go',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9117,'en',51,244534,6560,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'travelling, and I would actually speak to\npeople who spoke English, they would ask me',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9118,'en',51,251120,4822,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'a question, I would answer them in\nJapanese because my inner voice',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9119,'en',51,255968,2182,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'was still talking\nto me in Japanese.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9120,'en',51,258150,8340,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'So, one really, really important and great\nthing that you can do is make your inside',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9121,'en',51,266464,6700,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'voice speak to you in English. This sounds\ncrazy, but I guarantee you that it&#39;s one of',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9122,'en',51,273190,6910,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'the ways that you know if you are coming actually\nbilingual (means you can speak two languages)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9123,'en',51,280100,6730,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'or trilingual. So, if your inside voice can\ntalk to you in two different languages, this',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9124,'en',51,286830,7312,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'is really amazing, and it means that your\nEnglish is improving. Everyone has a different',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9125,'en',51,294168,6292,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'timeline. Some people can do this within a\nyear, some people within months, some people',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9126,'en',51,300460,2386,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'it takes three or four\nyears to do this,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9127,'en',51,302872,3649,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'but once you have achieved this,\nwoohoo, you&#39;re almost there.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9128,'en',51,306547,5872,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'And the last one: you&#39;ll notice that if you\nsee crazy people on the subway or you see',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9129,'en',51,312419,9026,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'crazy people in your city, they&#39;re going to\ntalk to any or... This means &quot;or&quot;, by the',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9130,'en',51,321471,5948,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'way. Everyone. They don&#39;t care who it is.\nThey&#39;re not going to be picky and go: &quot;I don&#39;t',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9131,'en',51,327419,6691,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'want to talk to that person. I want to talk\nto everyone.&quot; So, the more people that you',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9132,'en',51,334110,5580,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'can speak to in English, the better. You don&#39;t\nhave to be picky. That means you don&#39;t have',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9133,'en',51,339664,6486,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'to choose. Is it a beautiful girl? A handsome\nboy? Young people, old people, babies, children.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9134,'en',51,346176,7454,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'Anyone that you know that speaks English,\ntry and talk to them. Even in your country,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9135,'en',51,353630,5468,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'you think: &quot;Ronnie, there&#39;s no one in my country\nthat speaks English&quot;, you might be surprised.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9136,'en',51,359124,5327,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'You can find people on websites, and you can\nfind other English speakers to talk to.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9137,'en',51,364477,5285,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'So, go crazy, speak as much as you\ncan, and learn English with me.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9138,'en',51,369848,4147,NULL,NULL,'2017-01-04 06:42:33',NULL,NULL,NULL,'I&#39;m Ronnie, and I&#39;m crazy.\nGood bye.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9139,'en',51,969,8543,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Hey, hola. ¿Tienes problemas o dificultades, o encuentras difícil el practicar hablando',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9140,'en',51,9538,7411,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Inglés? Quizá vives en un país donde nadie a tu alrededor habla inglés, o eres',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9141,'en',51,16949,6160,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'la única persona que conoces que habla inglés. Tengo algunos consejos para ti. Así que, ¿Cómo ayudarte',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9142,'en',51,23109,5827,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a mejorar hablando o charlando en inglés?',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9143,'en',51,29377,10160,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Vuélvete loco. Usualmente en inglés, no solemos decir &quot;Going&quot; o &quot;Trying&quot;. Nosotros decimos &quot;Goin&quot;.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9144,'en',51,39563,8437,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Así que, algunas veces en inglés tú ves esto, en realidad nosotros omitimos la &quot;g&quot;. Por lo tanto, problamente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9145,'en',51,48000,9770,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'debería decir: &quot;Going (Vuélvete) loco Trying (intentando) hablar o practicar inglés.&quot; Pero, cuando utilizamos la jerga',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9146,'en',51,57770,7319,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'solemos decir &quot;Goin&#39; (vuélvete) loco tryin&#39; (intentando) hablar o practicar Inglés.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9147,'en',51,65115,6566,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Así que, quiero hablarte de una cosa. La gente loca, hay una aquí. Yo estoy loca,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9148,'en',51,71707,7313,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'un poco. Pero cuando yo digo &quot;gente loca&quot;, Quiero decir gente que está mentalmente perturbada o',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9149,'en',51,79020,5820,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'que tiene algo realmente malo en su cerebro. Y nos gusta categorizar gente como',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9150,'en',51,84840,10075,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'loca, aunque realmente no lo estén. Sólo hacen sonidos extraños. Así que si alguien es conciderada',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9151,'en',51,94941,10341,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'loca, ¿Qué hacemos? La gente loca usualmente hablan con ellos mismos, oyen voces, especialmente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9152,'en',51,105308,7614,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'si son psicópatas, y hablan con cualquiera o todo el mundo para que lo escuchen.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9153,'en',51,112948,9722,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Así que, mi consejo para ti, secreto número 42 de cómo hablar inglés. Actúa como si estuvieras loco',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9154,'en',51,122670,2199,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'o sólo vuélvete loco aprendiendo inglés.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9155,'en',51,125126,10074,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'La primera cosa: &quot;La gente loca habla con si misma&quot;. Tú vas a hablar contigo mismo. Si',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9156,'en',51,135200,6585,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'quieres realmente poner esto en practica bien, podrías ir en el autobús (risas)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9157,'en',51,141811,7029,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'o en cualquier transporte público, ir sobre las calles de tu ciudad y sólo hablar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9158,'en',51,148840,5560,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'contigo mismo ahí. No recomiendo esto realmente. Si quieres hacer eso, tú',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9159,'en',51,154400,9470,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'puedes. Pero habla contigo mismo, grábalo. Entonces cuando hagas esto, estarás realmente escuchando',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9160,'en',51,163870,6346,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'tu inglés así que podrás encontrar tus errores y escuchar tu pronunciación.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9161,'en',51,170349,7681,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y, en realidad, ¿Qué es lo que suena inglés? Así que, regla número uno: Tu hablarás',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9162,'en',51,178030,9520,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'contigo mismo, y tendrás que grabarlo para revisar tus errores y podrás',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9163,'en',51,187550,6329,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ver qué tan bien hablas. Porque te apuesto a que hablas mejor de lo que piensas.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9164,'en',51,193879,8811,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Siguiente regla: &quot;La gente loca dice que oye voces&quot;. Ahora, yo sé que tienes una voz interior.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9165,'en',51,202768,9262,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Tú, como yo, tenemos un ser interior, una voz dentro de nuestra cabeza. La gente loca tiene',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9166,'en',51,212030,8620,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'más de una voz.  Si tienes esto, puede que quieras buscar ayuda. Pero cuando',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9167,'en',51,220650,9154,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'oyes voces, quiero decir de hablar contigo mismo en inglés dentro de tu cabeza. Cuando vivía',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9168,'en',51,229804,5540,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'en Japón, aprendí a hablar japonés. No tomé cursos. No me gusta estudiar. Pero',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9169,'en',51,235344,9190,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mi voz interior hablaba en japonés conmigo. Así que  podía regresar a Canadá o podría ir',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9170,'en',51,244534,6560,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'viajando, y yo podía hablar con personas que hablaban en inglés, podían hacerme una',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9171,'en',51,251120,4822,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pregunta, y yo podía responderles en japonés porque mi voz interior',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9172,'en',51,255968,2182,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'seguía hablándome en japonés.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9173,'en',51,258150,8314,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Así que una cosa muy, muy importante y genial que puedes hacer es hablar con tu voz',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9174,'en',51,266464,6700,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'interior en inglés. Esto suena loco, pero te garantizo que es una de',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9175,'en',51,273190,6910,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'las maneras en que podrás convertirte realmente en bilingüe (osea que hablarás dos idiomas)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9176,'en',51,280100,6730,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'o trilingüe. Así que si tu voz interior puede hablarte en dos diferentes idiomas, esto',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9177,'en',51,286830,7312,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'es realmente sorprendente, y significa que tu inglés está mejorando. Todos tienen un diferente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9178,'en',51,294168,6292,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ritmo. Algunas personas pueden hacerlo en un año, otros pueden en meses, a algunas personas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9179,'en',51,300460,2386,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'les toma tres o cuatro años hacer esto.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9180,'en',51,302872,3649,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Pero una vez hayas conseguido esto, woohoo, estarás casi ahí.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9181,'en',51,306547,5872,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Y la última regla; te darás cuenta si ves gente loca en el camión o si la ves',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9182,'en',51,312419,9026,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'en tu ciudad, ellos hablan a cualquiera o... Esto significa &quot;o&quot;, por cierto.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9183,'en',51,321471,5948,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Todos. A ellos no les importa quién. Ellos no van a ser difíciles y decir: &quot;Yo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9184,'en',51,327419,6691,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'no quiero hablar con esa persona&quot;. Quiero hablar con todos. Entonces, entre más puedas hablar con gente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9185,'en',51,334110,5554,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'en inglés, mejor. No debes de ser exigente. Eso quiere decir que no tienes',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9186,'en',51,339664,6486,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'elección. ¿Es una hermosa chica? ¿Un muchacho guapo? Gente joven, gente vieja, bebés, niños.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9187,'en',51,346176,7454,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Cualquiera que sepas que habla inglés, intenta y habla con ellos. Incluso en tu ciudad,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9188,'en',51,353630,5468,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'quizá piensas &quot;Ronnie, no hay nadie en miciudad que hable inglés&quot;, puede que te sorprendas.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9189,'en',51,359124,5327,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Puedes encontrar gente en los sitios web, y puedes encontrar a otras personas que hablen inglés para charlar.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9190,'en',51,364477,5285,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Así que, vuélvete loco, habla tanto como puedas, y aprende inglés conmigo.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9191,'en',51,369848,4147,NULL,NULL,'2017-01-04 06:42:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Soy Ronnie, y estoy loca. Adiós.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9192,'en',51,969,8543,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Oi pessoal. Você está tendo problemas ou dificuldades, ou você acha difícil de praticar a fala',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9193,'en',51,9538,7411,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'em inglês? Talvez você viva em um país que ninguém ao redor de você fale inglês, ou você é',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9194,'en',51,16949,6160,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a única pessoa que você sabe que fala inglês. Eu tenho um conselho para você. Então, como ajudar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9195,'en',51,23109,5827,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a melhorar sua fala ou sua conversa em inglês.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9196,'en',51,29377,10160,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Ficando louco. Normalmente em inglês,, nós nunca falamos: &quot;going&quot; ou &quot;trying&quot;. Nós dizemos: &quot;goin&quot;.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9197,'en',51,39563,8437,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Então, toda hora em inglês você vê isso, nós estamos na verdade esquecendo o &quot;g&quot;. Então, provavelmente você',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9198,'en',51,48000,9770,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'deveria dizer:  &quot;Ficando louco tentando falar ou praticar inglês&quot;. Mas, na gíria no discurso',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9199,'en',51,57770,7319,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'regular, nós falamos: &quot;Goin&#39; crazy trying&#39; to speak or practice English&quot;.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9200,'en',51,65115,6566,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Então, eu quero que você reflita sobre uma coisa. Pessoas loucas, tem uma bem aqui. Eu sou louca,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9201,'en',51,71707,7313,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'um pouco. Mas quando eu digo &quot;pessoas loucas&quot;, quero dizer pessoas que são mentalmente perturbadas ou',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9202,'en',51,79020,5820,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'tem algo muito errado com seus cérebros. E nós gostamos de categorizar pessoas como',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9203,'en',51,84840,10075,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'loucas, mas eles realmente não são insanas. Eles apenas fazem barulhos estranhos. Então se alguém é considerado',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9204,'en',51,94941,10341,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'louco, o que eles fazem? Pessoas loucas normalmente conversam consigo mesmas, especialmente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9205,'en',51,105308,7614,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'se eles são psicóticos, e eles vão pegar qualquer um ou todos que irão ouvi-los.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9206,'en',51,112948,9722,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Então, meu aviso à vocês, número secreto 42 do como falar inglês, é agir como você fosse louco',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9207,'en',51,122670,2199,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ou apenas ser louco aprendendo inglês.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9208,'en',51,125126,10074,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'O primeiro: pessoas loucas conversam consigo mesmas. Você vai falar com você. Se você',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9209,'en',51,135200,6585,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'quer realmente colocar... Trazer isso pra fora e fazer isso certo, você poderia ir ao ônibus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9210,'en',51,141811,7029,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ou num meio de trans... Trasporte público, vá às ruas de sua cidade e apenas converse',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9211,'en',51,148840,5560,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'com você  na rua. Eu não recomendo muito isso. Se você quiser fazer isso, você',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9212,'en',51,154400,9470,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pode. Mas falar consigo mesmo, mas gravar isso. Então quando você faz isso, você está na verdade ouvindo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9213,'en',51,163870,6346,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'seu inglês então você pode pegar seus erros e ouvir sua pronúncia.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9214,'en',51,170349,7681,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'E, realmente, e com o que você soa em inglês? Então, regra número um: você vai falar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9215,'en',51,178030,9520,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'consigo mesmo, mas você vai gravar isso para que você cheque seus erros e consiga',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9216,'en',51,187550,6329,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ver o quão bom você fala. Porque eu aposto em você, você fala melhor do que você imagina.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9217,'en',51,193879,8811,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Próxima: pessoas loucas falam sobre ouvir vozes. Agora, eu sei que dentro de você tem uma voz.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9218,'en',51,202768,9262,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Você, tipo, tem um ser interior,, uma voz dentro da sua cabeça. Pessoas loucas são conhecidas por',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9219,'en',51,212030,8620,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ter mais de uma voz. Se você tem isso, você deveria procurar ajuda. Mas quando',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9220,'en',51,220650,9154,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'você ouve vozes, eu quero que você fale com você mesmo dentro da sua cabeça em inglês. Quando eu morei',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9221,'en',51,229804,5540,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'no Japão, eu aprendi a falar japonês. Eu não fiz um curso. Eu não gosto de estudar. Mas',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9222,'en',51,235344,9190,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'minha voz interna falou comigo em japonês. Então eu viria pro Canadá ou iria',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9223,'en',51,244534,6560,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'viajar, e iria na verdade falar com as pessoas que falam inglês, elas iriam me perguntar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9224,'en',51,251120,4822,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'e eu iria responder em japonês porque minha voz interna',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9225,'en',51,255968,2182,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'estava ainda falando comigo em japonês.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9226,'en',51,258150,8314,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'então, uma coisa muito, muito importante e boa coisa que você pode fazer sua voz interna',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9227,'en',51,266464,6700,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'falar com você em inglês. Isso parece um pouco louco, mas eu garanto a você que é uma das',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9228,'en',51,273190,6910,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'maneiras que você sabe se você está se tornando bilíngue (significa que você fala duas línguas)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9229,'en',51,280100,6730,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ou trilíngue. Então, se sua voz interna pode conversar com você em duas diferentes línguas, isso',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9230,'en',51,286830,7312,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'é muito surpreendente, e isso significa que seu inglês está melhorando. Todo mundo tem um diferente',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9231,'en',51,294168,6292,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'cronograma. Algumas pessoas podem fazer isso com um ano, outras com meses, outras',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9232,'en',51,300460,2386,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'dura três ou quatro anos para fazer isso,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9233,'en',51,302872,3649,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'mas uma vez que você atingiu isso, whoohoo, você está quase lá.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9234,'en',51,306547,5872,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'E a última: você vai perceber que se você ver pessoas loucas no metrô ou você ver',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9235,'en',51,312419,9026,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'pessoas loucas na sua cidade, eles vão conversar com algum ou... Isso significa &quot;ou&quot;, a propósito.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9236,'en',51,321471,5948,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Todo mundo. Eles não ligam quem é. Eles não vão ser exigentes e ir: &quot;Eu não',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9237,'en',51,327419,6691,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'quero conversar com essa pessoa. Eu quero conversar com todo mundo.&quot; Então, quanto mais pessoas que você',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9238,'en',51,334110,5554,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'conseguir conversar em inglês, melhor. Você não tem que ser exigente. Isso significa que você não tem que',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9239,'en',51,339664,6486,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'escolher. É uma garota bonita? Um cara bonito? Pessoas novas, velhas, bebês, crianças.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9240,'en',51,346176,7454,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Qualquer um que você sabe que fala inglês, tente e fale com eles. Mesmo em seu país,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9241,'en',51,353630,5468,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'você pensa: &quot;Ronnie, não tem ninguém no meu país que fala inglês&quot;, você deve estar surpreso.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9242,'en',51,359124,5327,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Você pode achar pessoas em sites e achar outros falantes de inglês para conversas.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9243,'en',51,364477,5285,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Então, seja louco, fale o quanto mais você pode, e aprenda inglês comigo.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9244,'en',51,369848,4147,NULL,NULL,'2017-01-04 06:42:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Eu sou Ronnie, e sou louca. Tchau.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

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
