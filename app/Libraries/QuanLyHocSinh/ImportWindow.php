<?php
namespace QuanLyHocSinh;

use QuanLyHocSinh\Html\Input;
use QuanLyHocSinh\Html\Text;
/*
* ImportWindow
*/

class ImportWindow extends ActionWindow {

    public function __construct($view) {
        $this->view = $view;
    }

    public function getWindow()
    {
        $v = view('documents/import-window.html');
        $inputs = '';
        switch ($this->view) {
            case 'students':
                $inputs .= Text::header(3, 'Nhập thủ công', ['class' => 'form-header']);
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'manual-form id-form']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'manual-form name-form']). '<br>';
                $inputs .= Input::label('Ngày sinh', ['class' => 'birthday-label']). '<br>';
                $inputs .= Input::date('dob', '', ['class' => 'manual-form birthday-form']). '<br>';
                $inputs .= Input::label('Giới tính (0 là nam, 1 là nữ)', ['class' => 'gender-label']). '<br>';
                $inputs .= Input::number('gender', '0', ['class' => 'manual-form gender-form', 'min' => 0, 'max'=>1]). '<br>';
                $inputs .= Input::label('Lớp', ['class' => 'class-label']). '<br>';
                $inputs .= Input::text('class', '', ['class'=> 'manual-form class-form']). '<br>';
                $inputs .= Input::button('Nhập' , ['class' => 'manual-import-btn']);
                $v = placeholder($v, 'window_title', 'Nhập danh sách học sinh');
                break;
        }
        
        $v = placeholder($v, 'form_content', $inputs);
        return $v;
    }
}