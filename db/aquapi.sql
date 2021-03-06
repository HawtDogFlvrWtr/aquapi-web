-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `module_types`
--

DROP TABLE IF EXISTS `module_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleTypeName` varchar(255) NOT NULL,
  `featureCount` int(11) NOT NULL,
  `defaultIcon` varchar(255) NOT NULL,
  `parameterId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`moduleTypeName`,`featureCount`,`defaultIcon`,`parameterId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_types`
--

LOCK TABLES `module_types` WRITE;
/*!40000 ALTER TABLE `module_types` DISABLE KEYS */;
INSERT INTO `module_types` VALUES (1,'AquaPi-Probe',1,'',0),(2,'AquaPi-Power',8,'mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us,mdi-power-socket-us',0),(3,'AquaPi-Multiprobe',4,'',0);
/*!40000 ALTER TABLE `module_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outlet_types`
--

DROP TABLE IF EXISTS `outlet_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlet_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outletType` varchar(255) NOT NULL,
  `typeIcon` varchar(255) NOT NULL,
  `typeColor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outlet_types`
--

LOCK TABLES `outlet_types` WRITE;
/*!40000 ALTER TABLE `outlet_types` DISABLE KEYS */;
INSERT INTO `outlet_types` VALUES (1,'Heater','mdi-oil-temperature','#f82b54'),(2,'ATO','mdi-water-percent','#727cf5'),(3,'Powerhead','mdi-waves','#2991ae'),(4,'Pump','mdi-loop','#00ffdd'),(5,'Skimmer','mdi-chart-bubble','#6610f2'),(6,'UV Light','mdi-lightbulb','#7f1ae5'),(7,'Reactor','mdi-chart-bubble','#fd7e14'),(8,'Chiller','mdi-snowflake','#007bff'),(9,'Feeder','mdi-fish','#28a745'),(10,'Dose Pump','mdi-needle','#DC143C'),(11,'Fans','mdi-fan','#00FF00'),(13,'Backup Heater','mdi-thermometer-lines','#dc3545'),(14,'Light','mdi-led-on','#FFD700'),(15,'AquaPi','mdi-raspberrypi','#C71585');
/*!40000 ALTER TABLE `outlet_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parameter_types`
--

DROP TABLE IF EXISTS `parameter_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parameter_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventName` varchar(50) NOT NULL,
  `lineColor` varchar(255) NOT NULL DEFAULT '#727cf5',
  `annoColor` varchar(255) NOT NULL,
  `decimals` int(1) NOT NULL,
  `step` float NOT NULL,
  `dontGraph` tinyint(1) NOT NULL DEFAULT '0',
  `bgColor` varchar(255) NOT NULL DEFAULT '#727cf5',
  `max` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventName` (`eventName`),
  KEY `decimals` (`decimals`),
  KEY `step` (`step`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter_types`
--

LOCK TABLES `parameter_types` WRITE;
/*!40000 ALTER TABLE `parameter_types` DISABLE KEYS */;
INSERT INTO `parameter_types` VALUES (1,'Nitrite','#ffbc00','',2,0.05,0,'',0),(2,'Nitrate','#fa5c7c','',0,1,0,'',0),(3,'Iron','#000','',2,0.1,0,'',0),(4,'Iodine','#000','',2,0.1,0,'',0),(5,'ORP','#000','',2,0.1,0,'',0),(6,'Silicate','#000','',2,0.1,0,'',0),(7,'Boron','#cddc39','',2,0.1,0,'',0),(8,'Copper','#ff9800','',2,0.1,0,'',0),(9,'Calcium','#0acf97','',0,1,0,'',800),(10,'Phosphate','#727cf5','',2,0.1,0,'',0),(11,'Potassium','#000','',2,0.1,0,'',0),(12,'Ammonia','#6c757d','',2,0.05,0,'#ffbc00',0),(13,'Chlorine','#ffc107','',2,0.1,0,'',0),(14,'Phosphorus','#39afd1','',2,0.1,0,'',0),(15,'TDS','#39afd1','',2,0.1,0,'#39afd1',0),(16,'Magnesium','#000','',2,0.1,0,'',0),(17,'Stronium','#000','',2,0.1,0,'',0),(18,'GH','#000','',2,0.1,0,'',0),(19,'Salinity','#ffbc00','',3,0.001,0,'#fa5c7c',0),(20,'PH','#fa5c7c','',3,0.1,0,'#0acf97',0),(21,'Alkalinity','#0acf97','',2,0.1,0,'',0),(22,'Chloramine','#ffeb3b','',2,0.1,0,'',0),(23,'Temperature','#727cf5','#4250f2',2,0.01,0,'#727cf5',0),(24,'ATOFloat','#8bc34a','#ffbc00',0,0,1,'',0),(25,'Room_Temperature','#000','',2,0.01,0,'',0),(26,'Feeding Button','#ff95722','',0,0,1,'',0),(27,'Outdoor_Temperature','#000','',2,0.01,0,'',0),(28,'Clean Button','#000','',0,0,1,'',0);
/*!40000 ALTER TABLE `parameter_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_password` varchar(255) NOT NULL,
  `dashboard_update` int(3) NOT NULL DEFAULT '1',
  `tz` varchar(255) NOT NULL,
  `graphLimit` varchar(9999) NOT NULL,
  `defaultGraphLimit` varchar(25) NOT NULL,
  `performAction` varchar(255) NOT NULL,
  `pumpStatus` tinyint(1) NOT NULL,
  `lightStatus` tinyint(1) NOT NULL,
  `feedTime` int(255) NOT NULL DEFAULT '300',
  `cleanTime` int(255) NOT NULL DEFAULT '300',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(255) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `light_override` varchar(255) NOT NULL,
  `pump_override` varchar(255) NOT NULL,
  `tempScale` varchar(1) NOT NULL,
  `version` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'49536546735152925daf2c92917d6bae',1,'America/New_York','1-hour:Last Hour,3-hour:Last 3 Hours,6-hour:Last 6 Hours,12-hour:Last 12 Hours,1-day:Last Day,2-day:Last 2 Days,1-week:1 Week,1-month:1 Month','3-hour','',1,1,600,600,'2020-10-11 15:27:35','hawtdogflvrwtr@gmail.com','aquapi-session','resume','','f','1.0.0Beta');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tankkeeping_types`
--

DROP TABLE IF EXISTS `tankkeeping_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tankkeeping_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `word_color` varchar(255) NOT NULL,
  `cal_color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tankkeeping_types`
--

LOCK TABLES `tankkeeping_types` WRITE;
/*!40000 ALTER TABLE `tankkeeping_types` DISABLE KEYS */;
INSERT INTO `tankkeeping_types` VALUES (1,'Top_Off','mdi-cup-water','primary','#727cf5'),(2,'Water_Change','mdi-water-pump','success','#0acf97'),(3,'Dosing','mdi-test-tube','secondary','#6c757d'),(4,'Filter_Replacement','mdi-broom','danger','#fa5c7c'),(5,'Bulb_Change','mdi-lightbulb-on-outline','warning','#ffbc00'),(6,'Feeding','mdi-fish','info','#39afd1'),(7,'Feeding (No timer)','mdi-fish','info','#39afd1'),(8,'Tank_Cleaning','mdi-broom','danger','#fa5c7c'),(9,'Inhabitant_Addition','mdi-fish','warning','#ffbc00'),(10,'Inhabitant_Removal','mdi-fish','danger','#fa5c7c'),(11,'Equipment_Addition','mdi-power-plug','success','#0acf97'),(12,'Equipment_Removal','mdi-power-plug-off','danger','#fa5c7c'),(14,'Checked_Parameters','mdi-beaker','success','#0acf97');
/*!40000 ALTER TABLE `tankkeeping_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `module_entries`
--

DROP TABLE IF EXISTS `module_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleType` int(11) NOT NULL,
  `moduleSerial` varchar(255) NOT NULL,
  `moduleAddress` varchar(15) NOT NULL,
  `moduleFirmware` varchar(15) NOT NULL,
  `moduleNote` varchar(255) NOT NULL,
  `moduleColor` varchar(255) NOT NULL DEFAULT 'dark',
  `epoch` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`moduleType`,`moduleSerial`,`moduleAddress`,`moduleFirmware`,`moduleNote`,`moduleColor`,`epoch`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `outlet_entries`
--

DROP TABLE IF EXISTS `outlet_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlet_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleId` int(11) NOT NULL,
  `portNumber` int(11) NOT NULL,
  `outletType` int(11) NOT NULL,
  `outletStatus` tinyint(1) NOT NULL,
  `alwaysOn` tinyint(1) NOT NULL,
  `offAtFeeding` tinyint(1) NOT NULL DEFAULT '0',
  `offAtCleaning` tinyint(1) NOT NULL DEFAULT '0',
  `outletNote` varchar(30) NOT NULL DEFAULT 'N/A',
  `outletTriggerValue` float NOT NULL,
  `outletTriggerParam` int(11) NOT NULL,
  `outletTriggerTest` varchar(2) NOT NULL DEFAULT '=',
  `outletTriggerCommand` varchar(3) NOT NULL,
  `outletIcon` varchar(255) NOT NULL,
  `on_time` time DEFAULT NULL,
  `off_time` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`moduleId`,`portNumber`,`outletType`,`outletStatus`,`alwaysOn`,`offAtFeeding`,`offAtCleaning`,`outletNote`,`outletTriggerValue`,`outletTriggerParam`,`outletTriggerTest`,`outletTriggerCommand`,`outletIcon`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `parameter_entries`
--

DROP TABLE IF EXISTS `parameter_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parameter_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `value` (`value`),
  KEY `value_2` (`value`),
  KEY `id` (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `type_id_2` (`type_id`),
  KEY `timestamp_2` (`timestamp`),
  KEY `value_3` (`value`),
  KEY `timestamp_3` (`timestamp`),
  KEY `value_4` (`value`),
  KEY `type_id_3` (`type_id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `type_id_4` (`type_id`),
  KEY `timestamp_4` (`timestamp`),
  KEY `value_5` (`value`),
  KEY `graphsIndex` (`value`,`timestamp`,`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9340832 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `tankkeeping_entries`
--

DROP TABLE IF EXISTS `tankkeeping_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tankkeeping_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(9999) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `timestamp` (`timestamp`),
  KEY `id` (`id`,`type_id`,`timestamp`,`note`(767))
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
-- MySQL dump 10.15  Distrib 10.0.28-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: aquapi    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.0.28-MariaDB-2+b1

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
-- Table structure for table `outlet_trigger_entries`
--

DROP TABLE IF EXISTS `outlet_trigger_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlet_trigger_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleId` int(11) NOT NULL,
  `outletId` int(11) NOT NULL,
  `paramId` int(11) NOT NULL,
  `value` varchar(3) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `paramId` (`paramId`),
  KEY `value` (`value`),
  KEY `timestamp` (`timestamp`),
  KEY `moduleId` (`moduleId`),
  KEY `outletId` (`outletId`),
  KEY `paramId_2` (`paramId`),
  KEY `value_2` (`value`),
  KEY `timestamp_2` (`timestamp`),
  KEY `id` (`id`,`moduleId`,`outletId`,`paramId`,`value`,`timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=244386 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-11 17:48:59
