<?php
header('Content-Type: application/json; charset=utf-8');
$response = [];
if (!isset($_POST['secert']) || !isset($_POST['username']) || $_POST['secert'] == null || $_POST['username']==null) {
    $response = ['code' =>2 , 'message' => resolveErrorCode(2)];
    exit(json_encode($response));
}

if (!isset($_POST['type']) || $_POST['type'] == null) {
    $response = ['code' =>5 , 'message' => resolveErrorCode(5)];
    exit(json_encode($response));
}

$secert = $_POST['secert'];
$username = $_POST['username'];
$type = $_POST['type'];

if (!validSession($username, $secert)) {
    $response = ['code' =>4 , 'message' => resolveErrorCode(4)];
    exit(json_encode($response));
}

$db = db_connect();

$db->setPrefix('qlhs_');
$table = $db->prefixTable($type);

if (isset($_POST['search']) && $_POST['search'] != null) {
    $search_arr = explode('-', $_POST['search']);
    if (count($search_arr) < 2) {
        $response = ['code' =>6 , 'message' => resolveErrorCode(6)];
        exit(json_encode($response));
    }
    $sr = '%'.$search_arr[1].'%';
    $query = $db->query("SELECT * FROM $table WHERE `$search_arr[0]` LIKE '$sr'");
} else {
    $query = $db->query("SELECT * FROM $table");
}

$response = ['code' => 0, 'message' => 'Thành công', 'data' => $query->getResult('array')];

exit(json_encode($response));