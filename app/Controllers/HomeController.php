<?php

/**
 * HomeController Class
 * Handles rendering of the home view
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    /**
     * Renders the home view
     *
     * @return void
     */
    public function renderHomeView(): void
    {

        $this->view(
            'home',
            [
                'title' => 'Home Page',
            ]
        );
    }
}
