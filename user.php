<?php
include_once 'settings.php';
$user = new user();
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){
    $user->save();
}   
if(array_key_exists('userid', $_SESSION)){
    $user->userid['content']= $_SESSION['userid'];
    $user->updateInfo = TRUE;
    $user->edit();
}
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
            <h2><?php echo t("User settings");?></h2>
            <?php  
                echo (new formview)->buildHtmlForm($user);
            ?>
        </section>
        </div>
    </body>
</html>
