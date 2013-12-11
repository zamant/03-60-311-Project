<?php
require_once('includes/lib.php');

$title='Search for a book';
$article_class='search_form';
include('includes/template/head.php');
?>

<form action="search.php" method="POST">
			<?php 
				if (array_key_exists('addbook',$_SESSION) && $_SESSION['addbook'] == 'error'){
					echo '<div class="formerror">Sorry, your submission was not accepted. Please fix the errors below and resubmit.</div>';
				}
				echo form_text_field(
					'Book Title:',
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'title', 
					'title',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Book Author:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'author', 
					'author',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'ISBN:', 
					'ISBN', 
					true,
					'isbn', 
					'isbn',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Subject:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'subject', 
					'subject',
					'text'
				);
				?><br/>
			<div class="content-center">
				<input type="submit" value="Search Book" />
			</div>
		</form>
<?php

include('includes/template/foot.php');
?>