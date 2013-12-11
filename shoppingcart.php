<?

	require_once('includes/lib.php');
	requireLogin();
	if($_REQUEST['command']=='delete' && $_REQUEST['pid']>0){
		remove_product($_REQUEST['pid']);
	}
	else if($_REQUEST['command']=='clear'){
		unset($_SESSION['cart']);
	}
	else if($_REQUEST['command']=='update'){
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=intval($_REQUEST['product'.$pid]);
			if($q>0 && $q<=999){
				$_SESSION['cart'][$i]['qty']=$q;
			}
			else{
				$msg='Some products not updated!, quantity must be a number between 1 and 999';
			}
		}
	}


$title='Shopping Cart';
$article_class='shopping_cart';
include('includes/template/head.php');
?>

<script language="javascript">
	function del(pid,name){
		if(confirm('Remove "'+name+'" from cart?')){
			document.form1.pid.value=pid;
			document.form1.command.value='delete';
			document.form1.submit();
		}
	}
	function clear_cart(){
		if(confirm('This will empty your shopping cart, continue?')){
			document.form1.command.value='clear';
			document.form1.submit();
		}
	}
	function update_cart(){
		document.form1.command.value='update';
		document.form1.submit();
	}


</script>
<?php //var_dump($_SESSION)?>
<form name="form1" method="post">
<input type="hidden" name="pid" />
<input type="hidden" name="command" />
	
    	<h1 align="center">Your Shopping Cart</h1>
    </div>
    	<div style="color:#F00"><?=$msg?></div>
    	<table  class="allbooks">
    	<?
			if(is_array($_SESSION['cart'])){
            	echo '<tr><th>Name</th><th>Cover</th><th>Price</th><th>Qty</th><th>Amount</th><th>Options</th><th>Pay</th></th>';
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
                                         //echo $pid.="qweqweqweqwe";
					$q=$_SESSION['cart'][$i]['qty'];
                                       // echo $q.="12312312312312312312";
					$book = getBook($pid);
					$pname=$book['TITLE'];//get_product_name($pid);
					if($q==0) continue;
			?>
            		<tr bgcolor="#FFFFFF"><td><?=$pname?></td>
                    <td><a class="seller" href="book_details.php?id=<?=$book['ID']; ?>">
		<?php print_book_image($book);?></a></td>
					<td>$ <?=$book['PRICE']?></td><!--get_price function provide in includes/function.php the parameter $con need to pass
					because get price function using mysqli query, if you want to use PDO mysql,make sure provide a connecttion to db in get price function-->
                    <?$price=$book['PRICE'];?>
					<td><input type="text" name="product<?=$pid?>" value="<?=$q?>" maxlength="3" size="2" /></td>                    
                    <td>$ <?=$book['PRICE']*$q?></td>
                    <td><a href="javascript:del(<?=$pid?>,'<?=$pname?>')">Remove</a></td>
                     <td>     </form>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="business" value="recieve@gmail.com"> <!--<?//=get_seller($pid,$con)?> need to use user email in user table-->
<input type="hidden" name="cmd" value="_xclick"> 
<input type="hidden" name="rm" value="2"> 
<input type="hidden" name="return" value="http://www.sqlview.com/payment/notify.php"> 
<input type="hidden" name="custom" value="myvalue"> 
<input type="hidden" name="item_name" value="<?=$pname?>"> 
<input type="hidden" name="amount" value="<?=$price?>"> 
<input type="hidden" name="currency_code" value="CAD"> 
<input type="hidden" name="undefined_quantity" value="<?=$q?>"> <!-- there are a little bug in pass quanlity to paypal-->
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="Buy Now">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"> 
</form> </td>
                </tr>
            <?					
				}
			?>
			</table>
				<div class="right">Order Total: $<?=get_order_total()?></div>
				<br />
				<input type="button" value="Clear Cart" onclick="clear_cart()"><input type="button" value="Update Cart" onclick="update_cart()">
				<!--<input type="button" value="Place Order" onclick="window.location='billing.php'">-->
				<a href="processorder.php"><img alt="Checkout" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"></a>
			<?
            }
			else{
				echo "<tr bgColor='#FFFFFF'><td>There are no items in your shopping cart!</td>";
			}
		?>
        
    </div>
</form>
<?php
include('includes/template/foot.php');
?>
