<!DOCTYPE html>
<html>
<head>
<?php
error_reporting(E_ALL | E_STRICT);
require_once("includes/lib.php"); 
header('Content-Type: text/html');

print_head_snippet();

if (array_key_exists('HTTP_REFERER',$_SERVER)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}
if (!array_key_exists('user',$_SESSION)){?>
	<title>Login or Register</title>
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
<aside>
</aside>
<section>
	<table><tr><td>
		<article>
		<header>Login</header>
		<form action="processlogin.php" method="POST">
			<?php 
				if (array_key_exists('login',$_SESSION) && $_SESSION['login'] == 'error'){
					echo '<div class="formerror">Login failed.</div>';
				}
				echo form_text_field(
					'Username:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'name', 
					'name',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Password:', 
					'Valid password', 
					true,
					'password', 
					'password',
					'password'
				);
			?><br />
			<input type="submit" value="Login" />
		</form>
	</article>
	</td><td>
	<article>
		<header>Register</header>
		<form action="processregistration.php" method="POST">
			<?php 
				if (array_key_exists('registration',$_SESSION) && $_SESSION['registration'] == 'error'){
					echo '<div class="formerror">Sorry, your submission was not accepted. Please fix the errors below and resubmit.</div>';
				}
				echo form_text_field(
					'Username:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'rname', 
					'rname',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Password:', 
					'Valid password', 
					true,
					'rpassword', 
					'rpassword',
					'password'
				);
				echo "<br />";
				echo form_text_field(
					'Confirm:', 
					'Valid password', 
					true,
					'ucpass', 
					'ucpass',
					'password'
				);    
				echo "<br />";
				echo form_text_field(
					'Email:', 
					'Valid email address', 
					true,
					'remail', 
					'remail',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Postal Code:',
					'Valid Canadian Postal Code',
					true,
					'rpostcode',
					'rpostcode',
					'text'
				);
			?><br />
			<input type="submit" value="Register" />
		</form>
	</article>
    </td></tr></table>
</section>
<footer>
	<?php
		require_once("includes/template/footer.php");
	?>
</footer>
</body>
</html>
<?php 
}else{
	header('Location: '.url_to_redirect_to('index.php'));
}
clear_validation_messages();
exit();
?>