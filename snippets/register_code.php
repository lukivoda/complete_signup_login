<?php
include "../init/config.php";


   $user->first_name = $user->cleanInput($_POST["first_name"]);
   $user->last_name = $user->cleanInput($_POST['last_name']);
   $user->username =  $user->cleanInput($_POST['username']);
   $user->email = $user->cleanInput($_POST['email']);
    $user->password = $user->cleanInput($_POST['password']);
    $confirm_password = $user->cleanInput($_POST['confirm_password']);

    if(!$user->printRegisterErrors($confirm_password)){
        $user->validation_code = md5($user->username.microtime());
        $user->password = password_hash($user->password,PASSWORD_BCRYPT,array('cost'=>12));
        if(!$user->save()){
            echo "Registration is not successful ";
        }else{
            $subject = "Activate Account";
            $body = "Please click the link to activate your account: <a href='http://localhost/complete_signup_login/activate.php?email=$user->email&code=$user->validation_code'>Click here</a>";
            if($user->sendEmail($user->email,$user->first_name,$subject,$body)) {
                $_SESSION['message'] = "<p class='bg-success text-center'>Please check your email for the activation link!</p>";
                echo "success";
            }else{
                echo "We are having problem sending you mail on ".$user->email;
            }
        }
    }else{
        echo $user->printRegisterErrors($confirm_password);
    }

