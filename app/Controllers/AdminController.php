<?php

namespace App\Controllers;

class AdminController
{
    public function login()
    {
        $login = request()->login;
        $password = request()->password;

        if (!$login && !$password) {
            return request()->back();
        }

        if ($login !== config('admin_login')) {
            return request()->back();
        }

        if ($password !== config('admin_password')) {
            return request()->back();
        }

        auth()->login($login);

        return request()->back();
    }

    public function logout()
    {
        auth()->logout();

        return request()->back();
    }
}
