<?php

namespace QuanLyHocSinh;

/**
 * Description of ActionWindow
 *
 * @author long
 */
define('MAX_FORM', '<div class="max filter-section">
Hiển thị <input type="number" min="1" max="50" value=\'10\' name="max" class="max-filter"/> phần tử 1 trang.
</div>');

define('SEARCH_FORM', '<div class=\'search filter-section\'>
Tìm kiếm
<input type="text" name="search" id="search">
tại trường
<select name="field" id="field"></select>
</div>');

define('DATE_FORM', '<div class="date filter-section">
Từ ngày <input type="date" name="date-start" id="date-start"> đến <input type="date" name="date-end" id="date-end">
</div>');

abstract class ActionWindow {
    //put your code here
    public $user;
    
    public function __construct($user) {
        if (!($user instanceof User)) {
            throw \Exception("Expected \"User\" but got \"".gettype($user)."\"", 1000);
        }
        $this->user = $user;
    }
    
    public abstract function getWindow();
}
