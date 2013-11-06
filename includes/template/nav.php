<ul>
<li><a href="index.php">All Used Books Ad</a></li>
<li><a href="searchbook.php">Search For Books</a></li>
<?php if (!array_key_exists('user',$_SESSION)){?>
<li><a href="login.php">Login/Register</a></li>
<?php } else{?>
<li><a href="addbook.php">Post a Book Advertisement</a></li>
<li><a href="searchbook.php">Edit/Delete Advertisement</a></li>
<li><a href="logout.php">Logout</a></li>
<?php }?>
</ul>