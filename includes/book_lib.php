<?php
/*
a library of book-related functions

*/


function newBook($title,$sellerid,$author,$price,$subject,$description,$isbn,$image_url){
	return dbquery('INSERT INTO books(Title,SellerID,Author,Price,Subject,Description,isbn,image_url) VALUES (?,?,?,?,?,?,?,?)',
	$title,$sellerid,$author,
	$price,$subject,
	$description,$isbn,$image_url
	);
}

function getBookById($id)
{
	return getRowById($id,'books');
}


function getSellerFromBook($book)
{
	// if $book is likely a book id, load it from the database.
	if (is_numeric($book)):
		$book = getBookById($book);
	endif;
	$user = getUserByID($book['SELLERID']);
	return $user;
}

function getPriceFromBook($book)
{
	// if $book is likely a book id, load it from the database.
	if (is_numeric($book)):
		$book = getBookById($book);
	endif;
	//$user = getUserByID($book['SELLERID']);
	return $book;
}

function print_book_image($book)
{
	?>		<img alt="Book Image" src="<?php			//need to make image accessible to book_detailes 
			echo (($book['IMAGE_URL'] && strlen($book['IMAGE_URL'])>3) 
			? htmlspecialchars($book['IMAGE_URL']) 
			: 'images/no_image.png'); 
			
			?>" />
			<?php
}
	
function displayAllAds()
{
	$num_columns = 5;
	$i = 0;
	?><table class="wide allbooks">
		<tbody><?php
	
	// loop through all the books.
	foreach (getAllBooks() as $book)
	{
		if ($i%$num_columns==0):
			?><tr><?php
		endif;
		
		$details_url='book_details.php?id='.$book['ID'];
		?><td>
		<h3><?php echo htmlspecialchars($book['TITLE']); ?></h3> 
<a class="seller" href="<?php echo $details_url; ?>">
		<?php
	print_book_image($book);
?></a>
		
		<br/><strong>
		<?php
		$seller=getSellerFromBook($book);
	
		if ($seller):
			?>by: <?php
			echo htmlspecialchars($seller['NAME']);
		else:
			?>No seller<?php
		endif;
		
		?></strong></br>
		<span class="seller italic">
		<?php
		$seller=getPriceFromBook($book);
	
		if ($seller['PRICE']!='0.00'):
			?>CAD: $<?php
			echo htmlspecialchars($seller['PRICE']);
		else:
			?>For Exchange<?php
		endif;
		
		?></span></a></td><?php
		$i++;			
		if ($i%$num_columns==0):
			?></tr><?php
		endif;
	}
	
	// if the last tr wasn't ended, end it now.
	if (!($i%$num_columns==0)):
		?></tr><?php
	endif;
	
	?></tbody></table><?php
}