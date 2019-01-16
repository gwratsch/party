<?php
include_once 'settings.php';
$pagetemplate = new pagetemplate();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
        <?php 
        echo $pagetemplate->head();
        ?>
    <title>Party lists</title>
</head>
<body id="bodyinstall">
        <?php 
        $pagetemplate->title="Update scripts";
        echo $pagetemplate->header();
        echo $pagetemplate->navigation();
        ?>
    <section class="container">
        <?php
         echo '<h2>'.t("Database Update").'</h2>';
        (new DB_install)->installdb();
        echo '<br /><a href="/user.php">'.t('Naar user page').'</a>';
        ?>
    </section>
</body>
</html>
