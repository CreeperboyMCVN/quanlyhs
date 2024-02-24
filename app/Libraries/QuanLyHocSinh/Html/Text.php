<?php
namespace QuanLyHocSinh\Html;

class Text {
    public static function header($size = 1, $title = '' , array $attr = []) {
        if ($size < 1 || $size > 6) {
            throw new \Exception('Size must be between 1 and 6');
        }
        return '<h'.$size.' ' . self::getAttributes($attr).'>'.htmlspecialchars($title).'</h'.
        $size.">";
    }

    private static function getAttributes(array $attributes){
        $str='';
        foreach ($attributes as $key => $val){
            if (is_int($key)){
                $str.=$val.' ';
            }else{
                $str .= $key.'="'.$val.'" ';
            }
        }
        return trim($str);
    }
}