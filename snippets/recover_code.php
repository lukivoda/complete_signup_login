<?php
include "../init/config.php";

if(!empty($_POST['email'])) {
    $email = $user->cleanInput($_POST['email']);
    $user->recoverPassword($email);


}else{
    echo "Email field could not be empty";
}