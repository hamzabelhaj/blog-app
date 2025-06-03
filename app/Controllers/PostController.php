<?php

/**
 * PostController Class
 * Handles post related operations such as listing posts, creating, editing,
 * deleting posts.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Models\PostModel;
use App\Controllers\DashboardController;
use Respect\Validation\Validator as v;
use HTMLPurifier as purifier;
use HTMLPurifier_Config as purifierConfig;


class PostController extends DashboardController
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    /**
     * Lists posts on the public posts view and handles pagination requests
     *
     * @param int|null $page  The page number of posts list
     *
     * @return void
     */
    public function listPublicPosts(?int $page = null): void
    {
        //pagination
        $currentPage = $page ?? 1;
        $limit = 3;
        $offset = ($currentPage - 1) * $limit;
        $totalPosts = $this->postModel->countPublishedPosts();
        $totalPages = ceil($totalPosts / $limit);

        //check if given page exists
        if (($currentPage > $totalPages) || ($currentPage < 1)) {
            http_response_code(404);
            exit('Page not found');
        }

        $posts = $this->postModel->getPublishedPosts($limit, $offset);

        //check for pagination AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'posts' => $posts,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]);
            return;
        }
        $this->view(
            'posts/list',
            [
                'title' => 'Public Posts Page',
                'posts' => $posts,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ]

        );
    }
    /**
     * Lists posts on the dashboard posts subview and handles pagination requests
     *
     * @param int|null $page  The page number of posts list
     *
     * @return void
     */
    public function listDashboardPosts(?int $page = null): void
    {
        $role = $_SESSION['user']['role'] ?? null;
        $isEditor = in_array($role, ['admin', 'editor']);
        $userID = $_SESSION['user']['id'] ?? null;

        //Pagination setup
        $currentPage = $page ?? 1;
        $limit = 3;

        //Count total posts
        if (!$isEditor) {
            $totalPosts = $this->postModel->countPosts($userID);
        } else {
            $totalPosts = $this->postModel->countPosts();
        }

        $totalPages = ceil($totalPosts / $limit);

        //Validate current page
        if (($currentPage > $totalPages && $totalPages !== 0) || $currentPage < 1) {
            http_response_code(404);
            exit('Page not found');
        }

        //Calculate offset and fetch posts
        $offset = ($currentPage - 1) * $limit;

        if (!$isEditor) {
            $posts = $this->postModel->getAllPosts($userID, $limit, $offset);
        } else {
            $posts = $this->postModel->getAllPosts(null, $limit, $offset);
        }
        // Handle AJAX response for pagination
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
           header('Content-Type: application/json');
            echo json_encode([
                'posts' => $posts,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]);
            return;
        }

        //Render view
        $this->renderDashboard(
            'dashboard/posts/list',
            [
                'title' => 'Dashboard Posts Page',
                'posts' => $posts,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ]
        );
    }
    /**
     * Renders a single post view
     *
     * @param int $id  The id of post to display
     *
     * @return void
     */
    public function showPost(int $id): void
    {
        $post = $this->postModel->getPostById($id);
        //check if post exists
        if (!$post) {
            http_response_code(404);
            exit('Post not found');
        }
        //check if post is published
        if (($post['status'] ?? '') !== 'published') {
            $userId = $_SESSION['user']['id'] ?? null;
            $role = $_SESSION['user']['role'] ?? null;
            $isEditor = in_array($role, ['editor', 'admin']);
            //only logged in users can see unpublished posts
            if (!$userId) {
                http_response_code(403);
                exit('You must be logged in to view this post.');
            } else {
                $postUserId = $post['user_id'] ?? null;
                //non editor users can only see their own unpublished posts
                if (!$isEditor && $postUserId !== $userId) {
                    http_response_code(403);
                    exit('You do not have permission to view this post.');
                }
            }
        }
        $this->view(
            'posts/show',
            [
                'title' => 'post',
                'post' => $post
            ]
        );
    }
    /**
     * Renders the create post subview on the dashboard view
     *
     * @return void
     */
    public function createPost(): void
    {
        $this->renderDashboard(
            'dashboard/posts/create',
            [
                'title' => 'Create Post Page',
            ]
        );
    }

    /**
     * Stores a new post
     *
     * @return void
     */
    public function storePost(): void
    {
        header('Content-Type: application/json');

        $title = strip_tags(trim($_POST['title'] ?? ''));
        $body = trim($_POST['body'] ?? '');
        // Configure HTML Purifier
        $config = purifierConfig::createDefault();
        $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title],ul,ol,li,br,img[src|alt|width|height]');
        // Define allowed URI schemes
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
            'ftp' => true,
        ]);
        $purifier = new purifier($config);
        $body = $purifier->purify($body); //sanitize post body
        $publishValue = $_POST['publish'] ?? 0;

        $errors = [];
        //validate title 
        if (!v::notEmpty()->validate($title)) {
            $errors[] = 'Title cannot be empty.';
        } elseif (!v::stringType()->length(3, 255)->validate($title)) {
            $errors[] = 'Title must be between 4 and 255 characters.';
        }
        //validate body
        if (!v::notEmpty()->validate($body)) {
            $errors[] = 'Body cannot be empty.';
        } elseif (!v::stringType()->length(3, 5000)->validate($body)) {
            $errors[] = 'Body must be between 4 and 5000 characters.';
        }
        //validate publish state
        $allowedPublishValues = [0, 1];
        if (!in_array($publishValue, $allowedPublishValues)) {
            $errors[] = 'Invalid publish value.';
        }
        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }
        //check for duplicate title
        if ($this->postModel->getPostByTitle($title)) {
            $errors[] = 'Another post exists with the same title. Choose another title.';
        }
        // Check for duplicate body
        if ($this->postModel->getPostByBody($body)) {
            $errors[] = 'Another post exists with the same body. Choose another body.';
        }
        if ($errors) {
            http_response_code(409);
            echo json_encode(['error' => $errors]);
            return;
        }
        $userId = $_SESSION['user']['id'] ?? null;
        $status = ($publishValue === '0') ? 'draft' : 'published';

        $this->postModel->createPost($userId, $title, $body, $status);
        echo json_encode(['success' => true]);
    }
    /**
     * Renders the update post subview on the dashboard view
     *
     * @param int $id  the id of post to edit
     * 
     * @return void
     */
    public function editPost(int $id): void
    {
        $post = $this->postModel->getPostById($id);
        $role = $_SESSION['user']['role'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        // Restrict authors to editing their own posts only
        if ($role === 'author' && $post['user_id'] !== $userId) {
            http_response_code(403);
            exit('Access denied');
        }
        $isPublished = ($post['status'] ?? '') === 'published';

        $this->renderDashboard(
            'dashboard/posts/edit',
            [
                'title' => 'Edit Post Page',
                'post' => $post,
                'isPublished' => $isPublished,
            ]
        );
    }
    /**
     * Updates a post 
     *
     * @param int $id  the id of post to update
     * 
     * @return void
     */
    public function updatePost(int $id): void
    {

        header('Content-Type: application/json');

        $title = strip_tags(trim($_POST['title'] ?? ''));
        $body = trim($_POST['body'] ?? '');
        // Configure HTML Purifier
        $config = purifierConfig::createDefault();
        $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title],ul,ol,li,br,img[src|alt|width|height]');
        // Define allowed URI schemes
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
            'ftp' => true,
        ]);
        $purifier = new purifier($config);
        $body = $purifier->purify($body);
        $publishValue = $_POST['publish'] ?? 0;
        $errors = [];
        //validate title
        if (!v::notEmpty()->validate($title)) {
            $errors[] = 'Title cannot be empty.';
        } elseif (!v::stringType()->length(3, 255)->validate($title)) {
            $errors[] = 'Title must be between 4 and 255 characters.';
        }
        //validate body
        if (!v::notEmpty()->validate($body)) {
            $errors[] = 'Body cannot be empty.';
        } elseif (!v::stringType()->length(3, 5000)->validate($body)) {
            $errors[] = 'Body must be between 4 and 5000 characters.';
        }
        //no changes
        $postToEdit = $this->postModel->getPostById($id);
        $status = ($publishValue === '0') ? 'draft' : 'published';
        if ($postToEdit['title'] === $title && $postToEdit['body'] === $body && $postToEdit['status'] === $status) {
            $errors[] = 'No changes were made: choose another title, body or publishing status';
        }
        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }

        $existingPostByTitle = $this->postModel->getPostByTitle($title);
        //check for duplicate title
        if ($existingPostByTitle && $existingPostByTitle['id'] !== $id) {
            $errors[] = 'Another post exists with the same title. Choose another title.';
        }
        // Check for duplicate body
        $existingPostByBody = $this->postModel->getPostByBody($body);
        if ($existingPostByBody && $existingPostByBody['id'] !== $id) {
            $errors[] = 'Another post exists with the same body. Choose another body.';
        }
        if ($errors) {
            http_response_code(409);
            echo json_encode(['error' => $errors]);
            return;
        }
        $this->postModel->updatePost($id, $title, $body, $status);
        echo json_encode(['success' => true]);
    }

    /**
     * Deletes a post 
     *
     * @param int $id  the id of post to delete
     * 
     * @return void
     */
    public function deletePost(int $id): void
    {
        header('Content-Type: application/json');
        if ($_POST['method'] !== 'DELETE') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
        $post = $this->postModel->getPostById($id);
        $role = $_SESSION['user']['role'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;
        // Check if post exists
        if (empty($post)) {
            http_response_code(404);
            echo json_encode(['error' => 'Post not found']);
            return;
        }
        // Restrict authors to deleting their own posts only
        if ($role === 'author' && $post['user_id'] !== $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied: you can only delete your own posts']);
            return;
        }
        $this->postModel->deletePost($id);
        echo json_encode(['success' => true]);
    }
}
