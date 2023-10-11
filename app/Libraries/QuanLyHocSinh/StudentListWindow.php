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
        $view = view('documents/action-window.html');
        
        return $view;
    }
}
