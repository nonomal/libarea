<?php

namespace App\Controllers\Auth;

class LogoutController extends \MainController
{
    public function index()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        // Возможно, что нужно очистить все или некоторые cookies
        setcookie("remember", "", time() - 3600, "/");
        redirect('/');
    }
}
