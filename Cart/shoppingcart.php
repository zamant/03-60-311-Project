<?
	require_once('includes/db.php');
	require_once('includes/functions.php');
	
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
				$msg='Some proudcts not updated!, quantity must be a number between 1 and 999';
			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart</title>
<script language="javascript">
	function del(pid){
		if(confirm('Do you really mean to delete this item')){
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
</head>

<body>
<form name="form1" method="post">
<input type="hidden" name="pid" />
<input type="hidden" name="command" />
	<div style="margin:0px auto; width:600px;" >
    <div style="padding-bottom:10px">
    	<h1 align="center">Your Shopping Cart</h1>
    <input type="button" value="Continue Shopping" onclick="window.location='products.php'" />
    </div>
    	<div style="color:#F00"><?=$msg?></div>
    	<table border="0" cellpadding="5px" cellspacing="1px" style="font-family:Verdana, Geneva, sans-serif; font-size:11px; background-color:#E1E1E1" width="100%">
    	<?
			if(is_array($_SESSION['cart'])){
            	echo '<tr bgcolor="#FFFFFF" style="font-weight:bold"><td>Serial</td><td>Name</td><td>Price</td><td>Qty</td><td>Amount</td><td>Options</td><td>Pay</td></tr>';
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
                                         //echo $pid.="qweqweqweqwe";
					$q=$_SESSION['cart'][$i]['qty'];
                                       // echo $q.="12312312312312312312";
					$pname=get_product_name($pid,$con);
					if($q==0) continue;
			?>
            		<tr bgcolor="#FFFFFF"><td><?=$i+1?></td><td><?=$pname?></td>
                    
					<td>$ <?=get_price($pid,$con)?></td><!--get_price function provide in includes/function.php the parameter $con need to pass
					because get price function using mysqli query, if you want to use PDO mysql,make sure provide a connecttion to db in get price function-->
                    <?$price=get_price($pid,$con);?>
					<td><input type="text" name="product<?=$pid?>" value="<?=$q?>" maxlength="3" size="2" /></td>                    
                    <td>$ <?=get_price($pid,$con)*$q?></td>
                    <td><a href="javascript:del(<?=$pid?>)">Remove</a></td>
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
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"> 
</form> </td>
                </tr>
            <?					
				}
			?>
				<tr><td><b>Order Total: $<?=get_order_total($con)?></b></td><td colspan="5" align="right"><input type="button" value="Clear Cart" onclick="clear_cart()"><input type="button" value="Update Cart" onclick="update_cart()"><input type="button" value="Place Order" onclick="window.location='billing.php'"></td></tr>
			<?
            }
			else{
				echo "<tr bgColor='#FFFFFF'><td>There are no items in your shopping cart!</td>";
			}
		?>
        </table>
    </div>
</form>
</body>
</html>
