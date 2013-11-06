<?php
//database connection info
include "db.php";
if(isset($_POST['submit']))
{
 $query="SELECT * FROM books WHERE ID >= 1";
 if(isset($_POST['title']) && $_POST['title']!="")
     $title=$_POST['title'];
     $query.=" && Title='".$title."'";
     echo $title;
 if(isset($_POST['author']) && $_POST['author']!="")
     $author=$_POST['author'];
     $query.=" && Author='".$author."'";
     echo $author;    
 if(isset($_POST['ISBN']) && $_POST['ISBN']!="")
     $ISBN=$_POST['ISBN'];
     $query.=" && ISBN='".$ISBN."'";
     echo $ISBN;
 /*if(isset($_POST['edition']) && $_POST['edition']!="")
     $edition=$_POST['edition'];
     $query.=" && edition='".$edition."'";
     echo $edition;*/
//$method=$_POST['method'];
//echo $method;
//SQL statment to select what a search
}
else $query="SELECT * FROM books ";	  
// run sql statement
$result = mysqli_query($con,$query);
//$result = mysqli_query($con,"SELECT * FROM books");
//find out how many matches
$number=mysqli_num_rows($result);

$pageTitle="Search Result";
//include "header.php";
print <<<HERE
<h2>Search Result</h2>
<h3>$number result found searching for "$search"</h3>
<table cellpadding="15">
HERE;

//loop through result and get variables

while ($row=mysqli_fetch_array($result)){
//assign variable name match database 
$title=$row["Title"];
//assign a variable name to the image name
$image=$row["image"];
$filename="images/$image";


//check to see if a file is there for the picture
if(!file_exists($filename)){
   $filename="images/blank.gif";//isit does not exist , set to blank
}print " <tr>
<td align=\"center\"><img src=\"$filename\" /></td>   
<td><strong>$Title</strong><br />
     <strong>$Author</strong><br />
     <strong>$ISBN</strong><br />
Phone: $phone <br />
Seller: $user <br />
Email: <a href=\"mailto: $email \">$email</a></td></tr>";
}
print "</html>";

?>

