<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once("includes/lib.php");
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
$error_flag = validate_all(array('rname','rpassword','ucpass','remail','rpostcode'));

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
  newUser($_POST['rname'],$_POST['rpassword'],$_POST['remail'],$_POST['rpostcode']);
}
header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);
clear_validation_messages();
exit();
