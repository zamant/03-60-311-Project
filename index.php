<?php
  // We can include this file in all our files
  // this way, every file will contain all our functions and exceptions
 require_once("config/config.php"); 
  header("Welcome");
  
  display_login_form();
  
  //display_photo_gallary();
  
  footer();
  
  
?>
