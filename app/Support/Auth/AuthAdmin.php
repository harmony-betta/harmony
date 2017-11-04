<?php

namespace App\Support\Auth;

use App\Models\Admins;

class AuthAdmin
{
    public function admin()
    {
        return Admins::find(@$_SESSION['admin']);
    }

    public function checkAdmin()
    {
        return isset($_SESSION['admin']);
    }

    public function attemptAdmin($email, $password)
    {
        // grab user by email
        $admin = Admins::where('email', $email)->first();

        // if !admin return false
        if (!$admin) {
            return false;
        }

        // verify password for that user
        if (password_verify($password, $admin->password)) {
            // set into session
            $_SESSION['admin'] = $admin->id;
            return true;
        }

        return false;
    }

    public function logoutAdmin()
    {
        unset($_SESSION['admin']);
    }
}