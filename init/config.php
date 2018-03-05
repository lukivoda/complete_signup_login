<?php  ob_start();
session_start();
define('ROOT',"C:\wamp64\www\complete_signup_login");
require  ROOT."/autoload_classes.php" ;




use App\User;
$user = new User();




?>