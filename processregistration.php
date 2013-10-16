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
if (array_key_exists('rusername.err', $_SESSION))
  unset($_SESSION['rusername.err']);

// Process the username field...
if (array_key_exists('uname', $_POST))
{
  // Remember this username...
  $_SESSION['rusername'] = $_POST['uname'];

  // Check for existing username
  $testval = dbquery('SELECT * FROM Users WHERE Name = ?',$_POST['uname']);
  if ( $testval->fetchColumn() ){
    $error_flag = TRUE;
    // Username already exists, so store session error message...
    $_SESSION['rusername.err'] = <<<ZZEOF
Username already taken. Please choose a different username.
ZZEOF;
  }
  // Check if username is from 1 to 40 chars and alnum or space...
  if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['uname']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['rusername.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }
}

//===========================================================================
// Check the password...

// Unset any previous error message...
if (array_key_exists('rpassword.err', $_SESSION))
  unset($_SESSION['rpassword.err']);

// Process the password field...
if (array_key_exists('upass', $_POST))
{
  // Remember this password...
  $_SESSION['rpassword'] = $_POST['upass'];

  // Check if password is from 5 to 40 chars and alnum or space...
  if (preg_match('/^[A-Za-z0-9 ]{5,40}$/', $_POST['upass']) != 1)
  {
    $error_flag = TRUE;
    // Password is invalid, so store session error message...
    $_SESSION['rpassword.err'] = <<<ZZEOF
Passwords can only have alphabetic, numeric, or spaces characters and must have at least 5 characters and no more than 40 characters.
ZZEOF;
  }
}
//===========================================================================
// Check if passwords match...
// Unset any previous error message...
if (array_key_exists('rpassword.err', $_SESSION))
  unset($_SESSION['rpassword.err']);

// Process the password field...
if (array_key_exists('ucpass', $_POST))
{
  // Check if password is the same...
  if ($_POST['upass'] != $_POST['ucpass'])
  {
    $error_flag = TRUE;
    // Password is invalid, so store session error message...
    $_SESSION['rcpassword.err'] = <<<ZZEOF
Passwords don't match.
ZZEOF;
  }
}

//===========================================================================
// Check the email address...

// Unset any previous error message...
if (array_key_exists('remailaddress.err', $_SESSION))
  unset($_SESSION['remailaddress.err']);

// Process the uemail field...
if (array_key_exists('uemail', $_POST))
{
  // Remember this emailaddress...
  $_SESSION['remailaddress'] = $_POST['uemail'];
  // Check for existing email address
  $query = dbquery('SELECT * FROM Users WHERE Email = ?',$_POST['uemail']);
  if ($query->fetchColumn()){
    $error_flag = TRUE;
    // Email already exists, so store session error message...
    $_SESSION['remailaddress.err'] = <<<ZZEOF
Email address already taken. Please choose a different email address.
ZZEOF;
  }
  // Check if email address is valid...
  if (!is_valid_email_address($_POST['uemail']))
  {
    $error_flag = TRUE;
    // Email address is invalid, so store session error message...
    $_SESSION['remailaddress.err'] = <<<ZZEOF
Email address must be valid.
ZZEOF;
  }
}
//===========================================================================
// Check the postal code...

// Unset any previous error message...
if (array_key_exists('rpostcode.err', $_SESSION))
  unset($_SESSION['rpostcode.err']);

// Process the postcode field...
if (array_key_exists('upostcode', $_POST))
{
  // Remember this postal code...
  $_SESSION['rpostcode'] = $_POST['upostcode'];

  // Check if postal code is from 6 chars...
  if (strlen($_POST['upostcode']) != 6)
  {
    $error_flag = TRUE;
    // Postal code is invalid, so store session error message...
    $_SESSION['rpostcode.err'] = <<<ZZEOF
Please enter a valid postal code
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
if ($error_flag){
  $_SESSION['registration'] = 'error';
  unset($_SESSION['rcpassword']);
}else{
  $_SESSION['registration'] = 'success';
  newUser($_POST['uname'],$_POST['upass'],$_POST['uemail'],$_POST['upostcode']);
}
header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);
exit();
?>