<?
       require_once('db.php');
	function get_product_name($pid,$con){
              // $con=mysqli_connect("mysql17.000webhost.com","a4638665_zyx","zyx868656","a4638665_311");
              // $con=get_con();
		$result=mysqli_query($con,"SELECT Title FROM books WHERE ID='$pid' ");
		$row=mysqli_fetch_array($result);
		return $row['Title'];
	}
	function get_price($pid,$con){
               // $con=mysqli_connect("mysql17.000webhost.com","a4638665_zyx","zyx868656","a4638665_311");
		//$con=get_con();
                $result=mysqli_query($con,"select Price from books where ID='$pid'");
		$row=mysqli_fetch_array($result);
		return $row['Price'];
	}
        function get_seller($pid,$con)
        {
          $result=mysqli_query($con,"select Email from User where ID='$pid'");
		$row=mysqli_fetch_array($result);
		return $row['Email'];
        }
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
				break;
			}
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
	}
	function get_order_total($con){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid,$con);
			$sum+=$price*$q;
		}
		return $sum;
	}
	function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			if(product_exists($pid)) return;
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
		}
		else{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
	}
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}

?>
