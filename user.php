<?php
include_once 'settings.php';
$user = new user();
if(array_key_exists('submit', $_POST)){
    //include_once 'includes/user.class.php';
    $user->save();
}    
?>
<!DOCTYPE html>
<html lang="nl">
    <head>
        <?php 
        include 'templates/head.php';
        ?>
        <title>partyList</title>
    </head>
    <body>
        <?php 
        include 'templates/header.php';
        include 'templates/navigation.php';
        ?>
        <section class="container">
            <h2><?php echo t("User settings");?></h2>
            <form action="user.php" method="post" class="form-horizontal">
                <?php
                $formContent='';
                foreach ($user as $keyName => $keyContent) {
                    $type = $keyContent['type'];
                    $name = $keyContent['name'];
                    $content = $keyContent['content'];
                    $defaultChecked = $keyContent['defaultChecked'];
                   switch ($type) {
                        case 'hidden':
                            $formContent .='<input type="'.$type.'" name="'.$name.'" value="'.$content.'">';
                            break;
                        case 'text':
                            $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$name.'" class="form-control" value="'.$content.'"><input type="checkbox" name="'.$name.'block" '.$defaultChecked.'><br />';
                            break;
                        case 'email':
                            $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$name.'" class="form-control" value="'.$content.'"><input type="checkbox" name="'.$name.'block" '.$defaultChecked.'><br />';
                            break;
                        case 'textarea':
                            $formContent .='<div><label>'.t($name).'</label></div>:<textarea name="'.$name.'" class="form-control" >'.$content.'</textarea><input type="checkbox" name="'.$name.'block" '.$defaultChecked.'><br />';
                            break;
                        default:
                            break;
                    }
                }
                echo $formContent;
                ?>
                  <input type="submit" name="submit">
            </form>
        </section>
    </body>
</html>
