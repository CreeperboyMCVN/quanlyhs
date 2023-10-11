<?php

namespace QuanLyHocSinh\Html;

use QuanLyHocSinh\Utils;

class Table {
    public $data;
    public $header;

    public function __construct($data) {
        $this->data = $data;
        
    }

    public function setHeader($header) {
        $this->header = $header;
    }

    public function getTable() {
        $res = '';
        $header_class = 'table-header';
        $even = 'table-even';
        $odd = 'table-odd';
        if ($this->header != null) {
            $res .= "<tr class='$header_class'>";
            foreach ($this->header as $value) {
                # code...
                $res .= "<th>$value</th>";
            }
            $res .= '</tr>';
        }
        $k = 0;
        foreach ($this->data as $value) {
            # code...
            if ($k % 2 == 0) {$c = $even;} else {$c = $odd;}
            $res .= "<tr class='$c'>";
            foreach ($value as $v) {
                # code...
                $res .= "<td>$v</td>";
            }
            $res .= '</tr>';
            $k ++;
        }
        return $res;
    }


}