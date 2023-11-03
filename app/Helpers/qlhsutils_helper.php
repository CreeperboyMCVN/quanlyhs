<?php
function resolveErrorCode($code) {
    switch ($code) {
        case 0:
            return "";
        case 1:
            # code...
            return "Người dùng không tồn tại!";
        case 2:
            return "Quyền truy cập bị từ chối!";
        case 3:
            return "Vui lòng nhập tên người dùng";
        case 4:
            return "Phiên đã hết hạn hoặc bạn đã đăng nhập trên thiết bị khác!";
        case 5:
            return "Vui lòng xác định bảng dữ liệu!";
        case 6:
            return "Không đủ tham số!";
        case 7:
            return "Vui lòng điền đủ thông tin!";
        default:
            # code...
            return "Lỗi không xác định!";
            
    }
}

function error($code) {
    // json header
    header('Content-Type: application/json');
    $response = ['code' => $code, 'message' => resolveErrorCode($code)];
    die(json_encode($response));
}

function placeholder($view, $placeholder, $replace) {
    return str_replace('${'.$placeholder.'}', $replace, $view);
}

function clearUnusedPlaceholder($view) {
    return preg_replace('/\$\{.*\}/i', '', $view);
}

function echoDocument($view) {
    $v = $view;
    $v = clearUnusedPlaceholder($v);
    echo $v;
}

function arrayToUrlFormEncoded($array) {
    $url = '?';
    foreach ($array as $key => $value) {
        # code...
        $url .= $key . '=' . $value . '&';
    }
    $url = preg_replace('/.$/i', '', $url);
    return $url;
}

function parseSideBarContent($pageData, $select) {
    $actions = [
        'students' => 'Danh sách học sinh',
        'years'    => 'Năm học'
    ];
    
    $selected_class = 'selected';
    $btn_class = 'sidebar-button';
    $ctn_class = 'sidebar-container';

    $out = parseHtmlTag('ul', [$ctn_class]);
    foreach ($actions as $key => $value) {
        $tmpPD = $pageData;
        $tmpPD['action'] = $key;
        if ($select == $key) {
            $out .= anchor(current_url().arrayToUrlFormEncoded($tmpPD), parseHtmlTag('li', 
                []) . $value . '</li>', ['class' => $selected_class. ' '. $btn_class]);
        } else {
            $out .= anchor(current_url().arrayToUrlFormEncoded($tmpPD), parseHtmlTag('li', 
                []) . $value . '</li>', ['class' => $btn_class]);
        }
    }
    $out .= '</ul>';
    return $out;
}

function parseHtmlTag($tag, $class) {
    return '<' . $tag . ' class="' . implode(' ', $class) . '">';
}