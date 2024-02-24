<?php

namespace QuanLyHocSinh;

class Utils {
    public static function tag($tag, $classes, $content) {
        $c = implode(' ', $classes);
        return "<$tag class='$c'>$content</$tag>";
    }

    public static function is_assoc($array) {
        $keys = array_keys($array);
        return ($keys !== array_keys($keys));
    }
}

