-- 
-- Data to insert into tables
--

-- users have same hashed password: password123
INSERT INTO users (username, email, password, created_at)
VALUES 
  ('alice', 'alice@example.com', '$2y$10$Yz6MwYjvYB0fWqTduhJ46.Lg5oVYG/oMc6lT0SyOfB52e9xmynHIMAm', NOW()),
  ('bob', 'bob@example.com', '$2y$10$zdlIaHAF7PRHDZcqptvJ5e20cTnPbnQ1/VCu7aYl/XCVy9ZdrZDiO', NOW()), 
  ('charlie', 'charlie@example.com', '$2y$10$YkTL8hG90hEyE4wnOiXl6u4EwqLy7KDZGx/h7v.lQ2pZ7G/POp6A.', NOW()),
  ('diana', 'diana@example.com', '$2y$10$EpMaO3UbiH34RUEBjWzLquCmzv6YemOhAMi7UFGFiqE1i1lQa4mzm', NOW()),
  ('eve', 'eve@example.com', '$2y$10$YB1HgM3tEK7qEF5CAvX7ZOw3T2MwDxYHpN5E3XtkpZrheHhGCEiP2', NOW());


-- posts
INSERT INTO posts (user_id, title, body) VALUES
(1, 'Welcome to My Blog', 'This is the first post on the blog. Stay tuned for more content!'),
(2, 'PHP Tips and Tricks', 'In this post, we discuss some useful PHP tricks for beginners and intermediate developers.'),
(4, 'Modern PHP with Composer', 'Using Composer makes PHP development much cleaner and modular. Here\'s how you can get started.'),
(3, 'Why MVC Matters', 'The Model-View-Controller pattern helps separate concerns in your code, making it easier to manage and test.');