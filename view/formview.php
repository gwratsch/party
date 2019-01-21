<?php
include_once 'settings.php';
class formview {

    function buildHtmlForm($pageObject){
       //var_dump($pageObject);
    $content='<form action="'.$pageObject->PageClassFile.'" method="post" class="form-horizontal">'
            . $this->buildHtmlFormContent($pageObject).
            '<input type="submit" name="submit">
            </form>';
    return $content;
    }
    
    function buildHtmlFormContent($pageObject){
        $formContent='';
        $protectInfo = false;
        if(array_key_exists('displayProtectInfo', $pageObject)){$protectInfo = $pageObject->displayProtectInfo;}
        foreach ($pageObject as $keyName => $keyContent) {
            if(is_array($keyContent) && array_key_exists('type', $keyContent)){
                $type = $keyContent['type'];
                $name = $keyContent['name'];
                $content='';
                if($pageObject->updateInfo == TRUE){
                    $content = $keyContent['content'];
                }
                $defaultChecked = $keyContent['defaultChecked'];
                switch ($type) {
                    case 'hidden':
                        $formContent .='<input type="'.$type.'" name="'.$keyName.'" value="'.$content.'">';
                        break;
                    case 'textarea':
                        $formContent .='<div><label>'.t($name).'</label></div>:<textarea name="'.$keyName.'" class="form-control" >'.$content.'</textarea>';
                        if($protectInfo == true){$formContent .='<input type="checkbox" name="'.$name.'block" '.$defaultChecked.'>';}
                        $formContent .= '<br />';
                        break;
                    case 'checkbox':
                        if($content ==1){$defaultChecked = 'checked';}
                        $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$keyName.'" class="form-control" value="'.$keyName.'" '.$defaultChecked.'>';
                        if($protectInfo == true){$formContent .='<input type="checkbox" name="'.$name.'block" '.$defaultChecked.'>';}
                        $formContent .= '<br />';
                        break;
                    default:
                        $formContent .='<div><label>'.t($name).'</label></div>:<input type="'.$type.'" name="'.$keyName.'" class="form-control" value="'.$content.'" '.$defaultChecked.'>';
                        if($protectInfo == true){$formContent .='<input type="checkbox" name="'.$name.'block" '.$defaultChecked.'>';}
                        $formContent .= '<br />';
                        break;
                }
            }
        }
        return $formContent;

    }
            
}
