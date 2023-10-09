<?php

namespace QuanLyHocSinh;

class StringUtils {
    public static function tag($tag, $classes, $content) {
        $c = implode(' ', $classes);
        return "<$tag class='$c'>$content</$tag>";
    }
}

