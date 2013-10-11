<?php
	function dbInfo(){
		//Username and password to login to the database
		$user = "root";
		$password = "zaman040377";
		
		//Name of the database.
		$dbname = "60311";
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
	function db_connect(){
		$dbinfo = dbInfo();
		$user = $dbinfo['user'];
		$pass = $dbinfo['pass'];
		$dsn = 'mysql:host='.$dbinfo['host'].';dbname='.$dbinfo['dbname'];
		$dbh = new PDO($dsn, $user, $pass);
		$dbh->setAttribute(
		PDO::ATTR_CASE, PDO::CASE_UPPER
		);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}
	function db_result_to_array(){
		$args = func_get_args();
		$numargs = func_num_args();
		$query = $args[0];
		$dbh = db_connect();
		$stmt = $dbh->prepare($query);
		for ($i = 1; $i < $numargs; $i++){
			if (is_int($args[$i])){
				$type = PDO::PARAM_INT;
			}else{
				$type = PDO::PARAM_STR;
			}
			$stmt->bindValue($i,$args[$i],PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt;
	}
	function pg_numrows($arg){
		return count($arg->fetch());
	}
	function pg_exec($db,$query){
		$output = $db->exec($query);
		return $output;
	}
	function pg_query($db,$query){
		//error_log($query);
		$output = $db->query($query);
		return $output;
	}
	function pg_fetch_object($resource){
		return $resource->fetch(PDO::FETCH_ASSOC);
	}
	
//TEST
	function testDBInfoOutputs($output){
	//Use this for Info functions or ones where you expect multiple arrays, like getting all topics
	//Classes? Object-oriented programming? What are those?
		foreach ($output as $key1=>$value1){
			echo "<br />".$key1.": ";
			foreach ($value1 as $key2=>$value2){
				echo $key2." - ".$value2."|";
			}
			
		}
	}
	function testDBOutputs($output){
	//Use this when a single array is expected, like a single user's info
		echo "<br />";
		foreach ($output as $key2=>$value2){
			echo $key2." - ".$value2."|";
		}
	}
//END TEST

	function getAllUsers($page,$max){
		$result = db_result_to_array('SELECT * FROM Users ORDER BY ID LIMIT ?,?',$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = array(
										'Name' => $row['NAME'],
										'Level' => $row['LEVEL'],
										'Password' => $row['PASSWORD'],
										'Email' => $row['EMAIL']
										);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllUsersXML($page,$max){
		return arrayToXML(getAllUsers($page,$max),"User");
	}
//END GETALL

//GET
	function getUser($name){ //CHANGE THIS
		$result = db_result_to_array('SELECT * FROM Users WHERE Name = ?',$name);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'Name' => $row['NAME'],
						'Level' => $row['LEVEL'],
						'Password' => $row['PASSWORD'],
						'Email' => $row['EMAIL']
						);
		return $output;
	}

	function getSalt(){
	//I am and this is a hack - Matt
		return md5("311");
	}
//NEW
	function newUser($name,$password,$email){
		$shapass = sha1($password.getSalt());
		db_result_to_array('INSERT INTO user(Name,Password,Email) VALUES (?,?,?)',htmlspecialchars($name),$shapass,htmlspecialchars($email));//CHANGE THIS
	}
	function checkUser($name,$password){
		$shapass = sha1($password.getSalt());
		$user = getUser($name);
		return ($user['Password']==$shapass);
	}

?>