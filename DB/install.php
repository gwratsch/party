<?php
include_once '../settings.php';
if(array_key_exists('submit', $_POST)){
    include_once 'DB_install.php';
    $formDBsettings=array();
    foreach ($_POST as $key => $value) {
        if($key == 'db_pw'){
            $formDBsettings[$key]=$value;
        }else{
            $formDBsettings[$key]=clean($value);
        }
    }
    install($formDBsettings);
    echo '<a href="/user.php">'.t('Naar user page').'</a>';
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party lists</title>
</head>
<body>
    <header><h1><?php echo t('Installation scripts');?></h1></header>
    <section>
        <form action='install.php' method="post">
            <label><?php echo t('Database user');?> : </label><input type='text' name='db_user'><br />
            <label><?php echo t('User password');?> : </label><input type='text' name='db_pw'><br />
            <label><?php echo t('Host');?> : </label><input type='text' name='host'><br />
            <label><?php echo t('Database name');?> : </label><input type='text' name='database'><br />
            <input type='submit' name='submit'>
        </form>
    </section>
</body>
</html>
