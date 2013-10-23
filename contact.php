 <!DOCTYPE html>
<html>
<head>
<?php
error_reporting(E_ALL | E_STRICT);
require_once("lib.php"); 
header('Content-Type: text/html');
if (array_key_exists('HTTP_REFERER',$_SERVER)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}
	if (array_key_exists('fromname',$_POST)
	&& array_key_exists('fromemail',$_POST)
	&& array_key_exists('adID',$_POST)
	&& array_key_exists('message',$_POST)){
		$adID = $_POST['adID'];
		$fromname = $_POST['fromname'];
		$fromemail = $_POST['fromemail'];
		$adInfo = getBook($adID);
		$seller = getUserByID($adInfo['SellerID']);

		$response = handleContactForm($adID,$fromname,$fromemail,$seller['Email'],$_POST['message']);
		if ($response){
			$_SESSION['contactresult'] = $response;
		}else{
			$_SESSION['contactresult'] = "Message sent.";
		}
		if (array_key_exists('previous-page',$_SESSION)){
			header('Location: '.$_SESSION['previous-page']);
		}else{
			header('Location: index.php');
		}
	}
exit();
?>