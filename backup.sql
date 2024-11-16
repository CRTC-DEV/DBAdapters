-- MySQL dump 10.14  Distrib 5.5.56-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: dbadapters
-- ------------------------------------------------------
-- Server version	5.5.56-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ArrivalMovement`
--

DROP TABLE IF EXISTS `ArrivalMovement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ArrivalMovement` (
  `Id` int(11) NOT NULL,
  `MovementId` int(11) NOT NULL,
  `AircraftId` int(11) DEFAULT NULL,
  `AircraftTypeId` int(11) DEFAULT NULL,
  `MovementDatetime` datetime DEFAULT NULL,
  `RouteId` int(11) DEFAULT NULL,
  `AirlineId` int(11) DEFAULT NULL,
  `FlightId` varchar(10) DEFAULT NULL,
  `ScheduledDatetime` datetime DEFAULT NULL,
  `StandId` int(11) DEFAULT NULL,
  `CarouselId` int(11) DEFAULT NULL,
  `PaxAdult` int(11) DEFAULT NULL,
  `EstimatedTime` datetime DEFAULT NULL,
  `ConfidentTime` datetime DEFAULT NULL,
  `PaxChild` int(11) DEFAULT NULL,
  `ConfidentPaxCount` int(11) DEFAULT NULL,
  `StatusArr` varchar(20) DEFAULT NULL,
  `Qualifier` varchar(128) DEFAULT NULL,
  `FirstBagTime` datetime DEFAULT NULL,
  `LastBagTime` datetime DEFAULT NULL,
  `HandlerArr` varchar(20) DEFAULT NULL,
  `CallSignId` varchar(20) DEFAULT NULL,
  `SuffixId` varchar(10) DEFAULT NULL,
  `RemarksArr` varchar(128) DEFAULT NULL,
  `PBB1Start` datetime DEFAULT NULL,
  `PBB1End` datetime DEFAULT NULL,
  `PBB2Start` datetime DEFAULT NULL,
  `PBB2End` datetime DEFAULT NULL,
  `PBB3Start` datetime DEFAULT NULL,
  `PBB3End` datetime DEFAULT NULL,
  `ChocksOn` datetime DEFAULT NULL,
  `ChocksOff` datetime DEFAULT NULL,
  `CarouselChangeLog` varchar(256) DEFAULT NULL,
  `PaxInfant` int(11) DEFAULT NULL,
  `NonOperational` int(11) DEFAULT NULL,
  `NonSeasonal` int(11) DEFAULT NULL,
  `StandAllocation` varchar(10) DEFAULT NULL,
  `BatchDate` datetime DEFAULT NULL,
  `BaggageLoadTotal` int(11) DEFAULT NULL,
  `MovementSourceDataVersion` int(11) DEFAULT NULL,
  `ResponseCode` varchar(50) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `Status_B` char(10) DEFAULT 'CLS',
  `ModifiMovementId` varchar(164) DEFAULT NULL,
  `Status_A` tinyint(4) NOT NULL DEFAULT '0',
  `ResponsedDate` datetime DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ArrivalMovement`
--

LOCK TABLES `ArrivalMovement` WRITE;
/*!40000 ALTER TABLE `ArrivalMovement` DISABLE KEYS */;
/*!40000 ALTER TABLE `ArrivalMovement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dbadapters'
--

--
-- Dumping routines for database 'dbadapters'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-15  9:16:32
