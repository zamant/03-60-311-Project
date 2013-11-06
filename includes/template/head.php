<!DOCTYPE html>
<html>
<head>

<?php

print_head_snippet();
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