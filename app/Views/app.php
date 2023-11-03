<?php
use QuanLyHocSinh\User;
use QuanLyHocSinh\Utils;
use QuanLyHocSinh\Html\Table;

if (isset(session()->username)) {
    $user = new User(session()->username);
} else {
    $user = new User($_COOKIE['qlhs_user_name']);
}

if (isset($_GET['window'])) {
    $user->window = $_GET['window'];
}

if (isset($_GET['page'])) {
    $user->page = $_GET['page'];
}

if (isset($_GET['view'])) {
    $user->view = $_GET['view'];
}

if (isset($_GET['max'])) {
    $user->max = $_GET['max'];
}

//echo var_dump($user->getPermissions());


$workframe = view('documents/workframe.html');

if ($user->window == 'workframe') {
    $workframe = placeholder($workframe, 'document_title', 'Test');
    $workframe = placeholder($workframe, 'sidebar', $user->getSideBar());
    $workframe = placeholder($workframe, 'action_window', $user->getViewWindow());
    $workframe = placeholder($workframe, 'max', $user->max);
    $workframe = placeholder($workframe, 'username', $user->username);
    $workframe = placeholder($workframe, 'user_window', $user->window);
    $workframe = placeholder($workframe, 'user_view', $user->view);
    $workframe = placeholder($workframe, 'import_link', basename($_SERVER['REQUEST_URI']) . '&import=');
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