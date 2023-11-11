<?php
namespace QuanLyHocSinh;

use QuanLyHocSinh\Html\Input;
use QuanLyHocSinh\IconDictionary;

class MenuWindow extends ActionWindow {
    public function getWindow() {
        $vi = view('documents/menu.html');
        $buttons = '';
        $buttons .= '<a href="'. base_url("app") . '?window=workframe">' . Input::htmlButton(IconDictionary::$user. '<br><div>Bảng Admin</div>', ['class' => 'button']) . '</a>';
        $buttons .= '<a href="'. base_url("app") . '?window=pos">' . Input::htmlButton(IconDictionary::$pen. '<br><div><br>POS</div>', ['class' => 'button']) . '</a>';
        $buttons .= '<a href="'. base_url("app") . '?window=pos&view=record">' . Input::htmlButton(IconDictionary::$table. '<br><div>Thống kê</div>', ['class' => 'button']) . '</a>';
        $buttons .= '<a href="'. base_url("help") . '">' . Input::htmlButton(IconDictionary::$question. '<br><div>Trợ giúp</div>', ['class' => 'button']) . '</a>';
        $buttons .= '<a href="'. base_url("info") . '">' . Input::htmlButton(IconDictionary::$info. '<br><div>Thông tin</div>', ['class' => 'button']) . '</a>';    
        $vi = placeholder($vi, 'buttons', $buttons);

        $vi = placeholder($vi, 'menu_title', 'Phần mềm quản lý học sinh vi phạm');
        return $vi;
    }
}