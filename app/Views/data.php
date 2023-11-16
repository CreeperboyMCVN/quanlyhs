<?php

use QuanLyHocSinh\User;

header('Content-Type: application/json; charset=utf-8');
$response = [];
if (!isset($_POST['secert']) || !isset($_POST['username']) || $_POST['secert'] == null || $_POST['username']==null) {
    error(2);
}

if (!isset($_POST['type']) || $_POST['type'] == null) {
    error(5);
}

$secert = $_POST['secert'];
$username = $_POST['username'];
$user = new User($username);
$type = $_POST['type'];

if (!validSession($username, $secert)) {
    error(4);
}

$gb = isset($_POST['global_search']);
$strict = isset($_POST['strict']);

$db = db_connect();

$db->setPrefix('qlhs_');
$table = $db->prefixTable($type);

if (isset($_POST['search']) && $_POST['search'] != null && !$gb) {
    $search_arr = explode('-', $_POST['search']);
    if (count($search_arr) < 2) {
        error(6);
    }
    $sr = '%'.$search_arr[1].'%';
    if (!$strict) {
        $query = $db->query("SELECT * FROM $table WHERE `$search_arr[0]` LIKE '$sr'");
    } else {
        $query = $db->query("SELECT * FROM $table WHERE `$search_arr[0]` = '$search_arr[1]'");
    }
    
} else if (isset($_POST['search']) && $_POST['search'] != null && $gb) {
    $tbdes = $db->query("DESCRIBE $table");
    $tbdesRes = $tbdes->getResult('array');
    $likeStmt = '';
    $sear = $_POST['search'];
    foreach ($tbdesRes as $val) {
        $f = $val['Field'];
        $likeStmt .= " `$f` LIKE '%$sear%' OR";
    }
    $likeStmt = rtrim($likeStmt, 'OR');
    $query = $db->query("SELECT * FROM $table WHERE $likeStmt");
} else {
    $query = $db->query("SELECT * FROM $table");
}

$mainDateField = '';
$tableHeader = [];

switch ($type) {
    case 'students':
        # code...
        $tableHeader = ['Mã học sinh', 'Tên', 'Lớp', 'Ngày sinh', 'Giới tính'];
        $mainDateField = 'dob';
        break;
    case 'teachers':
        # code...
        $tableHeader = ['Mã giáo viên', 'Tên', 'Lớp', 'Email', 'Tổ'];
        $mainDateField = '';
        break;

    case 'violate':
        # code...
        $tableHeader = ['Mã vi phạm', 'Tên', 'Điểm trừ'];
        $mainDateField = '';
        break;
    case 'log':
        # code...
        $tableHeader = ['Mã nhật ký', 'Mã học sinh vi phạm', 'Mã vi phạm', 'Thời gian', 'Giám thị', 'Trừ điểm?'];
        $mainDateField = 'time';
        break;
    case 'users':
        $tableHeader = ['ID', 'Tên đăng nhập', 'Quyền'];
        $mainDateField = '';
        break;
    
    default:
        # code...
        break;
}

$response = ['code' => 0, 'message' => 'Thành công', 'data' => $query->getResult('array'),
'header' => $tableHeader,
'datefield' => $mainDateField];

exit(json_encode($response));