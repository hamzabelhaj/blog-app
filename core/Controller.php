<?php

namespace Core;
use \League\Plates\Engine;

abstract class Controller
{
    public function __construct()
    {
        //creates session token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // to render a view template
    protected function view(string $template, array $data = []): void
    {
        $templates = new Engine('../app/Views');
        echo $templates->render($template, $data);
    }
}