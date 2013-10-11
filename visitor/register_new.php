<?php
  // include function files for this application
  require_once('../config/config.php');

  //create short variable names
  $email=$_POST['email'];
  $username=$_POST['username'];
  $passwd=$_POST['passwd'];
  $passwd2=$_POST['passwd2'];
  $name=$_POST['name'];
  $address=$_POST['address'];
  $city=$_POST['city'];
  $province=$_POST['province'];
  $postcode=$_POST['postcode'];
  // start session which may be needed later
  // start it now because it must go before headers
  session_start();
  try   {
    // check forms filled in
    if (!filled_out($_POST)) {
      throw new Exception('You have not filled the form out correctly - please go back and try again.');
    }

    // email address not valid
    if (!valid_email($email)) {
      throw new Exception('That is not a valid email address.  Please go back and try again.');
    }

    // passwords not the same
    if ($passwd != $passwd2) {
      throw new Exception('The passwords you entered do not match - please go back and try again.');
    }

    // check password length is ok
    // ok if username truncates, but passwords will get
    // munged if they are too long.
    if ((strlen($passwd) < 6) || (strlen($passwd) > 16)) {
      throw new Exception('Your password must be between 6 and 16 characters Please go back and try again.');
    }
    $_SESSION['valid_user'] = $username;
    $_SESSION['passwd']=$passwd;
    // attempt to register
    // this function can also throw an exception
    register($username, $email, $passwd,$name,$address,$city,$province,$postcode);
    // register session variable
   
    // provide link to members page
    head('Registration successful');
    echo 'Your registration was successful.  Go to the members page to start order you meal';
    do_html_url('../member/member.php', 'Go to members page');

   // end page
   footer();
  }
  catch (Exception $e) {
     head('Problem:');
     echo $e->getMessage();
     footer();
     exit;
  }
?>
