<?php

require_once("includes/lib.php"); 
require_once('includes/book_lib.php');

requireLogin();

// TO DO: check that the current user is allowed to delete the book.
if (isset($_GET['id'])&&isset($_GET['delete'])):
	deleteBook($_GET['id']);
	// redirect back to user panel.
	header('Location: userpanel.php');
	exit(0);
endif;