<?php

function serverEmail(){
	return 'post@usedbookexchange.ca';
}

function dbInfo(){
	//Username and password to login to the database
	$user = "laforet3_311";
	$password = "password";
	//$user = "";
	//$password = "";
	
	//Name of the database.
	$dbname = "laforet3_311v2";
	//$dbname = "60311";
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