<?php 
require_once('includes/lib.php');

unset($_SESSION['success']);

if (isset($_POST['book_id'])&&is_numeric($_POST['book_id']))
{
	$book =  getBookById($_POST['book_id']);
	if (!$book)
		unset($book);
}
if (!isset($book)):
	echo 'book_id required';
	exit(0);
else:

	$errors = '';
	$seller = getSellerFromBook($book);
	$myemail = $seller['EMAIL'];

	$error_code = validate_all(array('name','email','message'));

	if (!$error_code):
		
		$name = $_POST['name']; 
		$email_address = $_POST['email']; 
		$message = $_POST['message']; 

		$to = $myemail; 
		$email_subject = "Contact form submission: $name";
		$email_body = "You have received a new message. ".
		" Here are the details:\n Name: $name \n Email: $email_address \n Message \n $message"; 
		
		$headers = "From: $myemail\n"; 
		$headers .= "Reply-To: $email_address";
		
		mail($to,$email_subject,$email_body,$headers);
		$_SESSION['success']=TRUE;
	endif;
	
	// redirect back to the form.
	$url='book_details.php?id='.$book['ID'];
	header('Location: '.$url);
	
endif;