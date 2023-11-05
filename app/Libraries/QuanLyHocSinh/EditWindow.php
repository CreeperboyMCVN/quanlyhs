<?php
namespace QuanLyHocSinh;

use QuanLyHocSinh\Html\Input;
use QuanLyHocSinh\Html\Text;

class EditWindow extends ActionWindow {
    public function __construct($view) {
        $this->view = $view;
    }

    public function getWindow() {
        $v = view('documents/edit-window.html');
        $inputs = '';
        switch ($this->view) {
            case 'students':
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'edit-form id-form primary-form', 'autocomplete' => 'off']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'edit-form name-form']). '<br>';
                $inputs .= Input::label('Ngày sinh', ['class' => 'birthday-label']). '<br>';
                $inputs .= Input::date('dob', '', ['class' => 'edit-form birthday-form']). '<br>';
                $inputs .= Input::label('Giới tính (0 là nam, 1 là nữ)', ['class' => 'gender-label']). '<br>';
                $inputs .= Input::number('gender', '0', ['class' => 'edit-form gender-form', 'min' => 0, 'max'=>1]). '<br>';
                $inputs .= Input::label('Lớp', ['class' => 'class-label']). '<br>';
                $inputs .= Input::text('class', '', ['class'=> 'edit-form class-form']). '<br>';
                $inputs .= Input::button('Sửa' , ['class' => 'edit-btn']);
                $inputs .= Input::button('Xóa' , ['class' => 'delete-btn warning']);
                $v = placeholder($v, 'window_title', 'Chỉnh sửa học sinh');
                break;
        }
        $v = placeholder($v, 'form_content', $inputs);
        return $v;
    }
}