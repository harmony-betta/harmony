<?php

namespace App\Support\Auth;

use App\Models\Users;

class Auth
{
    public function user()
    {
        return Users::find(@$_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password)
    {
        // grab user by email
        $user = Users::where('email', $email)->first();

        // if !user return false
        if (!$user) {
            return false;
        }

        // verify password for that user
        if (password_verify($password, $user->password)) {
            // set into session
            $_SESSION['user'] = $user->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}