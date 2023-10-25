<?php
namespace QuanLyHocSinh;

/*
* ImportWindow
*/

class ImportWindow extends ActionWindow {

    public function __construct($view) {
        $this->view = $view;
    }

    public function getWindow()
    {
        $v = view('documents/import-window.html');
        return $v;
    }
}