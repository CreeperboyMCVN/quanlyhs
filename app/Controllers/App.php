<?php

namespace App\Controllers;

class App extends BaseController
{
    public function index()
    {
        $session = session();
        //echo $session->username;
        if ($session->username == NULL) return redirect('/');
        //return view('app');
        return view('app');
    }

    
}
