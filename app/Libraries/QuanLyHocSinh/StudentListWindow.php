<?php


namespace QuanLyHocSinh;


/**
 * Description of StudentListWindow
 *
 * @author long
 */
class StudentListWindow extends ActionWindow {
    //put your code here
    
    public function getWindow() {
        $view = view('documents/action-list-window.html');
        $view = placeholder($view , 'list_title', 'Danh sách học sinh');
        $view = placeholder($view , 'filter_max', MAX_FORM);
        $view = placeholder($view , 'filter_search', SEARCH_FORM);
        $view = placeholder($view , 'filter_date', DATE_FORM);
        return $view;
    }
}
