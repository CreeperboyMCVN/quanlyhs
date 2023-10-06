<?php
// no success
helper('qlhsutils');
$session = session();
$view = view('documents/login-form.html');
if (isset($session->code)) {
    $view = placeholder($view, 'error_code', resolveErrorCode($session->code));
    $session->remove('code');
}
echoDocument($view);
?>