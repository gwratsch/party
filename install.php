<?php
include_once 'settings.php';
$path = configsettings::pathname();
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){
    (new DB_install)->installdb();
    echo '<a href="/user.php">'.t('Naar user page').'</a>';
}
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
        $pagetemplate->title="Installation scripts";
        echo $pagetemplate->header();
        echo $pagetemplate->navigation();
        ?>
    <section class="container">
        <h2><?php echo t("Database settings");?></h2>
        <form action='install.php' method="post" class="form-horizontal">
            <div><label><?php echo t('Database type');?> </label>: </div><select id="DbType" class="form-control" name='dbtype' onclick="selectDbType()">
                <?php 
                    $pdoDrivers = (PDO::getAvailableDrivers());

                    $count=0;
                    $defaultDbType='';
                    foreach ($pdoDrivers as $key => $value) {
                        $dirverslist=array('mysql','pgsql');
                        if( in_array ($value,$dirverslist)){
                            if($count==0){$defaultDbType=$value;$count=1;}
                            echo '<option value="'.$value.'">'.$value.'</option>';
                        }
                    }
                ?>
            </select><br />
            <div><label><?php echo t('Database user');?></label>: </div><input type='text' name='dbUser' class="form-control" required><br />
            <div><label><?php echo t('User password');?></label>: </div><input type='text' name='userPW' class="form-control" required><br />
            <div><label><?php echo t('Host');?> </label>: </div><input type='text' name='host' class="form-control" required><br />
            <?php
            $displayValue = 'none';
            $requiredInput ='';
            if($defaultDbType == 'pgsql'){$displayValue = 'block';$requiredInput = "required";}
            echo '<div class="portlabel" style="display:'.$displayValue.'" ><label>'.t("Port").'</label>: </div><input class="portinput form-control" style="display:'.$displayValue.'" type="text" name="port" '.$requiredInput.'>';
            if($displayValue == 'block'){echo '<br />';}
            ?>
                
            <div><label><?php echo t('Database name');?> </label>: </div><input type='text' name='dbname' class="form-control" required><br />
            <input type='submit' name='submit'>
        </form>
    </section>
    <script src=<?php echo $path."js/party.js";?>></script>
</body>
</html>
