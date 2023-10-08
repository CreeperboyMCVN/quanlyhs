<?php
use IconDictionary;

public class GlobalParser {
    public static parseSidebar($user) {
        if (!($user instanceof User)) {
            throw new Exception('Expected "User" but got "'. gettype($user).'"', 1000);
        }

        $actions = [
            'students'      => IconDictionary::users . 'Danh sách học sinh',
            'teachers'      => IconDictionary::teacher . 'Danh sách giáo viên',
            'violate'       => IconDictionary::warning . 'Các vi phạm',
            'log'           => IconDictionary::checkcircle . 'Học sinh vi phạm',
        ];
        
        $selected_class = 'selected';
        $btn_class = 'function-item';
        
        
    }
}