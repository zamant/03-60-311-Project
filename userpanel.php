<?php
require_once("includes/lib.php"); 


if (array_key_exists('HTTP_REFERER',$_SERVER)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}

requireLogin();

$title='Manage your advertisements';
require_once('includes/template/head.php');


?>

<!------------Code goes here------------->
<?php

$sellerid=getUserIDFromUsername($_SESSION['user']);
if ($sellerid!==false):
	$user_books = getAllBooksBySeller(1,25,$sellerid);

	?><table class="user_books"><tbody><?php
	
	foreach ($user_books as $book):
		
		?><tr><td><?php
		print_book_image($book);
		?></td><td><h3><?php
		echo htmlspecialchars($book['TITLE']);
		?></h3>
		
		<ul>
			<li>Author: <?php echo htmlspecialchars($book['AUTHOR']); ?></li>
			<li>ISBN: <?php 
				if ($book['ISBN'] && strlen($book['ISBN'])>3):
					echo htmlspecialchars($book['ISBN']);
				else:
					?>Not available!<?php
				endif;
				?>
			</li>
			<li>Phone: 
			<?php if ($book['CONTACTNO']): ?>
				<?php echo htmlspecialchars($book['CONTACTNO']); ?>
			<?php else: ?>
				Not available!
			<?php endif; ?>
			</li>
			<li>Price: 
			<?php if ($book['PRICE']!='0.00'): ?>
				<?php echo htmlspecialchars($book['PRICE']); ?>
			<?php else: ?>
				For Exchange
			<?php endif; ?>
			</li>
			<li>Subject: 
			<?php if ($book['SUBJECT']): ?>
				<?php echo htmlspecialchars($book['SUBJECT']); ?>
			<?php else: ?>
				Not available!
			<?php endif; ?>
			</li>
		</ul><?php
		
		?></td><td>
		<a href="addbook.php?id=<?php echo $book['ID']; ?>">Edit Advertisement</a>
		<a href="book_details.php?id=<?php echo $book['ID']; ?>">Advertisement Details</a>
		<a href="processbook.php?id=<?php echo $book['ID']; ?>&amp;delete=1">Delete Advertisement</a>
		
		</td></tr><?php
	endforeach;

	?></tbody></table><?php
else:
	?>Unable to get sellerid for you.<?php
endif;
?>
<!--------------------------------------->

<?php 
	require_once('includes/template/foot.php');

clear_validation_messages();

exit();
?>