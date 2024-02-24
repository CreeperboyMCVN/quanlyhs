<?php

namespace QuanLyHocSinh;

class User {
    public $username;
    public $window = 'menu';
    public $page = 1;
    public $view = 'students';
    public $max = 20;
    public $action = 'list';

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
        return $query->getFirstRow()->permission;
    }

    public function hasPermission($perm):bool {
        $perms = $this->getPermissions();
        return $perm == $perms;
    }

    public function getSideBar() {
        $sidebar = new SidebarParser($this);
        return $sidebar->parse();
    }
    
    public function getViewWindow() {
        if (isset($_GET['edit'])) {
            $aw = new EditWindow($this->view, $this);
        } else if (isset($_GET['import'])) {
            $aw = new ImportWindow($this->view, $this);
        } else {
            $aw = new ListWindow($this->view, $this);
        }
        if (!isset($aw)) return '';
        return $aw->getWindow();
    }
}