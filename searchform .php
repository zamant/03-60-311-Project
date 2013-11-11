<?php
require_once('includes/lib.php');

$title='Search';
$article_class='search_form';
include('includes/template/head.php');
?>
<form method="post" action="search.php">
	 Title:<input type="text" name="title"/><br />
	 Author:<input type="text" name="author"/><br />
	 ISBN:  <input type="text" name="ISBN"/><br />
	 Subject: <input type="text" name="subject"/><br />
	 <br />
     <input type="submit" name="submit" value="Search"/>
</form>
<?php

include('includes/template/foot.php');
?>