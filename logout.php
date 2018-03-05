<?php
include "init/config.php";

session_destroy();

if(isset($_COOKIE['email'])){
    unset($_COOKIE['email']);
    setcookie('email','',time()-6000,'/');
}

$user->redirectTo('index');