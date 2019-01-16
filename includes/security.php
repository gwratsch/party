<?php
class security {
    function clean($string) {
       $string = strip_tags($string);
       $string = str_replace(' ', '-', $string);
       return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
    function valid($str, $type){
        $str = $this->removeHtmlTag($str);
        switch ($type) {
            case "email":
                $pattern = "/^[0-9a-zA-Z_\.-]{1,}@([0-9a-zA-Z_\-]{1,}\.)+[0-9a-zA-Z_\-]{2,}$/";
                return $this->setResult($pattern, $str);
                break;
            case "firstname":
                $pattern = "/^[A-Za-z. -]+$/";
                return $this->setResult($pattern, $str);
                break;
            case "text":
                $pattern = "/[A-Za-z0-9]*$/";
                return $this->setResult($pattern, $str);
                break;
            case "voorletters":
                $pattern = "/^[A-Z.]+$/";
                return $this->setResult($pattern, $str);
                break;
            case "lastname":
                $pattern = "/^[A-Za-z -]+$/";
                return $this->setResult($pattern, $str);
                break;
            case "adress":
                $pattern = "/^([A-Za-z-])+[0-9]+([a-z0-9 -])*$/";
                return $this->setResult($pattern, $str);
                break;
            case "zipcode":
                $pattern = "/^[1-9][0-9]{3}[ ]?[A-Za-z]{2}$/";
                return $this->setResult($pattern, $str);
                break;
            case "city":
                $pattern = "/([A-Za-z -])+$/";
                return $this->setResult($pattern, $str);
                break;
            case "phone":
                $pattern = "/^[0-9]{10}$/";
                return $this->setResult($pattern, $str);
                break;
            case "banknumber":
                $pattern = "/^[1-9][0-9]{3,}$/";
                return $this->setResult($pattern, $str);
                break;
            case "number":
                $pattern = "/^[0-9][0-9]*$/";
                return $this->setResult($pattern, $str);
                break;
            case "checkbox":
                return 1;
                break;
            case "textarea":
            case "hidden":
            case "password":
                return $str;
                break;
            default:
                break;
        }
    }
    function removeHtmlTag($string){
        return strip_tags($string);
    }
    function setResult($pattern, $str){
        if(preg_match($pattern, $str)){
            $content = $str;
        }else{
            $content= "Dit is geen geldige waarde.";
        }
        return $content;
    }
}