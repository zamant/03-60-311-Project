<?php
	require_once('includes/lib.php');
	
	if (!array_key_exists('cart',$_SESSION)){
		header('Location: '.url_to_redirect_to('index.php'));
	}
	//testDBInfoOutputs($_SESSION['cart']);
	$title='Billing';
	require_once('includes/template/head.php');

	$cart = array();
	$cart['business']="recieve@gmail.com";
	$cart['cmd']="_cart";
	$cart['upload']="1";
	$cart['currency_code']="CAD";
	$count = 0;
	foreach($_SESSION['cart'] as $item){
	$book = getBook($item['productid']);
		for ($i = 0;$i<$item['qty'];$i++){
			$count++;
			$cart['item_name_'.$count]=$book['TITLE'];
			$cart['amount_'.$count]=$book['PRICE'];
		}
	}
	
	echo '<table class="allbooks">';
	echo '<tr><th>Name</th><th>Price</th></tr>';
	foreach ($cart as $a => $b) {
		echo '<tr>';
		echo '<td>'.$a.'</td>';
		echo '<td>'.$b.'</td>';
		echo '</tr>';
	}
	echo "</table>";
/*
	?>
<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' name='frm'>
<?php
foreach ($cart as $a => $b) {
echo "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>";
}
?>
<noscript><input type="submit" value="Click here if you are not redirected."/></noscript>
</form>
<script language="JavaScript">
document.frm.submit();
</script>
<?php*/
require_once('includes/template/foot.php');
?>