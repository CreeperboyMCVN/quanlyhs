<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        helper('auth');
        $session = session();
        if (isset($_COOKIE['qlhs_user_token'])) {
            $name = $_COOKIE['qlhs_user_name'];
            if (validSession($name, $_COOKIE['qlhs_user_token'])) return redirect('app');
        }
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
                newSession($username);
                return redirect('app');
            } else $session->set(['code' => $code]);
        }
        return view('login');
    }

    public function logout() {
        session()->destroy();
        delete_cookie('qlhs_user_token');
        delete_cookie('qlhs_user_name');
        return redirect('/');
    }

    public function spreadsheet() {
        return view('spreadsheetapi');
    }
    
    public function setup() {
        return view('setup');
    }

    public function data() {
        return view('data');
    }

    public function import() {
        return view('import');
    }

    public function edit() {
        return view('edit');
    }
}
