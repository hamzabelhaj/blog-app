<?php

/**
 * PostModel class
 * 
 * Handles database operations related to posts such as retrieval, creation, updating, and deletion.
 */

declare(strict_types=1);

namespace App\Models;

use App\Services\DatabaseHandler;

class PostModel
{
    protected DatabaseHandler $databaseHandler;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
    }

    /**
     * Retrieves all posts with pagination.
     *
     * @param null|int $userId  User ID of the posts to be retrieved
     * @param int $limit  Pagination limit
     * @param int $offset Pagination offset
     * @return array|null List of posts
     */
    public function getAllPosts(?int $userId = null, int $limit, int $offset): ?array
    {

        if (!is_int($limit) || !is_int($offset)) {
            throw new \InvalidArgumentException("Limit and offset must be integers.");
        }
        $query = "SELECT posts.*, users.username FROM posts 
                  JOIN users ON posts.user_id = users.id";
        $params = [];
        if ($userId !== null) {
            $query .= " WHERE user_id = ?";
            $params = [$userId];
        }
        // Inline limit and offset since they are safe integers
        $query .= " ORDER BY posts.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        return $this->databaseHandler->select($query, $params) ?? null;
    }
    /**
     * Retrieves all published posts with pagination.
     *
     * @param int $limit  Pagination limit
     * @param int $offset Pagination offset
     * @return array|null List of posts
     */
    public function getPublishedPosts(int $limit, int $offset): ?array
    {
        if (!is_int($limit) || !is_int($offset)) {
            throw new \InvalidArgumentException("Limit and offset must be integers.");
        }
        $query = "SELECT posts.*, users.username FROM posts 
              JOIN users ON posts.user_id = users.id 
              WHERE posts.status = 'published' 
              ORDER BY posts.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        return $this->databaseHandler->select($query) ?? null;
    }
    /**
     * Counts posts with optional user id.
     *
     * @param int|null  $userId The user id associated to posts
     * @return int|null  Total count of posts
     */
    public function countPosts(?int $userId = null): ?int
    {
        $query = "SELECT COUNT(*) AS total FROM posts";
        $params = [];
        if ($userId !== null) {
            $query .= " WHERE user_id = :userId";
            $params = [
                'userId' => $userId,
            ];
        }
        $records = $this->databaseHandler->select($query, $params);
        return  (int) $records[0]['total'] ?? null;
    }
    /**
     * Counts posts with a published status
     *
     * @return int|null  Total count of published posts
     */
    public function countPublishedPosts(): ?int
    {
        $query = "SELECT COUNT(*) AS total FROM posts WHERE status = 'published'";
        $records = $this->databaseHandler->select($query);
        return  (int) $records[0]['total'] ?? null;
    }
    /**
     * Retrieves a single post by its ID.
     *
     * @param int $id Post ID
     * @return array|null Post data
     */
    public function getPostById(int $id): ?array
    {
        $query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = :postId LIMIT 1";
        $params = ['postId' => $id];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }
    /**
     * Retrieves a post by its title.
     *
     * @param string $title Title to search
     * @return array|null Post data
     */
    public function getPostBytitle(string $title): ?array
    {
        $query = "SELECT * FROM posts WHERE title = :title LIMIT 1";
        $params = ['title' => $title];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }
    /**
     * Retrieves a post by its body content.
     *
     * @param string $body Body content to search
     * @return array|null Post or null
     */
    public function getPostBybody(string $body): ?array
    {
        $query = "SELECT * FROM posts WHERE body = :body LIMIT 1";
        $params = ['body' => $body];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }
    /**
     * Creates a new post.
     *
     * @param int $userId ID of the user creating the post
     * @param string $title Title of the post
     * @param string $body Body content
     * @param string $status Post status (draft, published)
     * @return bool success status
     */
    public function createPost(int $userId, string $title, string $body, string $status): bool
    {
        $query = "INSERT INTO posts (user_id, title, body, status) VALUES (:user_id, :title, :body, :status)";
        $params = [
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'status' => $status
        ];
        return $this->databaseHandler->insert($query, $params) ?? false;
    }
    /**
     * Updates an existing post.
     *
     * @param int $id Post ID
     * @param string $title New title
     * @param string $body New body
     * @param string $status New status
     * @return bool success status
     */
    public function updatePost(int $id, string $title, string $body, string $status): bool
    {
        $query = "UPDATE posts SET title = :title, body = :body, status = :status WHERE id = :id";
        $params = [
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'status' => $status
        ];
        return $this->databaseHandler->update($query, $params) ?? false;
    }
    /**
     * Deletes a post by its ID.
     *
     * @param int $id Post ID
     * @return bool success status
     */
    public function deletePost(int $id): bool
    {
        $query = "DELETE FROM posts WHERE id = :id";
        $params = ['id' => $id];
        return $this->databaseHandler->delete($query, $params) ?? false;
    }
}
