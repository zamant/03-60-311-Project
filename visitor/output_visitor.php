<!DOCTYPE html>
<?php

function head($title){

?>

<html>
<head>
   <script src="js/gallery.js" type="text/javascript"></script>
   <link rel="stylesheet" type="text/css"  media="all"  href="css/visitor.css" />
   

   <title><?php echo $title;?></title>   
</head>
<body  onload="clickMenu('gallery')">
<div id="wrapper">
  <div id="header"><a href="../index.php"><img src="images/title.png" alt="title" /></a></div>
<?php
}
function display_login_form() {
?>

<div id="content">
 <div id="content-left">
  
  <form method="post" action="member/member.php">
  <table>
    <td> <a href="visitor/register_form.php">Not a member?</a></td>
   <tr>
     <td colspan="2">Members log in here:</td>
   </tr>
   <tr>
     <td>Username:</td>
     <td><input type="text" name="username"/></td>
   </tr>
   <tr>
     <td>Password:</td>
     <td><input type="password" name="passwd"/></td>
   </tr>
   <tr>
     <td colspan="2" align="center">
     <input type="submit" value="Log in"/></td>
   </tr>
   <tr>
     <td colspan="2"><a href="member/forgot_form.php">Forgot your password?</a></td>
   </tr>
   <tr>
     <td colspan="2"><a href="admin/index.html">Log in as Admin?</a></td>
   </tr>
 </table>
 </form>
 </div>

<?php
}
function display_registration_form() {
?>

<div id="content" align="center">
 <form method="post" action="register_new.php" >
 <table>
   <tr>
     <td>Email address:</td>
     <td><input type="text" name="email" size="30" maxlength="100"/></td>
   </tr>
   <tr>
     <td>Preferred login username <br />(max 16 chars):</td>
     <td valign="top"><input type="text" name="username"
         size="16" maxlength="16"/></td>
   </tr>
   <tr>
     <td>Password <br />(between 6 and 16 chars):</td>
     <td valign="top"><input type="password" name="passwd"
         size="16" maxlength="16"/></td>
   </tr>
   <tr>
     <td>Confirm password:</td>
     <td><input type="password" name="passwd2" size="16" maxlength="16"/></td>
  </tr>
   <tr>
  
     <td>Name:</td>
     <td><input type="text" name="name" size="20" maxlength="30"/></td>
  </tr>
   
   <tr>
     <td>Address:</td>
     <td><input type="text" name="address" size="30" maxlength="100"/></td>
   </tr>
   
   <tr>
     <td>City:</td>
     <td><input type="text" name="city" size="30" maxlength="100"/></td>
	
   </tr>
   <tr>
     <td>Province:</td>
     <td><input type="text" name="province" size="15" maxlength="20"/></td>
     
  </tr>
   
   <tr>
     <td>Postcode:</td>
     <td><input type="text" name="postcode" size="6" maxlength="6"/><br />(6 chars):</td>
  </tr>
  <tr>
     <td>cellphone:</td>
     <td><input type="text" name="cellphone" size="10" maxlength="10"/><br />(10 digits):</td>
  </tr>
   
     <td colspan=2 align="center">
     <input type="submit" value="Register"></td>
 </table>
 </form>
</div>
<?php
}
  function display_photo_gallary()
{

?>
<div id="content-main" align="center" onload="clickMenu('gallery')">
 <div id="gallery">
<ul>
	<li><i><img src="images/01.png" title="" alt="" /></i><span><b>Congee</b><br />Minced Pork Congee with Preserved Egg</span></li>
	<li><i><img src="images/02.png" title="" alt="" /></i><span><b>Toufu</b><br /> stewed beancurd with minced pork in pepper sauce</span></li>
	<li><i><img src="images/03.png" title="" alt="" /></i><span><b>Fry Rice</b><br />Fried rice with shrimp and vegetables</span></li>
	<li><i><img src="images/04.png" title="" alt="" /></i><span><b>Soup.</b><br />Chicken Soup,simmered with vatious others ingredients</span></li>
	<li class="click"><i><img class="default" src="images/01.png" title="" alt="" /></i><span><b>Congee</b><br />Minced Pork Congee with Preserved Egg</span></li>
</ul>
</div>
</div>
<?php
}
function do_html_url($url, $name) {
  // output URL as link and br
  //<?php echo dirname(__DIR__) ?>
  <br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
}
function footer(){
?>
<div id="footer" align="center"> Copyright@balabala</div>
</div>
</body>
</html>
<?php
}
?>