<?php

namespace App\Controllers;

class App extends BaseController
{
    public function index()
    {
        $session = session();
        if (isset($_COOKIE['qlhs_user_token'])) {
            if (!validSession($_COOKIE['qlhs_user_name'], $_COOKIE['qlhs_user_token'])) {
                $session->set(['code' => 4]);
                delete_cookie('qlhs_user_token');
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
        
        //return view('app');
        return view('app');
    }

    
}
