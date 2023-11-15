<?php
use QuanLyHocSinh\User;
use QuanLyHocSinh\MenuWindow;
use QuanLyHocSinh\InputPosWindow;

if (isset(session()->username)) {
    $user = new User(session()->username);
} else {
    $user = new User($_COOKIE['qlhs_user_name']);
}

if (isset($_GET['window'])) {
    $user->window = $_GET['window'];
}

if (isset($_GET['import'])) {
    $user->action = 'import';
}

if (isset($_GET['edit'])) {
    $user->action = 'edit';
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
$pos = view('documents/pos.html');

if ($user->window == 'workframe') {
    $workframe = placeholder($workframe, 'document_title', 'Test');
    $workframe = placeholder($workframe, 'sidebar', $user->getSideBar());
    $workframe = placeholder($workframe, 'action_window', $user->getViewWindow());
    $workframe = placeholder($workframe, 'max', $user->max);
    $workframe = placeholder($workframe, 'username', $user->username);
    $workframe = placeholder($workframe, 'user_window', $user->window);
    $workframe = placeholder($workframe, 'user_view', $user->view);
    $workframe = placeholder($workframe, 'import_link', basename($_SERVER['REQUEST_URI']) . '&import=');
    $workframe = placeholder($workframe, 'base_url', base_url());
    // js for view
    switch ($user->action) {
        case 'edit':
            $workframe = placeholder($workframe, 'javascript_file', './js/qlhs/edit-window.js');
            break;
        case 'import':
            $workframe = placeholder($workframe, 'javascript_file', './js/qlhs/import-window.js');
            break;
        default:
            $workframe = placeholder($workframe, 'javascript_file', './js/qlhs/list-window.js');
            break;
    }
}

if ($user->window == 'pos') {
    switch ($user->view) {
        case 'record':
            $pos = placeholder($pos, 'pos_content', view('documents/stats-pos.html'));
            $pos = placeholder($pos, 'window_title', 'POS Sơ kết');
            $pos = placeholder($pos, 'javascript_file', './js/qlhs/record-pos.js');
            break;
        default:
            $pos = placeholder($pos, 'pos_content', (new InputPosWindow($user))->getWindow());
            $pos = placeholder($pos, 'javascript_file', './js/qlhs/input-pos.js');
            break;
    }
}

//echo var_dump($user->getUrlFormPageData());

switch ($user->window) {
    case 'menu':
        # something with menu
        $vi = new MenuWindow($user);
        echoDocument($vi->getWindow());
        break;

    case 'workframe':
        echoDocument($workframe);
        break;

    case 'pos':
        echoDocument($pos);
        break;
    
    default:
        # return menu
        //echo $window;
        $vi = new MenuWindow($user);
        echoDocument($vi->getWindow());
        break;
}
