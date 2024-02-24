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
    $spreadsheet->getActiveSheet()->setCellValue("A1", "SƠ KẾT VI PHẠM CỦA HỌC SINH");
    $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
    $style = [
        'font'      => [
            'bold' =>  true,
            'size' =>  20,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle("A1")->applyFromArray($style);
    $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
    // top
    $style = [
        'font'      => [
            'bold' =>  true,
            'color'  => ['argb' => '1212FD'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'border' => [
            'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'color'  => ['argb' => '000000'],
        ]
    ];
    $spreadsheet->getActiveSheet()->getStyle("A2:F2")->applyFromArray($style);
    $spreadsheet->getActiveSheet()->fromArray($data, NULL, 'A2');
} catch (\Throwable $e) {
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode(['code' => 3, 'message' => 'Error while creating spreadsheet. Maybe the data is a component, not a list or 2d list']));
}

// Auto column width for all column spreadsheet
/*
foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
    foreach ($worksheet->getColumnDimensions() as $columnDimension) {
        // Set auto size to true
        $columnDimension->setAutoSize(true);
    }
}

foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
    $tableArray = $worksheet->toArray();
    $idx = 0;
    foreach ($worksheet->getRowDimensions() as $rows) {
        $maxLnFeed = 0;
        foreach ($tableArray[$idx] as $cell) {
            $lnFeed = substr_count($cell, '\n');
            if ($lnFeed > $maxLnFeed) $maxLnFeed = $lnFeed;
        }
        $rows->setRowHeight(($maxLnFeed+1)
    }
}
*/
$debug = '';
$worksheet = $spreadsheet->getActiveSheet();
$cellItr = $worksheet->getRowIterator(2)->current()->getCellIterator();
$cellItr->setIterateOnlyExistingCells(true);
foreach($cellItr as $cell) {
    $worksheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    $width = $worksheet->getColumnDimension($cell->getColumn())->getWidth('px');
    
    $col = $worksheet->getColumnIterator($cell->getColumn(), $cell->getColumn())
        ->current()->getCellIterator();
    foreach ($col as $colCell) {
        $worksheet->getCell($colCell->getCoordinate())->getStyle()
            ->getAlignment()->setWrapText(true);
    }
}


$writer = new Xlsx($spreadsheet);
$writer->save(config('App')->SpreadsheetsDir . $filename . '.xlsx');

header('Content-Type: application/json; charset=utf-8');
exit(json_encode(['code' => 0, 'filename' => config('App')->baseURL . config('App')->SpreadsheetsDir . $filename . '.xlsx', 'message' => 'Spreadsheet created successfully!', 'debug' => $debug]));