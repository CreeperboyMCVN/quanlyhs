<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        helper('auth');
        $session = session();
        if (isset($session->username)) return redirect('app');
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }

        // auth
        if (isset($username) && isset($password)) $code = auth($username, $password);
        // redirect if auth success
        if (isset($code)) {
            if ($code == 0) {
                $session->set(['username' => $username, 'code' => 0]);
                return redirect('app');
            } else $session->set(['code' => $code]);
        }
        return view('login');
    }

    public function logout() {
        session()->destroy();
        return redirect('/');
    }

    public function spreadsheet() {
        return view('spreadsheetapi');
    }
}
