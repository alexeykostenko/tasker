<?php

namespace Library;

class Auth
{
    private $user;

    public function login($user)
    {
        return session()->put('auth', $user);
    }

    public function check()
    {
        return session()->has('auth');
    }

    public function user()
    {
        session()->get('auth');
    }

    public function logout()
    {
        return session()->forget('auth');
    }
}
