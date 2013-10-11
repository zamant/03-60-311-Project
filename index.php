<!DOCTYPE html>
<html>
<head> 
	<?php
		require_once('lib.php');
		clear_session('remailaddress','rpassword','rcpassword','rusername','lpassword','lusername','error','login','registration','rpostcode');
		$_SESSION['GET'] = $_GET;
		if (array_key_exists('postnum',$_POST) && array_key_exists('topicid',$_POST)){
			if (is_loggedin()){
				deletePost($_POST['topicid'],$_POST['postnum']);
				if ($_POST['postnum'] == 1){
					header('Location: '.url_to_redirect_to('index.php'));
				}
			}
		}
		
	?>
	<title>
		334 Project - Summer 2013
	</title>
</head>
<body>
<header>
	<?php
		require_once("template/header.php");
	?>
</header>
<nav>
	<?php
		require_once("template/nav.php");
	?>
</nav>
<section>
	<article>
	<header>&nbsp;</header>
	<?php
	if (array_key_exists('page',$_GET) && is_numeric($_GET['page'])){
		$page = intval($_GET['page'])-1;
	}else{
		$page = 0;
	}
	if (array_key_exists('max',$_GET) && is_numeric($_GET['max'])){
		$max = intval($_GET['max']);
	}else{
		$max = 10;
	}/*
	if(isset($_GET['action']) && $_GET['action'] == 'post'){
			//Insert Code for Post
			if (is_loggedin()){
				createInput("processpost.php","reply","title");
			}else{
				echo 'You are not logged in';
			}
	}elseif(isset($_GET["topic"])){
		if (is_numeric($_GET["topic"])){
			if (getTopic($_GET["topic"])){
				$page = 0;
				$max = intval(getNumPosts($_GET["topic"]));
				echo '<header>THREAD: '.getTopic($_GET["topic"])['Title'].'</header>';
				$xmlt = new DOMDocument;
				$xmlt->loadXML(getAllPostsXML($_GET["topic"],$page,$max));
				$xsl = new DOMDocument;
				$xsl->load('main.xsl');
				$proct = new XSLTProcessor();
				$proct->registerPHPFunctions();
				if (is_loggedin()){
					$proct->setParameter(null, 'sesuser', $_SESSION['user']);
				}else{
					$proct->setParameter(null, 'sesuser', '');
				}
				$proct->setParameter(null, 'call', 'thread');
				if(isset($_GET['action']) && $_GET['action'] == 'edit'){
					$proct->setParameter(null, 'action', 'edit');
				}else{
					$proct->setParameter(null, 'action', '');
				}
				$proct->importStylesheet($xsl);
				echo $proct->transformToXML($xmlt);
				if (is_loggedin()){
					echo '<h2><a href="?action=reply&topic='.$_GET["topic"].'">Reply</a></h2><br />';
				}
				if(isset($_GET['action'])){
					if($_GET['action'] == 'reply'){
						if (is_loggedin()){
							createInput("processpost.php","reply");
						}else{
							echo '<p>You must be logged in to post.</p>';
						}
					}
				}
			}else{
				//404, topic not found
				echo 'Error: Topic '.$_GET["topic"].' not found';
			}
		}else{
			//Invalid Topic ID Error
			echo 'Error: Topic ID must be a number';
		}
	}else{
			$page = 0;
			$max = intval(getNumTopics());
			echo '<table>';
			$topicdata = getAllTopics($page,$max);
			foreach ($topicdata as $i => $j){
				echo '<tr>';
				echo '<td class="thread">';
				echo '<a href="?topic='.$i.'"> '.$j['Title'].'</a><br />';
				echo 'Author: '.$j['User'];
				echo '</td><td class ="thread2">';
				
				echo '<br />';
				$replies = getNumPosts($i) - 1;
				echo 'Replies: '.$replies.'<br />Last Post at: '.$j['Timestamp'];
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			if (is_loggedin()){
				echo '<article>';
				echo '<br /><h2><a href="?action=post">Create New Topic</a></h2></article>';
			}
	}*/
	?>
    </article>
</section>
<footer>
	<?php
		require_once("template/footer.php");
	?>
</footer>
</body>
</html>