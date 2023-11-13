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
$record = [];

switch ($type) {
    case 'none':
        # code...
        $student_data = $db->query('SELECT * FROM `qlhs_students`')->getResult('array');
        $violate_data = $db->query('SELECT * FROM `qlhs_violate`')->getResult('array');
        $log_data = $db->query('SELECT * FROM `qlhs_log`')->getResult('array');
        foreach ($student_data as $value) {
            # code...
            
            $name = $value['name'];
            $class = $value['class'];
            $gender = $value['gender'];
            $totalPts = 0;
            $violate = [];
            foreach ($log_data as $value1) {
                # code...
                if ($value1['student_id'] == $value['id']) {
                    $pts = 0;
                    foreach ($violate_data as $value2) {
                        if ($value2['id'] == $value1['violate_id']) {
                            $pts = $value2['points'];
                            $totalPts += $pts;
                            $id = $value1['id'];
                            $sv = $value1['supervisor'];
                            array_push($violate, $value2['name'] . ' ' . 
                                date("d/m/Y", strtotime($value1['time'])) .
                                " ($sv, $id)");
                        }
                    }
                    
                }
            }
            if ($totalPts > 0) {
                array_push($record, [
                    "name" => $name,
                    "class" => $class,
                    "gender" => $gender,
                    "totalPoints" => $totalPts,
                    "violate" => $violate
                ]);
            }
            
        }
        die(json_encode(['code' => 0, 'message' => 'Thành công', 'data' => $record, 'tableHeader' 
            => ['Tên', 'Lớp', 'Giới tính', 'Tổng điểm trừ', 'Vi phạm']]));
        break;

    case 'detail':
        if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
            $start = $_POST['dateStart'];
            $end = $_POST['dateEnd'];
            if ($start == '' && $end == '') {
                $log_data = $db->query('SELECT * FROM `qlhs_log`')->getResult('array');
            } else if ($start != '' && $end == '') {
                $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` >= '$start'")->getResult('array');
            } else if ($start == '' && $end != '') {
                $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` <= '$end'")->getResult('array');
            } else {
                $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` BETWEEN '$start' AND '$end'")->getResult('array');
            }
        } else {
            $log_data = $db->query('SELECT * FROM `qlhs_log`')->getResult('array');
        }
        if (isset($_POST['class']) && $_POST['class'] != '') {
            $class = $_POST['class'];
            $student_data = $db->query("SELECT * FROM `qlhs_students` WHERE `class` = '$class'")->getResult('array');
        } else {
            $student_data = $db->query('SELECT * FROM `qlhs_students`')->getResult('array');
        }
        $violate_data = $db->query('SELECT * FROM `qlhs_violate`')->getResult('array');
        foreach ($student_data as $value) {
            # code...
            
            $name = $value['name'];
            $class = $value['class'];
            $gender = $value['gender'];
            $totalPts = 0;
            $violate = [];
            foreach ($log_data as $value1) {
                # code...
                if ($value1['student_id'] == $value['id']) {
                    $pts = 0;
                    foreach ($violate_data as $value2) {
                        if ($value2['id'] == $value1['violate_id']) {
                            $pts = $value2['points'];
                            $supervisor = $value1['supervisor'];
                            $id = $value1['id'];
                            array_push($violate, $value2['name'] . ' ' . 
                                date("d/m/Y", strtotime($value1['time'])) . 
                                " ($supervisor, $id)");
                            $totalPts += $pts;
                        }
                    }
                    
                }
            }
            if ($totalPts > 0) {
                array_push($record, [
                    "name" => $name,
                    "class" => $class,
                    "gender" => $gender,
                    "totalPoints" => $totalPts,
                    "violate" => $violate
                ]);
            }
            
        }
        die(json_encode(['code' => 0, 'message' => 'Thành công', 'data' => $record, 'tableHeader' 
            => ['Tên', 'Lớp', 'Giới tính', 'Tổng điểm trừ', 'Vi phạm'],
            ]));
        break;
    
}
