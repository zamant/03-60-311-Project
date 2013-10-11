<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once("lib.php");
if (array_key_exists('user',$_SESSION)){
	$user = explode("|",$_SESSION['user']);
	setLoginCookie($user[0],time()-3600);

	header('Location: '.$_SERVER['HTTP_REFERER']);
}else{
	header(  'Location: '.url_to_redirect_to('index.php'));
}
exit();
?>