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

//echo var_dump($user->getPermissions());


$workframe = view('documents/workframe.html');

if ($user->window == 'workframe') {
    $workframe = placeholder($workframe, 'document_title', 'Test');
    $workframe = placeholder($workframe, 'sidebar', $user->getSideBar());
    $workframe = placeholder($workframe, 'username', $user->username);
    $workframe = placeholder($workframe, 'secert_key', session()->secert);
    $workframe = placeholder($workframe, 'action_window', $user->getActionWindow());
    $workframe = placeholder($workframe, 'max', $user->max);
    $workframe = placeholder($workframe, 'user_window', $user->window);
    $workframe = placeholder($workframe, 'user_action', $user->action);
    $workframe = placeholder($workframe, 'user_page', $user->page);
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