<!DOCTYPE html>
<html>
<head>
<?php
error_reporting(E_ALL | E_STRICT);
require_once("includes/lib.php");
print_head_snippet();

$temp = $_SESSION;
//===========================================================================
// Define a function to make it easy to unset() all relevant $_SESSION 
// variables. (NOTE: In a large site one might only want to unset() some of
// the $_SESSION variables --not all of them. This is a way of doing just
// that.)


//===========================================================================
// Clear all $_SESSION information related to form.php and process.php
// NOTE: referring-page is not used here so we'll unset it.
clear_session('remailaddress','rpassword','rcpassword','rusername','lpassword','lusername','error','login','registration','previous-page','rpostcode');

foreach ($_SESSION as $key=>$value){
	if (substr_compare($key, '.err', -4, 4) === 0){
		unset($_SESSION[$key]);
	}
}
// If one wants to simply destroy all $_SESSION information, the easiest way
// is to $_SESSION = array().

if (array_key_exists('registration',$temp) && $temp['registration'] == 'success'){
	$type = 'Registration';
}else{
	$type = 'Login';
}
if (array_key_exists('previous-page',$temp)){
?>
	<title>Success</title>
	<?php
	if ($type == 'Registration'){
		echo '<meta http-equiv="refresh" content="5;url='.$temp['previous-page'].'" />';
	}else{
		header('Location: '.$temp['previous-page']);
		exit();
	}
	?>
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
	<article>
		<h1><?php echo $type?> Successful</h1>
		<?php
		echo '<a href="'.$temp['previous-page'].'">Please wait while you&apos;re redirected, or click here if you don&apos;t want to wait.</a><br />
		Before logging in, you must verify your email address. A link has been sent to the registered address.';
		//For now, all email addresses will default to automatically verified. Add in email verification later
		?>
    </article>
</section>
<footer>
	<?php
		require_once("includes/template/footer.php");
	?>
</footer>
</body></html>
<?php
}else{
	header('Location: '.url_to_redirect_to('index.php'));
	exit();
}
?>