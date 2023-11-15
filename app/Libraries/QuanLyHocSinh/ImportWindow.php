<?php
namespace QuanLyHocSinh;

use QuanLyHocSinh\Html\Input;
use QuanLyHocSinh\Html\Text;
/*
* ImportWindow
*/

class ImportWindow extends ActionWindow {

    public function __construct($view, $user) {
        $this->view = $view;
        $this->user = $user;
    }

    public function getWindow()
    {
        $v = view('documents/import-window.html');
        if (!$this->user->hasPermission('admin')) {
            return 'Bạn không có quyền!';
        }
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
                $v = placeholder($v, 'window_title', 'Nhập học sinh');
                break;
            case 'teachers':
                $inputs .= Text::header(3, 'Nhập thủ công', ['class' => 'form-header']);
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'manual-form id-form']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'manual-form name-form']). '<br>';
                $inputs .= Input::label('Lớp', ['class' => 'class-label']). '<br>';
                $inputs .= Input::text('class', '', ['class'=> 'manual-form class-form']). '<br>';
                $inputs .= Input::label('Email' , ['class' => 'email-label']) . '<br>';
                $inputs .= Input::text('email', '', ['class'=> 'manual-form email-form']). '<br>';
                $inputs .= Input::label('Tổ', ['class' => 'team-label']). '<br>';
                $inputs .= Input::text('team', '', ['class'=> 'manual-form team-form']). '<br>';
                $inputs .= Input::button('Nhập' , ['class' => 'manual-import-btn']);
                $v = placeholder($v, 'window_title', 'Nhập giáo viên chủ nhiệm');
                break;
            case 'violate':
                $inputs .= Text::header(3, 'Nhập thủ công', ['class' => 'form-header']);
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'manual-form id-form']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'manual-form name-form']). '<br>';
                $inputs .= Input::label('Điểm trừ', ['class' => 'points-label']). '<br>';
                $inputs .= Input::number('points', '0', ['class'=> 'manual-form points-form']). '<br>';
                $inputs .= Input::button('Nhập' , ['class' => 'manual-import-btn']);
                $v = placeholder($v, 'window_title', 'Nhập vi phạm');
                break;
            case 'users':
                $inputs .= Text::header(3, 'Nhập thủ công', ['class' => 'form-header']);
                $inputs .= Input::label('Tên (vui lòng không ghi kí tự đặc biệt)', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('username', '', ['class' => 'manual-form username-form']). '<br>';
                $inputs .= Input::label('Mật khẩu', ['class' => 'points-label']). '<br>';
                $inputs .= Input::password('password', '', ['class'=> 'manual-form password-form']). '<br>';
                $inputs .= Input::label('Quyền (admin, supervisor, class_monitor)', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('permission', '', ['class' => 'manual-form permission-form']). '<br>';
                $inputs .= Input::button('Nhập' , ['class' => 'manual-import-btn']);
                $v = placeholder($v, 'window_title', 'Nhập người dùng');
                break;
            case 'log':
                return 'Không thể nhập trực tiếp! Vui lòng dùng POS!';
        }
        
        $v = placeholder($v, 'form_content', $inputs);
        return $v;
    }
}