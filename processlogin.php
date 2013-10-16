<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once("lib.php");
$form_url = 'login.php';
$success_url = 'success.php';
if (!array_key_exists('previous-page',$_SESSION)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}
//===========================================================================
// Abort processing if this is not an HTTP POST request...
if ($_SERVER['REQUEST_METHOD'] != 'POST')
  die;

//===========================================================================
// Abort processing if the HTTP-Referrer header is not provided or is
// incorrect...

//===========================================================================
// Initially assume there are no errors in processing...
$error_flag = FALSE;

//===========================================================================
// Check the username...

// Unset any previous error message...
if (array_key_exists('lusername.err', $_SESSION))
  unset($_SESSION['lusername.err']);

// Process the username field...
if (array_key_exists('luname', $_POST))
{
  // Remember this username...
  $_SESSION['lusername'] = $_POST['luname'];

  // Check if username is from 1 to 40 chars and alnum or space...
  if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['luname']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['lusername.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }
}

//===========================================================================
// Check the password...

// Unset any previous error message...
if (array_key_exists('lpassword.err', $_SESSION))
  unset($_SESSION['lpassword.err']);

// Process the password field...
if (array_key_exists('lupass', $_POST))
{
  // Remember this password...
  $_SESSION['lpassword'] = $_POST['lupass'];

  // Check if password is from 5 to 40 chars and alnum or space...
  if (preg_match('/^[A-Za-z0-9 ]{5,40}$/', $_POST['lupass']) != 1)
  {
    $error_flag = TRUE;
    // Password is invalid, so store session error message...
    $_SESSION['lpassword.err'] = <<<ZZEOF
Passwords can only have alphabetic, numeric, or spaces characters and must have at least 5 characters and no more than 40 characters.
ZZEOF;
  }
}

//===========================================================================
// Redirect the web browser to either the form (if there were any errors) or
// the success page (if there were no errors)...
if (array_key_exists('error', $_SESSION)){
  unset($_SESSION['error']);
}
$_SESSION['forminfo'] = $_POST;
if (!checkUser($_POST['luname'],$_POST['lupass'])){
	$error_flag = TRUE;
}
if ($error_flag){
  $_SESSION['login'] = 'error';
}else{
  $_SESSION['login'] = 'success';
  setLoginCookie($_POST['luname'],time()+60*60*24*30);
}
header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);
exit();
?>