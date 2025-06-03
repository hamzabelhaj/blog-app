<?php

/**
 * Base Controller class
 * Renders a view template and sets the csrf token for csrf validation.
 */

namespace App\Controllers;

use \League\Plates\Engine;

abstract class BaseController
{
    public function __construct()
    {
        //creates session token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Renders a view template and passes data to the view
     * @param string $template  the view templates to be rendered
     * @param null|array $data  the data to be passed to the view
     */
    protected function view(string $template, ?array $data = []): void
    {
        $templates = new Engine('../app/Views');
        $user = $_SESSION['user'] ?? null;
        $templates->addData([ //passes data to all templates
            'user' => $user,
            'isLoggedIn' => isset($user['id']),
            'role' => $user['role'] ?? null,
            'csrf_token' => $_SESSION['csrf_token'],
        ]);
        echo $templates->render($template, $data);
    }
}
