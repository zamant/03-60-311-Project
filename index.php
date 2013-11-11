<?php
	require_once('includes/lib.php');

	clear_session('remailaddress','rpassword','rcpassword','rusername','lpassword','lusername','error','login','registration','rpostcode');
	$_SESSION['GET'] = $_GET;
	if (array_key_exists('del',$_GET) && is_numeric($_GET['del'])){
		deleteBook($_GET['del']);
		header('Location: '.url_to_redirect_to('index.php'));
	}
	$title='All Advertised Books';
	
	require_once('includes/template/head.php');
	displayAllAds('index.php',$page,$max);
	require_once("includes/template/foot.php");
?>
