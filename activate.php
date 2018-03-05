<?php include "includes/header.php";
include  "includes/nav.php";
?>

    <div class="jumbotron">
        <p class="bg-success text-center"><?php echo $user->activateUser()?"Your account has been activated,Please <a href='login.php'>login</a>":"Your account is not activated.Please try to <a href='register.php'>register </a>again" ?></p>

    </div>



<?php  include "includes/footer.php" ?>