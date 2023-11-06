<?php
namespace QuanLyHocSinh\Html;

class Input {
    public static function text($name, $value = '', array $attributes = []){
        return '<input type="text" name="' . htmlspecialchars($name) . '" value="'.htmlspecialchars($value
        ).'" '.(count($attributes)>0?self::getAttributes($attributes):'').'>';
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

    public static function number($name, $value= '', array $attr = []) {
        return '<input type="number" name="' . htmlspecialchars($name). '" value="'.htmlspecialchars($value
        ) .'" '. self::getAttributes($attr).'>';
    }

    public static function label($title, array $attr = []) {
        return '<label '. self::getAttributes($attr).'>'.htmlspecialchars($title).'</label>';
    }

    public static function date($name, $value= '', array $attr = []) {
        return '<input type="date" name="' . htmlspecialchars($name). '" value="'.htmlspecialchars($value
        ) .'" '. self::getAttributes($attr).'>';
    }

    public static function button($title, array $attr = []) {
        return '<button '. self::getAttributes($attr).'>'.htmlspecialchars($title).'</button>';
    }

    public static function email($name, $value = '', array $attr = []) {
        return '<input type="email" name="' . htmlspecialchars($name). '" value="'.htmlspecialchars($value
        ) .'" '. self::getAttributes($attr).'>';
    }
}
                    
