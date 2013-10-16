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
if (array_key_exists('user',$_SESSION)){?>
	<title>Add Book</title>
</head>
<body>
<header>
	<?php
		require_once("template/header.php");
	?>
</header>
<nav>
	<?php
		require_once("template/nav.php");
	?>
</nav>
<aside>
</aside>
<section>
	<article>
		<header>Register</header>
		Items marked with * are optional.
		<form action="processaddbook.php" method="POST">
			<?php 
				if (array_key_exists('addbook',$_SESSION) && $_SESSION['addbook'] == 'error'){
					echo '<div class="formerror">Sorry, your submission was not accepted. Please fix the errors below and resubmit.</div>';
				}
				echo form_text_field(
					'Title:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'utitle', 
					'title',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Author:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'uauthor', 
					'author',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'*Price:', 
					'Price in CAD', 
					true,
					'uprice', 
					'price',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'*Subject:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'usubject', 
					'subject',
					'text'
				);
				echo '<label for="description">Description</label>';
				echo '<textarea cols="40" rows="5" name="description"></textarea>';
			?><br />
			<input type="submit" value="AddBook" />
		</form>
	</article>
</section>
<footer>
	<?php
		require_once("template/footer.php");
	?>
</footer>
</body>
</html>
<?php 
}else{
	header('Location: '.url_to_redirect_to('index.php'));
}
exit();
?>