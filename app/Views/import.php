<?php
if (!isset($_POST['username']) || !isset($_POST['token']) || !validSession($_POST['username'] , $_POST['token'])) {
    error(2);
}

if (!isset($_POST['view'])) {
    error(5);
}

$table = $_POST['view'];

$file = $_FILES['file'];  // get the uploaded file data
if (!empty($file)) {
    //open file with phpspreadsheet
    $db = db_connect();
    $db->query("TRUNCATE TABLE `qlhs_$table`");
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($_FILES["file"]["tmp_name"]);
    $worksheet = $spreadsheet->getActiveSheet();
    //read all rows and columns from the spreadsheet into an array
    $first_row = true;
    $header = [];
    foreach ($worksheet->getRowIterator() as $row) {
        $cells = $row->getCellIterator();
        if ($first_row) {
            foreach ($cells as $key => $value) {
                $header[] = $value->getValue();
            }
            $first_row = false;
        } else {
            $data = [];
            foreach ($cells as $key => $value) {
                $data[] = "'$value'";
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
        

} else {
    echo "Import using data";
}