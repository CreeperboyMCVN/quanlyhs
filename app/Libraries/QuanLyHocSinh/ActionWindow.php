<?php

namespace QuanLyHocSinh;

/**
 * Description of ActionWindow
 *
 * @author long
 */
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
