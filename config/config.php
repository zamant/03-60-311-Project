<?php
require_once(dirname(__FILE__) . '/../member/user_check.php');
require_once(dirname(__FILE__) . '/../member/out_put_member.php');
require_once(dirname(__FILE__) . '/../member/password.php');
require_once(dirname(__FILE__) . '/../member/food_fns.php');
require_once(dirname(__FILE__) . '/../member/order_fns.php'); 
require_once(dirname(__FILE__) . '/../visitor/output_visitor.php');
require_once(dirname(__FILE__) . '/../visitor/data_valid_fns.php'); 
require_once(dirname(__FILE__) . '/../config/newdb.php');
/*require_once(dirname(__FILE__) . '/../config/db_fns.php');
require_once(dirname(__FILE__) . '/../admin/out_put_admin.php');
require_once(dirname(__FILE__) . '/../admin/admin_fns.php');
require_once(dirname(__FILE__) . '/../admin/user_auth_fns.php');
require_once(dirname(__FILE__) . '/../admin/menu.php'); */
$DBNAME="60311";
$DBUSER="root";
$DBPASS="zaman040377";
/*function pg_connect(){
     global $DBNAME, $DBUSER, $DBPASS;
  $db = new PDO("mysql:host=localhost;dbname=$DBNAME", $DBUSER, $DBPASS);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
  $db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
	return $db;
  }*/
/*function db_connect() {
     global $DBNAME, $DBUSER, $DBPASS;
    //$dbconn = pg_connect("host=localhost dbname=group07db user=group07 password=VZmh_KtW&bjf");
   //$dbconn = pg_connect("host=localhost dbname="$DBNAME" user="$DBUSER" password="$DBPASS"");
	$dbconn = pg_connect("host=localhost dbname='".$DBNAME."' user='".$DBUSER."' password='".$DBPASS."'");
   //$dbconn = pg_connect("host=localhost dbname="$DBNAME" user="$DBUSER" password="$DBPASS"");
   //'".$DBNAME."'
   if (!$dbconn) {
     throw new Exception('Could not connect to database server');
   } else {
     return $dbconn;
   }
}*/
$db = db_connect();

//var_dump($db->query("SELECT * FROM user")->fetchall());

/*function db_result_to_array($result) {
   $res_array = array();
   $numrows = pg_numrows($result);
   for ($count=0; $count < $numrows; $count++) {
     $row = pg_fetch_array($result, $count);
     $res_array[$count] = $row;
   }

   return $res_array;
}*/
?>
