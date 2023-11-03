<?php
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
$type = $_POST['type'];

if (!validSession($username, $secert)) {
    error(4);
}

$db = db_connect();

$db->setPrefix('qlhs_');
$table = $db->prefixTable($type);

if (isset($_POST['search']) && $_POST['search'] != null) {
    $search_arr = explode('-', $_POST['search']);
    if (count($search_arr) < 2) {
        error(6);
    }
    $sr = '%'.$search_arr[1].'%';
    $query = $db->query("SELECT * FROM $table WHERE `$search_arr[0]` LIKE '$sr'");
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
    
    default:
        # code...
        break;
}

$response = ['code' => 0, 'message' => 'Thành công', 'data' => $query->getResult('array'),
'header' => $tableHeader,
'datefield' => $mainDateField];

exit(json_encode($response));