<?php
include_once 'settings.php';
$party = new party();
$party->updateInfo = FALSE;
$pagetemplate = new pagetemplate();
if(array_key_exists('submit', $_POST)){

        $party->save();
}  

if(array_key_exists('partyid', $_GET)){
    $getID = intval($_GET['partyid']);
    if(is_int($getID)){
        $party->partyid['content'] = $getID;
        $party->edit();
        $party->updateInfo = TRUE;
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
            <?php 
            if(array_key_exists('userid', $_SESSION)){$party->userid['content'] = $_SESSION['userid'];} 
            echo (new formview)->buildHtmlForm($party);?>
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
