<?php
include_once 'settings.php';
$path = (new configsettings)->pathname();
$user = new user();
$updateUserInfo = FALSE;
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){
    $user->save();
}   
if(array_key_exists('userid', $_SESSION)){
    $user->userid['content']= $_SESSION['userid'];
    $updateUserInfo = TRUE;
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
            <form action="user.php" method="post" class="form-horizontal">
                <?php
                $formContent='';
                foreach ($user as $keyName => $keyContent) {
                    $type = $keyContent['type'];
                    $name = $keyContent['name'];
                    $content='';
                    if($updateUserInfo == TRUE){
                        $content = $keyContent['content'];
                    }
                    $defaultChecked = $keyContent['defaultChecked'];
                   switch ($type) {
                        case 'hidden':
                            $formContent .='<input type="'.$type.'" name="'.$keyName.'" value="'.$content.'">';
                            break;
                        case 'textarea':
                            $formContent .='<div><label>'.t($name).'</label></div>:<textarea name="'.$keyName.'" class="form-control" >'.$content.'</textarea><input type="checkbox" name="'.$name.'block" '.$defaultChecked.'><br />';
                            break;
                        default:
                            $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$keyName.'" class="form-control" value="'.$content.'"  '.$defaultChecked.'><input type="checkbox" name="'.$name.'block" '.$defaultChecked.'><br />';
                            break;
                    }
                }
                echo $formContent;
                ?>
                  <input type="submit" name="submit">
            </form>
        </section>
        </div>
    </body>
</html>
