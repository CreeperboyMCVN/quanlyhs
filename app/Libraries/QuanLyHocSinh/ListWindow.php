<?php


namespace QuanLyHocSinh;


/**
 * ListWindow
 *
 * @author long
 */
class ListWindow extends ActionWindow {
    //put your code here

    public function __construct($view) {
        $this->view = $view;
    }
    
    public function getWindow() {
        $view = view('documents/action-list-window.html');
        switch ($this->view) {
            case 'students':
                # code...
                
                $view = placeholder($view , 'list_title', 'Danh sách học sinh');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                $view = placeholder($view , 'filter_search', SEARCH_FORM);
                $view = placeholder($view , 'filter_date', DATE_FORM);
                return $view;
            
            default:
                # code...
                break;
        }
    }
}
