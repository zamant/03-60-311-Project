<!DOCTYPE html>
<html>
<head>

<?php

print_head_snippet();
if (array_key_exists('page',$_GET) && is_numeric($_GET['page'])){
	$page = intval($_GET['page']);
}else{
	$page = 1;
}
if (array_key_exists('pagemax',$_COOKIE)){
	$max = intval($_COOKIE['pagemax']);
}else{
	$max = 25;
}	
?>
	<title>
		311 Project - Fall 2013
	</title>
</head>
<body>
<header>
	<?php
		require_once("includes/template/header.php");
	?>
</header>
<nav>
	<?php
		require_once("includes/template/nav.php");
	?>
</nav>
<section>
	<article<?php
		if (isset($article_class)):
			?> class="<?php
			echo $article_class;
			?>"<?php
		endif;
	?>>
	<header><?php echo (
		isset($title)
		? $title
		: 'Untitled Page'); 
	?></header>