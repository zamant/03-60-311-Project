<ul>
<li><a href="index.php">All Used Books Ad</a></li>
<li><a href="searchform.php">Search For Books</a></li>
<?php if (!is_loggedin()){?>
<li><a href="login.php">Login/Register</a></li>
<?php }else{
		if (is_admin(currentUser())){
?>
		
<?php
		}
?>
<li><a href="addbook.php">Post a Book Advertisement</a></li>
<li><a href="userpanel.php">Edit/Delete Advertisement</a></li>
<li><a href="cart.php">View Cart</a></li>
<li><a href="logout.php">Logout</a></li>
<?php 	
	}?>
</ul>
