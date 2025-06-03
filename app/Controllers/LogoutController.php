<?php

/**
 * LogoutController Class
 * Handles user logout.
 */

namespace App\Controllers;

class LogoutController
{
    /**
     * logs user out
     * 
     * @return void
     */
    public function logoutUser(): void
    {
        session_unset();
        session_destroy();
        header('location: ' . url('login'));
        exit;
    }
}
