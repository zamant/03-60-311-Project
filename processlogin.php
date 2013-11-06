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

$error_flag = validate_all(array('name','password'));

$_SESSION['forminfo'] = array('name'=>$_POST['name']);
if (!checkUser($_POST['name'],$_POST['password'])){
	$error_flag = TRUE;
}

if ($error_flag){
  $_SESSION['login'] = 'error';
}else{
  $_SESSION['login'] = 'success';
  setLoginCookie($_POST['name'],time()+60*60*24*30);
}
header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);
clear_validation_messages();
exit();
?>