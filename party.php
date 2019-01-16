<?php
include_once 'settings.php';
$path = (new configsettings)->pathname();
$party = new party();
$updatePartyInfo = FALSE;
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){
    if(array_key_exists('updateUser', $_POST)){ 
        $updatePartyInfo = TRUE;
    }else{
        $party->save();
    }
}  

if(array_key_exists('partyid', $_GET)){
    $getID = intval($_GET['partyid']);
    if(is_int($getID)){
        $party->partyid['content'] = $getID;
        $party->edit();
        $updatePartyInfo = TRUE;
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
        <div class="row m-1">
        <section class="container col p-3">
            <h2><?php echo t("Party info");?></h2>
            <form action="party.php" method="post" class="form-horizontal">
                <?php
                $formContent='';
                foreach ($party as $keyName => $keyContent) {
                    if(is_array($keyContent) && array_key_exists('type', $keyContent)){
                        $type = $keyContent['type'];
                        $name = $keyContent['name'];
                        $content='';
                        if($keyName == 'userid' && array_key_exists('userid', $_SESSION)){$content = $_SESSION['userid']; }
                        if($updatePartyInfo == TRUE){
                            $content = $keyContent['content'];
                        }
                        $defaultChecked = $keyContent['defaultChecked'];
                       switch ($type) {
                            case 'hidden':
                                $formContent .='<input type="'.$type.'" name="'.$keyName.'" value="'.$content.'">';
                                break;
                            case 'textarea':
                                $formContent .='<div><label>'.t($name).'</label></div>:<textarea name="'.$keyName.'" class="form-control" >'.$content.'</textarea><br />';
                                break;
                            case 'checkbox':
                                if($content ==1){$defaultChecked = 'checked';}
                                $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$keyName.'" class="form-control" value="'.$keyName.'" '.$defaultChecked.'><br />';
                                break;
                            default:
                                $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$keyName.'" class="form-control" value="'.$content.'" '.$defaultChecked.'><br />';
                                break;
                        }
                    }
                }
                echo $formContent;
                ?>
                  <input type="submit" name="submit">
            </form>
        </section>
        <section class="container col">
            <h2>Party list</h2>
        <?php
        $userid=0;
        if(array_key_exists('userid', $_SESSION)){$userid = $_SESSION['userid']; }
        $party->userid['content'] = $userid;
        $party->partyid['content'] = '';
        $result = $party->select();
        echo '<table class="table table-striped"><thead>
      <tr>
        <th>Name</th>
        <th>Locatie</th>
        <th></th>
      </tr>
    </thead>
    <tbody>';
        foreach ($result as $key => $value) {
            echo '<tr><td class="list--item">'.$value->partyinfo.'</td><td>'.$value->location.'</td><td><a href="/party.php?partyid='.$value->partyid.'">EDIT</a></td>';
        }
        echo '</tr></tbody></table>';
        ?>
        </section>
        </div>
    </body>
</html>
