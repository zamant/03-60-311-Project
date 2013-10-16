<?php
if(isset($_POST['email_id'])) {

// EDIT THE 2 LINES BELOW AS REQUIRED
$email_to = "zhang15t@uwindsor.ca";  // This email address will recieve the data of comments.htmlshoud be modify to connect to DB
$email_subject = "test";   // This would be the subject of email that you will recieve

function catch_error($error) {
echo "Problem encountered with the form you submitted!. <br />";
echo "Following error occured with your form please fix.<br /><br />";
echo $error."<br /><br />";

die();
}

// validation expected data exists
if(!isset($_POST['fname']) ||
!isset($_POST['lname']) ||
!isset($_POST['email_id']) ||
!isset($_POST['comment'])) {
die('There is a problem with the form u submitted.Please Resubmit');
}

$fname = $_POST['fname']; // required
$lname = $_POST['lname']; // required
$email_id = $_POST['email_id']; // required
$comment = $_POST['comment']; // required

$error_message = "";
//Using regular expressions for email id validation
$email_verfiy = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
if(!preg_match($email_verfiy,$email_id)) {
$error_message .= 'The Email Address you entered is not valid.<br />';
}
$name_verify = "/^[A-Za-z .'-]+$/";
//Using regular expressions for name validation
if(!preg_match($name_verify,$fname)) {
$error_message .= 'The First Name you entered is not valid.<br />';
}
if(!preg_match($name_verify,$lname)) {
$error_message .= 'The Last Name you entered is not valid.<br />';
}
if(strlen($comment) < 2) {
$error_message .= 'The Comment you entered is not valid.<br />';
}
if(strlen($error_message) > 0) {
catch_error($error_message);
}
$email_message = "Form details below.\n\n";

function clean_text($string) {
$bad = array("content-type","bcc:","to:","cc:","href");
return str_replace($bad,"",$string);
}

$email_message .= "First Name: ".clean_text($fname)."\n";
$email_message .= "Last Name: ".clean_text($lname)."\n";
$email_message .= "Email: ".clean_text($email_id)."\n";
$email_message .= "Website: ".clean_text($website)."\n";
$email_message .= "Comments: ".clean_text($comment)."\n";

@mail($email_to, $email_subject, $email_message);

/*
Put your custom message here or redirect user to any other page
after successful submission of form.<br/>
for example a simple message like this:<br /> */
echo "Your form has been Submitted Successfuly.!!
We will be back to you ASAP";

}
?>
