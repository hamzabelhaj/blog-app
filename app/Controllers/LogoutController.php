<?php

namespace App\Controllers;


use Core\Controller;
use App\Modules\UserModule;
use Config\Database;
use League\Plates\Engine;
use Respect\Validation\Validator as v;



class LogoutController
{

    public function logoutUser(): void
    {
        session_unset();
        session_destroy();
        header('location: ' . url('login'));
        exit;
    }
}
