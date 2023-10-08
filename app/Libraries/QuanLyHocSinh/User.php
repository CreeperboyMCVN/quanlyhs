<?php

namespace QuanLyHocSinh;

class User {
    public $username;
    public $window = 'menu';
    public $page = 1;
    public $action = 'students';
    public $max = 20;

    private $def_window = 'menu';
    private $def_page = 1;
    private $def_action = 'students';
    private $def_max = 20;

    private $database;

    public function __construct($username) {
        $this->username = $username;
        $this->database = db_connect();
    }

    public function getUrlFormPageData() {
        $pageData = [];
        if ($this->window != $this->def_window) {
            $pageData = array_merge($pageData, ["window" => $this->window]);
        } 
        if ($this->page != $this->def_page) {
            $pageData = array_merge($pageData, ["page" => $this->page]);
        }
        if ($this->action != $this->def_action) {
            $pageData = array_merge($pageData, ["action" => $this->action]);
        }
        if ($this->max != $this->def_max) {
            $pageData = array_merge($pageData, ["max" => $this->max]);
        }
        return arrayToUrlFormEncoded($pageData);
    }

    public function getPermissions() {
        $query = $this->database->query('SELECT `permission` FROM `qlhs_users` WHERE `username`="'.$this->username.'"');
        //echo var_dump($query);
        return explode(',', $query->getFirstRow()->permission);
    }

    public function hasPermission($perm):bool {
        return in_array('*', getPermissions()) || in_array($perm, getPermissions());
    }
}