<?php
$con=mysqli_connect("mysql17.000webhost.com","a4638665_zyx","zyx868656","a4638665_311");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Create table
$sql="CREATE TABLE IF NOT EXISTS `books` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(64) COLLATE ascii_bin NOT NULL,
  `SellerID` int(11) NOT NULL,
  `Author` varchar(40) COLLATE ascii_bin NOT NULL,
  `Price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Subject` varchar(40) COLLATE ascii_bin NOT NULL DEFAULT '',
  `Description` varchar(200) COLLATE ascii_bin NOT NULL,
  `isbn` varchar(15) COLLATE ascii_bin DEFAULT NULL,
  `image_url` varchar(150) COLLATE ascii_bin DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Secondary` (`Timestamp`,`Title`,`SellerID`),
  KEY `SellerID` (`SellerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=ascii COLLATE=ascii_bin AUTO_INCREMENT=7 ;";

// Execute query
if (mysqli_query($con,$sql))
  {
  echo "Table books created successfully";
  }
else
  {
  echo "Error creating table: " . mysqli_error($con);
  }
mysqli_query($con,"INSERT INTO `books` (`ID`, `Timestamp`, `Title`, `SellerID`, `Author`, `Price`, `Subject`, `Description`, `isbn`, `image_url`) VALUES
(1, '2013-11-03 17:47:21', 'PHP Advanced for the World Wide Web', 8, 'Larry Ullman', '0.00', '', '', NULL, NULL),
(2, '2013-11-03 08:16:21', 'Java: How to Program', 4, 'Harvey M. Deitel, Paul J. Deitel', '20.00', 'Computer Science', '', NULL, NULL),
(3, '2013-11-03 08:17:28', 'Java: How to Program', 1, 'Harvey M. Deitel, Paul J. Deitel', '15.00', 'Computer Science', '', NULL, NULL),
(4, '2013-11-03 08:18:37', 'Java: How to Program', 2, 'Harvey M. Deitel, Paul J. Deitel', '22.00', 'Computer Science', '', NULL, NULL),
(5, '2013-11-04 12:18:37', 'Java: How to Program', 8, 'Harvey M. Deitel, Paul J. Deitel', '22.00', 'Computer Science', '', '1234567891234', 'http://www.computersciencelab.com/Images/cppHTP4_large.jpg')");

$result = mysqli_query($con,"SELECT * FROM books");

while($row = mysqli_fetch_array($result))
  {
  echo $row['ID'] . " " . $row['Title'];
  echo "<br>";
  }
$ID = 3;
$result = mysqli_query($con,"SELECT Title FROM books WHERE ID = '$ID' ");

while($row = mysqli_fetch_array($result))
  {
  echo $row['Title'];
  echo "<br>";
  }
session_start();
?>
