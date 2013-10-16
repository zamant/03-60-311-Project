<?php

function dbInfo(){
	//Username and password to login to the database
	$user = "laforet3_root";
	$password = "zaman040377";
	
	//Name of the database.
	$dbname = "laforet3_311";
	//Location of the database. If on same server, likely localhost.
	$host = "localhost";
	
	
	$output = array(
					'user' => $user,
					'pass' => $password,
					'dbname' => $dbname,
					'host' => $host,
					);
	return $output;
}
?>