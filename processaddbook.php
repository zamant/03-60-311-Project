 <?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once("lib.php");
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
// Initially assume there are no errors in processing...
$error_flag = FALSE;

//===========================================================================
// Check the title...

// Unset any previous error message...
if (array_key_exists('title.err', $_SESSION))
  unset($_SESSION['title.err']);

// Process the title field...
if (array_key_exists('utitle', $_POST))
{
  // Remember this title...
  $_SESSION['title'] = $_POST['utitle'];

  // Check if username is from 1 to 40 chars and alnum or space...
  /*if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['utitle']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['title.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }*/
}

//===========================================================================
// Check the author..

// Unset any previous error message...
if (array_key_exists('author.err', $_SESSION))
  unset($_SESSION['author.err']);

// Process the title field...
if (array_key_exists('uauthor', $_POST))
{
  // Remember this title...
  $_SESSION['author'] = $_POST['uauthor'];

  // Check if username is from 1 to 40 chars and alnum or space...
  /*if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['uauthor']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['author.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }*/
}

//===========================================================================
// Check the price...

// Unset any previous error message...
if (array_key_exists('price.err', $_SESSION))
  unset($_SESSION['price.err']);

// Process the title field...
if (array_key_exists('uprice', $_POST))
{
  // Remember this title...
  $_SESSION['price'] = $_POST['uprice'];

  // Check if username is from 1 to 40 chars and alnum or space...
  /*if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['uprice']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['price.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }*/
}

//===========================================================================
// Check the subject...

// Unset any previous error message...
if (array_key_exists('subject.err', $_SESSION))
  unset($_SESSION['subject.err']);

// Process the title field...
if (array_key_exists('usubject', $_POST))
{
  // Remember this title...
  $_SESSION['subject'] = $_POST['usubject'];

  // Check if username is from 1 to 40 chars and alnum or space...
  /*if (preg_match('/^[A-Za-z0-9 ]{1,40}$/', $_POST['usubject']) != 1)
  {
    $error_flag = TRUE;
    // Username is invalid, so store session error message...
    $_SESSION['subject.err'] = <<<ZZEOF
Usernames can only have alphabetic, numeric, or spaces characters and must have at least 1 character and no more than 40 characters.
ZZEOF;
  }*/
}

//===========================================================================
// Redirect the web browser to either the form (if there were any errors) or
// the success page (if there were no errors)...
if (array_key_exists('error', $_SESSION)){
  unset($_SESSION['error']);
}
$_SESSION['forminfo'] = $_POST;
if (!$error_flag){
	$book = newBook($_POST['utitle'],currentUser()['ID'],$_POST['uauthor'],$_POST['uprice'],$_POST['usubject'],$_POST['description']);
	if (!$book){
		$error_flag = TRUE;
	}else{
		clear_session('utitle','uauthor','uprice','usubject');
	}
}

header(
  'Location: '.
  ($error_flag === TRUE
    ? url_to_redirect_to($form_url)
    : url_to_redirect_to($success_url))
);
exit();
?>