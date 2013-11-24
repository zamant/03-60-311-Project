<?php
require_once('includes/lib.php');

$title='Book Details';
$article_class='book_details';

if (isset($_GET['id']) && is_numeric($_GET['id'])){
	$book = getBookById($_GET['id']);
	if (array_key_exists('addtocart',$_REQUEST) && is_numeric($_REQUEST['addtocart'])){
		addtocart($_REQUEST['addtocart'],1);
		header('Location: '.url_to_redirect_to('book_details.php').'?id='.$_GET['id']);
	}
}


include('includes/template/head.php');

if (isset($book) && $book):
	$currentuser = currentUser();
	if ($book['SELLERID'] == $currentuser['ID'] || is_admin($currentuser)){
		deleteButton("'index.php?'",$book["ID"]);
	}
	?>
	
	<div class="content">
	<?php if ($book['PRICE'] != '0.00'){
			if (!isInCart($book['ID'])){
				addtocartButton("book_details.php?id=".$book['ID']."&",$book['ID']);
			}
				$pname = $book['TITLE'];
				$price = $book['PRICE'];
				?>
				<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="business" value="recieve@gmail.com"> <!--<?//=get_seller($pid,$con)?> need to use user email in user table-->
<input type="hidden" name="cmd" value="_xclick"> 
<input type="hidden" name="rm" value="2"> 
<input type="hidden" name="return" value="http://www.sqlview.com/payment/notify.php"> 
<input type="hidden" name="custom" value="myvalue"> 
<input type="hidden" name="item_name" value="<?=$pname?>"> 
<input type="hidden" name="amount" value="<?=$price?>"> 
<input type="hidden" name="currency_code" value="CAD"> 
<input class="addtocart" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="Buy Now">

</form>
			<?php
		}?>
		<h1><?php echo htmlspecialchars($book['TITLE']); ?></h1>
		<h5>Author: <?php echo htmlspecialchars($book['AUTHOR']); ?></h5>
		
		<?php
		
		print_book_image($book);
		?>
			<div class="properties">
				<ul>
					<li><b>ISBN: </b><?php
					echo htmlspecialchars($book['ISBN']);
					?></li><li><b>SUBJECT: </b><?php
					echo htmlspecialchars($book['SUBJECT']);
					?></li><li><b>POSTED ON: </b><?php
					echo htmlspecialchars($book['TIMESTAMP']);
					?></li><li><b>STATUS: </b><span><?php
					if ($book['PRICE']=='0.00'):
						echo 'For Exchange';
					else:
						echo 'Price CAD $'.$book['PRICE'];
					endif;
					?></span>
					</li><li><b>CONTACT NO: </b><span><?php
					if ($book['CONTACTNO']=='0'):
						echo 'Unavailable!';
					else:
						echo formatPhoneNumber(htmlspecialchars($book['CONTACTNO']));
					endif;
					?></span></li>
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
		
		<h3 class="book_description">Book Description:</h3>
		<p> <?php
		if (!$book['DESCRIPTION']
		/*|| strlen($book['DESCRIPTION'])<10 Matt - Why is this a condition?- because I dont want to display anything less then length 10, just put this condition, by any chance if database field have some unwanted thing.*/
		):
			?><span class="italic">No description found.</span><?php
		else:
			echo htmlspecialchars($book['DESCRIPTION']);
		endif;
		
	?></p></div><?php
else:
	?>Invalid book id in URL.<?php
endif;



include('includes/template/foot.php');
