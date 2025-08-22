-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: localhost    Database: internationalbusbookingsystem
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity` (
  `ActivityID` int NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `WhoDidIt` varchar(100) DEFAULT NULL,
  `Role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ActivityID`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bookingdetails`
--

DROP TABLE IF EXISTS `bookingdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookingdetails` (
  `BookingID` int NOT NULL AUTO_INCREMENT,
  `CustomerID` int DEFAULT NULL,
  `ScheduleID` int DEFAULT NULL,
  `SeatNumber` varchar(100) DEFAULT NULL,
  `BookingDate` date DEFAULT NULL,
  `TravelDate` date DEFAULT NULL,
  `Status` enum('Confirmed','Canceled') DEFAULT 'Confirmed',
  PRIMARY KEY (`BookingID`),
  KEY `CustomerID` (`CustomerID`),
  KEY `ScheduleID` (`ScheduleID`),
  CONSTRAINT `bookingdetails_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  CONSTRAINT `bookingdetails_ibfk_2` FOREIGN KEY (`ScheduleID`) REFERENCES `scheduleinformation` (`ScheduleID`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `bookingsummary`
--

DROP TABLE IF EXISTS `bookingsummary`;
/*!50001 DROP VIEW IF EXISTS `bookingsummary`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bookingsummary` AS SELECT 
 1 AS `BookingID`,
 1 AS `CustomerName`,
 1 AS `DepartureTime`,
 1 AS `ArrivalTime`,
 1 AS `SeatNumber`,
 1 AS `AmountPaid`,
 1 AS `PaymentMode`,
 1 AS `StartLocation`,
 1 AS `Destination`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bus`
--

DROP TABLE IF EXISTS `bus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bus` (
  `BusID` int NOT NULL AUTO_INCREMENT,
  `BusNumber` varchar(20) DEFAULT NULL,
  `YearOfManufacture` int DEFAULT NULL,
  `Capacity` int DEFAULT NULL,
  `EngineNumber` varchar(50) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `Mileage` float DEFAULT NULL,
  PRIMARY KEY (`BusID`),
  UNIQUE KEY `BusNumber` (`BusNumber`),
  UNIQUE KEY `EngineNumber` (`EngineNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `CustomerID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `PassportNumber` varchar(20) DEFAULT NULL,
  `Nationality` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CustomerID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance` (
  `MaintenanceID` int NOT NULL AUTO_INCREMENT,
  `BusID` int DEFAULT NULL,
  `ServiceDone` varchar(100) DEFAULT NULL,
  `ServiceDate` date DEFAULT NULL,
  `Cost` float DEFAULT NULL,
  `MaterialUsed` varchar(100) DEFAULT NULL,
  `LSD` date DEFAULT NULL,
  `NSD` date DEFAULT NULL,
  `TechnicianID` int DEFAULT NULL,
  PRIMARY KEY (`MaintenanceID`),
  KEY `BusID` (`BusID`),
  KEY `TechnicianID` (`TechnicianID`),
  CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`BusID`) REFERENCES `bus` (`BusID`),
  CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`TechnicianID`) REFERENCES `staff` (`StaffID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`Email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paymentdetails`
--

DROP TABLE IF EXISTS `paymentdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paymentdetails` (
  `PaymentID` int NOT NULL AUTO_INCREMENT,
  `BookingID` int DEFAULT NULL,
  `AmountPaid` float DEFAULT NULL,
  `PaymentMode` enum('Mobile Money','Card') DEFAULT NULL,
  `PaymentDate` datetime DEFAULT NULL,
  `ReceiptNumber` varchar(50) DEFAULT NULL,
  `TransactionID` varchar(50) DEFAULT NULL,
  `Status` enum('Completed','Pending','Refund Pending') DEFAULT 'Pending',
  PRIMARY KEY (`PaymentID`),
  KEY `BookingID` (`BookingID`),
  CONSTRAINT `paymentdetails_ibfk_1` FOREIGN KEY (`BookingID`) REFERENCES `bookingdetails` (`BookingID`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `route`
--

DROP TABLE IF EXISTS `route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `route` (
  `RouteID` int NOT NULL AUTO_INCREMENT,
  `StartLocation` varchar(50) DEFAULT NULL,
  `Destination` varchar(50) DEFAULT NULL,
  `Distance` float DEFAULT NULL,
  `RouteName` varchar(100) DEFAULT NULL,
  `RouteType` varchar(20) DEFAULT NULL,
  `Security` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`RouteID`),
  UNIQUE KEY `RouteName` (`RouteName`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scheduleinformation`
--

DROP TABLE IF EXISTS `scheduleinformation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scheduleinformation` (
  `ScheduleID` int NOT NULL AUTO_INCREMENT,
  `BusID` int DEFAULT NULL,
  `RouteID` int DEFAULT NULL,
  `DepartureTime` datetime DEFAULT NULL,
  `ArrivalTime` datetime DEFAULT NULL,
  `Cost` float DEFAULT NULL,
  `DriverID` int DEFAULT NULL,
  `CodriverID` int DEFAULT NULL,
  PRIMARY KEY (`ScheduleID`),
  KEY `BusID` (`BusID`),
  KEY `RouteID` (`RouteID`),
  KEY `DriverID` (`DriverID`),
  KEY `CodriverID` (`CodriverID`),
  CONSTRAINT `scheduleinformation_ibfk_1` FOREIGN KEY (`BusID`) REFERENCES `bus` (`BusID`),
  CONSTRAINT `scheduleinformation_ibfk_2` FOREIGN KEY (`RouteID`) REFERENCES `route` (`RouteID`),
  CONSTRAINT `scheduleinformation_ibfk_3` FOREIGN KEY (`DriverID`) REFERENCES `staff` (`StaffID`),
  CONSTRAINT `scheduleinformation_ibfk_4` FOREIGN KEY (`CodriverID`) REFERENCES `staff` (`StaffID`)
) ENGINE=InnoDB AUTO_INCREMENT=1276 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `StaffID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `StaffNumber` varchar(20) DEFAULT NULL,
  `Role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`StaffID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `StaffNumber` (`StaffNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `bookingsummary`
--

/*!50001 DROP VIEW IF EXISTS `bookingsummary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bookingsummary` AS select `bookingdetails`.`BookingID` AS `BookingID`,`customer`.`Name` AS `CustomerName`,`scheduleinformation`.`DepartureTime` AS `DepartureTime`,`scheduleinformation`.`ArrivalTime` AS `ArrivalTime`,`bookingdetails`.`SeatNumber` AS `SeatNumber`,`paymentdetails`.`AmountPaid` AS `AmountPaid`,`paymentdetails`.`PaymentMode` AS `PaymentMode`,`route`.`StartLocation` AS `StartLocation`,`route`.`Destination` AS `Destination` from ((((`bookingdetails` join `customer` on((`bookingdetails`.`CustomerID` = `customer`.`CustomerID`))) join `scheduleinformation` on((`bookingdetails`.`ScheduleID` = `scheduleinformation`.`ScheduleID`))) left join `paymentdetails` on((`bookingdetails`.`BookingID` = `paymentdetails`.`BookingID`))) join `route` on((`scheduleinformation`.`RouteID` = `route`.`RouteID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-20 21:56:13
