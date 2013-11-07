<?php
	require_once('includes/lib.php');

	clear_session('remailaddress','rpassword','rcpassword','rusername','lpassword','lusername','error','login','registration','rpostcode');
	$_SESSION['GET'] = $_GET;
	if (array_key_exists('del',$_GET) && is_numeric($_GET['del'])){
		deleteBook($_GET['del']);
		header('Location: '.url_to_redirect_to('index.php'));
	}
	$title='All Advertised Books';
	
	include('includes/template/head.php');
	
	if (array_key_exists('page',$_GET) && is_numeric($_GET['page'])){
		$page = intval($_GET['page']);
	}else{
		$page = 1;
	}
	if (array_key_exists('pagemax',$_COOKIE)){
		$max = intval($_COOKIE['pagemax']);
	}else{
		$max = 25;
	}
	$numbooks = getNumBooks();
	$maxpage = ceil($numbooks/$max);
	if ($page < 1){
		header('Location: '.url_to_redirect_to('index.php'));
	}
	if (($page-1)*$max > $numbooks){
		header('Location: '.url_to_redirect_to('index.php').'?page='.$maxpage);
	}
	if ($page > 1){
		echo '<div class="left">';
		echo '<a href="index.php?page=1">&lt;&lt;First page </a>';
		echo '<a href="index.php?page='.($page-1).'">&lt;Previous page </a>';
		echo '</div>';
	}
	if ($page < $maxpage){
		echo '<div class="right">';
		echo '<a href="index.php?page='.($page+1).'"> Next page&gt;</a>';
		echo '<a href="index.php?page='.($maxpage).'"> Last page&gt;&gt;</a>';
		echo '</div>';
	}
	displayAllAds($page,$max);
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
	require_once("includes/template/foot.php");
?>
