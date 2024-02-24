<?php
function auth($username, $password) {
    if ($username == "") return 3;
    $db = db_connect();

    $query = $db->query('SELECT * FROM `qlhs_users` WHERE `username`="'.$username.'"');
    if ($query->getNumRows() == 0) return 1;
    $pass = $query->getFirstRow()->password;
    $passComp = explode('$', $pass);
    $encodedPass = $passComp[0];
    //echo var_dump($passComp);
    $salt = $passComp[1];
    if (validate($password, $encodedPass, $salt)) {return 0;} else {return 2;}
}

function validate($rawPass, $encoded, $salt):bool
{
    $hash = hash('sha256', $rawPass);
    $pass = hash('sha256', $hash . $salt);
    return $pass == $encoded;
}

function randomString($length):string
{
    $lowercase = "abcdefghijklmnopqrstuvwxyz";
    $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $number = "1234567890";

    $str = "";

    for ($i=0; $i < $length; $i++) { 
        # code...
        $case = random_int(0,2);
        switch ($case) {
            case 0:
                # code...
                $str .= $lowercase[random_int(0, strlen($lowercase)-1)];
                break;
            case 1:
                    # code...
                $str .= $uppercase[random_int(0, strlen($uppercase)-1)];
                break;
            
            case 2:
                # code...
                $str .= $number[random_int(0, strlen($number)-1)];
                break;
        }
    }
    return $str;
}

function generatePassword($password):string {
    $salt = randomString(16);
    $hash = hash('sha256', $password);
    $pwd = hash('sha256', $hash . $salt);
    return $pwd . '$' . $salt;
}

function validSession($username, $token) {
    $db = db_connect();
    $res = $db->query('SELECT * FROM `qlhs_token` WHERE `user`="'. $username .'"');
    if ($res->getNumRows() == 0) {
        return false;
    }
    foreach ($res->getResult() as $value) {
        # code...
        if ($token == $value->token) {
            if (time() > $value->expiry) return false;
            return true;
        }
    }
    
    return false;
}

function newSession($username) {
    $token = randomString(32);
    $db = db_connect();

    $db->query('INSERT INTO `qlhs_token` (`token`, `user`, `expiry`) VALUES 
    ("'.$token.'", "'.$username.'", DATE_ADD(NOW(), INTERVAL \'30\' DAY))');

    $session = session();
    $session->set(['username' => $username]);

    setcookie('qlhs_user_token', $token, time() + 60*60*24*30);
    setcookie('qlhs_user_name', $username, time() + 60*60*24*30);
}

function delete_cookie($cookie) {
    if(isset($_COOKIE[$cookie])){
        setcookie($cookie, '' ,-1);
    }
}