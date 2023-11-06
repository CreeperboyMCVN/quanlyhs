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
            case 'teachers':
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'edit-form id-form primary-form', 'autocomplete' => 'off']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'edit-form name-form']). '<br>';
                $inputs .= Input::label('Lớp', ['class' => 'class-label']). '<br>';
                $inputs .= Input::text('class', '', ['class'=> 'edit-form class-form']). '<br>';
                $inputs .= Input::label('Email' , ['class' => 'email-label']) . '<br>';
                $inputs .= Input::text('email', '', ['class'=> 'edit-form email-form']). '<br>';
                $inputs .= Input::label('Tổ', ['class' => 'team-label']). '<br>';
                $inputs .= Input::text('team', '', ['class'=> 'edit-form team-form']). '<br>';
                $inputs .= Input::button('Sửa' , ['class' => 'edit-btn']);
                $inputs .= Input::button('Xóa' , ['class' => 'delete-btn warning']);
                $v = placeholder($v, 'window_title', 'Chỉnh sửa giáo viên chủ nhiệm');
                break;
            case 'violate':
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'edit-form id-form primary-form', 'autocomplete' => 'off']). '<br>';
                $inputs .= Input::label('Tên', ['class' => 'name-label']). '<br>';
                $inputs .= Input::text('name', '', ['class' => 'edit-form name-form']). '<br>';
                $inputs .= Input::label('Điểm trừ', ['class' => 'points-label']). '<br>';
                $inputs .= Input::text('points', '', ['class'=> 'edit-form points-form']). '<br>';
                $inputs .= Input::button('Sửa' , ['class' => 'edit-btn']);
                $inputs .= Input::button('Xóa' , ['class' => 'delete-btn warning']);
                $v = placeholder($v, 'window_title', 'Chỉnh sửa vi phạm');
                break;
            case 'log':
                $inputs .= Text::header(6, 'Lưu ý: Khuyến khích chỉnh sửa nhật ký bằng POS hơn chỉnh sửa trực tiếp');
                $inputs .= Input::label('ID', ['class' => 'id-label']) . '<br>';
                $inputs .= Input::text('id', '', ['class' => 'edit-form id-form primary-form', 'autocomplete' => 'off']). '<br>';
                $inputs .= Input::label('ID học sinh', ['class' => 'student-label']). '<br>';
                $inputs .= Input::text('student_id', '', ['class' => 'edit-form student-form']). '<br>';
                $inputs .= Input::label('ID vi phạm', ['class' => 'violate-label']). '<br>';
                $inputs .= Input::text('violate_id', '', ['class'=> 'edit-form violate-form']). '<br>';
                $inputs .= Input::label('Thời gian', ['class' => 'time-label']). '<br>';
                $inputs .= Input::date('time', '', ['class'=> 'edit-form time-form']). '<br>';
                $inputs .= Input::label('Giám thị', ['class' => 'supervisor-label']). '<br>';
                $inputs .= Input::text('supervisor', '', ['class'=> 'edit-form supervisor-form']). '<br>';
                $inputs .= Input::label('Có tính điểm trừ? (0 là không, 1 là có)', ['class' => 'count-label']). '<br>';
                $inputs .= Input::number('count', '0', ['class' => 'edit-form gender-form', 'min' => 0, 'max'=>1]). '<br>';
                $inputs .= Input::button('Sửa' , ['class' => 'edit-btn']);
                $inputs .= Input::button('Xóa' , ['class' => 'delete-btn warning']);
                $v = placeholder($v, 'window_title', 'Chỉnh sửa nhật ký vi phạm');
                break;
    }
        $v = placeholder($v, 'form_content', $inputs);
        return $v;
    }
}