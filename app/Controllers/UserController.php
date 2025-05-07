<?php

declare(strict_types=1);

namespace App\Controllers;

use League\Plates\Engine;

class UserController
{

    public function index(): void
    {

        $template = new Engine('../app/Views');
        echo $template->render('users/index', ['title' => 'User List']);
    }

    public function store(): void
    {
        // Handle form submission
    }
}
