<?php
require_once('includes/lib.php');

$title='Book Details';
$article_class='book_details';
include('includes/template/head.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])):
	$book = getBookById($_GET['id']);
endif;

if (isset($book) && $book):
	if ($book['SELLERID'] == $currentuser['ID'] || $currentuser['LEVEL'] == 1){
		deleteButton("'index.php?'",$book["ID"]);
	}
	?>
	
	<div class="content">
		<h1><?php echo htmlspecialchars($book['TITLE']); ?></h1>
		<h5>Author: <?php echo htmlspecialchars($book['AUTHOR']); ?></h5>
		
		<?php
		
		print_book_image($book);
		
		?>
			<div class="properties">
				<ul>
					<li>ISBN: <?php
					echo htmlspecialchars($book['ISBN']);
					?></li><li>SUBJECT: <?php
					echo htmlspecialchars($book['SUBJECT']);
					?></li><li>POSTED ON: <?php
					echo htmlspecialchars($book['TIMESTAMP']);
					?></li><li>STATUS: <span><?php
					if ($book['PRICE']=='0.00'):
						echo 'For Exchange';
					else:
						echo 'Price CAD $'.$book['PRICE'];
					endif;
					
					?></span>
					</li>
				</ul>
			</div>
			<div class="email_form">
				<?php
				if (isset($_SESSION['success'])):
				?>
					<p>Your email was sent.</p>
				<?php
				else:
				?>
					<form method="post" action="contact-form-handler.php">
						<input type="hidden" name="book_id" value="<?php
						echo $book['ID'];
						?>" />
						<?php
							echo form_text_field(
							'*Name:',
							'', 
							true,
							'name', 
							'name',
							'text'
						);
						?><br/><?php
							echo form_text_field(
							'*Email:',
							'', 
							true,
							'email', 
							'email',
							'text'
						);
						?><br/>
						<?php	  errorCheck('message');
						?>
						<label for="message">Your Message:</label>
						<textarea maxlength="320" cols="45" rows="8" name="message"></textarea>
						<br />
						<div class="content-center">
							<input type="submit" value="Send" />
						</div>
					</form>
				<?php
				endif;
				unset($_SESSION['success']);
				clear_validation_messages();

				?>
			</div>
		
		<h3 class="book_description">Book Description</h3><p><?php
		if (!$book['DESCRIPTION']
		/*|| strlen($book['DESCRIPTION'])<10 Matt - Why is this a condition?*/
		):
			?><span class="italic">No description found.</span><?php
		else:
			echo htmlspecialchars($book['DESCRIPTION']);
		endif;
		
	?></p></div><?php
else:
	?>Invalid book id in URL.<?php
endif;

?></article><?php

include('includes/template/foot.php');