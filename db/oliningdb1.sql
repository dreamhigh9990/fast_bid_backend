-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2016 at 06:44 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olining`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answers_id` int(11) NOT NULL,
  `answerer_id` int(11) DEFAULT NULL,
  `liked_count` int(11) DEFAULT NULL,
  `is_correct_answer` tinyint(1) DEFAULT NULL,
  `answer` text,
  `questions_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `books_id` int(11) NOT NULL,
  `courses_id` int(11) DEFAULT NULL,
  `name` varchar(240) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`books_id`, `courses_id`, `name`) VALUES
(3, 0, 'Family Album'),
(4, 0, 'New English 900'),
(5, 1, 'Voice Tube'),
(6, 1, 'AJ Hoge Book');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `name` varchar(240) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `icon` varchar(240) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `name`, `parent`, `icon`) VALUES
(1, 'Shacharit', 0, 'wb_sunny'),
(4, 'Arvit', 0, 'wb_sunny'),
(6, 'Mincha', 0, 'wb_sunny'),
(7, 'Prayers', 0, 'format_quote'),
(8, 'Bedtime Shema', 0, 'hotel'),
(9, 'Food Brachot', 0, 'restaurant'),
(10, 'Before a Meal', 9, NULL),
(11, 'After a Meal', 9, NULL),
(13, 'Test1', 9, NULL),
(14, 'Test2', 11, NULL),
(15, 'Test3', 10, NULL),
(16, 'Test4', 11, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `chapters_id` int(11) NOT NULL,
  `name` varchar(240) DEFAULT NULL,
  `books_id` int(11) DEFAULT NULL,
  `words_count` int(11) DEFAULT NULL,
  `sentences_count` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapters_id`, `name`, `books_id`, `words_count`, `sentences_count`) VALUES
