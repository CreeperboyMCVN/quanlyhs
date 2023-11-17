<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['username']) || !isset($_POST['token']) || !validSession($_POST['username'] , $_POST['token'])) {
    error(2);
}

$db = db_connect();
$time = '';

if (isset($_POST['dateStart']) && isset($_POST['dateEnd'])) {
    $start = $_POST['dateStart'];
    $end = $_POST['dateEnd'];
    if ($start == '' && $end == '') {
        $log_data = $db->query('SELECT * FROM `qlhs_log`')->getResult('array');
        $time = 'từ trước đến nay';
    } else if ($start != '' && $end == '') {
        $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` >= '$start'")->getResult('array');
        $time = "từ ngày ".date("d/m/Y", strtotime($start));
    } else if ($start == '' && $end != '') {
        $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` <= '$end'")->getResult('array');
        $time = "trước ngày ".date("d/m/Y", strtotime($start));
    } else {
        $log_data = $db->query("SELECT * FROM `qlhs_log` WHERE `time` BETWEEN '$start' AND '$end'")->getResult('array');
        $time = "từ ngày ".date("d/m/Y", strtotime($start)). " đến ngày " . date("d/m/Y", strtotime($end));
    }
} else {
    $log_data = $db->query('SELECT * FROM `qlhs_log`')->getResult('array');
    $time = 'từ trước đến nay';
}

$mail_res = [];
$error = false;
$teachers = $db->query('SELECT * FROM `qlhs_teachers`')->getResult('array');
foreach ($teachers as $value) {
    $dat = getLog($value['class'], $log_data);
    if (count($dat) > 0) {
        $template = view('documents/mail-template.html');
        $template = placeholder($template, 'teacher', $value['name']);
        $template = placeholder($template, 'time', $time);
        $table = '<tr><th>Tên</th><th>Tổng điểm trừ</th><th>Vi phạm</th></tr>';
        $ct = 0;
        foreach ($dat as $val) {
            if ($ct % 2 == 0) {
                $table .= "<tr class='even'>";
            } else {
                $table .= "<tr class='odd'>";
            }
            $violateTxt = '';
            foreach($val['violate'] as $line) {
                $violateTxt .= $line."<br>";
            }
            $table .= "<td>" . $val['name'] . "</td>";
            $table .= "<td>" . $val['totalPoints'] . "</td>";
            $table .= "<td>" . $violateTxt . "</td>";
            $table .= "</tr>";
        }
        $template = placeholder($template, 'studentTable', $table);
        $subject = 'Sơ kết học sinh vi phạm';
        $body = $template;
        $res = sendMail($value['email'], $subject, $body);
        if ($res['status'] == -1) $error = true;
        array_push($mail_res, $res);
    }
}

if (!$error) {
    exit(json_encode(['status'=> 0, 'mailData' => $mail_res, 'message' => 'Thành công!']));
} else {
    exit(json_encode(['status'=> -1, 'mailData' => $mail_res, 'message' => 'Thất bại!']));
}

function getLog($class, $log_data) : array {
    $db = db_connect();
    $record = [];
    $student_data = $db->query("SELECT * FROM `qlhs_students` WHERE `class` = '$class'")->getResult('array');
    $violate_data = $db->query('SELECT * FROM `qlhs_violate`')->getResult('array');
    foreach ($student_data as $value) {

        $name = $value['name'];
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
                        array_push($violate, $value2['name'] . ' ' . 
                            date("d/m/Y", strtotime($value1['time'])));
                        $totalPts += $pts;
                    }
                }
                
            }
        }
        if ($totalPts > 0) {
            array_push($record, [
                "name" => $name,
                "gender" => $gender,
                "totalPoints" => $totalPts,
                "violate" => $violate
            ]);
        }
    }
    return $record;
}


function sendMail($reciever, String $subject = 'Default Subject', String $body = 'Default body', String $alt = 'Alt body') {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'quanlyhocsinhthptvk@gmail.com';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;  
        $mail->CharSet    = 'UTF-8';
                                          //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('quanlyhocsinhthptvk@gmail.com', 'Hệ thống quản lý học sinh THPT Vĩnh Kim');
        $mail->addAddress($reciever, 'Reciever');     //Add a recipient
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $alt;
    
        $mail->send();
        return ['reciever' => $reciever, 'status' => 'OK'];
        
    } catch (Exception $e) {
        return ['reciever' => $reciever, 'status' => 'Failed', 'message' => $mail->ErrorInfo];
    }
}