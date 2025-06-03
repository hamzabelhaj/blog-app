<?php

/**
 * DashboardController Class
 * Handles rendering of the dashboard view
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    /**
     * Renders dashboard layout with the specified subview and data.
     *
     * @param string|null $subview  The path to the specific dashboard subview to load.
     * @param array|null  $data     Additional data to pass to the view.
     *
     * @return void
     */
    public function renderDashboard(?string $subview = null, ?array $data = []): void
    {
        $user = $_SESSION['user'] ?? null;
        $role = $user['role'] ?? null;
        $isAdmin = ($role === 'admin');
        $isEditor = in_array($role, ['admin', 'editor']);

        $initialData = [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'isEditor' => $isEditor,
        ];

        $data = array_merge($initialData, $data);

        $this->view(
            'dashboard/layout',
            [
                'subView' => $subview,
                'data' => $data
            ]
        );
    }
}
