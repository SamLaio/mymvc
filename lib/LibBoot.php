<?php
class LibBoot {
   function __construct($url){
        include 'LibDataBase.php';
        $view = (isset($url[2]) and $url[2] != '')?$this->FileCk(SCANDIR ('view'),$url[2]):'index';
        $control = (isset($url[2]) and $url[2] != '')?$this->FileCk(SCANDIR ('control'),$url[2]):'index';
        $data['get'] = $this->InDataCk($_GET);
        $data['post'] = $this->InDataCk($_POST);

        include "control/$control.php";
        $control_odj = new $control;
        if(isset($url[3]) and $url[3] != ''){
            $url[3] = explode('?', $url[3]);
            $url[3] = $url[3][0];
            if(method_exists($control_odj,$url[3])){
                if(count($data['get']) != 0  or count($data['post']) != 0)
                    $control_odj->{$url[3]}($data);
                else
                    $control_odj->{$url[3]}();
            }else{
                $view = 'error';
                $control = $view;
            }
        }
        if($view == $control)
            include "view/$view.html";
    }
    private function FileCk($arr,$file_name){
        $ret = 'error';
        foreach($arr as $value){
             if(substr( $value, 0, strrpos($value, ".")) == $file_name)
                  $ret = $file_name;
        }
        return $ret;
    }
    private function InDataCk($arr){
        $data = array();
        foreach($arr as $key =>$value){
            $data[$key] = $this->CheckInput($value);
        }
        return $data;
    }
    private function CheckInput($value){
        if(is_array($value)){
            foreach($value as $key2 => $value2)
                $value[$key2] = $this->CheckInput($value2);
        }else{
            $value = str_replace ( "&", '@&5', $value);
            $value = str_replace ( "'", '@&1', $value);
            $value = str_replace ( '"', '@&2', $value);
            $value = str_replace ( "<", '@&3', $value);
            $value = str_replace ( ">", '@&4', $value);
        }
        return $value;
    }
    /*function html_decode($body){
         $body = str_replace ( '@&4', ">", $body);
         $body = str_replace ( '@&3', "<", $body);
         $body = str_replace ( '@&2', '"', $body);
         $body = str_replace ( '@&1', "'", $body);
         $body = str_replace ( '@&5', "&", $body);
         return $body;
    }*/
}