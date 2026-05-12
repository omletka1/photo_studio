-- MariaDB dump 10.19  Distrib 10.8.4-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: stat
-- ------------------------------------------------------
-- Server version	10.8.4-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES
(1,'Анна Смирнова','Куратор конкурса','anna@example.com','+7 (900) 123-45-67','/images/team1.jpg'),
(2,'Иван Петров','Техническая поддержка','ivan@example.com','+7 (901) 765-43-21','/images/team2.jpg');
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_request`
--

DROP TABLE IF EXISTS `contact_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `description` text DEFAULT NULL,
  `contacts` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `contact_request_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_request`
--

LOCK TABLES `contact_request` WRITE;
/*!40000 ALTER TABLE `contact_request` DISABLE KEYS */;
INSERT INTO `contact_request` VALUES
(1,7,'МОжно','','dfvd@mail.ru','2025-05-18 15:11:42');
/*!40000 ALTER TABLE `contact_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konkurs`
--

DROP TABLE IF EXISTS `konkurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `konkurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konkurs`
--

LOCK TABLES `konkurs` WRITE;
/*!40000 ALTER TABLE `konkurs` DISABLE KEYS */;
INSERT INTO `konkurs` VALUES
(1,'«Весна в объективе»','Конкурс весенней фотографии','2025-04-02','2025-05-20','закрыт'),
(2,'«Город и люди»','Фотоистории о жизни в городе','2025-04-01','2025-04-05','закрыт'),
(3,'Архитектура','Гармония форм и линий в городской среде. От исторических зданий до современных небоскребов.','2025-05-01','2025-05-31','открыт'),
(4,'Документальная фотография','Реальная жизнь без прикрас. Социальные темы, исторические события и человеческие истории.','2025-05-02','2025-05-31','открыт'),
(5,'Концептуальная фотография','Фотография как искусство, передающее идеи и концепции. Символизм, метафоры и глубокий смысл.','2025-05-01','2025-06-30','открыт'),
(6,'Портрет','Искусство запечатления человеческой души через выражение лица, позу и композицию. Покажите нам глубину характера ваших моделей.','2025-04-01','2025-05-12','закрыт'),
(7,'Природа','Красота и мощь природного мира - от величественных пейзажей до макросъемки. Запечатлейте удивительные моменты живой природы.','2025-05-01','2025-07-18','открыт');
/*!40000 ALTER TABLE `konkurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nominations`
--

DROP TABLE IF EXISTS `nominations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nominations`
--

LOCK TABLES `nominations` WRITE;
/*!40000 ALTER TABLE `nominations` DISABLE KEYS */;
INSERT INTO `nominations` VALUES
(1,'Портрет','Искусство запечатления человеческой души через выражение лица, позу и композицию. Покажите нам глубину характера ваших моделей.','portrait.jpg'),
(2,'Свадьба','Трогательные и искренние моменты самого важного дня в жизни людей. Эмоции, детали и неповторимая атмосфера свадебных церемоний.','wedding.jpg'),
(3,'Путешествия','Уникальные места, культура и люди со всего мира. Покажите нам ваши самые впечатляющие кадры из путешествий.','travel.jpg'),
(4,'Природа','Красота и мощь природного мира - от величественных пейзажей до макросъемки. Запечатлейте удивительные моменты живой природы.','nature.jpg'),
(5,'Уличная фотография','Настоящая жизнь в ее самых искренних проявлениях. Спонтанные моменты, городская среда и человеческие истории.','street.jpg'),
(6,'Фэшн','Стиль, красота и эстетика в мире моды. Творческие образы, модели и fashion-истории.','fashion.jpg'),
(7,'Архитектура','Гармония форм и линий в городской среде. От исторических зданий до современных небоскребов.','architecture.jpg'),
(8,'Ч/Б фотография','Игра света и тени, контрасты и эмоции в монохромном исполнении. Вне времени, вне цвета - только суть.','bw.jpg'),
(9,'Концептуальная фотография','Фотография как искусство, передающее идеи и концепции. Символизм, метафоры и глубокий смысл.','conceptual.jpg'),
(10,'Документальная фотография','Реальная жизнь без прикрас. Социальные темы, исторические события и человеческие истории.','documentary.jpg');
/*!40000 ALTER TABLE `nominations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizers`
--

DROP TABLE IF EXISTS `organizers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizers`
--

LOCK TABLES `organizers` WRITE;
/*!40000 ALTER TABLE `organizers` DISABLE KEYS */;
INSERT INTO `organizers` VALUES
(1,'Анна Смирнова','Главный организатор','Фотограф с 15-летним стажем, куратор международных выставок, основатель Portfolio Award.','anna-smirnova.jpg','https://vk.com/','https://t.me/telegram','https://annasmirnova.com','2025-04-20 15:52:22','2025-05-04 07:51:19'),
(2,'Дмитрий Петров','Технический директор','Специалист по организации крупных фотофестивалей, технический эксперт в области фотографии.','dmitry-petrov.jpg','https://vk.com/','https://t.me/telegram','https://petrov-photo.ru','2025-04-20 15:52:22','2025-05-04 07:51:22'),
(3,'Елена Ковалева','Координатор жюри','Куратор фотографических проектов, организатор мастер-классов и воркшопов для фотографов.','elena-kovaleva.jpg','https://vk.com/','https://t.me/telegram',NULL,'2025-04-20 15:52:22','2025-05-04 07:51:24'),
(4,'Максим Волков','PR-менеджер','Специалист по продвижению фотографических проектов, организатор медиа-партнерств.','maxim-volkov.jpg',NULL,'https://t.me/telegram','https://volkov-media.com','2025-04-20 15:52:22','2025-05-04 07:51:27'),
(5,'Ольга Белова','Координатор участников','Организатор фотоконкурсов с 2010 года, отвечает за работу с участниками и обработку заявок.','olga-belova.jpg','https://vk.com/',NULL,NULL,'2025-04-20 15:52:22','2025-05-04 07:49:34'),
(6,'Иван Соколов','Технический специалист','Обеспечивает работу онлайн-платформы конкурса, специалист по цифровым технологиям в фотографии.','ivan-sokolov.jpg',NULL,'https://t.me/telegram','https://sokolov-tech.ru','2025-04-20 15:52:22','2025-05-04 07:51:29'),
(7,'София Морозова','Куратор номинаций','Эксперт в различных жанрах фотографии, отвечает за разработку и координацию конкурсных номинаций.','sofia-morozova.jpg','https://vk.com/','https://t.me/telegram',NULL,'2025-04-20 15:52:22','2025-05-04 07:51:31');
/*!40000 ALTER TABLE `organizers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `konkurs_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `ocenka` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `konkurs_id` (`konkurs_id`,`submission_id`),
  KEY `submission_id` (`submission_id`),
  CONSTRAINT `result_ibfk_1` FOREIGN KEY (`konkurs_id`) REFERENCES `konkurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `result_ibfk_2` FOREIGN KEY (`submission_id`) REFERENCES `submission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result`
--

LOCK TABLES `result` WRITE;
/*!40000 ALTER TABLE `result` DISABLE KEYS */;
/*!40000 ALTER TABLE `result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submission`
--

DROP TABLE IF EXISTS `submission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `konkurs_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`konkurs_id`),
  KEY `konkurs_id` (`konkurs_id`),
  CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submission_ibfk_3` FOREIGN KEY (`konkurs_id`) REFERENCES `konkurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submission`
--

LOCK TABLES `submission` WRITE;
/*!40000 ALTER TABLE `submission` DISABLE KEYS */;
INSERT INTO `submission` VALUES
(18,7,2,'Люди спешат на работу','Городская суета прекрасна','uploads/img_6803b4c29b324.jpg','uploads/img_6803b4c2a6bea.jpg','uploads/img_6803b4c2b1188.jpg','','','2025-05-02 04:50:06','2025-05-21 14:58:47',1),
(22,7,1,'Весна','Как же прекрасна природа весной!','uploads/img_6829f3f8a55f7.jpg','uploads/img_6829f3f8aa931.jpg','uploads/img_6829f3f8b0592.jpg','uploads/img_6829f3f8b63f2.jpg','','2025-05-18 14:51:36','2025-05-19 17:13:37',3),
(23,7,3,'аввма','вммва','uploads/img_6830392151e73.jpg','uploads/img_683039215cc4f.jpg','uploads/img_6830392164dd5.jpg','','','2025-05-23 09:00:17','2025-05-23 09:00:17',NULL);
/*!40000 ALTER TABLE `submission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(255) NOT NULL,
  `updated_at` int(255) NOT NULL,
  `access_token` int(255) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(3,'nn','$2y$13$6vL2u44p5aXtODSeDLunGuyNcjWw9m.KcXXpP75ZM4hwfCUrmIauC','1','1',1741080033,1741080033,0,'elewwlec@mail.ru',0),
(6,'yy','$2y$13$g5VnRdUraxko2J3IVIA0X.BZzmuokgLtB673QTj7TbL4n2.b6DuHS','Пахарьков','Никита',1744905478,1744905478,0,'elewwlec@mail.ru',NULL),
(7,'alina','$2y$13$rNyuvJ9.yWjPlou1xHlTiu68oDNb0RhVZAJZGOjH64kmMgB0fYzO6','Шепелина','Алина',1745035561,1747672283,0,'elewwlec@mail.ru',1),
(8,'tink','$2y$13$lMVLAHJDYOaGpx7wYXWzquhN2/oYnuU4NzCaoF94XWaeVFmp7iygy','Тиньков','Валерий',1745038764,1745038764,0,'elewwlec@mail.ru',NULL),
(9,'aleksei','$2y$13$W/SVBi5paK2RuE/hvj7K3u0aYbv9/54M99synmE8FIYxaHTJ1YeOW','Павлов','Алексей',1747415016,1747415016,0,'elewwlec@mail.ru',NULL),
(10,'123456','$2y$13$q5siTr4.uxbPd8JzVMycbuvC/4xeKXElCs1yxJlBYZxh8KhQ2IQgu','Аллаяров','Никита',1747462902,1747462902,0,'elewwlec@mail.ru',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_submission_unique` (`user_id`,`submission_id`),
  KEY `fk_submission` (`submission_id`),
  CONSTRAINT `fk_submission` FOREIGN KEY (`submission_id`) REFERENCES `submission` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
INSERT INTO `vote` VALUES
(27,9,18,'2025-05-16 17:48:14'),
(68,7,22,'2025-05-19 13:39:37'),
(72,7,18,'2025-05-22 10:34:25');
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-25 21:25:00
