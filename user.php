<?php
include_once 'settings.php';
$path = (new configsettings)->pathname();
$user = new user();
$updateUserInfo = FALSE;
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){
    if(array_key_exists('updateUser', $_POST)){ 
        $updateUserInfo = TRUE;
    }else{
        $user->save();
    }
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
        <section class="container col">
            <h2><?php echo t("User settings");?></h2>
            <form action="user.php" method="post" class="form-horizontal">
                <?php
        var_dump('<br />USER object : <br />');
        var_dump($user);
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
        <section class="container col">
            <h2>User list</h2>
        <?php
        $user->userid['content'] = '*';
        $result = $user->select();
        var_dump('<br />USER select list : <br />');
        var_dump($result);
        echo '<table class="table table-striped"><thead>
      <tr>
        <th>User id</th>
        <th>Name</th>
      </tr>
    </thead>
    <tbody>';
        foreach ($result as $key => $value) {
            echo '<tr><td class="list--item">'.$value['userid'].'</td><td>'.$value['firstname'].' '.$value['lastname'].'</td>';
        }
        echo '</tr></tbody></table>';
        ?>
        </section>
        </div>
    </body>
</html>
