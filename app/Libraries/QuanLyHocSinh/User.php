<?php

namespace QuanLyHocSinh;

class User {
    public $username;
    public $window = 'menu';
    public $page = 1;
    public $view = 'students';
    public $max = 20;

    private $def_window = 'menu';
    private $def_page = 1;
    private $def_view = 'students';
    private $def_max = 20;

    private $database;

    public function __construct($username) {
        $this->username = $username;
        $this->database = db_connect();
    }

    public function getPageData() {
        $pageData = [];
        if ($this->window != $this->def_window) {
            $pageData = array_merge($pageData, ["window" => $this->window]);
        } 
        if ($this->page != $this->def_page) {
            $pageData = array_merge($pageData, ["page" => $this->page]);
        }
        if ($this->view != $this->def_view) {
            $pageData = array_merge($pageData, ["view" => $this->view]);
        }
        if ($this->max != $this->def_max) {
            $pageData = array_merge($pageData, ["max" => $this->max]);
        }
        return $pageData;
    }

    public function getPermissions() {
        $query = $this->database->query('SELECT `permission` FROM `qlhs_users` WHERE `username`="'.$this->username.'"');
        //echo var_dump($query);
        $arr = explode(',', $query->getFirstRow()->permission);
        if (in_array('*', $arr)) {
            return ['*' => '*'];
        }
        $res = [];
        foreach ($arr as $value) {
            $arr2 = explode("-", $value);
            if (count($arr) == 1) {
                $res = array_merge($res, [$arr2[0] => 'r']);
            } else {
                $res = array_merge($res, [$arr2[0] => $arr2[1]]);
            }
        }
        return $res;
    }

    public function hasPermission($perm, $rw):bool {
        $perms = $this->getPermissions();
        foreach ($perms as $k => $v) {
            if ($k == '*') return true;
            if ($k == $perm) {
                
                return ($v == $rw) || $v == '*';
            }
        }
        return false;
    }

    public function getSideBar() {
        $sidebar = new SidebarParser($this);
        return $sidebar->parse();
    }
    
    public function getViewWindow() {
        if (isset($_GET['import'])) {
            $aw = new ImportWindow($this->view);
        } else {
            $aw = new ListWindow($this->view);
        }
        if (!isset($aw)) return '';
        return $aw->getWindow();
    }
}