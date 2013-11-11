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

requireLogin();

$title='Your Cart';
require_once('includes/template/head.php');


?>

<!------------Code goes here------------->
<p align=center style="font-size:20px">Under Construction!</p>
<!--------------------------------------->

<?php 
	require_once('includes/template/foot.php');

clear_validation_messages();

exit();
?>
