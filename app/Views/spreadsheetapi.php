<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$spreadsheet = new Spreadsheet();

$filename = 'spreadsheet';
if (isset($_POST['filename'])) $filename = $_POST['filename'];

if (!isset($_POST['data'])) {
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(['code' => 1, 'message' => 'Data is not given!']));
}

$data = json_decode($_POST['data']);

if (!isset($data) || $data == NULL) {
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(['code' => 2, 'message' => 'Data is not in json encoded!']));
}

try {
    $spreadsheet->getActiveSheet()->fromArray($data, NULL, 'A1');
} catch (\Throwable $e) {
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(['code' => 3, 'message' => 'Error while creating spreadsheet. Maybe the data is a component, not a list or 2d list']));
}


$writer = new Xlsx($spreadsheet);
$writer->save(config('App')->SpreadsheetsDir . $filename . '.xlsx');

header('Content-Type: application/json; charset=utf-8');
exit(json_encode(['code' => 0, 'filename' => config('App')->baseURL . config('App')->SpreadsheetsDir . $filename . '.xlsx', 'message' => 'Spreadsheet created successfully!']));