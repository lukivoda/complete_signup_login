<?php include "includes/header.php";
include  "includes/nav.php";
?>

    <div class="jumbotron">
        <h1 class="text-center"><?php if($user->loggedIn()) {
                echo "logged in";
            }else{
            $user->redirectTo('index');
            }

            ?>

        </h1>
    </div>



<?php  include "includes/footer.php" ?>