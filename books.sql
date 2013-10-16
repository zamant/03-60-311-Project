CREATE TABLE IF NOT EXISTS `Books` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(64) COLLATE ascii_bin NOT NULL,
  `SellerID` int(11) NOT NULL,
  `Author` varchar(40) COLLATE ascii_bin NOT NULL,
  `Price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Subject` varchar(40) COLLATE ascii_bin NOT NULL DEFAULT '',
  `Description` varchar(200) COLLATE ascii_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Secondary` (`Timestamp`,`Title`,`SellerID`),
  KEY `SellerID` (`SellerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii COLLATE=ascii_bin AUTO_INCREMENT=1 ;

ALTER TABLE `Books`
  ADD CONSTRAINT `Books_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `Users` (`ID`) ON UPDATE CASCADE;
