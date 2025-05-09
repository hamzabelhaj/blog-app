<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use League\Plates\Engine;



class HomeController extends Controller
{

    public function index(): void
    {
        $template = new Engine('../app/Views');
        echo $template->render('home');
    }
}
