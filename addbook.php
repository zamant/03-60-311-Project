 <!DOCTYPE html>
<html>
<head>
<?php
require_once("includes/lib.php"); 
requireLogin();

if (array_key_exists('HTTP_REFERER',$_SERVER)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}

$title='Advertisement Post Form';
 if (isset($_GET['id'])):
	$title='Advertisement Edit Form';	
endif; 
print_head_snippet();
require_once('includes/template/head.php');

if (isset($_GET['id'])):
	// load book information from database.
	$book = getBook($_GET['id']);
	$_SESSION['isbn']=$book['ISBN'];
	$_SESSION['title']=$book['TITLE'];
	$_SESSION['contactno']=$book['CONTACTNO'];
	$_SESSION['author']=$book['AUTHOR'];
	$_SESSION['description']=$book['DESCRIPTION'];
	$_SESSION['image_url']=$book['IMAGE_URL'];
	$_SESSION['subject']=$book['SUBJECT'];
	$_SESSION['price']=$book['PRICE'];
	
endif;
?>
		<div class="content-center">
		Items marked with * are optional.
		</div>
		<form action="processaddbook.php" method="POST">
		<?php if (isset($_GET['id'])): ?>
			<input type="hidden" name="id" value="<?php echo htmlspecialchars(trim($_GET['id'])); ?>" />
		<?php endif; ?>
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
					'Contact No:', 
					'Contact', 
					true,
					'contactno', 
					'contactno',
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
				<textarea cols="45" rows="8" name="description"><?php
				if (isset($_SESSION['description'])):
					echo htmlspecialchars($_SESSION['description']);
				endif;
				?></textarea>
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