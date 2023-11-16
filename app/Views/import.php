<?php

use QuanLyHocSinh\User;

if (!isset($_POST['username']) || !isset($_POST['token']) || !validSession($_POST['username'] , $_POST['token'])) {
    error(2);
}

if (!isset($_POST['view'])) {
    error(5);
}

$username = $_POST['username'];
$user = new User($username);
if (!$user->hasPermission('supervisor') && !$user->hasPermission('admin')) {
    error(2);
}

$table = $_POST['view'];
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];  // get the uploaded file data
}
if (isset($file) && !empty($file)) {
    //open file with phpspreadsheet
    $db = db_connect();
    $db->query("TRUNCATE TABLE `qlhs_$table`");
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($_FILES["file"]["tmp_name"]);
    $worksheet = $spreadsheet->getActiveSheet();
    //read all rows and columns from the spreadsheet into an array
    $first_row = true;
    $header = [];
    $pwCol = -1;
    foreach ($worksheet->getRowIterator() as $row) {
        $cells = $row->getCellIterator();
        if ($first_row) {
            foreach ($cells as $key => $value) {
                $header[] = '`'.$value->getValue().'`';
            }
            if (array_search('`password`', $header)) {
                $pwCol = array_search('`password`', $header);
            }
            $first_row = false;
        } else {
            $data = [];
            $i = 0;
            foreach ($cells as $key => $value) {
                if ($i == $pwCol) {
                    $pw = generatePassword($value);
                    $data[] = "'$pw'";
                } else {
                    $data[] = "'$value'";
                }
                $i++;
            }
            $fields = join(', ', $header);
            $values = join(', ', $data);
            $query = "INSERT INTO `qlhs_$table` ($fields) VALUES ($values)";
            
            try {
                $db->query($query);
            } catch (\Throwable $t) {
                //json header
                header('Content-Type: application/json');
                die(json_encode(['code' => $t->getCode(), 'message' => $t->getMessage(), 'query' => $query]));
            }
        }
        
    }
    header('Content-Type: application/json');
    die(json_encode(['code' => 0, 'message' => 'Thành công!']));
        

} else {
    $arr = [];
    parse_str($_POST['data'], $arr);
    $pwIdx = -1;
    $pw = '';
    $i = 0;
    foreach ($arr as $key => $value) {
        # code...
        if ($value == '') error(7);
        if ($key == 'password') {
            $pw = generatePassword($value);
            $pwIdx = $i;
        }
        $i++;
    }
    $value = array_values($arr);
    if ($pwIdx != -1) {
        $value[$pwIdx] = $pw;
    }
    $fields = '`' . implode('`,`', array_keys($arr)) . '`';
    $values = '\'' . implode('\',\'', $value) . '\'';

    $db = db_connect();
    $sql = "INSERT INTO `qlhs_$table` ($fields) VALUES ($values)";
    header('Content-Type: application/json');
    try {
        $db->query($sql);
        die( json_encode([
            'code' => 0,
            'message' => 'Thêm thành công!'
        ]));
    } catch (\Throwable $e) {
        die( json_encode([
            'code' => $e->getCode(),
            'message' => $e->getMessage()
            ]));
    }
    
}