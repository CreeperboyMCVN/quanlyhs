<?php

use QuanLyHocSinh\User;

header('Content-Type: application/json; charset=utf-8');
if (!isset($_POST['token']) || !isset($_POST['username'])) {
    error(2);
}
$username = $_POST['username'];
$user = new User($username);
$token = $_POST['token'];
if (!validSession($username, $token)) {
    error(2);
}

if (!isset($_POST['view'])) {
    error(5);
}

$table = $_POST['view'];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = "edit";
}

if (!isset($_POST['primary'])) {
    error(8);
}

$primary = $_POST['primary'];

if (!isset($_POST['data'])) {
    error(6);
}

$data = [];
parse_str($_POST['data'], $data);

$primOpt = explode('-',$primary);
$cond = "`$primOpt[0]` = '$primOpt[1]'";

$db = db_connect();
$dat = '';
foreach ($data as $key => $value) {
    # code...
    if ($key == 'password') {
        $pw = generatePassword($value);
        $dat .= "`$key`='$pw',";
    } else {
        $dat .= "`$key`='$value',";
    }
}
$dat = substr($dat, 0, strlen($dat)-1);

switch ($action) {
    case 'edit':
        $query = "UPDATE `qlhs_$table` SET $dat WHERE $cond";
        try {
            $sql = $db->query($query);
            $affR = $db->affectedRows();
            die(json_encode(['code' => 0, 'message' => "Thành công, $affR dòng bị ảnh hưởng", 'query' => $query]));
            //$db->affectedRows();
        } catch (\Throwable $e) {
            die(json_encode(['code' => $e->getCode(), 'message' => $e->getMessage(), 'query' => $query]));
        }
        break;
    case 'delete':
        $query = "DELETE FROM `qlhs_$table` WHERE $cond";
        try {
            $sql = $db->query($query);
            $affR = $db->affectedRows();
            die(json_encode(['code' => 0, 'message' => "Thành công, $affR dòng bị ảnh hưởng", 'query' => $query]));
        } catch (\Throwable $e) {
            die(json_encode(['code' => $e->getCode(), 'message' => $e->getMessage(), 'query' => $query
            ]));
        }
        break;
    case 'delete-all':
        $query = "TRUNCATE TABLE `qlhs_$table`";
        try {
            $sql = $db->query($query);
            $affR = $db->affectedRows();
            die(json_encode(['code' => 0, 'message' => "Thành công, $affR dòng bị ảnh hưởng", 'query' => $query]));
        } catch (\Throwable $e) {
            die(json_encode(['code' => $e->getCode(), 'message' => $e->getMessage(), 'query' => $query
            ]));
        }
        break;
}