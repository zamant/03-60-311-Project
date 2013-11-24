<?php
	//Add useful global functions here, and require_once this file in any files needing them
	//database.php is already required here so it shouldn't be required in other files
	session_start();
	require_once('config.php');
	require_once('book_lib.php'); // book-related functions.
	require_once('validation_lib.php');
	require_once('database.php');
	
	authenticateLoginCookie();
?>
<?php

	function requireLogin()
	{
		if (!is_loggedin())
		{
			header('Location: '.url_to_redirect_to('login.php'));
			//exit(0);
		}
	}

	function print_head_snippet()
	{
			?>
		<script type="text/javascript" src="js/lib.js">
		</script>
		<link rel="stylesheet" type="text/css" href="css/base.css" />
		<link rel="stylesheet" id="coloursheet" type="text/css" href="css/blackandwhite.css" />
		<meta charset="UTF-8">
	<?php	
	}
	function is_admin($user){
		return $user['LEVEL'] == 1;
	}
	function is_loggedin(){
	//Self-explanitory, internal workings could change but otherwise just verifies the current user is logged-in
		return array_key_exists('user',$_SESSION);
	}
	function arrayToXML($array,$type = "Post",$root = "Root"){
		$sxml = new SimpleXMLElement('<'.$root.'/>');
		$keys = array_keys($array);
		while ($value = array_shift($array)){
			$node = $sxml;
			$key = array_shift($keys);
			if (is_array($value)){
				if (is_numeric($key)){
					$node = $node->addChild($type);
					$node->addChild("pID",$key);
				}else{
					$node = $node->addChild($key);
				}
				foreach ($value as $k=>$v){
					if (explode("||",$v) == $v){
						$node->addChild($k,$v);
					}else{
						$attributes = explode("||",$v);
						$child = $node->addChild($k,$attributes[0]);
						for ($i=1;$i<count($attributes);$i++){
							$attribute = explode("=",$attributes[$i]);
							$child->addAttribute($attribute[0],$attribute[1]);
						}
					}
				}
			}else{
				if (explode("||",$value) == $value){
					$node->addChild($key,$value);
				}else{
					$attributes = explode("||",$value);
					$child = $node->addChild($key,$attributes[0]);
					for ($i=1;$i<count($attributes);$i++){
						$attribute = explode("=",$attributes[$i]);
						$child->addAttribute($attribute[0],$attribute[1]);
					}
				}
			}
		}
		return $sxml->asXML();
	}
	
	function clear_session()
	{
	  $num_args = func_num_args();
	  $args = func_get_args();
	  for ($i = 0; $i < $num_args; $i++)
	  {
		// unset() the provided session key...
		if (array_key_exists($args[$i], $_SESSION))
		  unset($_SESSION[$args[$i]]);

		// unset() any provided session key's .err entry if any...
		if (array_key_exists($args[$i], $_SESSION))
		  unset($_SESSION[$args[$i].'.err']);
	  }
	}
	function setLogin($name){
		$_SESSION['user']=$name;
	}

	function authenticateLoginCookie(){
		if (!is_loggedin() && checkLoginCookie()){
			$user = explode("|",$_COOKIE['user']);
			setLogin($user[0]);
			setLoginCookie($user[0],time()+60*60*24*30);
		}
	}
	
	function form_text_field($label, $tip, $required, $htmlname, $base_sessid, $type,$size = 0)
	{//Not wholly necessary, just convenient for the login/registration pages

	  errorCheck($base_sessid);

	// Output label...
	  echo '
		  <label for="'.htmlspecialchars($htmlname).'">'.
		htmlspecialchars($label).'</label>';

	  // Output <input> tag...
	  echo '<input type="'.$type.'" name="'.htmlspecialchars($htmlname).'" ';
	  if (array_key_exists($base_sessid, $_SESSION))
		echo ' value="'.htmlspecialchars($_SESSION[$base_sessid]).'" ';
		echo 'id="'.htmlspecialchars($base_sessid).'" ';
		if ($size){
			echo 'size='.$size;
		}
	  echo ' />';

	}
	function errorCheck($base_sessid){
	// If there is an error message, output it with an "formerror" CSS class.
	  // (You can add a CSS page and set the style to highlight and style
	  // the error appropriately. A <div> is used here for demo purposes.)
	  if (array_key_exists($base_sessid.'.err', $_SESSION))
	  {
		echo '<div class="formerror">'.
		  htmlspecialchars($_SESSION[$base_sessid.'.err']).
		  '</div>';
	  }
	}
	
	
	function url_to_redirect_to($relative_url)
	{//Don't understand how it works, only that it does work.
	  $url = 'http';
	  if (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on')
		$url .= 's';
	  return $url.'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/".urlencode($relative_url);
	}
	function handleContactForm($adID,$name,$fromemail,$toemail,$message){
		$errors = '';
		if(empty($adID) ||
			empty($name) ||
			empty($fromemail) ||
			empty($toemail) ||
			empty($message))
		{
			$errors .= "Error: All fields are required<br />";
		}

		if (!is_valid_email_address($fromemail) ||
			!is_valid_email_address($toemail))
		{
			$errors .= "Error: Invalid email address<br />";
		}

		if( empty($errors))
		{
			$email_subject = "Reply to ad #".$adID;
			$email_body = "You have received a new reply to your ad ".$adID.
			" Here are the details:\n Name: $name \n Email: $fromemail \n Message \n $message"; 
			
			$headers = "From: ".serverEmail()."\n"; 
			$headers .= "Reply-To: $fromemail";
			
			mail($toemail,$email_subject,$email_body,$headers);
			return 0;
		} 

		return $errors;
	}
	function displayAd($ID){
		$book = getBook($_GET["id"]);
			if ($book){
				deleteButton("'index.php?'",$book['ID']);
				testDBOutputs($book);
			}
		?>
		<article>
		<header>Contact</header>
		<?php
			if ($_SESSION['contactresult']){
				echo $_SESSION['contactresult'];
				unset($_SESSION['contactresult']);
			}
		?>
		<form action="contact.php" method="POST">
			<textarea rows="10" cols="30" name="message">
			</textarea>
			<?php
				if (is_loggedin()){
				$user = currentUser();
				$fromname = $user['Name'];
				$fromemail = $user['Email'];
					echo '<input type="hidden" name = "fromname" value="'.$fromname.'" />';
					echo '<input type="hidden" name = "fromemail" value="'.$fromemail.'" />';
				}else{
			?>
			<br />
			Name: <input type="text" name="fromname" />
			<br />
			Email Address: <input type="text" name="fromemail" />
			<?php
			}
			echo '<input type="hidden" name="adID" value="'.$ID.'" />';
			?>
			<input type="submit" value="Send" />
		</form>
	</article>
	<?php
	}
	function deleteButton($location,$ID){
		echo '<input type="button" class="delete" value="[X]" onclick="return deleteAd('.$location.','.$ID.');" />';
	}
	function addtocartButton($location,$ID){
		echo '<a href="'.$location.'addtocart='.$ID.'"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_SM.gif" class="addtocart"/></a>';
	}
	function formatPhoneNumber($stringnum){
		if (strlen($stringnum) != 10){
			return false;
		}
		return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $stringnum);
	}
	
	
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
				break;
			}
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
	}
	function get_order_total(){
		$sum=0;
		foreach($_SESSION['cart'] as $item){
			$book = getBook($item['productid']);
			$quantity = $item['qty'];
			$price=$book['PRICE'];
			$sum+=$price*$quantity;
		}
		return $sum;
	}
	function get_order_total_old(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}
	function addtocart($pid,$q){
		error_log("TESTING1");
		if($pid<1 or $q<1) return;
		error_log("TESTING2");
		if(is_array($_SESSION['cart'])){
		error_log("TESTING3");
			if(product_exists($pid)) return;
			error_log("TESTING4");
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
		}
		else{
		error_log("TESTING5");
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
	}
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}
	function isInCart($id){
		if (array_key_exists('cart',$_SESSION)){
			foreach ($_SESSION['cart'] as $item){
				if ($item['productid'] == $id){
					return true;
				}
			}
		}
		return false;
	}
?>