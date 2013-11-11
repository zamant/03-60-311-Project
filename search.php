<?php
require_once("includes/lib.php");
$form_url = 'Searchform.php';
if (!array_key_exists('previous-page',$_SESSION)){
	$_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$_SESSION['searchterms'] = array();
	if (empty($_POST['title'])){
		$_SESSION['searchterms']['title'] = "a";
	}else{
		$_SESSION['searchterms']['title'] = "%".strtoupper($_POST['title'])."%";
	}
	if (empty($_POST['author'])){
		$_SESSION['searchterms']['author'] = "a";
	}else{
		$_SESSION['searchterms']['author'] = "%".strtoupper($_POST['author'])."%";
	}
	if (empty($_POST['ISBN'])){
		$_SESSION['searchterms']['ISBN'] = "a";
	}else{
		$_SESSION['searchterms']['ISBN'] = "%".strtoupper($_POST['ISBN'])."%";
	}
	if (empty($_POST['subject'])){
		$_SESSION['searchterms']['subject'] = "a";
	}else{
		$_SESSION['searchterms']['subject'] = "%".strtoupper($_POST['subject'])."%";
	}
	header('Location: '.url_to_redirect_to('search.php').'?page=1');
}
if (isset($_SESSION['searchterms'])){
	$title = "Search Results";
	include('includes/template/head.php');
	$searchresults = searchAllBooks($_SESSION['searchterms']['title'],$_SESSION['searchterms']['author'],$_SESSION['searchterms']['ISBN'],$_SESSION['searchterms']['subject'],$page,$max);
	$numbooks = getNumSearchResults($_SESSION['searchterms']['title'],$_SESSION['searchterms']['author'],$_SESSION['searchterms']['ISBN'],$_SESSION['searchterms']['subject']);
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
	displayAds($searchresults);
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
}else{
	header('Location: '.url_to_redirect_to('searchform.php'));
}
?>