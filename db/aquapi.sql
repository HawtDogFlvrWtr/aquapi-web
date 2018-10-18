-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
-- Table structure for table `light_override`
--

DROP TABLE IF EXISTS `light_override`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `light_override` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `light_override`
--

LOCK TABLES `light_override` WRITE;
/*!40000 ALTER TABLE `light_override` DISABLE KEYS */;
INSERT INTO `light_override` VALUES (1,'Daylight','daylight','mdi-white-balance-sunny'),(2,'Sunrise/Sunset','sunrise_sunset','mdi-weather-sunset'),(3,'Moonlight','moonlight','mdi-weather-night');
/*!40000 ALTER TABLE `light_override` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_types`
--

LOCK TABLES `module_types` WRITE;
/*!40000 ALTER TABLE `module_types` DISABLE KEYS */;
INSERT INTO `module_types` VALUES (1,'AquaPi-Probe',1),(2,'AquaPi-Power',5),(3,'AquaPi-Multiprobe',4);
/*!40000 ALTER TABLE `module_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outlet_trigger_entries`
--

DROP TABLE IF EXISTS `outlet_trigger_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outlet_trigger_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outletId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outlet_trigger_entries`
--

LOCK TABLES `outlet_trigger_entries` WRITE;
/*!40000 ALTER TABLE `outlet_trigger_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `outlet_trigger_entries` ENABLE KEYS */;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outlet_types`
--

LOCK TABLES `outlet_types` WRITE;
/*!40000 ALTER TABLE `outlet_types` DISABLE KEYS */;
INSERT INTO `outlet_types` VALUES (0,'None'),(1,'Heater'),(2,'ATO'),(3,'Powerhead'),(4,'Pump'),(5,'Skimmer'),(6,'UV Light'),(7,'Reactor'),(8,'Chiller'),(9,'Feeder'),(10,'Dose Pump'),(11,'Fans');
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
  `line-color` varchar(255) NOT NULL,
  `decimals` int(1) NOT NULL,
  `step` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventName` (`eventName`),
  KEY `decimals` (`decimals`),
  KEY `step` (`step`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter_types`
--

LOCK TABLES `parameter_types` WRITE;
/*!40000 ALTER TABLE `parameter_types` DISABLE KEYS */;
INSERT INTO `parameter_types` VALUES (1,'Nitrite','#727cf5',2,0.05),(2,'Nitrate','#6c757d',0,1),(3,'Iron','#0acf97',2,0.1),(4,'Iodine','#fa5c7c',2,0.1),(5,'ORP','#ffbc00',2,0.1),(6,'Silicate','#39afd1',2,0.1),(7,'Boron','#313a46',2,0.1),(8,'Copper','#727cf5',2,0.1),(9,'Calcium','#6c757d',2,0.1),(10,'Phosphate','#0acf97',2,0.1),(11,'Potassium','#fa5c7c',2,0.1),(12,'Ammonia','#ffbc00',2,0.05),(13,'Chlorine','#39afd1',2,0.1),(14,'Phosphorus','#313a46',2,0.1),(15,'TDS','#727cf5',2,0.1),(16,'Magnesium','#6c757d',2,0.1),(17,'Stronium','#0acf97',2,0.1),(18,'GH','#fa5c7c',2,0.1),(19,'Salinity','#ffbc00',3,0.001),(20,'PH','#39afd1',1,0.1),(21,'Alkalinity','#313a46',2,0.1),(22,'Chloramine','#727cf5',2,0.1),(23,'Temperature','#6c757d',2,0.01);
/*!40000 ALTER TABLE `parameter_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pump_override`
--

DROP TABLE IF EXISTS `pump_override`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pump_override` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(9999) NOT NULL,
  `value` varchar(9999) NOT NULL,
  `icon` varchar(9999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pump_override`
--

LOCK TABLES `pump_override` WRITE;
/*!40000 ALTER TABLE `pump_override` DISABLE KEYS */;
INSERT INTO `pump_override` VALUES (1,'Feeding','feed','mdi-fish'),(2,'Off','off','mdi-fan-off');
/*!40000 ALTER TABLE `pump_override` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `python_update` int(3) NOT NULL DEFAULT '5',
  `user_password` varchar(255) NOT NULL,
  `dashboard_update` int(3) NOT NULL DEFAULT '1',
  `tz` varchar(255) NOT NULL,
  `graphLimit` varchar(9999) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `performAction` varchar(255) NOT NULL,
  `pumpStatus` tinyint(1) NOT NULL,
  `lightStatus` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(255) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `light_override` varchar(255) NOT NULL,
  `pump_override` varchar(255) NOT NULL,
  `tempScale` varchar(1) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,5,'7384b383463417bb4e97c1502629d953',1,'America/New_York','1-hour:Last Hour,3-hour:Last 3 Hours,6-hour:Last 6 Hours,24-hour:Last day,48-hour:Last 2 days,1-week:1 Week,1-month:1 Month,3-month:3 Months,6-month:6 Months,1-year:1 Year','11111111111111111111','',1,1,'2018-10-16 20:25:28','hawtdogflvrwtr@gmail.com','aquapi-session','resume','','f','','1.0.0Beta');
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
  `text-color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tankkeeping_types`
--

LOCK TABLES `tankkeeping_types` WRITE;
/*!40000 ALTER TABLE `tankkeeping_types` DISABLE KEYS */;
INSERT INTO `tankkeeping_types` VALUES (1,'Top_Off','mdi-cup-water','primary'),(2,'Water_Change','mdi-water-pump','secondary'),(3,'Dosing','mdi-test-tube','success'),(4,'Filter_Replacement','mdi-broom','danger'),(5,'Bulb_Change','mdi-lightbulb-on-outline','warning'),(6,'Feeding','mdi-fish','info'),(7,'Checked_Parameters','mdi-beaker','success'),(8,'Tank_Cleaning','mdi-broom','danger'),(9,'Inhabitant_Addition','mdi-fish','warning'),(10,'Inhabitant_Removal','mdi-fish','danger'),(11,'Equipment_Addition','mdi-power-plug','success'),(12,'Equipment_Removal','mdi-power-plug-off','danger');
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

-- Dump completed on 2018-10-17 21:04:04
-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-17 21:04:04
-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
  `offDuringFeeding` tinyint(1) NOT NULL DEFAULT '0',
  `outletStatus` tinyint(1) NOT NULL,
  `alwaysOn` tinyint(1) NOT NULL,
  `outletNote` varchar(255) NOT NULL,
  `outletTriggerValue` int(11) NOT NULL,
  `outletTriggerParam` int(11) NOT NULL,
  `outletTriggerTest` varchar(2) NOT NULL DEFAULT '=',
  `outletIcon` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-17 21:04:04
-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96475 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-17 21:04:04
-- MySQL dump 10.16  Distrib 10.1.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: aquapi
-- ------------------------------------------------------
-- Server version	10.1.23-MariaDB-9+deb9u1

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
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-17 21:04:05
