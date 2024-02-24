<?php


namespace QuanLyHocSinh;


/**
 * ListWindow
 *
 * @author long
 */
class ListWindow extends ActionWindow {
    //put your code here

    public function __construct($view, $user) {
        $this->view = $view;
        $this->user = $user;
    }
    
    public function getWindow() {
        $view = view('documents/action-list-window.html');
        if (!$this->user->hasPermission('admin')) {
            return 'Bạn không có quyền!';
        }
        switch ($this->view) {
            case 'students':
                # code...
                
                $view = placeholder($view , 'list_title', 'Danh sách học sinh');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                $view = placeholder($view , 'filter_search', SEARCH_FORM);
                $view = placeholder($view , 'filter_date', DATE_FORM);
                return $view;
            
            case 'teachers':
                $view = placeholder($view , 'list_title', 'Danh sách giáo viên chủ nhiệm');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                $view = placeholder($view , 'filter_search', SEARCH_FORM);
                return $view;
            case 'violate':
                $view = placeholder($view , 'list_title', 'Danh sách vi phạm');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                $view = placeholder($view , 'filter_search', SEARCH_FORM);
                return $view;

            case 'log':
                $view = placeholder($view , 'list_title', 'Nhật ký vi phạm');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                $view = placeholder($view , 'filter_search', SEARCH_FORM);
                $view = placeholder($view , 'filter_date', DATE_FORM);
                return $view;
            case 'users':
                $view = placeholder($view , 'list_title', 'Danh sách người dùng');
                $view = placeholder($view , 'filter_max', MAX_FORM);
                return $view;
            case 'login_log':
                $view = placeholder($view , 'list_title', 'Lịch sử đăng nhập');
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
