<?php
include_once '../settings.php';
include_once '../config.php';
if(array_key_exists('submit', $_POST)){
    include_once 'DB_install.php';
    $formDBsettings=array();
    foreach ($_POST as $key => $value) {
        if($key == 'db_pw'){
            $formDBsettings[$key]=$value;
        }else{
            $formDBsettings[$key]=$value;
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
            <label><?php echo t('Database type');?> : </label><select id="DbType" name='db_type' onclick="selectDbType()">
                <?php 
                    $pdoDrivers = (PDO::getAvailableDrivers());

                    $count=0;
                    $defaultDbType='';
                    foreach ($pdoDrivers as $key => $value) {
                        $dirverslist=array('mysql','pgsql');
                        if( in_array ($value,$dirverslist)){
                            if($count==0){$defaultDbType=$value;}
                            echo '<option value="'.$value.'">'.$value.'</option>';
                        }
                    }
                ?>
            </select><br />
            <label><?php echo t('Database user');?> : </label><input type='text' name='db_user' required><br />
            <label><?php echo t('User password');?> : </label><input type='text' name='db_pw' required><br />
            <label><?php echo t('Host');?> : </label><input type='text' name='host' required><br />
            <?php
            $displayValue = 'none';
            $requiredInput ='';
            echo $defaultDbType;
            if($defaultDbType == 'pgsql'){$displayValue = 'block';$requiredInput = "required";}
            echo '<label class="portlabel" style="display:'.$displayValue.'" >'.t("Port").': </label><input class="portinput" style="display:'.$displayValue.'" type="text" name="port" '.$requiredInput.'>';
            if($displayValue == 'block'){echo '<br />';}
            ?>
                
            <label><?php echo t('Database name');?> : </label><input type='text' name='database' required><br />
            <input type='submit' name='submit'>
        </form>
    </section>
    <script src="../js/party.js"></script>
</body>
</html>
