<?php
include_once 'settings.php';
if(array_key_exists('submit', $_POST)){
    //include_once 'includes/user.class.php';
    $user = new user();
    $user->save();
}    
?>
<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>partyList</title>
    </head>
    <body>
        <header><h1><?php t("Wishlist");?></h1></header>
        <nav></nav>
        <section>
            <form action="user.php" method="post">
                <input type="hidden" name="userid" value="">
                <label><?php t("firstname");?></label>:<input type="text" name="firstname"><input type="checkbox" name="firstnameblock" ><br />
                <label><?php t("lastname");?></label>:<input type="text" name="lastname"><input type="checkbox" name="lastnameblock" ><br />
                <label><?php t("adres");?></label>:<input type="text" name="adres"><input type="checkbox" name="adresblock" checked><br />
                <label><?php t("city");?></label>:<input type="text" name="city"><input type="checkbox" name="cityblock" checked><br />
                <label><?php t("country");?></label>:<input type="text" name="country"><input type="checkbox" name="countryblock" checked><br />
                <label><?php t("email");?></label>:<input type="email" name="email"><input type="checkbox" name="emailblock" checked><br />
                <label><?php t("user_info");?></label>:<textarea name="user_info"></textarea><input type="checkbox" name="user_infoblock" checked><br />
                <input type="submit" name="submit">
            </form>
        </section>
    </body>
</html>
