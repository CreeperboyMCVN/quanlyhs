<?php
namespace QuanLyHocSinh;

class SidebarParser extends Parser {
    
    public function parse():string {
        $actions = [
            'students'      => IconDictionary::$users . Utils::tag('span', ['function-item-label'], 'Danh sách học sinh'),
            'teachers'      => IconDictionary::$teacher . Utils::tag('span', ['function-item-label'], 'Danh sách giáo viên chủ nhiệm'),
            'violate'       => IconDictionary::$warning . Utils::tag('span', ['function-item-label'], 'Danh sách vi phạm'),
            'log'           => IconDictionary::$checkcircle . Utils::tag('span', ['function-item-label'], 'Nhật ký vi phạm'),
            'users'          => IconDictionary::$users . Utils::tag('span', ['function-item-label'], 'Người dùng'),
            'login_log'          => IconDictionary::$key . Utils::tag('span', ['function-item-label'], 'Lịch sử đăng nhập')
        ];
        
        $user = $this->user;
        $res = '';
        $selectedClass = 'selected';
        $itemClass = 'function-item';
        
        foreach ($actions as $key => $value) {
            if ($user->hasPermission('admin')) {
                $pageData = $user->getPageData();
                $pageData["view"] = $key;
                $url = current_url() . arrayToUrlFormEncoded($pageData);
                $class = $itemClass;
                
                if ($user->view == $key) {
                    $class .= ' ' . $selectedClass;
                }
                
                $res .= "<a href='$url'>"
                        . "<div class='$class'>"
                        . "$value"
                        . "</div>"
                        . "</a>";
            }
        }
        return $res;
    }
}