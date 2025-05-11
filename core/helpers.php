<?php

declare(strict_types=1);

use Pecee\SimpleRouter\SimpleRouter as Router;
use \Pecee\Http\Request;
use \Pecee\Http\Response;

/*
|--------------------------------------------------------------------------
| Routing Helpers
|--------------------------------------------------------------------------
| These functions assist with generating URLs and redirecting users
| based on routes defined in SimpleRouter.
*/

/**
 * Generate a full URL to a given path.
 * Example: url('users') → http://localhost/my-app/public/users
 */
function url(string $path = ''): string
{
    return rtrim(BASE_URL_PATH, '/') . '/' . ltrim($path, '/');
}

/**
 * Generate a URL to a named route with optional parameters.
 * Example: route('user.profile', ['id' => 5])
 */
function route(string $name, array $params = []): string
{
    return  Router::getUrl($name, $params) ?? '';
}

/**
 * Redirect to a given path or URL.
 * Example: redirect('/login')
 */
function redirect(string $url, int $code = null): void
{
    if ($code != null) {
        response()->httpCode($code);
    }
    response()->redirect($url);
}


/**
 * Check if the current request method is POST.
 */
function is_post(): bool
{
    return request()->getMethod() === 'post';
}

/**
 * Check if the current request method is GET.
 */
function is_get(): bool
{
    return request()->getMethod() === 'get';
}

/*
|--------------------------------------------------------------------------
| Output & Security
|--------------------------------------------------------------------------
| Output escaping and CSRF protection helpers.
*/

/**
 * Escape output for safe HTML rendering (prevents XSS).
 * Example: e($user->name)
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Get the current CSRF token (if CSRF middleware is used).
 */
function csrf_token(): ?string
{
    return request()->getCsrfToken() ?? $_SESSION['csrf_token'] ?? null;
}

/**
 * Generate a hidden CSRF input field for use in forms.
 * Example: <?= csrf_field() ?>
 */
function csrf_field(): string
{
    $token = csrf_token();
    return $token
        ? '<input type="hidden" name="csrf_token" value="' . e($token) . '">'
        : '';
}

/*
|--------------------------------------------------------------------------
| Utility Helpers
|--------------------------------------------------------------------------
| Useful for asset management and API responses.
*/

/**
 * Generate the full URL to a static asset like a CSS or JS file.
 * Example: asset('css/style.css')
 */
function asset(string $path): string
{
    return url('/') . ltrim($path, '/');
}

/**
 * Return a JSON response with status code.
 * Example: json_response(['message' => 'OK'], 200)
 */
function json_response(array $data, int $status = 200): void
{
    response()->httpCode($status);
    response()->header('Content-Type', 'application/json');
    echo json_encode($data);
    exit;
}
