<?php
	//Add useful global functions here, and require_once this file in any files needing them
	//database.php is already required here so it shouldn't be required in other files
	session_start();
	require_once('database.php');
	//date_default_timezone_set('UTC');
	authenticateLoginCookie();
?>
	<!--<script src="ckeditor/ckeditor.js"></script>-->
	<script type="text/javascript">
		/*function deletePost(postnum){
			document.getElementById(postnum).submit();
		}*/
		function changeCSS(sel,set){
		//CSS changing is completely unnecessary at this point
			 var value = sel.options[sel.selectedIndex].value;
			 document.getElementById('coloursheet').href = value;
			 if (set){
				setCookie('colorsheet',value,30);
			 }
		}
		function readCookie(name) {//Copied from stackoverflow
		//Saves trouble when reading cookies
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}
		function setCookie(c_name,value,exdays)
		{//Copied from w3schools
		//Saves trouble when setting cookies
			var exdate=new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value;
		}
		function checkColourCookie(){
			var cookie = readCookie('colorsheet');
			if (cookie != null){
				var sel = document.getElementById('colourselect');
				for(var i=0;i<sel.options.length;i++){
					if (sel.options[i].value == cookie) {
						sel.selectedIndex = i;
						break;
					}
				}
				changeCSS(sel,false);
			}
		}
	</script>
	<!--<script type="text/javascript" src="ajax.js"></script>-->
	<link rel="stylesheet" type="text/css" href="base.css" />
	<link rel="stylesheet" id="coloursheet" type="text/css" href="blackandwhite.css" />
	<meta charset="UTF-8">
<?php
	/*function convertTimeZone($utctime,$offset){
		$datetime = new DateTime($utctime);
		$tz = new DateTimeZone('UTC');
		$datetime->setTimeZone($tz);
		return $datetime->format('M j Y, h:i A');
	}*/
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
	/*function createInput($action, $name, $title="", $postid="", $i=""){
		if ($action == "mail.php"){
			echo '<form action="'.$action.'" method="post">';
				form_text_field(
						'Subject:', 
						'Subject of the message', 
						true,
						$title, 
						$title,
						'text'
					);
				echo '<input type="hidden" name="post_id" id="post_id" value ="'.$postid.'">';
				
				?>
				</textarea>
				<input type="submit" value="Send">
				</form>
				<?php
		}else{
		if($postid){
			if($title){
				$_SESSION[$title] = getTopic($postid['Topic'])['Title'];
				echo '<form action="'.$action.'" method="post">';
				form_text_field(
						'Topic:', 
						'Title of the new Topic', 
						true,
						$title, 
						$title,
						'text'
					);
				$_SESSION[$title] = "";
				echo '<input type="hidden" name="post_id" id="post_id" value ="'.$i.'">';

				echo $postid['Body'];
				?>
				
				</textarea>
				<input type="submit" value="Save Changes">
				</form>
				
				<?php
				
			}
			else{
				//Editing Post
				echo '<form action="'.$action.'" method="post">';
				echo '<input type="hidden" name="post_id" id="post_id" value ="'.$i.'">';

				echo $postid['Body'];
				?>
				</textarea>
				
				<input type="submit" value="Save Changes">
				</form>
				
				<?php
			}
		
		}elseif ($title){
			echo '<form action="'.$action.'" method="post">';
			form_text_field(
					'New Thread Topic:', 
					'Title of the new Topic', 
					true,
					$title, 
					$title,
					'text'
				);
			
			?>
			</textarea>
			<input type="submit" value="Create">
			</form>
			
			<?php
		}else{
			//Only new Post
			echo '<form action="'.$action.'" method="post">';
			?>
			</textarea>
			<input type="submit" value="Reply">
			</form>
			
			<?php
		}}
	}*/
	
	/*function deleteButton($topicid,$postnum){
		if (is_array($topicid)){
			error_log("TOPICID ISARRAY: ".var_export($topicid,true));
		}
		if (is_array($postnum)){
			error_log("POSTNUM ISARRAY: ".var_export($postnum,true));
		}
		$output = '<form id="'.$postnum.'" action="?'.http_build_query($_GET).'" method="post">';
		$output .= '<input type="hidden" name="postnum" value ="'.$postnum.'"/>';
			$output .= '<input type="hidden" name="topicid" value="'.$topicid.'" />';
			$output .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1">';
			$output .= '<g onmouseup="deletePost('.$postnum.') " cursor="pointer">';
			  $output .=  '<text x="5" y="15" fill="red" >[X]</text>';
			$output .= '</g></svg>';
		$output .= '</form>';
		echo $output;
	}*/
	
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
	/*function parseBBCode($output){
		$bbcode = new BBCode;
		return $bbcode->Parse($output);
	}*/
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
	  // Output label...
	  echo '
		  <label for="'.htmlspecialchars($htmlname).'">'.
		htmlspecialchars($label).'</label>';

	  // Output <input> tag...
	  echo '<input type="'.$type.'" name="'.htmlspecialchars($htmlname).'" ';
	  if (array_key_exists($base_sessid, $_SESSION))
		echo ' value="'.htmlspecialchars($_SESSION[$base_sessid]).'" ';
		echo 'id = '.htmlspecialchars($htmlname).' ';
		if ($size){
			echo 'size='.$size;
		}
	  echo ' />';

	  errorCheck($base_sessid);
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
	function is_valid_email_address($email){
	//Server-side validation, taken from somewhere so we don't have to do this ourselves
        $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
        $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
        $atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
            '\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
        $quoted_pair = '\\x5c[\\x00-\\x7f]';
        $domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
        $quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
        $domain_ref = $atom;
        $sub_domain = "($domain_ref|$domain_literal)";
        $word = "($atom|$quoted_string)";
        $domain = "$sub_domain(\\x2e$sub_domain)*";
        $local_part = "$word(\\x2e$word)*";
        $addr_spec = "$local_part\\x40$domain";
        return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
	}
?>