-- MariaDB dump 10.19  Distrib 10.5.19-MariaDB, for Linux (x86_64)
--
-- Host: mysql    Database: sm_nursery_system
-- ------------------------------------------------------
-- Server version	11.3.2-MariaDB-1:11.3.2+maria~ubu2204

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
-- Current Database: `sm_nursery_system`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sm_nursery_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `sm_nursery_system`;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `atten_date` varchar(255) DEFAULT NULL,
  `studentid` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `subject` int(11) DEFAULT NULL,
  `teacherid` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `mins_late` int(11) NOT NULL DEFAULT 0,
  `atten_status` varchar(10) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,'2024-04-20 19:59:09','2024-04-20',3,1,3,'tr6','Student moved out immediately after attendance',6,'AM',1),(2,'2024-04-20 21:19:52','2024-04-20',2,1,3,'tr6','Was in sick bay',20,'L',1),(3,'2024-04-20 21:20:28','2024-04-20',3,1,2,'tr6','Student came late, this is my edit',10,'L',1),(9,'2024-04-21 12:20:17','2024-04-21',3,1,3,'tr6',NULL,0,'P',1),(8,'2024-04-21 12:20:03','2024-04-21',2,1,2,'tr6','No reason',20,'L',1),(7,'2024-04-21 12:19:56','2024-04-21',3,1,2,'tr6','came 30 mins late',30,'L',0),(10,'2024-04-21 12:20:18','2024-04-21',2,1,3,'tr6',NULL,0,'P',1),(11,'2024-04-25 20:45:59','2024-04-25',3,1,3,'tr6',NULL,0,'P',1),(12,'2024-04-25 20:46:08','2024-04-25',2,1,3,'tr6','No reason',0,'P',1),(13,'2024-04-28 19:46:17','2024-04-28',3,1,3,'tr6','No comment',20,'L',1),(14,'2024-04-28 19:48:45','2024-04-28',2,1,2,'tr6','',0,'A',1),(15,'2024-04-28 19:48:47','2024-04-28',3,1,2,'tr6','No comment',20,'L',1),(16,'2024-04-30 23:37:36','2024-04-30',3,1,2,'tr6','Was running late',20,'L',1),(17,'2024-05-08 22:22:25','2024-05-08',3,1,3,'tr6','Was running late',30,'L',1);
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `teacher` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Year1','Section 2','tr6',1),(2,'Year2','Section 2','tr2',1),(4,'Reception','Section 1','tr4',1),(5,'Day care','Section 2','tr5',1),(6,'Night care','Section 1','tr4',0);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `eventname` text DEFAULT NULL,
  `eventdate` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `details` text NOT NULL,
  `event_image` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fsize` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'2024-04-22 18:00:19','Cultural Day Practices','2024-04-22','Pastoral','We shall hold our cultural galla on the specified date. All learners will perform in thier houses','0019trashbin.png',6504,1),(2,'2024-04-22 18:29:40','Reporting Term 3 2024','2024-04-24','Admission','All boarding learners must report on that date','2940Screenshot_(76).png',1256053,1),(3,'2024-04-30 21:48:07','Sports Day','2024-05-01','Sports','Reminder: Sports Day is beginning tomorrow please pack plenty of snacks and water for the children.','4807sports_day.jpg',441001,1),(4,'2024-05-08 22:17:18','happy day','2024-05-08','Sports','happy day ','17185250tech3.jpg',33746,1);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `response_date` varchar(255) DEFAULT NULL,
  `responded_by` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
INSERT INTO `inquiries` VALUES (1,'2024-04-21 11:21:55','Kazibwe Davis','kazibwedavis6@gmail.com','Kindly requiring about admission policy and fees structure','Hello sending in a minute','2024-04-21','admin',1),(2,'2024-04-21 12:29:50','superadmin','rehemanabukeera@gmail.com','helllo',NULL,NULL,NULL,1),(3,'2024-05-02 14:19:28','Jason ','amal.alame12@gmail.com','This is a test message','this is a test reply','2024-05-08','admin',1),(4,'2024-05-08 21:58:36','Amal','amal.alame12@gmail.com','this is a tester please reply ','this is a tester message','2024-05-08','admin',1);
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_review`
--

