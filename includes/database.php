<?php
	function newDB(){
	
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
	function getDB(){//Set up like this in case we want to do persistent connection
		return newDB();
	}
	
	/*All database queries follow a certain format.
	The first argument to the dbquery function is a string, and is the actual query. 
	The next arguments correspond to the values being inserted, in order. That's what the ?s are for. Any number of arguments can be passed so long as all those after the first correspond to a ?
	To insert, for example, it could look like:
	dbquery('INSERT INTO users(Name,Password,Email,PostCode) VALUES (?,?,?,?)',$name,$pass,$email,$postcode)	
	Selecting, likewise, looks like:
	dbquery('SELECT * FROM users WHERE Name = ?',$name)
	*/
	function dbquery(){
		$args = func_get_args();
		$numargs = func_num_args();
		//var_dump($args);
		$query = $args[0];
		$dbh = getDB();
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

//GETALL
	/*function getAllTopics($page,$max){
	//$page is how many prior pages of $max topics to skip, while $max is the max number displayed
	//Essentially this can determine which 'page' to show, so topics 1-10 vs 11-20
	//1-10 would be $page=0,$max=10 while 11-20 would be $page=1,$max=10
	//Return value is an array of arrays, indexed by 'ID' (a unique value)
	//The inner arrays are indexed by 'Title', 'User', and 'Timestamp' so these values can be obtained
	//So $output[1]['Title'] should get the title of topic # 1
		$result = dbquery('SELECT * FROM Topics ORDER BY Timestamp DESC LIMIT ?,?',$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = array(
										'Title' => $row['TITLE'],
										'User' => $row['USER'],
										'Timestamp' => '<div class="time">'.$row['TIMESTAMP'].'</div>',
										);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllTopicsXML($page,$max){
		return arrayToXML(getAllTopics($page,$max),"Topic");
	}
	function getAllPosts($topic_id,$page,$max){
	//See above except the output is indexed by Postnum,
	//and then that is indexed by 'Topic','User','Body','Timestamp'
	//So $output[1]['Body'] should presumably give the Body of Post # 1
		$result = dbquery('SELECT * FROM Posts WHERE Topic = ? ORDER BY Postnum LIMIT ?,?',$topic_id,$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['POSTNUM']] = array(
										'Topic' => $row['TOPIC'],
										'User' => $row['USER'],
										'Body' => parseBBCode($row['BODY']),
										'Timestamp' => $row['TIMESTAMP'].'||class=time',
										);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllPostsXML($topic_id,$page,$max){
		return arrayToXML(getAllPosts($topic_id,$page,$max));
	}*/
	function getAllUsers($page=1,$max=100){
	//Page and Max are for if want to only show so many users on one screen. Otherwise make sure to pass a $page of 1 and a sufficiently large $max
	//Output is an array of arrays, indexed by numerical userID
		$result = dbquery('SELECT * FROM users ORDER BY ID LIMIT ?,?',($page-1)*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = $row;
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllBooks($page=1,$max=25,$orderby = 'ID',$desc = true){
		//As above with page and max
		$dir = "";
		if ($desc){
			$dir = 'DESC';
		}
		$result = dbquery('SELECT * FROM books ORDER BY '.$orderby.' '.$dir.' LIMIT ?,?',($page-1)*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] =$row;
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllBooksBySeller($page=1,$max=25,$sellerid,$orderby = 'ID'){
		$result = dbquery('SELECT * FROM books WHERE SELLERID = ? ORDER BY '.$orderby.' LIMIT ?,?',$sellerid,($page-1)*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = $row;
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllUsersXML($page,$max){
		return arrayToXML(getAllUsers($page,$max),"User");
	}
//END GETALL
	function getNumSearchResults($title,$author,$ISBN,$subject){
		$result = dbquery('SELECT COUNT(*) FROM books WHERE (UPPER(Title) LIKE ?) OR (UPPER(Author) LIKE ?) OR (UPPER(ISBN) LIKE ?) OR (UPPER(Subject) LIKE ?)',$title,$author,$ISBN,$subject);
		return $result->fetch()[0];
	}
	function searchAllBooks($title,$author,$ISBN,$subject,$page = 1,$max = 25,$orderby = 'ID',$desc = true){
		//var_dump(func_get_args());
		$dir = "";
		if ($desc){
			$dir = 'DESC';
		}
		$result = dbquery('SELECT * FROM books WHERE (UPPER(Title) LIKE ?) OR (UPPER(Author) LIKE ?) OR (UPPER(ISBN) LIKE ?) OR (UPPER(Subject) LIKE ?) ORDER BY '.$orderby.' '.$dir.' LIMIT ?,?',$title,$author,$ISBN,$subject,($page-1)*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] =$row;
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
//GET


	function getUser($name){
		$result = dbquery('SELECT * FROM users WHERE Name = ?',$name);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		return $row;
	}
	
	function getRowById($id,$tableName)
	{
		$result = dbquery('SELECT * FROM '.$tableName.' WHERE id = ?',intval($id));
		$row = $result->fetch(PDO::FETCH_ASSOC);
		if ($row)
		{
			return $row;
		}
		else
			return false;
	}
	
	function getUserByID($id){
		return getRowById($id,'users');		
	}
	function getBook($id){
		return getRowById($id,'books');		
	}
	function getNumBooks(){
		$result = dbquery('SELECT COUNT(*) FROM books');
		return $result->fetch()[0];
	}
	function getSalt(){
	//Password salt for additional security. Not necessary and should probably be done some other way, but it works.
		return md5("311");
	}
//END GET

//NEW
	function newUser($name,$password,$email,$postcode){
		$shapass = hash("sha256",$password.getSalt());//sha1($password.getSalt());
		return dbquery('INSERT INTO users(Name,Password,Email,PostCode) VALUES (?,?,?,?)',htmlspecialchars($name),$shapass,htmlspecialchars($email),htmlspecialchars($postcode));
	}

//CHECK
	function currentUser(){
	//Simply returns info on the currently-logged-in user from the database
		if (is_loggedin()){
			return getUser($_SESSION['user']);
		}
		return 0;
	}
	function checkUser($name,$password){
	//This is what checks a user's login info
		$shapass = hash("sha256",$password.getSalt());//sha1($password.getSalt());
		$user = getUser($name);
		if (($user['PASSWORD']==$shapass)){
			if (!$user['VERIFIED']){
				$_SESSION['lpassword.err'] = "You must verify your email address before logging in.";
				return false;
			}
			return true;
		}else{
		return false;
		}
	}
	function checkLoginCookie(){
	//Used for user persistence across sessions via cookies
		if (array_key_exists('user', $_COOKIE)){
			$user = explode("|",$_COOKIE['user']);
			$results = dbquery('SELECT Token FROM authentication WHERE Name = ?',$user[0]);
			$match = false;
			$row = $results->fetch(PDO::FETCH_ASSOC);
			if ($row)
			{
				foreach ($row as $key=>$value){
					if($value == $user[1]){
						$match = true;
					}
				}
			}
			return $match;
		}else{
			return false;
		}
	}
//END CHECK
//DELETE
	function deleteBook($book_id){
		$book = getBook($book_id);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['ID'] == $book['SELLERID'] || $currentuser['LEVEL'] == 1){
			$result = dbquery('DELETE FROM books WHERE ID = ?',$book_id);
			if (is_int($result) && $result == 0){
				return 0;
			}else{
				return 1;
			}
		}
		return 0;
	}
//END DELETE
	function setLoginCookie($name,$expire){
	//Counterpart to checkLoginCookie
		$token = sha1($_SERVER['REMOTE_ADDR'].time());
		
		dbquery('DELETE FROM authentication WHERE Name = ?',$name);
		if ($expire > time()){
			setLogin($name);
			dbquery('INSERT INTO authentication(Name,Token) VALUES (?,?)',$name,$token);
		}else{
			clear_session('user');
		}
		setcookie('user', $name."|".$token, $expire);
		return $name;
	}
?>