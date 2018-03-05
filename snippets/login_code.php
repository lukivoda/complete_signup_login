<?php
include "../init/config.php";

$user->email = $user->cleanInput($_POST['email']);
$user->password = $user->cleanInput($_POST['password']);
$remember =  isset($_POST['remember']);

if(!$user->printLoginErrors()){

    if ($user_obj = $user->emailDbOb()) {

        if(password_verify($user->password,$user_obj->password)){
            //calling the $user->login method to get the user object
            //store the user credentials in session
            $_SESSION['id'] = $user_obj->id;
            $_SESSION['username'] = $user_obj->username;
            $_SESSION['email'] = $user_obj->email;

            if($remember){
            setcookie("email",$user_obj->email,time()+6000,"/");
            }


            echo "success";
        }else {
            //If email & password don't match print error
            echo "Your credentials are not correct!";
        }


    } else {
        //If email & password don't match print error
        echo "Your credentials are not correct!";
    }

}else {
    echo $user->printLoginErrors();
}