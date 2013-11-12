 <?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once("includes/lib.php");
$form_url = 'addbook.php';
$success_url = 'index.php';
if (!array_key_exists('previous-page',$_SESSION)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}
//===========================================================================
// Abort processing if this is not an HTTP POST request...
if ($_SERVER['REQUEST_METHOD'] != 'POST')
  die;

//===========================================================================
// Abort processing if the HTTP-Referrer header is not provided or is
// incorrect...

//===========================================================================
$error_flag = FALSE;

//===========================================================================
// Check validity of everything and update $_SESSION appropriately...
$error_flag=validate_all(array('title','author','price','subject','description','isbn','contactno','image_url'));

//===========================================================================
// Redirect the web browser to either the form (if there were any errors) or
// the success page (if there were no errors)...
if (array_key_exists('error', $_SESSION)){
  unset($_SESSION['error']);
}
$_SESSION['forminfo'] = $_POST;
if (!$error_flag)
{
	$book = newBook($_POST['title'],currentUser()['ID'],$_POST['author'],$_POST['price'],$_POST['subject'],$_POST['description'],$_POST['isbn'],$_POST['contactno'],$_POST['image_url']);
	if (!$book){
		$error_flag = TRUE;
	}else{
		clear_session('forminfo','title','author','price','subject');
	}
}

// if success, don't continue storing the validation messages
if ($error_flag===FALSE)
	clear_validation_messages();

header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);

	
exit();
?>
