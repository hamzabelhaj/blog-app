<?php
namespace App\Controllers;

use Core\Controller;
use League\Plates\Engine;



class DashboardController extends Controller
{

    public function index(): void
    {

        $template = new Engine('../app/Views');
        echo $template->render('home');
    }
}
