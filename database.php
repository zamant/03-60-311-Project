<?php
	require_once("config.php");
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
	function dbquery(){
	//All database queries follow a certain format.
	//The first argument to the dbquery function is a string, and is the actual query. The next arguments correspond to the values being inserted, in order. That's what the ?s are for. Any number of arguments can be passed so long as all those after the first correspond to a ?.
	
	//To insert, for example, it could look like:
	//dbquery('INSERT INTO Users(Name,Password,Email,PostCode) VALUES (?,?,?,?)',$name,$pass,$email,$postcode)
	
	//Selecting, likewise, looks like:
	//dbquery('SELECT * FROM Users WHERE Name = ?',$name)
		$args = func_get_args();
		$numargs = func_num_args();
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
		foreach ($output as $key2=>$value2){
			echo $key2." - ".$value2."|";
		}
		echo "<br />";
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
	function getAllUsers($page=0,$max=100){
	//Page and Max are for if want to only show so many users on one screen. Otherwise make sure to pass a $page of 0 and a sufficiently large $max
	//Output is an array of arrays, indexed by numerical userID
		$result = dbquery('SELECT * FROM Users ORDER BY ID LIMIT ?,?',$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = array(
										'ID' => $row['ID'],
										'Name' => $row['NAME'],
										'Level' => $row['LEVEL'],
										'Password' => $row['PASSWORD'],
										'Email' => $row['EMAIL'],
										'PostCode' => $row['POSTCODE'],
										'Verified' => $row['VERIFIED']
										);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllBooks($page=0,$max=100,$orderby = 'ID'){
		//As above with page and max
		$result = dbquery('SELECT * FROM Books ORDER BY '.$orderby.' LIMIT ?,?',$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = array(
										'ID' => $row['ID'],
										'Timestamp' => $row['TIMESTAMP'],
										'Title' => $row['TITLE'],
										'SellerID' => $row['SELLERID'],
										'Author' => $row['AUTHOR'],
										'Price' => $row['PRICE'],
										'Subject' => $row['SUBJECT'],
										'Description' => $row['DESCRIPTION']
										);
			$row = $result->fetch(PDO::FETCH_ASSOC);
		}
		return $output;
	}
	function getAllBooksBySeller($page=0,$max=100,$sellerid){
		$result = dbquery('SELECT * FROM Books WHERE SELLERID = ? ORDER BY '.$orderby.' LIMIT ?,?',$sellerid,$page*$max,$max);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$output = array();
		while ($row && ($max-- > 0)){
			$output[$row['ID']] = array(
										'ID' => $row['ID'],
										'Timestamp' => $row['TIMESTAMP'],
										'Title' => $row['TITLE'],
										'SellerID' => $row['SELLERID'],
										'Author' => $row['AUTHOR'],
										'Price' => $row['PRICE'],
										'Subject' => $row['SUBJECT'],
										'Description' => $row['DESCRIPTION']
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
	function getUser($name){
		$result = dbquery('SELECT * FROM Users WHERE Name = ?',$name);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'ID' => $row['ID'],
						'Name' => $row['NAME'],
						'Level' => $row['LEVEL'],
						'Password' => $row['PASSWORD'],
						'Email' => $row['EMAIL'],
						'PostCode' => $row['POSTCODE'],
						'Verified' => $row['VERIFIED']
						);
		return $output;
	}
	function getUserByID($id){
		$result = dbquery('SELECT * FROM Users WHERE ID = ?',$id);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'ID' => $row['ID'],
						'Name' => $row['NAME'],
						'Level' => $row['LEVEL'],
						'Password' => $row['PASSWORD'],
						'Email' => $row['EMAIL'],
						'PostCode' => $row['POSTCODE'],
						'Verified' => $row['VERIFIED']
						);
		return $output;
	}
	function getBook($id){
		$result = dbquery('SELECT * FROM Books WHERE ID = ?',$id);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'ID' => $row['ID'],
						'Timestamp' => $row['TIMESTAMP'],
						'Title' => $row['TITLE'],
						'SellerID' => $row['SELLERID'],
						'Author' => $row['AUTHOR'],
						'Price' => $row['PRICE'],
						'Subject' => $row['SUBJECT'],
						'Description' => $row['DESCRIPTION']
						);
		return $output;		
	}
	/*
	function getTopic($topic_id){
		$result = dbquery('SELECT * FROM Topics WHERE ID = ?',$topic_id);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'Title' => $row['TITLE'],
						'User' => $row['USER'],
						'Timestamp' => '<div class="time">'.$row['TIMESTAMP'].'</div>',
						);
		return $output;
	}
	function getPost($topic_id,$postnum){
		$result = dbquery('SELECT * FROM Posts WHERE Topic = ? AND Postnum = ?',$topic_id,$postnum);
		$row = $result->fetch();
		if (!$row){
			return 0;
		}
		$output = array(
						'Topic' => $row['TOPIC'],
						'User' => $row['USER'],
						'Body' => parseBBCode($row['BODY']),
						'Timestamp' => $row['TIMESTAMP'].'||class=time',
						);
		return $output;
	}
	function getLastPostNum($topic_id){
		$result = dbquery('SELECT max(Postnum) FROM Posts WHERE Topic = ?',$topic_id);
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}
	function getLastPost($topic_id){
		$postnum = getLastPostNum($topic_id);
		$post = getPost($topic_id,$postnum);
		return $post;
	}
	function getFirstPostNum($topic_id){
		$result = dbquery('SELECT min(Postnum) FROM Posts WHERE Topic = ?',$topic_id);
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}
	function getFirstPost($topic_id){
		$postnum = getFirstPostNum($topic_id);
		$post = getPost($topic_id,$postnum);
		
		return $post;
	}
	function getNumPosts($topic_id){
		$result = dbquery("SELECT count(Postnum) FROM Posts WHERE Topic = ?",$topic_id);
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}
	function getLastTopicNum(){
		$result = dbquery("SELECT max(ID) FROM Topics");
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}
	function getFirstTopicNum(){
		$result = dbquery("SELECT min(ID) FROM Topics");
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}
	function getNumTopics(){
		$result = dbquery("SELECT count(ID) FROM Topics");
		$row = $result->fetch(PDO::FETCH_NUM);
		if (!$row){
			return 0;
		}
		return $row[0];
	}*/
	function getSalt(){
	//Password salt for additional security. Not necessary and should probably be done some other way, but it works.
		return md5("311");
	}
	function getUserLevel($username){
		$user = getUser($username);
		return $user['Level'];
	}
//END GET

//NEW
	function newUser($name,$password,$email,$postcode){
		$shapass = hash("sha256",$password.getSalt());//sha1($password.getSalt());
		return dbquery('INSERT INTO Users(Name,Password,Email,PostCode) VALUES (?,?,?,?)',htmlspecialchars($name),$shapass,htmlspecialchars($email),htmlspecialchars($postcode));
	}
	function newBook($title,$sellerid,$author,$price,$subject,$description){
		return dbquery('INSERT INTO Books(Title,SellerID,Author,Price,Subject,Description) VALUES (?,?,?,?,?,?)',htmlspecialchars($title),$sellerid,htmlspecialchars($author),htmlspecialchars($price),htmlspecialchars($subject),htmlspecialchars($description));
	}
	/*function newThread($title, $user, $body){
		$topic_id = newTopic($user,$title);
		if ($topic_id == 0 || newPost($topic_id,$user,$body) == 0){
			return 0;
		}
		return $topic_id;
	}
	function newTopic($user,$title){
		$result = dbquery('INSERT INTO Topics(Title, User) VALUES (?,?)',htmlspecialchars($title),$user);
		$id = getLastTopicNum();
		if ((is_int($result) && $result == 0)){
			return 0;
		}
		return $id;
	}
	function newPost($topic_id, $user, $body){
		if (strlen($body)>=5000){
			$_SESSION['posterror'] = "Error, post too long."; //Plug into posting form like with login
			return 0;
		}//other error conditions such as nonexistent user or topic
		$result = dbquery('SELECT Postindex FROM Topics WHERE ID = ?',$topic_id);
		$row = $result->fetch(PDO::FETCH_NUM);
		$postnum = $row[0];
		$result = dbquery('INSERT INTO Posts(Topic, User, Body, Postnum) VALUES(?,?,?,?)',$topic_id,$user,$body,$postnum+1);
		if (is_int($result) && $result == 0){
			return 0;
		}
		$postnum = getLastPostNum($topic_id);
		dbquery('UPDATE Topics SET Postindex = Postindex+1, Timestamp = CURRENT_TIMESTAMP WHERE ID = ?',$topic_id);
		return $postnum;
	}*/
//END NEW
//EDIT
/*
	function editThread($title, $topic_id, $postid, $body){
		if (editTopic($topic_id, $title) == 0 || editPost($topic_id,$postid,$body) == 0){
			return 0;
		}
		return $topic_id;
	}
	function editPost($topic_id, $postnum, $body){
		$post = getPost($topic_id,$postnum);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['Name'] == $post['User'] || $currentuser['Level'] == 1){
			if (strlen($body)>=5000){
				$_SESSION['posterror'] = "Error, post too long."; //Plug into posting form like with login
				return 0;
			}//other error conditions such as nonexistent user or topic
			$result = dbquery('UPDATE Posts SET Body = ? WHERE Topic = ? AND Postnum = ?',$body,$topic_id,$postnum);
			if (is_int($result) && $result == 0){
				return 0;
			}
			return $postnum;
		}
		return 0;
	}
	function editTopic($topic_id,$title){
		$topic = getTopic($topic_id);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['Name'] == $topic['User'] || $currentuser['Level'] == 1){
			$result = dbquery('UPDATE Topics SET Title = ? WHERE ID = ?',htmlspecialchars($title),$topic_id);
			if ((is_int($result) && $result == 0)){
				return 0;
			}
			return $topic_id;
		}
		return 0;
	}*/
//END EDIT
//DELETE
	function deleteBook($book_id){
		$book = getBook($book_id);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['ID'] == $book['SellerID'] || $currentuser['Level'] == 1){
			$result = dbquery('DELETE FROM Books WHERE ID = ?',$book_id);
			if (is_int($result) && $result == 0){
				return 0;
			}else{
				return 1;
			}
		}
		return 0;
	}
/*
	function deletePost($topic_id,$postnum){
		$post = getPost($topic_id,$postnum);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['Name'] == $post['User'] || $currentuser['Level'] == 1){
			$result = dbquery('DELETE FROM Posts WHERE Topic = ? AND Postnum = ?',$topic_id,$postnum);
			if (is_int($result) && $result == 0){
				return 0;
			}else{
				if ($postnum == 1){
					return deleteTopic($topic_id);
				}
				return 0;
			}
		}
		return 0;
	}
	function deleteTopic($topic_id){
		$topic = getTopic($topic_id);
		$currentuser = currentUser();
		if (is_int($currentuser)){
			return 0;
		}
		if ($currentuser['Name'] == $topic['User'] || $currentuser['Level'] == 1){
			$result = dbquery('DELETE FROM Topics WHERE ID = ?',$topic_id);
			if (is_int($result) && $result == 0){
				return 0;
			}else{
				return 1;
			}
		}
		return 0;
	}*/
//END DELETE

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
		if (($user['Password']==$shapass)){
			if (!$user['Verified']){
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
			$results = dbquery('SELECT Token FROM Authentication WHERE Name = ?',$user[0]);
			$match = false;
			$row = $results->fetch(PDO::FETCH_ASSOC);
			if ($row){
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
	function setLoginCookie($name,$expire){
	//Counterpart to checkLoginCookie
		$token = sha1($_SERVER['REMOTE_ADDR'].time());
		
		dbquery('DELETE FROM Authentication WHERE Name = ?',$name);
		if ($expire > time()){
			setLogin($name);
			dbquery('INSERT INTO Authentication(Name,Token) VALUES (?,?)',$name,$token);
		}else{
			clear_session('user');
		}
		setcookie('user', $name."|".$token, $expire);
		return $name;
	}
?>