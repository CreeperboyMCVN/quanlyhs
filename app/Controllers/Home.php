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
        } else if (isset($session->username)) {
            return redirect('app');
        } else if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }
        // auth
        
        if (isset($username) && isset($password)) $code = auth($username, $password);
        // redirect if auth success
        if (isset($code)) {
            // log
            $db = db_connect();
            $pq = $db->prepare(static function ($db) {
                return $db->table('qlhs_login_log')
                          ->insert([
                              'user' => null,
                              'status' => 0
                          ]);
            });
            if ($code != 1) $pq->execute($username, $code);
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

    public function record() {
        return view('record');
    }

    public function mail() {
        return view('mail');
    }

    public function about() {
        return view('documents/khkt.html');
    }

    public function recordSpreadsheet() {
        return view('recordspr');
    }
}
