<?
	include("includes/db.php");
	include("includes/functions.php");
	
	if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:shoppingcart.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Products</title>
<script language="javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	}
</script>
</head>


<body>
<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>
<div align="center">
	<h1 align="center">Products</h1>
	<table border="0" cellpadding="2px" width="600px">
		<?
			$result = mysqli_query($con,"SELECT * FROM books");
			while($row=mysqli_fetch_array($result)){
		?>
    	<tr>
        	<td><img src="<?=$row['image_url']?>" /></td>
            <td>   	<b><?=$row['Title']?></b><br />
            		<?=$row['Description']?><br />
                    Price:<big style="color:green">
                    	$<?=$row['Price']?></big><br /><br />
                    <input type="button" value="Add to Cart" onclick="addtocart(<?=$row['ID']?>)" />
			</td>
		</tr>
        <tr><td colspan="2"><hr size="1" /></td>
        <? } ?>
    </table>
</div>
</body>
</html>
