<?php
use QuanLyHocSinh\User;

$user = new User(session()->username);

if (isset($_GET['window'])) {
    $user->window = $_GET['window'];
}

if (isset($_GET['page'])) {
    $user->page = $_GET['page'];
}

if (isset($_GET['action'])) {
    $user->action = $_GET['action'];
}

if (isset($_GET['max'])) {
    $user->max = $_GET['max'];
}



$workframe = view('documents/workframe.html');

if ($user->window == 'workframe') {
    $workframe = placeholder($workframe, 'document_title', 'Test');
}

//echo var_dump($user->getUrlFormPageData());

switch ($user->window) {
    case 'menu':
        # something with menu
        echoDocument(view('documents/menu.html'));
        break;

    case 'workframe':
        echoDocument($workframe);
        break;
    
    default:
        # return menu
        //echo $window;
        echoDocument(view('documents/menu.html'));
        break;
}