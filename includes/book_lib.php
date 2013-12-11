<?php
/*
a library of book-related functions

*/


function newBook($title,$sellerid,$author,$price,$subject,$description,$isbn,$contactno,$image_url){
	$price=floatval($price);
	$sellerid=intval($sellerid);
	return dbquery('INSERT INTO books(Title,SellerID,Author,Price,Subject,Description,isbn,image_url,contactno) VALUES (?,?,?,?,?,?,?,?,?)',
	$title,$sellerid,$author,
	$price,$subject,
	$description,$isbn,$image_url,$contactno
	);
}

function updateBook($id,$title,$sellerid,$author,$price,$subject,$description,$isbn,$contactno,$image_url){
	$id=intval($id);
	$price=floatval($price);
	$sellerid=intval($sellerid);
	return dbquery('update books set Title=?, SellerID=?,Author=?,Price=?,Subject=?,Description=?,isbn=?,image_url=?,contactno=? where id=?',
	$title,$sellerid,$author,
	$price,$subject,
	$description,$isbn,$image_url,$contactno,$id
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
function displayAds($adsToDisplay){
	$num_columns = 5;
	$i = 0;
	?><table class="wide allbooks">
		<tbody><?php
	
	// loop through all the books.
	foreach ($adsToDisplay as $book)
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
		
		?></strong><br />
		<span class="seller italic">
		<?php
		$seller=getPriceFromBook($book);
	
		if ($seller['PRICE']!='0.00'){
			?>CAD: $<?php
			echo htmlspecialchars($seller['PRICE']);
			echo '<br />';
			if (is_loggedin()){
				if (!isInCart($book['ID'])){
					addtocartButton("index.php?",$book['ID']);
				}else{
					echo "[In cart]";
				}
			}
		}else{
			?>For Exchange<?php
		}
		
		?></span></td><?php
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
function displayAllAds($url,$page=1,$max=25)
{
	$numbooks = getNumBooks();
	$maxpage = ceil($numbooks/$max);
	if ($page < 1){
		header('Location: '.url_to_redirect_to($url));
	}
	if (($page-1)*$max > $numbooks){
		header('Location: '.url_to_redirect_to($url).'?page='.$maxpage);
	}
	if ($page > 1){
		echo '<div class="left">';
		echo '<a href="'.$url.'?page=1">&lt;&lt;First page </a>';
		echo '<a href="'.$url.'?page='.($page-1).'">&lt;Previous page </a>';
		echo '</div>';
	}
	if ($page < $maxpage){
		echo '<div class="right">';
		echo '<a href="'.$url.'?page='.($page+1).'"> Next page&gt;</a>';
		echo '<a href="'.$url.'?page='.($maxpage).'"> Last page&gt;&gt;</a>';
		echo '</div>';
	}
	displayAds(getAllBooks($page,$max));
	?>
	<div class="right">
	Ads per page:
	<select id="pagemax" onchange="changePageMax(this,true)">
		<option value="5">5</option>
		<option value="10">10</option>
		<option value="25">25</option>
		<option value="50">50</option>
		<option value="100">100</option>
	</select>
	</div>
	<?php
}

function getAllBooks($page=1,$max=25,$orderby = 'ID',$desc = true){
	//As above with page and max
	$dir = "";
	if ($desc){
		$dir = 'DESC';
	}
	$result = dbquery('SELECT * FROM books ORDER BY '.$orderby.' '.$dir.' LIMIT ?,?',($page-1)*$max,$max);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$output = array();
	while ($row && ($max-- > 0)){
		$output[$row['ID']] =$row;
		$row = $result->fetch(PDO::FETCH_ASSOC);
	}
	return $output;
}

function getAllBooksBySeller($page=1,$max=25,$sellerid,$orderby = 'ID'){
	$result = dbquery('SELECT * FROM books WHERE SELLERID = ? ORDER BY '.$orderby.' LIMIT ?,?',$sellerid,($page-1)*$max,$max);
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$output = array();
	while ($row && ($max-- > 0)){
		$output[$row['ID']] = $row;
		$row = $result->fetch(PDO::FETCH_ASSOC);
	}
	return $output;
}

function getBook($id){
	return getRowById($id,'books');		
}
function getNumBooks(){
	$result = dbquery('SELECT COUNT(*) FROM books');
	return $result->fetch()[0];
}

function deleteBook($book_id){
	$book = getBook($book_id);
	$currentuser = currentUser();
	if (is_int($currentuser)){
		return 0;
	}
	if ($currentuser['ID'] == $book['SELLERID'] || $currentuser['LEVEL'] == 1){
		$result = dbquery('DELETE FROM books WHERE ID = ?',$book_id);
		if (is_int($result) && $result == 0){
			return 0;
		}else{
			return 1;
		}
	}
	return 0;
}
?>