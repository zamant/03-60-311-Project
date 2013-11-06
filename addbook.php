 <!DOCTYPE html>
<html>
<head>
<?php
error_reporting(E_ALL | E_STRICT);
require_once("includes/lib.php"); 
header('Content-Type: text/html');
print_head_snippet();

if (array_key_exists('HTTP_REFERER',$_SERVER)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}

requireLogin();

$title='Advertisement Post Form';
require_once('includes/template/head.php');


?>
		<div class="content-center">
		Items marked with * are optional.
		</div>
		<form action="processaddbook.php" method="POST">
			<?php 
				if (array_key_exists('addbook',$_SESSION) && $_SESSION['addbook'] == 'error'){
					echo '<div class="formerror">Sorry, your submission was not accepted. Please fix the errors below and resubmit.</div>';
				}
				echo form_text_field(
					'*Book Title:',
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'title', 
					'title',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'*ISBN:', 
					'ISBN', 
					true,
					'isbn', 
					'isbn',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'*Book Author:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'author', 
					'author',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Image URL:', 
					'URL', 
					true,
					'image_url', 
					'image_url',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'*Subject:', 
					'Legal name characters including letters, numbers, or spaces.', 
					true,
					'subject', 
					'subject',
					'text'
				);
				echo "<br />";
				echo form_text_field(
					'Price:', 
					'Price in CAD', 
					true,
					'price', 
					'price',
					'text'
				);
				?><br/>
				<label for="description">Book Description:</label>
				<textarea cols="45" rows="8" name="description"></textarea>
				<?php	  errorCheck('description');
						?>
			<br />
			<div class="content-center">
				<input type="submit" value="Post Advertisement" />
			</div>
		</form>
<?php 
	require_once('includes/template/foot.php');

clear_validation_messages();

exit();
?>