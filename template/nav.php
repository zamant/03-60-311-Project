<ul>
<li><a href="index.php">Main Page</a></li>
<?php if (!array_key_exists('user',$_SESSION)){?>
<li><a href="login.php">Login/Register</a></li>
<?php } else{?>
<li><a href="logout.php">Logout</a></li>
<?php }?>
</ul>