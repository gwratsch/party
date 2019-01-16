<?php
   ob_start();
include_once 'settings.php';
$path = (new configsettings)->pathname();
$updateUserInfo = FALSE;
$pagetemplate = new pagetemplate();
   
?>
<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php 
        echo $pagetemplate->head();
        ?>
        <title>partyList</title>
    </head>
    <body>
        <?php 
        $pagetemplate->title="Party";
        echo $pagetemplate->header();
        echo $pagetemplate->navigation();
        ?>
        <div class="row">
        <section class="container p-3">
              <h2>Enter Username and Password</h2> 
              <div class = "container form-signin">

                 <?php
                    $msg = "";

                    if (isset($_POST["login"]) && !empty($_POST["email"]) 
                       && !empty($_POST["password"])) {

                       echo (new login)->userlogin();
                       if(array_key_exists('valid', $_SESSION) && $_SESSION['valid']==TRUE){header("location: user.php");}
                    }
                 ?>
              </div> <!-- /container -->

              <div class = "container">

                 <form class = "form-signin" role = "form" 
                    action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); 
                    ?>" method = "post">
                    <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
                    <input type = "email" class = "form-control  m-2 w-50" 
                       name = "email" placeholder = "Emailadres" 
                       required autofocus></br>
                    <input type = "password" class = "form-control  m-2 w-50"
                       name = "password" placeholder = "password" required>
                    <button class = "btn btn-lg btn-primary btn-block  m-2 w-50" type = "submit" 
                       name = "login">Login</button>
                 </form>
              </div> 
        </section>
        </div>
    </body>
</html>

