-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 13, 2019 at 12:26 PM
-- Server version: 5.7.24
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2019_doctors`
--

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

DROP TABLE IF EXISTS `diseases`;
CREATE TABLE IF NOT EXISTS `diseases` (
  `did` int(10) NOT NULL AUTO_INCREMENT,
  `dname` varchar(255) NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`did`, `dname`) VALUES
(1, 'السكري'),
(2, 'الانفلونزا'),
(3, 'متلازمة رايتر'),
(4, 'حصى الكلى'),
(5, 'الحصبة'),
(6, 'الذئبة'),
(7, 'التهاب الجيوب المزمن');

-- --------------------------------------------------------

--
-- Table structure for table `dis_symp`
--

DROP TABLE IF EXISTS `dis_symp`;
CREATE TABLE IF NOT EXISTS `dis_symp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `did` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dis_symp`
--

INSERT INTO `dis_symp` (`id`, `did`, `sid`) VALUES
(1, 2, 2),
(2, 2, 3),
(15, 2, 11),
(4, 2, 5),
(5, 1, 6),
(6, 1, 8),
(7, 1, 9),
(8, 1, 10),
(9, 1, 11),
(10, 7, 12),
(11, 7, 7),
(12, 7, 13),
(13, 7, 11),
(14, 7, 4),
(16, 2, 12),
(17, 2, 14),
(18, 2, 15),
(19, 2, 16),
(20, 2, 7),
(21, 7, 19),
(22, 7, 20),
(23, 1, 21),
(24, 1, 22),
(25, 7, 5),
(26, 7, 15),
(27, 7, 6),
(28, 7, 7);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `web` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty`, `address`, `tel`, `web`) VALUES
(1, 'د. احمد سعيد الجدبة', 'الانف والاذن والحنجرة', 'غزة', '54553', 'موقع ويب'),
(2, 'د. ياسر عبدالرحيم الزيناتي', 'الانف والاذن والحنجرة', 'فلسطين - الخليل', '563', 'ويب'),
(3, 'د. سفيان رزق حمودة ابو حليمة', 'الانف والاذن والحنجرة', 'فلسطين - غزة', '545656', 'ويب'),
(4, 'د. جمال حرزالله', 'الانف والاذن والحنجرة', 'غزة ', '5635', 'ويب'),
(5, 'د. رواد محمد بدوي ابو ريان', 'الانف والاذن والحنجرة', 'غزة', '56456', 'ويب'),
(6, 'د. سائد رائد دياب', 'طب عام', 'فلسطين - غزة', '7852', 'ويب'),
(7, 'د. محمد زيارة', 'طب عام', 'غزة', '456356', 'ويب'),
(8, 'د. ردينة الدردساوي', 'باطنية', 'غزة', '56465456', 'ويب'),
(9, 'د. خالد ابوحليب', 'باطنية', 'غزة', '4574524', 'ويب'),
(10, 'د. عبد الفتاح رمضان محمد الاغا', 'باطنية', 'غزة', '478527', 'ويب');

-- --------------------------------------------------------

--
-- Table structure for table `doctors_diseases`
--

DROP TABLE IF EXISTS `doctors_diseases`;
CREATE TABLE IF NOT EXISTS `doctors_diseases` (
  `ddid` int(10) NOT NULL AUTO_INCREMENT,
  `dis_id` int(10) NOT NULL,
  `doc_id` int(10) NOT NULL,
  PRIMARY KEY (`ddid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctors_diseases`
--

INSERT INTO `doctors_diseases` (`ddid`, `dis_id`, `doc_id`) VALUES
(12, 1, 8),
(11, 2, 5),
(10, 2, 1),
(9, 2, 2),
(8, 2, 3),
(7, 2, 4),
(13, 1, 9),
(14, 1, 10),
(15, 7, 7),
(16, 7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

DROP TABLE IF EXISTS `symptoms`;
CREATE TABLE IF NOT EXISTS `symptoms` (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `sname` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`sid`, `sname`, `question`) VALUES
(1, 'اكتئاب', 'هل تشعر بالاكتئاب ؟'),
(2, 'الم عضلي', 'هل تشعر بالام بالعضلات ؟'),
(3, 'اسهال', 'هل تعاني من الاسهال ؟'),
(4, 'غثيان', 'هل تعاني من الغثيان ؟ '),
(5, 'صداع', 'هل تعاني من الصداع ؟'),
(6, 'جوع شديد', 'هل تشعر بالجوع الشديد ؟ '),
(7, 'سعال جاف', 'هل تعاني من السعال الجاف؟'),
(8, 'التبول كثيرا', 'هل تتبول كثيرا؟ '),
(9, 'تعب وارهاق', 'هل تعاني من التعب والارهاق ؟'),
(10, 'تشوش الرؤية', 'هل تعاني من تشوش الرؤية'),
(11, 'اعياء', 'هل تعاني من الاعياء الشديد ؟'),
(12, 'احتقان الانف', 'هل تعاني من احتقان الانف ؟'),
(13, ' ألم في الفك العلوي وفي الأسنان', 'هل تعاني من  ألم في الفك العلوي وفي الأسنان ؟ '),
(14, 'قشعريرة', 'هل تشعر بالقشعريرة'),
(15, 'تعرق', 'هل تشعر بالتعرق الكثير ؟'),
(16, 'فقدان شهيه', 'هل تعاني من فقدان الشهية ؟'),
(17, 'زياده الشهيه', 'هل تعاني من زيادة الشهية ؟'),
(18, 'زائده للضوء', 'هل تعاني من زائده للضوء ؟'),
(19, 'آلام في الأذنين', 'هل تعاني من آلام في الأذنين؟'),
(20, 'حساسية في الحلق', 'هل تعاني من حساسية في الحلق ؟'),
(21, 'العطش', 'هل تشعر بالعطش المستمر ؟'),
(22, 'شفاء الجروح ببطء', 'هل تعاني من شفاء الجروح ببطء ؟');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
