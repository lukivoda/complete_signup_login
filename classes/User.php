<?php

//require_once "vendor/autoload.php";

namespace App;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;


class User extends Main
{


    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $validation_code;
    public $email;
    public $password;
    public static $key ="id";
    public static  $table ="users";


    public function emailDbOb(){
        $db = Db::getConnection();
        $sql = "SELECT * FROM users WHERE email = :email and active = :active ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":active", '1');
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_OBJ);
        return $statement->fetch();

    }


    public function emailExists(){

        $db = Db::getConnection();
        $sql = "SELECT id FROM users WHERE email = :email  ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $this->email);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }



    }

    public function paramsCheckDb($email,$validation_code){

        $db = Db::getConnection();
        $sql = "SELECT id FROM users WHERE email = :email and validation_code = :validation_code ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":validation_code", $validation_code);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }



    }


    public function updateActive($email){

        $db = Db::getConnection();
        $sql = "UPDATE users SET active = :active,validation_code = :validation_code WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":validation_code", '0');
        $statement->bindValue(":active",'1');
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }



    }


    public function usernameExists(){

        $db = Db::getConnection();
        $sql = "SELECT id FROM users WHERE username = :username  ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":username", $this->username);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }



    }





    public function token_generator(){

        //The mt_rand() function generates a random integer using the Mersenne Twister algorithm.
        //true means bigger number
        $token = $_SESSION['token']=md5(uniqid(mt_rand(),true));

        return $token;
    }


    //cleaning inputs
    public function cleanInput($input) {

        //Convert the predefined characters to HTML entities:
        $input = htmlspecialchars($input);

        //strip whitespace from the beginning and end of a string.
        $input = trim($input);

        //remove the backslash in the string
        $input = stripcslashes($input);

        return $input;

    }



    public function printRegisterErrors($confirm_password){

        $min = 3;
        $max = 20;
        $errors = [];
        $message ='';

        if(empty($this->first_name) || empty($this->last_name) ||empty($this->username) || empty($this->email) || empty($this->password) || empty($confirm_password)){
            $errors[] = 'All fields must be filled!';
        }else{

     if(strlen($this->first_name)<$min){
        $errors[] = "Your first name can not be less than $min characters";
        }

        if(strlen($this->first_name)>$max){
                $errors[] = "Your first name can not be more than $max characters";
            }

        if(strlen($this->last_name)<$min){
            $errors[] = "Your last name can not be less than $min characters";
        }

            if(strlen($this->last_name)>$max){
                $errors[] = "Your last name can not be more than $max characters";
            }

            if(strlen($this->username)<$min){
                $errors[] = "Your username can not be less than $min characters";
            }

            if(strlen($this->username)>$max){
                $errors[] = "Your username can not be more than $max characters";
            }

            if($this->usernameExists()){
                $errors[] = "Username you've entered already exists in our database";
            }



            if($this->emailExists()){
                $errors[] = "Your email already exists in our database";
            }

            if(!preg_match("#.*^(?=.{6,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#",$_POST['password'])){
                $errors[] = "Your password should be at least 6 characters long and include one capital letter and one number";
            }


            if($this->password !== $confirm_password){
                $errors[] = "Your password do not match";
            }



    }



         if(!empty($errors)){
            foreach($errors as $error){
               $message .= "<p>$error</p>";
           }
           return $message;
         }else{
            return false;
         }


    }


    public function printLoginErrors(){

        if(empty($this->email) || empty($this->password)){
            echo  'All fields must be filled!';
        }else{
            return false;
        }


    }


    public function display_message(){
        if(isset($_SESSION['message'])){
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }


    }

    public function sendEmail($email,$first_name,$subject,$body){

        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

        $mail->IsSMTP(); // telling the class to use SMTP


        //$mail->Host       = "mail.gmail.com"; // SMTP server
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.stackmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;   // set the SMTP port for the GMAIL server
        $mail->SMTPKeepAlive = true;
        $mail->Mailer = "smtp";
        $mail->Username   = "**********@stevanris.info";  // GMAIL username
        $mail->Password   = "********";            // GMAIL password
        $mail->AddAddress($email, $first_name);
        $mail->SetFrom('stevanristov@stevanris.info', 'Stevan');
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subject;
        $mail->Body = $body;

        if(!$mail->Send()){
            return false;
        }else{
            return true;
        }


    }



    public function activateUser(){

        if($_SERVER['REQUEST_METHOD'] == "GET"){
            if(isset($_GET['email'])  && isset($_GET['code'])){
                $email = $this->cleanInput($_GET['email']);
                $validation_code =  $this->cleanInput($_GET['code']);
                //checking if we have $email and $validation_code combination (while the user is not active) in the database
                if($this->paramsCheckDb($email,$validation_code)){
                 if($this->updateActive($email)){
                     return true;
                 }else{
                     return false;
                 }
                }else{
                    return false;
                }

            }else{
                return false;

            }

        }else{
            return false;

        }

    }



    public function loggedIn(){
        if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
            return true;
        }else {
            return false;
        }
    }


    public function changeValidationCode($validation_code,$email){

        $db = Db::getConnection();
        $sql = "UPDATE users SET validation_code = :validation_code WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":validation_code",$validation_code);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }


    }


    public function validateCodeAndEmail($email,$validation_code){

        $db = Db::getConnection();
        $sql = "SELECT id FROM users WHERE validation_code = :validation_code AND email =:email  ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":validation_code", $validation_code);
        $statement->bindValue(":email", $email);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }

    }


    public function recoverPassword($email){

        if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']){
            $this->email = $email;
          if($this->emailExists()){
              $subject = "Please reset your password!";
              $user_obj =$this->emailDbOb();
              $first_name = $user_obj->first_name;
              $validation_code = md5($email.microtime());
              $body = "Hi $first_name <br> Here is your password reset code: $validation_code".".Click here to reset your password: http://localhost/complete_signup_login/code.php?email=".$this->email."&code=".$validation_code;
              if($this->changeValidationCode($validation_code,$this->email)){
                  if($this->sendEmail($this->email,$first_name,$subject,$body)){
                      setcookie("temp_access_code",$validation_code,time()+600,'/');
                      echo "success";
                  }else{
                      echo "Email is not sent.Try again!";
                  }
              }else{
                  echo "Something is wrong with the validation code";
              }

          }else{
              echo "Email you've entered doesn't exist in our database";
          }
        }else{
            $this->redirectTo('index.php');
        }

    }


    public function validateCode() {

        if(isset($_COOKIE['temp_access_code'])){
            if(isset($_POST['email_q']) && isset($_POST['validation_code_q'])){
                $this->email = $this->cleanInput($_POST['email_q']);
                if(isset($_POST['validation_code_i'])) {
                    $this->validation_code = $this->cleanInput($_POST['validation_code_i']);
                    if($this->validateCodeAndEmail($this->email,$this->validation_code)){
                       echo "success";
                    }else{
                        echo "Validation code and email combination are not correct!";
                    }
                }else{
                    echo "Validation code you've entered is not correct!";
                }
            }else{
               echo "Your validation code  is not correct!";

            }

        }else{
            echo  "Your validation cookie has expired!";

        }

    }


    public function passwordReset(){

        if(isset($_SESSION['token']) && isset($_POST['token']) && $_POST['token'] == $_SESSION['token']) {
            if (isset($_COOKIE['temp_access_code'])) {
                if (isset($_POST['email_q']) && isset($_POST['validation_code_q'])) {
                    if(isset($_POST['password']) && isset($_POST['confirm_password']) && (!empty($_POST['password']) || !empty($_POST['confirm_password']) )){

                        if(preg_match("#.*^(?=.{6,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#",$_POST['password'])){
                            if($_POST['password'] === $_POST['confirm_password']) {
                                $this->email = $this->cleanInput($_POST['email_q']);
                                $this->password = $this->cleanInput($_POST['password']);
                                $this->password = password_hash($this->password,PASSWORD_BCRYPT,array('cost'=>12));
                                if($this->updatePassword()){
                                    echo "success";
                                }else{
                                    echo "Password could not be updated!";
                                }

                            }else{
                                echo "Password fields are not the same!";
                            }

                        }else{
                            echo "Your password should be at least 6 characters long and include one capital letter and one number";
                        }

                    }else{
                    echo "All fields should be filled!";
                    }
                } else {
                    echo "Your validation code  is not correct!";
                }

            } else {
                echo "Your validation cookie has expired!";
            }

        }else {
           echo "Token is not correct";
        }
    }



    public function updatePassword(){

        $db = Db::getConnection();
        $sql = "UPDATE users SET password = :password WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password",$this->password);
        $statement->execute();

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }

}



    public function redirectTo($page){
        header("Location:$page".".php");
    }



}