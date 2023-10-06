<?php
$window = 'menu';
$page = 1;
$action = 'students';
$max = 20;

if (isset($_GET['window'])) {
    $window = $_GET['window'];
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['max'])) {
    $max = $_GET['max'];
}

$pageData = [
    'window' => $window,
    'page'   => $page,
    'action' => $action,
    'max'    => $max
];



$workframe = view('documents/workframe.html');

if ($window == 'workframe') {
    $workframe = placeholder($workframe, 'document_title', 'Test');
    $workframe = placeholder($workframe, 'sidebar_content', parseSidebarContent($pageData, $action));
}

switch ($window) {
    case 'menu':
        # something with menu
        echoDocument(view('documents/menu.html'));
        break;

    case 'workframe':
        echoDocument($workframe);
        break;
    
    default:
        # return menu
        echo $window;
        echoDocument(view('documents/menu.html'));
        break;
}