(1, 'EPISODE I  "46 Linden Street” -> Act 1', 0, NULL, NULL),
(2, 'EPISODE II  "AA” -> Act 1', 3, NULL, NULL),
(3, 'EPISODE I  "46 Linden Street” -> Act 1', 3, NULL, NULL),
(4, 'A clever way to estimate enormous', 5, NULL, NULL),
(5, 'AJ Hoge Book Chapter 1', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ci_cookies`
--

CREATE TABLE `ci_cookies` (
  `id` int(11) NOT NULL,
  `cookie_id` varchar(255) DEFAULT NULL,
  `netid` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `orig_page_requested` varchar(120) DEFAULT NULL,
  `php_session_id` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('847cacb71a2cba2d220f86bb521659ff', '192.168.0.121', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36', 1481730576, 'a:7:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;s:15:"videos_selected";N;s:22:"search_string_selected";N;s:5:"order";N;s:10:"order_type";N;}'),
('cc56d8fd2d05610ce4ce6b8f75651d03', '192.168.0.121', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36', 1481737324, 'a:7:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;s:15:"videos_selected";N;s:22:"search_string_selected";N;s:5:"order";N;s:10:"order_type";N;}');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courses_id` int(11) NOT NULL,
  `name` varchar(240) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courses_id`, `name`) VALUES
(1, 'AJ Hoge'),
(2, 'Crazy English');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
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
  `tzip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dictionarys`
--

CREATE TABLE `dictionarys` (
  `dictionarys_id` int(11) NOT NULL,
  `name` varchar(240) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `fromUser` int(11) NOT NULL,
  `toUser` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `content` text NOT NULL,
  `fromType` int(11) NOT NULL,
  `createdAt` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mail_contents`
--

CREATE TABLE `mail_contents` (
  `mail_id` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mail_contents`
--

INSERT INTO `mail_contents` (`mail_id`, `title`, `content`) VALUES
(1, 'asdfaa', 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_addres` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `pass_word` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`id`, `first_name`, `last_name`, `email_addres`, `user_name`, `pass_word`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'abcdasdf', 'bcdddasdf', 'a@a.com', 'abcd', 'e2fc714c4727ee9395f324cd2e7f331f');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `img_url` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='news table';

-- --------------------------------------------------------

--
-- Table structure for table `point_contents`
--

CREATE TABLE `point_contents` (
  `point_id` bigint(20) NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `point_contents`
--

INSERT INTO `point_contents` (`point_id`, `content`) VALUES
(1, 'abcd');

-- --------------------------------------------------------

--
-- Table structure for table `prayers`
--

CREATE TABLE `prayers` (
  `prayers_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `content_english` text,
  `content_hebrew` text,
  `content_transliterated` text,
  `published` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prayers`
--

INSERT INTO `prayers` (`prayers_id`, `category`, `name`, `tag`, `content_english`, `content_hebrew`, `content_transliterated`, `published`) VALUES
(8, '4', 'Test Arvit', NULL, 'asdofij', 'asdf\r\nas\r\ndf\r\nasdf\r\na\r\nsdf\r\nasd\r\nf', 'asoidjfoiwjaefoijwqoiejf\r\nasdf\r\nasd\r\nfa\r\nsdf\r\nasd\r\nf', 1),
(7, '1', 'Test Prayer1', NULL, 'On becoming conscious, say Modeh Ani, as follows:\r\n\r\nI give thanks before You,\r\nliving and eternal King,\r\nwho has returned my soul into me\r\nin compassion; great is Your faithfulness. ', 'ז. אחר כך ישב ויכניס התפילין של יד, וקודם לכן יבדוק שאין שום דבר תוצץ הן במקום ההנחה והן במקום הרצועות (בא"ח חיי שרה ה). ואז יסדר התפילין בידו החלשה יותר (שם ז), במקום השריר. ויכוין את התפילין לכיוון הלב, כך שהיו"ד [הוא פיסת רצועה הנמצאת בצד התפילין של יד שנקשר כעין אות יו"ד] יהיה לצד אחור במעט. ואז יכסה כל היד בטלית שעליו, כמ"ש "והיה לך לאות על ידך", לך ולא לאחרים. ואפילו יושב לבדו יעשה כן (בא"ח וירא טו).\r\nח. ואז אחר שהניח התפילין של יד על הקיבורת, ולפני הקשירה וההידוק, יברך "להניח תפילין" ביושב (שם ח):\r\n', 'On becoming conscious, say Modeh Ani, as follows:\r\n\r\nMo-deh   a-ni   l''fa-ne-cha\r\nme-lech   chai   v''ka-yam\r\nshe-he-che-zar-ta   bi   nish-ma-ti\r\nb''chem-la   ra-bah   e-mu-na-te-cha.\r\n\r\n\r\nAfter washing the hands, say:\r\n\r\nBa-ruch   a-tah   A-do-nai,\r\nE-lo-hei-nu,   Me-lech   Ha-o-lam,\r\na-sher   ki-d''sha-nu   b''mits-vo-tav,\r\nv''tsi-va-nu,\r\nal   n''ti-lat   ya-da-yim.\r\n\r\n\r\nAfter going to the bathroom, and then washing hands as above, say Asher Yatsar, as follows:\r\n\r\nBa-ruch  a-tah  A-do-nai,\r\nElo-hei-nu,  me-lech  l''o-lam,\r\nA-sher  ya-tsar\r\net  ha-a-dam  b''choch-mah,\r\nu-va-ra  vo\r\nn''ka-vim  n''ka-vim,\r\ncha-lu-lim  cha-lu-lim,\r\nga-lu  v''ya-du-a\r\nlif-nei  chi-sei  ch''vo-de-cha\r\nshe-im  yi-pa-tei-ach  e-chad  mei-hem\r\no  yi-sa-teim  e-chad  mei-hem,\r\ni  ef-shar  l''hit-ka-yeim\r\nv''la-a-mod  l''fa-ne-cha\r\n[a-fi-lu  sha-ah  e-chat].\r\nBa-ruch  a-tah  A-do-nai,\r\nro-fei  chawl  ba-sar\r\nu-maf-li  la-a-sot.\r\n\r\n\r\nBefore donning the small tallis, while inspecting the fringes, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher   ki-d''sha-nu  b''mits-vo-tav,\r\nv''tsi-va-nu,  \r\nal  mits-vat  tsi-tsit.\r\n\r\n\r\nBefore donning the large tallis, while inspecting the fringes, recite the first two verses of Psalm 104:\r\n\r\nBa-r''chi  naf-shi  et  A-do-nai,\r\nA-do-nai   E-lo-hai  ga-dal-ta  m''od,\r\nhod   v''ha-dar  la-vash-ta.\r\nO-te   or  ka-sal-mah,\r\nno-teh   sha-ma-yim  kai-ri-ah.\r\n\r\n\r\nWhen ready to don it, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher   ki-d''sha-nu  b''mits-vo-tav,\r\nv''tsi-va-nu,  l''-hit-a-teif  ba-tsi-tsit. \r\n\r\n\r\nWhile the head and body are enwrapped, recite verses 8 through 11 of Psalm 36:\r\n\r\nMa ya-kar   chas-d''cha  E-lo-him,\r\nuv-nei   a-dam  b''tseil  k''na-fe-cha  ye-che-sa-yun.\r\nYir-v''yun   mi-de-shen  bei-te-cha,\r\nv''na-chal   a-da-ne-cha  tash-keim.\r\nKi   i-m''cha  m''kor  cha-yim,\r\nb''o-r''cha   nir-eh  or.\r\nM''shoch   chas-d''cha  l''yo-d''e-cha,\r\nv''tsid-ka-t''cha   l''yish-rei leiv.\r\n\r\n\r\nBefore tightening the arm t''fillin, men say:\r\n\r\nBa-ruch   a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher  ki-d''sha-nu  b''mits-vo-tav,\r\nv''tsi-va-nu,  l''-ha-ni-ach  t''fi-lin. \r\n\r\n\r\nBefore donning the head t''fillin, men say:\r\n\r\nBa-ruch  a-tah  A-do-nai,\r\nE-lo-hei-nu,  Me-lech  Ha-o-lam,\r\na-sher  ki-d''sha-nu  b''mits-vo-tav,\r\nv''tsi-va-nu,\r\nal  mits-vat  t''fi-lin. ', 1),
(9, '8', 'Test Bedtime Shema', NULL, 'asdf', 'asdfbhsdfbdfb', 'asdf', 1),
(10, '1', 'Test Prayer12', NULL, 'asdf', 'asdfasdf', 'asdfasdf', 0),
(11, '6', 'Test Mincha', NULL, 'foaisjdfoijopwiejfgopi', 'q908jerfiajsgvoi', 'fqopiwjefpq1jf890234', 0),
(12, '6', 'asdf', NULL, 'asdf', 'asdf', 'asdf', 1),
(13, '10', 'asdf', NULL, 'asdf', 'asdf', 'asdf', 1),
(14, '1', 'Test Shacharit', NULL, 'a', 'a', 'a', 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questions_id` int(11) NOT NULL,
  `english` text,
  `chinese` text,
  `korean` text,
  `german` text,
  `direction` varchar(30) DEFAULT NULL,
  `description` text,
  `image_1` text,
  `image_2` text,
  `image_3` text,
  `image_4` text,
  `image_5` text,
  `asker_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reviewed`
--

CREATE TABLE `reviewed` (
  `reviewed_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `successed` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sentences`
--

CREATE TABLE `sentences` (
  `sentences_id` int(11) NOT NULL,
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
  `d_language` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tested`
--

CREATE TABLE `tested` (
  `tested_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `is_global` tinyint(1) DEFAULT NULL,
  `score` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
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
  `is_deliverer` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `phone` varchar(50) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `videos_id` int(11) NOT NULL,
  `link` text,
  `title` varchar(255) DEFAULT NULL,
  `reviewed_count` int(11) DEFAULT NULL,
  `image` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `viewed`
--

CREATE TABLE `viewed` (
  `viewed_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `sentences_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vsentences`
--

CREATE TABLE `vsentences` (
  `vsentences_id` int(11) NOT NULL,
  `english` text,
  `chinese` text,
  `korean` text,
  `german` text,
  `direction` varchar(30) DEFAULT NULL,
  `videos_id` int(11) DEFAULT NULL,
  `start_pos` int(11) DEFAULT NULL,
  `end_pos` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `editor_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `words_id` int(11) NOT NULL,
  `english` varchar(240) DEFAULT NULL,
  `chinese` varchar(240) DEFAULT NULL,
  `korean` varchar(240) DEFAULT NULL,
  `german` varchar(240) DEFAULT NULL,
  `direction` varchar(30) DEFAULT NULL,
  `pronunciation` varchar(240) DEFAULT NULL,
  `audio` text,
  `image` text,
  `dictionary_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answers_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`books_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`chapters_id`);

--
-- Indexes for table `ci_cookies`
--
ALTER TABLE `ci_cookies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courses_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`);

--
-- Indexes for table `dictionarys`
--
ALTER TABLE `dictionarys`
  ADD PRIMARY KEY (`dictionarys_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_contents`
--
ALTER TABLE `mail_contents`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `point_contents`
--
ALTER TABLE `point_contents`
  ADD PRIMARY KEY (`point_id`);

--
-- Indexes for table `prayers`
--
ALTER TABLE `prayers`
  ADD PRIMARY KEY (`prayers_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questions_id`);

--
-- Indexes for table `reviewed`
--
ALTER TABLE `reviewed`
  ADD PRIMARY KEY (`reviewed_id`);

--
-- Indexes for table `sentences`
--
ALTER TABLE `sentences`
  ADD PRIMARY KEY (`sentences_id`);

--
-- Indexes for table `tested`
--
ALTER TABLE `tested`
  ADD PRIMARY KEY (`tested_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`videos_id`);

--
-- Indexes for table `viewed`
--
ALTER TABLE `viewed`
  ADD PRIMARY KEY (`viewed_id`);

--
-- Indexes for table `vsentences`
--
ALTER TABLE `vsentences`
  ADD PRIMARY KEY (`vsentences_id`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`words_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answers_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `books_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapters_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ci_cookies`
--
ALTER TABLE `ci_cookies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courses_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dictionarys`
--
ALTER TABLE `dictionarys`
  MODIFY `dictionarys_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mail_contents`
--
ALTER TABLE `mail_contents`
  MODIFY `mail_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `point_contents`
--
ALTER TABLE `point_contents`
  MODIFY `point_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `prayers`
--
ALTER TABLE `prayers`
  MODIFY `prayers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `questions_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reviewed`
--
ALTER TABLE `reviewed`
  MODIFY `reviewed_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sentences`
--
ALTER TABLE `sentences`
  MODIFY `sentences_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT for table `tested`
--
ALTER TABLE `tested`
  MODIFY `tested_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `videos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `viewed`
--
ALTER TABLE `viewed`
  MODIFY `viewed_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vsentences`
--
ALTER TABLE `vsentences`
  MODIFY `vsentences_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `words_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
