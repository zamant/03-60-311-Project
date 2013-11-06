<?php

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

function validate_message($value)
{
	$value = trim($value);
	
	if (strlen($value)<10)
		return 'Too short';
		
	if (strlen($value)>320)
		return 'Too long';
		
	return '';
}

function sanitize_title($value)
{
	return trim($value);
}

function validate_title($value)
{
	$value = sanitize_title($value);
	if ($value=='')
		return 'Required';
		
	if (strlen($value)<3)
		return 'Too short';
		
	return '';
}

function sanitize_isbn($value)
{
	$value = preg_replace("/[^0-9,.]/", "", $value);
	return $value;
}

function validate_isbn($value)
{
	$value = sanitize_isbn($value);
	if (strlen($value)<13)
		return 'Too short.  Must contain 13 digits but only '.strlen($value);
	else if (strlen($value)>15)
		return 'Too many digits.  Must be at most 15 but you gave '.strlen($value);
	
	return ''; // indicate valid.
}

function validate_author($value)
{
	$value = trim($value);
	if ($value=='')
		return 'Required';
		
	return ''; // indicate valid.
}

function validate_price($value)
{
	$value = trim($value);
	
	// price is not required so empty is fine.
	if ($value=='')
		return '';
		
	if (!is_numeric($value))
		return 'Invalid price number';

	return '';
}

function validate_subject($value)
{
	$value = trim($value);
	
	// price is not required so empty is fine.
	if ($value=='')
		return '';
		
	if (strlen($value)<=3)
		return 'Empty or longer than 3 characters is ok.';

	return '';
}

function validate_name($value)
{
  if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $value) != 1)
  {
	return  'Usernames can only have alphabetic, numeric, or spaces characters';
  }
  
  return '';
}

function validate_rname($value)
{
	$result = validate_name($value);
	if ($result!='')
		return $result;
		
	  $testval = dbquery('SELECT * FROM Users WHERE Name = ?',$_POST['rname']);
	  
	  if ( $testval->fetchColumn() ){
		// Username already exists, so store session error message...
		return 'Username already taken. Please choose a different username.';
	  }
  
	return ''; // indicate valid.
}

function validate_password($value)
{
  if (preg_match('/^[A-Za-z0-9 ]{5,40}$/', $value) != 1)
  {
    return 'Passwords can only have alphabetic, numeric, or spaces '
	.'characters and must have at least 5 characters and no more than 40 characters.';
  }
	return '';
}

function validate_rpassword($value)
{
	return validate_password($value);
}

// validates confirmation password.
function validate_ucpass($value)
{
  if (!isset($_POST['rpassword']) || ($_POST['rpassword'] != $value) )
  {
    // Password is invalid, so store session error message...
    return "Passwords don't match.";
  }
  
  return '';
}

/// used for sending emails.
function validate_email($value)
{
	if (!is_valid_email_address($value))
		return 'Invalid Email Address';
  
	return ''; // indicate valid.
}

// used for registering users.  The email address must be new and not yet in the database.
function validate_remail($value)
{
	$result = validate_email($value);
	
	if ($result!='')
		return $result;
		
  // Check for existing email address
  $query = dbquery('SELECT * FROM Users WHERE Email = ?',$_POST['uemail']);
  if ($query->fetchColumn())
  {
    // Email already exists, so store session error message...
    return 'Email address already taken. Please choose a different email address.';
  }
  
	return ''; // indicate valid.
}

function validate_rpostcode($value)
{
	$value = trim($value);
	
	if (strlen($value)!=6)
		return 'Invalid Postal Code';
	
	return '';
}

function sanitize_field($key,$value)
{
	$func_name='sanitize_'.strtolower($key);
	if (function_exists($func_name)):
		return call_user_func($func_name,$value);
	endif;
	return trim($value); // default
}

function validate_field($key,$value)
{
	$func_name='validate_'.strtolower($key);
	if (function_exists($func_name)):
		return call_user_func($func_name,$value);
	endif;
	
	return ''; // indicate valid.
}

function validate_all($expected_keys=array())
{
	clear_validation_messages();
	
	// Initially assume there are no errors in processing...
	$result=FALSE;
		
	// loop through fields
	foreach ($expected_keys as $key):
		unset($_SESSION[$key.'.err']);
		if (!isset($_POST[$key])):
			$_SESSION[$key]='';
			$_SESSION[$key.'.err']='Undefined';
			echo 'undefined key('.$key.')';
			$result= TRUE; // indicate an error.
		else:	
			$value = $_POST[$key];
			$_SESSION[$key]=sanitize_field($key,$value);
			$_SESSION[$key.'.err']=validate_field($key,$value);
					
			if ($_SESSION[$key.'.err']!='')
			{
				$result=TRUE;
			}	

		endif; // end if the key is set in $_POST.
	endforeach;
	
	return $result;
}

function print_all_validation_messages()
{
	?>Validation messages are:<ul><?php
	foreach ($_SESSION as $key=>$value):
		if (strpos($key,'.err')!==false):
			?><li><?php
			echo $key.': "';
			echo $value.'"<br/>';
			?></li><?php
		endif;
	endforeach;
	
	?></ul><?php
}

// to be called when a form is successfully submitted.
function clear_validation_messages()
{
	// Unset any previous error message...
	foreach ($_SESSION as $key=>$value):
		if (strpos($key,'.err')!==FALSE):
			unset($_SESSION[$key]);
			unset($_SESSION[substr($key,0,-4)]); // clear without the .err
			$key = substr($key,0,-3);
			if (isset($_SESSION[$key])):
				unset($_SESSION[$key]);
			endif;
		endif;
	endforeach;	
}