DROP TABLE IF EXISTS `message_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `message_review` text NOT NULL,
  `userid` varchar(255) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_review`
--

LOCK TABLES `message_review` WRITE;
/*!40000 ALTER TABLE `message_review` DISABLE KEYS */;
INSERT INTO `message_review` VALUES (1,'2024-04-20 14:14:25','Okay mum, kindly lets meet on monday 22/04/2024 at 08:00AM in the auditorium','tr6','msg1',1),(2,'2024-04-20 16:13:47','okay i will it on monday','tr6','msg2',1),(3,'2024-04-20 17:41:22','Surely will be there','tr6','msg4',1),(4,'2024-05-02 14:22:53','yes that is okay we can do it for friday','tr6','msg5',1),(5,'2024-05-08 22:21:41','yes will do that urgently','tr6','msg3',1);
/*!40000 ALTER TABLE `message_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacherid` varchar(255) DEFAULT NULL,
  `parentid` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `messageid` varchar(255) DEFAULT NULL,
  `cat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'tr6','prt2','Hello teacher, kindly inquiring if at all it is okay for us to meet on monday 22/April/2024 to discuss my son\'s performance in your subject','2024-04-20 09:40:36',3,'msg1','teacher_parent'),(2,'tr6','prt2','Kindly send me on my son\'s attendance records on my email please','2024-04-20 10:24:15',3,'msg2','teacher_parent'),(3,'tr6','prt2','i need mid term report for my son','2024-04-20 13:10:12',3,'msg3','teacher_parent'),(4,'tr6','admin','Mr. derrick, tommorrow by my office I have some important information to pass to you','2024-04-20 14:33:11',3,'msg4','admin_teacher'),(5,'tr6','admin','hello derrick are you free this week for a scheduled meeting?','2024-04-30 22:03:30',3,'msg5','admin_teacher'),(6,'tr5','admin','this is a tester message','2024-05-08 22:14:18',1,'msg6','admin_teacher'),(7,'tr8','prt2','tester message','2024-05-08 22:28:18',1,'msg7','teacher_parent');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT NULL,
  `not_date` varchar(255) DEFAULT NULL,
  `not_group` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'2024-04-14 14:33:33','End Of Term Summer Break','2024-04-14','parent','Dear parents, this is a kind reminder that we will be going for our summer break, two weeks from now',1),(2,'2024-04-14 15:10:47','Picking Reports','2024-04-25','parent','please endeavor to pick student reports',1),(3,'2024-04-19 13:05:12','Staff Meeting Reminder','2024-04-19','teacher','Please this is a reminder for all staff to attend the meeting at the end of the day and to arrive 10 mins early to settle down.',1),(16,'2024-04-28 20:37:41','Exam day','2024-04-28','teacher','reminder that tomorrow is the start of exam day',1),(17,'2024-05-08 22:13:29','Urgent meeting','2024-05-08','teacher','meeting in half an hour',1);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `parentid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parents`
--

LOCK TABLES `parents` WRITE;
/*!40000 ALTER TABLE `parents` DISABLE KEYS */;
INSERT INTO `parents` VALUES (1,'2024-04-13 21:33:45',1,'Derrick','Dollar','derrickdollars6@gmail.com','+256780958321','prt1'),(2,'2024-04-14 07:47:44',1,'Kazibwe','Davis','kazibwedavis6@gmail.com','+256751166268','prt2'),(3,'2024-04-16 09:27:04',1,'layla','mulya','layla@gmail.com','526536271','prt3'),(4,'2024-04-17 09:51:52',1,'amal','alame','amal@gmail.com','07283834','prt4'),(6,'2024-04-17 10:28:43',1,'Belvia','Tsinda','belviatsinda@gmail.com','07423234934','prt5'),(7,'2024-05-08 13:36:53',1,'lola ','bunny','amal.alame12@gmail.com','4388393002','prt7'),(8,'2024-05-08 22:03:25',1,'lulu','mumu','amal.alame12@gmail.com','0787727362','prt8');
/*!40000 ALTER TABLE `parents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(45) NOT NULL,
  `parentId` int(11) NOT NULL,
  `adminId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin',1),(2,'parent',1),(3,'teacher',1),(4,'users',0);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_marks`
--

DROP TABLE IF EXISTS `student_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `studentid` text DEFAULT NULL,
  `course_work` int(11) DEFAULT 0,
  `end_mark` int(11) DEFAULT 0,
  `total_mark` text DEFAULT NULL,
  `grade` text DEFAULT NULL,
  `subjectid` text DEFAULT NULL,
  `classid` text DEFAULT NULL,
  `serie_id` text DEFAULT NULL,
  `teacherid` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `date_added` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_marks`
--

LOCK TABLES `student_marks` WRITE;
/*!40000 ALTER TABLE `student_marks` DISABLE KEYS */;
INSERT INTO `student_marks` VALUES (1,'2024-04-21 06:09:28','3',40,45,'85','A','3','1','MID','tr6','Excellent','2024-04-21',1),(2,'2024-04-21 06:09:28','2',20,46,'66','C','3','1','MID','tr6','Fair','2024-04-21',1),(3,'2024-04-21 06:43:40','3',22,60,'82','A','4','1','MID','tr6','Good','2024-04-21',1),(4,'2024-04-21 06:43:40','2',40,44,'84','A','4','1','MID','tr6','Good','2024-04-21',1),(5,'2024-04-21 12:22:26','3',40,34,'74','B','1','1','MID','tr6','good','2024-04-21',1),(6,'2024-04-21 12:22:26','2',40,50,'90','A*','1','1','MID','tr6','fair','2024-04-21',1),(7,'2024-05-08 13:31:16','3',30,50,'80','A','2','1','MID','tr6','Good Job','2024-05-08',1),(8,'2024-05-08 13:54:20','7',20,50,'70','B','2','4','MID','tr7','Fair','2024-05-08',1),(9,'2024-05-08 22:23:50','6',20,40,'60','C','2','4','MID','tr6','Fair','2024-05-08',1);
/*!40000 ALTER TABLE `student_marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_number` varchar(255) NOT NULL,
  `roll_number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `fsize` int(11) DEFAULT NULL,
  `academic_year` year(4) NOT NULL,
  `admissionDate` varchar(255) NOT NULL,
  `class` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `parentid` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `studentid` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'ST2024001','09819101','Amani Amania','5503tech1.png',467967,2025,'2024-04-25','3','Section 2','prt2',0,'st1'),(2,'8976566','908777','Nadia Kirungi','0553tech1.png',467967,2024,'2024-04-15','1','Section 1','prt2',0,'st2'),(3,'23225','232','Amal','25451-indian-kids-boy-school-student-EN9GYG.jpg',202520,2024,'2024-04-16','1','Section 1','prt2',1,'st3'),(4,'28y24982','348234y923','te','2625white_t_shirt.jpg',151800,2024,'2024-04-28','2','Section 2','prt1',0,'st4'),(5,'1342','23423','Jason','3150child.png',597468,2024,'2024-05-09','2','Section 1','prt4',1,'st5'),(6,'2226','28839','Bob','4018white_t_shirt.jpg',151800,2024,'2024-05-07','4','Section 2','prt4',1,'st6'),(7,'738829','38392','Rashid','3753child.png',597468,2024,'2024-05-03','5','Section 1','prt4',0,'st7'),(8,'362627','272782','mark','06170700white_t_shirt.jpg',151800,2024,'2024-05-08','4','Section 1','prt8',1,'st8');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_code` text DEFAULT NULL,
  `subject_name` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES ('2024-04-13 08:51:27',1,1,'0417','ICT'),('2024-04-13 08:55:22',1,2,'9618','Computer Science'),('2024-04-14 11:04:30',1,3,'9500','Mathematics'),('2024-04-16 09:34:57',1,4,'223','Arts');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `autodate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `assigned_subject` varchar(30) DEFAULT NULL,
  `image_link` text DEFAULT NULL,
  `fsize` int(11) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `teacherid` varchar(255) DEFAULT NULL,
  `section` text DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES ('2024-04-14 11:05:14',1,2,'Nabukeera','Rehema','2,','0514AdminLTELogo.png',10380,'rehema@gmail.com','0781010010','tr2','Section 1',0),('2024-04-14 15:08:55',1,3,'Davis','luwembe','1,','5007white_t_shirt.jpg',151800,'luwembe@gmail.com','0786656777','tr3','Section 1',0),('2024-04-17 09:08:26',1,4,'lola','mulya','1,3,','0826teacher-image.jpg',35137,'lola@gmail.com','072324322','tr4','Section 1',0),('2024-04-19 13:40:31',1,5,'jason','nkuru','2,','40311-indian-kids-boy-school-student-EN9GYG.jpg',202520,'jason@gmailc.om','07423234934','tr5','Section 1',1),('2024-04-20 09:31:45',0,6,'Ssenyonga','Derrick','2,3,','3522avatar.png',8543,'derrick@gmail.com','07091819912','tr6','Section 1',3),('2024-05-08 13:50:03',1,7,'James','bu','2,1,','500342251-indian-kids-boy-school-student-EN9GYG.jpg',202520,'amal.alame12@gmail.com','8393020','tr7','Section 1',4),('2024-05-08 22:16:09',1,8,'derrick','lu','2,1,','16091-indian-kids-boy-school-student-EN9GYG.jpg',202520,'derrick@gmail.com','8289291','tr8','Section 1',1);
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `toshow`
--

DROP TABLE IF EXISTS `toshow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toshow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item1` varchar(255) NOT NULL DEFAULT 'toshow',
  `item2` varchar(255) NOT NULL,
  `item3` varchar(255) DEFAULT NULL,
  `item4` varchar(255) DEFAULT NULL,
  `typez` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `toshow`
--

LOCK TABLES `toshow` WRITE;
/*!40000 ALTER TABLE `toshow` DISABLE KEYS */;
INSERT INTO `toshow` VALUES (1,'toshow','tr6','3','Mathematics','subject'),(3,'toshow','tr6','admin_teacher','Admin','message');
/*!40000 ALTER TABLE `toshow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'tr6','derrick','$2y$10$tqrEXrivKEJ9BqO1WFIVIO971Lb2rBiKli04zJN.J8GWOOY2KwRIu','teacher'),(2,'prt2','davis','$2y$10$mSQN24hh5BElEOg1a5KExOfK18cdQVyqxEogBno2LixuD/5a5CPaS','parent'),(6,'tr1','ivan','$2y$10$gfrhE6l1zNq8nkZ/tne0Ge85R75fJeeJbH.r32Hq27UhsCWhvZBMC','teacher'),(7,'tr2','rema','$2y$10$dIQiyDgRe3YDoPvrRYY0a.taadXXdMmXqnl76A5x0H2XQdj0jnYgu','parent'),(8,'tr3','gags','$2y$10$zUj5Gd8HjaZ7UYinMwXvdeAciQNsK7NCzu76UpLAcWKXcNt8c8GS2','teacher'),(9,'prt3','Layla123','$2y$10$bCQKzB2SwUyU.Jt1PnA2eeSVQV1109j4YiZH2QuiDS6ySH6d94aIS','parent'),(10,'tr4','lola123','$2y$10$Lts6YTw0vUXjBnsLeWAUJuk5s/6MFTbPlNxSIhRG.8hGE11mvu8im','teacher'),(11,'prt4','amal123','$2y$10$ZcmPNYrsaE5hCcKDj0kfcuvI2pujWf02CgY1v1tNPj2CvbLm14F5S','parent'),(13,'prt5','belvia123','$2y$10$Ka2LcoXibSXttsXCR6xhjezOjvsw8vLWrn9bQ7leIp0SDEpNqJr66','parent'),(14,'tr5','jason123','$2y$10$wCRc3cODsMe907KyUwzJD.3Ans0/f7p8vPJ.dpBfczG9FObAZCgOa','teacher'),(16,'prt7','lola1','$2y$10$PWwcIo0bKdXW9/W91pZ7JuBcgm.wFDzBn56jpCSjAG75fGGvyn3H.','parent'),(17,'tr7','james1','$2y$10$YHPrb/PSTwgPvSClbYVIEOufPoQaQ2kS7CCyAEMH3hg0RM3.YMSPm','teacher'),(18,'prt8','lulu1','$2y$10$jFsvs7KJyYjnZ856WXEtbeIdV.cM5ialyTTl545IRgRbwCV1fRF5S','parent');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sm_nursery_system'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-08 22:45:56
