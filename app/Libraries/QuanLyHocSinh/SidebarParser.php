<?php
namespace QuanLyHocSinh;

class SidebarParser extends Parser {
    
    public function parse():string {
        $actions = [
            'students'      => IconDictionary::$users . StringUtils::tag('span', ['function-item-label'], 'Danh sách học sinh'),
            'teachers'      => IconDictionary::$teacher . StringUtils::tag('span', ['function-item-label'], 'Danh sách giáo viên'),
            'violate'       => IconDictionary::$warning . StringUtils::tag('span', ['function-item-label'], 'Danh sách vi phạm'),
            'log'           => IconDictionary::$checkcircle . StringUtils::tag('span', ['function-item-label'], 'Nhật ký vi phạm')
        ];
        
        $user = $this->user;
        $res = '';
        $selectedClass = 'selected';
        $itemClass = 'function-item';
        
        foreach ($actions as $key => $value) {
            if ($user->hasPermission($key, 'r')) {
                $pageData = $user->getPageData();
                $pageData["action"] = $key;
                $url = current_url() . arrayToUrlFormEncoded($pageData);
                $class = $itemClass;
                
                if ($user->action == $key) {
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