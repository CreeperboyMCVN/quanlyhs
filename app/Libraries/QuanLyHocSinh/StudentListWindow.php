<?php


namespace QuanLyHocSinh;

use QuanLyHocSinh\Html\Table;

/**
 * Description of StudentListWindow
 *
 * @author long
 */
class StudentListWindow extends ActionWindow {
    //put your code here
    
    public function getWindow() {
        $view = view('documents/action-list-window.html');
        $db = db_connect();
        $query = $db->query("SELECT * FROM `qlhs_students`");
        $table_data = [];
        foreach ($query->getResult() as $row) {
            # code...
            $row_data = [$row->id , $row->name, $row->class, $row->dob];
            $table_data = array_merge($table_data, [$row_data]);
        }
        $table = new Table($table_data);
        $table->setHeader(['Mã học sinh', 'Tên', 'Lớp', 'Ngày sinh']);

        $view = placeholder($view , 'table_content', $table->getTable());
        $view = placeholder($view , 'list_title', 'Danh sách học sinh');

        return $view;
    }
}
