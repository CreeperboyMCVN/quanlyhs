<?php

namespace App\Controllers;

class App extends BaseController
{
    public function index()
    {
        $session = session();
        //echo $session->username;
        if ($session->username == NULL) return redirect('/');
        if (!validSession($session->username, $session->secert)) {
            $session->set(['code' => 4]);
            $session->remove('username');
            $session->remove('secert');
            return redirect('/');
        }
        //return view('app');
        return view('app');
    }

    
}
